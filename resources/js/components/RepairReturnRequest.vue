<script setup>
	import { computed, onMounted, ref } from "vue";

	const props = defineProps(["api"]);

	const form = ref([]);
	const submitLoading = ref(false);
	const itemsPerPage = 6;
	const currentPage = ref(1);

	const getLists = () => {
		axios.get(props.api)
			.then((response) => {
				form.value = response.data;
			})
	}

	const accept = () => {
		if (!hasPending.value) {
			submitLoading.value = true;
			axios.post(props.api + "/accept", {form: form.value})
			.then((response) => {
				submitLoading.value = false;
				if (response.status === 200) {
					window.location.assign(response.data.redirect)
				}
			})
		}
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

	const hasPending = computed(() => {
		return form.value.some(item => item.status === 0);
	})

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
				<h4 class="header-title mb-4">Manage Requests</h4>

				<div class="table-height">
					<div class="table-responsive">
						<table class="table table-hover table-centered m-0">
							<thead class="table-light">
								<tr>
									<th>Item</th>
									<th class="text-center">Brand</th>
									<th class="text-center">Amount</th>
									<th class="text-center">Description</th>
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
										</p>
									</td>
									<td class="text-center">
										<span class="badge p-1 bg-dark">{{ item.brand }}</span>
									</td>
									<td class="text-center">
										<span class="text-dark">&#8369;{{ item.amount }}</span>
									</td>
									<td class="text-center">
										<small class="text-dark">{{ item.description }}</small>
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
						<vue-ladda 
							@click="reject" 
							button-class="btn btn-sm btn-danger waves-effect waves-light me-2" 
							data-style="slide-left" :loading="submitLoading"
						>
							Reject Request
						</vue-ladda>
						<vue-ladda 
							@click="accept" 
							button-class="btn btn-sm btn-success waves-effect waves-light" 
							data-style="slide-left" :loading="submitLoading"
						>
							Accept Request
						</vue-ladda>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
