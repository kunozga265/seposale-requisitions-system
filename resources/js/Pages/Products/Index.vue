<template>
  <app-layout>
    <template #header>
      Products
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Products</span>
        </div>
      </li>
    </template>

    <template #actions>
      <div class="flex gap-x-2">
        <inertia-link :href="route('products.create')">
          <primary-button>
            New Product
          </primary-button>
        </inertia-link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              All
            </div>
          </div>
          <div class="page-section-content">

            <div v-if="products.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
              No Products Found
            </div>
            <div v-else>
              <div class="card">
                <div class="p-2 mb-2 relative ">

                  <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                    <thead class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                      <tr>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Name</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Variant</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Unit</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Rate</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Discounted Price</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Original Price</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Actions</th>

                      </tr>

                    </thead>
                    <tbody class="pt-8">

                      <tr @click="navigateToProduct(product.id)"
                        class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(product, index) in filteredProducts" :key="index">
                        <td class="p-2 text-left ">{{ index + 1 }}. {{ product.name }}</td>
                        <td class="p-2 text-left ">{{ product.description }}</td>
                        <td class="p-2 text-left ">{{ product.unit }}</td>
                        <td class="p-2 text-right ">{{ numberWithCommas(product.cost / product.quantity) }}</td>
                        <td class="p-2 text-right ">{{ numberWithCommas(product.cost) }}</td>
                        <td class="p-2 text-right ">{{ numberWithCommas(product.costOriginal) }}</td>
                        <td class="p-2 text-right ">
                          <a :href="route('products.variants.edit', { id: product.variant_id })" @click.stop
                            class="text-blue-600 hover:text-blue-800 text-sm mr-3">
                            Edit
                          </a>
                          <button type="button" class="text-red-600 hover:text-red-800 text-sm"
                            @click.stop="deleteVariant(product.variant_id)">
                            Delete
                          </button>
                        </td>

                      </tr>
                    </tbody>
                  </table>
                </div>

              </div>
              <!--              <pagination :object="products"/>-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import DoughnutChart from "@/Components/Charts/DoughnutChart";
import PieChart from "@/Components/Charts/PieChart";
import Request from "@/Components/Request";
import PrimaryButton from "@/Jetstream/Button";
import RequestStatus from "@/Components/RequestStatus.vue";
import Pagination from "@/Components/Pagination.vue";
import SaleStatus from "@/Components/SaleStatus.vue";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";

export default {
  props: [
    'products',
  ],
  components: {
    DeliveryStatus, SaleStatus,
    Pagination,
    RequestStatus,
    AppLayout,
    PrimaryButton,
  },
  computed: {
    filteredProducts() {
      let arr = [];
      for (let x in this.products.data) {
        for (let y in this.products.data[x].variants) {
          arr.push({
            "id": this.products.data[x].id,
            "name": this.products.data[x].name,
            "variant_id": this.products.data[x].variants[y].id,
            "variant_name": this.products.data[x].variants[y].name,
            "description": this.products.data[x].variants[y].description,
            "unit": this.products.data[x].variants[y].unit,
            "quantity": this.products.data[x].variants[y].quantity,
            "cost": this.products.data[x].variants[y].cost,
            "costOriginal": this.products.data[x].variants[y].costOriginal,
          })
        }
      }

      return arr;
    },
  },
  methods: {
    navigateToProduct(id) {
      this.$inertia.get(this.route('products.show', { 'id': id }))
    },
    deleteVariant(variantId) {
      if (!confirm('Delete this product variant? This cannot be undone.')) return

      this.$inertia.delete(this.route('products.variants.destroy', { id: variantId }))
    },
  }
}
</script>
