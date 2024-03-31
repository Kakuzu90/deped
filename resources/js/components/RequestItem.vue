<script setup>
	import { onMounted, ref } from 'vue';
	import { PerfectScrollbar } from 'vue3-perfect-scrollbar';
	
	const props = defineProps(["api"]);
	const isLoading = ref(false);
	const tabLoading = ref(false);
	const pending = ref([]);
	const pendingPagination = ref({});
	const repair = ref([]);
	const pendingCurrentPage = ref(1);

	const getPending = () => {
		tabLoading.value = true;
		axios.get(props.api + "pending")
			.then((response) => {
				tabLoading.value = false;
				pending.value = response.data.data;
				pendingPagination.value = response.data.pagination;
			})
	}

	const nextPending = (url) => {
		if (url) {
			tabLoading.value = true;
			axios.get(url)
				.then((response) => {
					tabLoading.value = false;
					pending.value = response.data.data;
					pendingPagination.value = response.data.pagination;
				})
		}
	}

	const prevPending = (url) => {
		if (url) {
			tabLoading.value = true;
			axios.get(url)
				.then((response) => {
					tabLoading.value = false;
					pending.value = response.data.data;
					pendingPagination.value = response.data.pagination;
				})
		}
	}

	onMounted(() => {
		getPending()
	})
	
</script>

<template>
  <div class="col-12">
		<div class="card">
			<div class="card-body pt-2">
				<ul class="nav nav-tabs nav-bordered">
					<li class="nav-item">
						<a href="#pending" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
							Pending
						</a>
					</li>
					<li class="nav-item">
						<a href="#repair" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
							Request Repair
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="pending">
						<div class="row">
							<div class="col-xl-8 col-lg-8 col-md-7">
								<div class="d-flex justify-content-end mb-3">
									<button type="button" class="btn btn-outline-blue">
										<i class="mdi mdi-content-save-move"></i>
										Save
									</button>
								</div>
								<div class="h-px-400">
									<div v-if="tabLoading" class="d-flex justify-content-center align-items-center h-100">
										<div class="spinner-border avatar-lg text-secondary" role="status"></div>
									</div>
									<div v-else class="table-responsive">
										<table class="table table-borderless table-nowrap table-centered mb-0">
											<thead class="table-light">
												<tr>
													<th>Item</th>
													<th class="text-center">Quantity</th>
													<th class="text-center">Item Type</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="item in pending" :key="item.id">
													<td>
														<img :src="item.image" class="rounded me-2" height="45" alt="Item Logo"/>
														<p class="m-0 d-inline-block align-middle font-16">
															<span class="font-family-secondary fw-bold text-primary">{{ item.name }}</span>
															<br>
															<small class="me-2">
																<b>Code: </b> {{ item.code }}
															</small>
														</p>
													</td>
													<td class="text-center">
														<input type="number" class="form-control mx-auto" min="1" :value="item.quantity" style="width: 90px;" placeholder="Qty" />
													</td>
													<td class="text-center">
														<span class="badge p-1" :class="item.color">{{ item.text }}</span>
													</td>
													<td class="text-center align-middle">
														<a href="javascript:void(0);" 
															class="delete action-icon">
															<i class="mdi mdi-delete-empty"></i>
														</a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="d-flex justify-content-between align-items-center" v-if="!tabLoading">
									<p class="text-dark fw-bold">
										{{ pendingPagination.showing }}
									</p>
									<ul class="pagination pagination-sm">
										<li class="page-item" :class="{'disabled': !pendingPagination.prev}">
											<a href="javascript:void(0);" @click="prevPending(pendingPagination.prev)" class="page-link" aria-label="Previous">
												<span>Previous</span>
											</a>
										</li>
										<li class="page-item active" v-if="pendingPagination.has_page">
											<a class="page-link" href="javascript: void(0);">{{ pendingCurrentPage }}</a>
										</li>
										<li class="page-item" :class="{'disabled': !pendingPagination.next}">
											<a href="javascript:void(0);" @click="nextPending(pendingPagination.next)" class="page-link" aria-label="Next">
												<span>Next</span>
											</a>
										</li>
									</ul>
								</div>
							</div>

							<div class="col-xl-4 col-lg-4 col-md-5">
								<div class="d-flex mb-3">
									<input type="text" class="form-control rounded-pill" name="search" placeholder="Search: Laptop, etc..." />
									<button type="button" class="btn btn-icon btn-outline-blue ms-2">
										<i class="mdi mdi-magnify"></i>
									</button>
								</div>
								<perfect-scrollbar class="border rounded item-search-container" ref="scrollbar">
									<ul class="list-group list-group-flush">
										<li v-for="index in 50" :key="index" class="list-group-item list-group-item-action">Google Drive</li>
									</ul>
								</perfect-scrollbar>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="repair">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<style src="vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.min.css"/>