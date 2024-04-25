<script setup>
	import { onMounted, ref, watch, computed } from "vue";
	import { PerfectScrollbar } from "vue3-perfect-scrollbar";

	const props = defineProps(["api"]);

	const search = ref("");
	const lists = ref([]);
	const form = ref([]);
	const searchLoading = ref(false);
	const btnLoading = ref(false);
	const itemsPerPage = 6;
	const currentPage = ref(1);

	const getLists = () => {
		axios.get(props.api + "return")
		.then((response) => {
			lists.value = response.data;
		})
	}

	const searchBtn = () => {
		searchLoading.value = true;
		axios.get(props.api + "return?search=" + search.value)
		.then((response) => {
			searchLoading.value = false;
			lists.value = response.data;
		})
	}

	const selectItem = (item) => {
		const isInArray = form.value.some(row => row.item_id === item.id);
		if (!isInArray) {
			const temp = {
				image: item.image,
				item_id: item.id,
				name: item.name,
				stock: item.stock,
				unit: item.unit,
				amount: item.amount,
				quantity: item.quantity,
				max: item.max,
				brand: item.brand,
				disabled: item.disabled,
				color: item.color,
				text: item.text,
			}

			form.value.unshift(temp);
		}
	}

	const removeItem = (index) => {
		form.value.splice(index, 1);
	}

	watch(search, (newValue, _) => {
		if (newValue === "") {
			getLists();
		}
	})

	const handleInput = (event, item) => {
		const newValue = parseInt(event.target.value);
		if (newValue > item.max) {
			item.quantity = item.max
		}else {
			item.quantity = newValue;
		}
	}

	const submit = () => {
		if (!hasPending.value) {
			btnLoading.value = true;
			axios.post(props.api + "return/store", {form: form.value})
			.then((response) => {
				btnLoading.value = false;
				if (response.status === 200) {
					window.location.assign(response.data.redirect)
				}
			})
		}
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

	const hasPending = computed(() => {
		return form.value.some(item => item.status === 0);
	})

	const setStatus = (index, status) => {
		form.value[index].status = status;
	}

	const rowBg = (status) => {
		if (status === 1) {
			return "bg-soft-success";
		}
		if (status === 2) {
			return "bg-soft-warning";
		}
		if (status === 3) {
			return "bg-soft-danger"
		}
		return ""
	}

	onMounted(() => {
		getLists()
	})

</script>

<template>
	<div class="col-12">
		<div class="card">
			<div class="card-body row">
				<div class="col-xl-8 col-lg-8 col-md-6">
					<div class="request-container">
						<div class="table-responsive">
							<table class="table table-borderless table-nowrap table-centered mb-0">
								<thead class="table-light">
									<tr>
										<th>Item</th>
										<th class="text-center">Brand</th>
										<th class="text-center">Amount</th>
										<th class="text-center">Condition</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr v-if="form.length === 0">
										<td colspan="4">
											<h5 class="text-muted text-center">No Items Available</h5>
										</td>
									</tr>
									<tr
										v-for="(item, index) in paginatedItems"
										:key="index"
										:class="rowBg(item.status)"
									>
										<td>
											<img :src="item.image" class="rounded me-2" height="40" alt="Item Logo"/>
											<p class="m-0 d-inline-block align-middle font-16">
												<span class="font-family-secondary fw-bold text-primary">{{ item.name }}</span>
												<br>
												<small class="me-2">
													<b>Stock No.: </b> {{ item.stock }}
												</small>
											</p>
										</td>
										<td class="text-center">
											<span>{{ item.brand }}</span>
										</td>
										<td class="text-center">
											<span>&#8369;{{ item.amount }}</span>
										</td>
										<td class="text-center">
											<button @click="setStatus(index, 1)" type="button" class="btn btn-sm btn-icon btn-success">
												<i class="mdi mdi-check-all"></i>
											</button>
											<button @click="setStatus(index, 2)" type="button" class="btn btn-sm btn-icon btn-warning mx-1">
												<i class="mdi mdi-tools"></i>
											</button>
											<button @click="setStatus(index, 3)" type="button" class="btn btn-sm btn-icon btn-danger">
												<i class="mdi mdi-image-broken-variant"></i>
											</button>
										</td>
										<td class="text-center align-middle">
											<a href="javascript:void(0);"
												@click="removeItem(index)"
												class="delete action-icon">
												<i class="mdi mdi-delete-empty"></i>
											</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="d-flex justify-content-between">
						<div class="btn-group" v-if="totalPages > 0">
							<button @click="prevPage" :disabled="currentPage === 1" type="button" class="btn btn-sm btn-outline-light waves-effect waves-light">Previous</button>
							<button type="button" class="btn btn-sm btn-secondary">Page {{ currentPage }} of {{ totalPages }}</button>
							<button @click="nextPage" :disabled="currentPage === totalPages" type="button" class="btn btn-sm btn-outline-light waves-effect waves-light">Next</button>
						</div>
						<vue-ladda @click="submit" button-class="btn btn-sm btn-blue waves-effect waves-light" data-style="slide-left" :loading="btnLoading">
							<i class="mdi mdi-content-save-move"></i> Submit
						</vue-ladda>
					</div>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-6">
					<div class="d-flex mb-3">
						<input type="text" class="form-control rounded-pill" @keypress.enter="searchBtn" v-model="search" name="search" placeholder="Search: Laptop, etc..." />
						<vue-ladda @click="searchBtn" button-class="btn btn-icon btn-blue ms-2" data-style="slide-left" :loading="searchLoading">
							<i class="mdi mdi-magnify"></i>
						</vue-ladda>
					</div>
					<perfect-scrollbar class="border rounded item-search-container" ref="scrollbar">
						<div v-if="lists.length === 0" class="d-flex justify-content-center align-items-center h-100 flex-column">
							<i class="mdi mdi-message-alert mdi-48px"></i>
							<h4 v-if="search" class="text-dark">No results found for {{ search }}</h4>
							<h4 v-else class="text-dark">No Items Available</h4>
						</div>
						<ul class="list-group list-group-flush">
							<li 
								v-for="item in lists" 
								:key="item.id" 
								class="list-group-item list-group-item-action"
								@click="selectItem(item)"
								>
								<p class="m-0 d-inline-block align-middle font-16">
									<span class="font-family-secondary fw-bold text-primary">{{ item.name }}</span>
									<br>
									<small class="me-2">
										<b>Stock No.: </b> {{ item.stock }}
									</small>
									<small>
										<b>Brand: </b> {{ item.brand }}
									</small>
									<br>
								</p>
							</li>
						</ul>
					</perfect-scrollbar>
				</div>
			</div>
		</div>
	</div>
</template>
<style src="vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.min.css"/>