<script setup>
import { onMounted, ref, watch } from "vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";


	const props = defineProps(["api"]);

	const search = ref("");
	const lists = ref([]);
	const form = ref([]);
	const searchLoading = ref(false);
	const btnLoading = ref(false);

	const getLists = () => {
		axios.get(props.api + "new")
		.then((response) => {
			lists.value = response.data;
		})
	}

	const searchBtn = () => {
		searchLoading.value = true;
		axios.get(props.api + "new?search=" + search.value)
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
				quantity: 1,
				max: item.quantity,
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
		axios.post(props.api + "new/store", {form: form.value})
			.then((response) => {
				console.log(response.data)
				console.log(response.status)
			})
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
										<th class="text-center">Quantity</th>
										<th class="text-center">Item Type</th>
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
										v-for="(item, index) in form"
										:key="index"
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
											</p>
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
										<td class="text-center">
											<span class="badge p-1" :class="item.color">{{ item.text }}</span>
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
						<div class="btn-group">
							<button type="button" class="btn btn-sm btn-outline-light waves-effect waves-light">Previous</button>
							<button type="button" class="btn btn-sm btn-secondary">1</button>
							<button type="button" class="btn btn-sm btn-outline-light waves-effect waves-light">Next</button>
						</div>
						<button type="button" class="btn btn-sm btn-blue waves-effect waves-light" @click="submit">
							<i class="mdi mdi-content-save-move"></i> Submit
						</button>
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
										<b>Code: </b> {{ item.id }}
									</small>
									<small>
										<b>Brand: </b> {{ item.brand }}
									</small>
									<br>
									<small>
										<b>Quantity: </b> {{ item.quantity }}
									</small>
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