<script setup>
import { computed, onMounted, ref } from "vue";

	const props = defineProps(["api"]);

	const form = ref([]);
	const checkLoading = ref(false);
	const submitLoading = ref(false);
	const isStartCheck = ref(false);
	const checkResponse = ref({});
	const itemsPerPage = 6;
	const currentPage = ref(1);

	const getLists = () => {
		axios.get(props.api)
			.then((response) => {
				form.value = response.data;
			})
	}

	const checkItem = (id, index) => {
		if (!isStartCheck.value) {
			isStartCheck.value = true;
		}
		checkLoading.value = true;
		form.value[index].checkLoading = true;
		axios.get(props.api + "/" + id + "/check")
			.then((response) => {
				checkLoading.value = false;
				form.value[index].checkLoading = false;
				form.value[index].status = response.data.item_status;
				checkResponse.value = response.data;
			})
	}

	const handleInput = (event, item) => {
		const newValue = parseInt(event.target.value);
		if (newValue > item.max) {
			item.quantity = item.max
		}else {
			item.quantity = newValue;
		}
	}

	const rowBg = (status) => {
		if (status === 2) {
			return "bg-soft-success";
		}
		if (status === 3) {
			return "bg-soft-danger"
		}
		return ""
	}

	const setToCheck = (index) => {
		if (form.value[index].quantity <= form.value[index].max) {
			form.value[index].status = 2;
		}
	}

	const totalAmount = computed(() => {
		return form.value.reduce((total, item) => {
			return total + (item.amount * item.quantity);
		}, 0);
	})

	const accept = () => {
		submitLoading.value = true;
		axios.post(props.api + "/accept", {form: form.value})
		.then((response) => {
			submitLoading.value = false;
			if (response.status === 200) {
				window.location.assign(response.data.redirect)
			}
		})
	}

	const reject = () => {
		submitLoading.value = true;
		axios.patch(props.api + "/reject")
		.then((response) => {
			submitLoading.value = false;
			if (response.status === 200) {
				window.location.assign(response.data.redirect)
			}
		})
	}

	const totalPages = computed(() => Math.ceil(form.value.length / itemsPerPage));
	const paginatedItems = computed(() => {
		const startIndex = (currentPage.value - 1) * itemsPerPage;
		const endIndex = startIndex + itemsPerPage;
		return form.value.slice(startIndex, endIndex);
	});

	const prevPage = () => {
		if (currentPage.value > 1) {
			currentPage.value--;
		}
	};

	const nextPage = () => {
		if (currentPage.value < totalPages.value) {
			currentPage.value++;
		}
	};

	onMounted(() => {
		getLists();
	})
</script>

<template>
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<button type="button" class="btn btn-icon btn-blue waves-effect waves-light float-end"
						data-bs-toggle="modal" data-bs-target="#add"
				>
						<i class="mdi mdi-qrcode-scan mdi-18px"></i>
				</button>
				<h4 class="header-title mb-4">Manage Requests</h4>

				<div class="row align-items-center">
					<div class="col-xl-9 col-lg-9 col-md-7">
						<div class="table-height">
							<div class="table-responsive">
								<table class="table table-hover table-centered mb-0">
									<thead class="table-light">
										<tr>
											<th>Item</th>
											<th class="text-center">Type</th>
											<th class="text-center">Quantity</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr
											v-for="(item, index) in paginatedItems"
											:key="item.id"
											:class="rowBg(item.status)"
										>
											<td>
												<img :src="item.image" class="rounded me-2" height="40" alt="Item Logo"/>
												<p class="m-0 d-inline-block align-middle font-16">
													<span class="font-family-secondary fw-bold text-primary">{{ item.name }}</span>
													<br>
													<small class="me-2">
														<b>Code: </b> {{ item.item_id }}
													</small>
													<small>
														<b>Brand: </b> {{ item.brand }}
													</small>
													<br>
													<small>
														<b>Amount: </b>&#8369;{{ item.amount }}
													</small>
												</p>
											</td>
											<td class="text-center">
												<span class="badge p-1" :class="item.color">{{ item.text }}</span>
											</td>
											<td class="text-center">
												<input type="number" class="form-control mx-auto" 
													min="1" :max="item.max" 
													:value="item.quantity" style="width: 90px;" 
													placeholder="Qty" 
													:disabled="item.disabled"
													@input="handleInput($event, item)"
													/>
											</td>
											<td class="text-center align-middle">
												<vue-ladda 
													@click="checkItem(item.id, index)" 
													button-class="btn btn-sm btn-dark waves-effect waves-light" 
													data-style="slide-left" :loading="item.checkLoading">
													Check
												</vue-ladda>
												<button v-if="!item.disabled" type="button" class="btn btn-sm btn-icon btn-warning ms-2" @click="setToCheck(index)">
													<i class="mdi mdi-check-all"></i>
												</button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<button @click="prevPage" type="button" class="btn btn-sm btn-outline-secondary" :disabled="currentPage === 1">
									Previous
								</button>
								<span class="mx-1 text-dark fw-bold">Page {{ currentPage }} of {{ totalPages }}</span>
								<button @click="nextPage" type="button" class="btn btn-sm btn-outline-secondary" :disabled="currentPage === totalPages">
									Next
								</button>
							</div>
							<div>
								<h5>
									<b>Total Amount:</b> <span>&#8369; {{ totalAmount }}</span>
								</h5>
								<div>
									<vue-ladda 
										@click="reject" 
										button-class="btn btn-sm btn-outline-danger waves-effect waves-light me-2" 
										data-style="slide-left" :loading="submitLoading"
									>
										Reject Request
									</vue-ladda>
									<vue-ladda 
										@click="accept" 
										button-class="btn btn-sm btn-outline-success waves-effect waves-light" 
										data-style="slide-left" :loading="submitLoading"
									>
										Accept Request
									</vue-ladda>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-5">
						<div class="border rounded p-5 text-center">
							<img src="/assets/images/delivery.png" alt="Delivery Image" height="100" v-if="!isStartCheck">
							<div v-else>
								<div v-if="checkLoading">
									<div class="d-flex justify-content-center">
										<div class="spinner-border avatar-lg" role="status"></div>
									</div>
									<h5 class="text-dark mb-0 mt-3">Please wait...</h5>
								</div>
								<div v-else>
									<span :class="checkResponse.icon"></span>
									<h5 class="fw-bold text-dark my-0">{{ checkResponse.title }}</h5>
									<p class="mb-0" v-if="checkResponse.sub_title">{{ checkResponse.sub_title }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>