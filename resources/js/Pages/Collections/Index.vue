<template>
  <app-layout>
    <template #header>
      {{ site.name }} - Collections
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('sites.overview', { code: site.code })"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            {{ site.name }}
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            Collections
          </span>
        </div>
      </li>
    </template>


    <!-- <template #actions>
      <inertia-link :href="route('clients.create')">
        <primary-button>
          New Client
        </primary-button>
      </inertia-link>
    </template> -->

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section" v-if="pending.length > 0">
          <div class="page-section-header">
            <div class="page-section-title">
              Pending
            </div>
          </div>
          <div class="page-section-content">
            <div>
              <div class="card default-table">
                <div class="p-2 relative overflow-x-auto">
                  <table class="overflow-auto w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class=" text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                      <tr>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Collection Status
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Sale Code</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Client</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Amount</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Payment Status</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Quantity</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Collected</th>

                        </th>

                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                        v-for="(productCompound, index) in pending" :key="index">
                        <td class=" cursor-pointer hover:bg-gray-50 ">
                          <collection
                            class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                            :client="productCompound.sale.client" :product="productCompound" :is-solo="true" />
                        </td>
                        <td @click="navigateToSale( productCompound.sale.id)" class="py-2 pr-1 cursor-pointer hover:bg-gray-50 ">
                          {{ productCompound.sale.code }}
                        </td>

                        <td @click="navigateToClient(productCompound.sale.client.id)" class="py-2 pr-1 cursor-pointer hover:bg-gray-50 ">
                          {{ productCompound.sale.client.name }}
                        </td>
                        <th @click="navigateToInventory(productCompound.inventory.id)" scope="row" :class="{ 'strike-through': productCompound.trashed }"
                          class="py-2 pr-1 cursor-pointer hover:bg-gray-50  font-medium text-gray-900 dark:text-white whitespace-nowrap">
                          {{ productCompound.inventory.name }}
                        </th>
                        <td class="py-2 pr-1 text-right">
                          {{ numberWithCommas(productCompound.amount) }}
                        </td>
                        <td class="py-2 pr-1 text-right">
                          {{ numberWithCommas(productCompound.balance) }}
                        </td>
                        <td>
                          <sale-status :status="productCompound.paymentStatus" :is-solo="true" />
                        </td>
                        <td class="py-2 pr-1 text-right">
                          {{
                            numberWithCommas(productCompound.quantity)
                          }}
                        </td>
                        <td class="py-2 pr-1 text-right">
                          {{ numberWithCommas(productCompound.collected) }}
                        </td>

                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="page-section" v-if="collections.data.length > 0">
          <div class="page-section-header">
            <div class="page-section-title">
              All
            </div>
          </div>

          <div class="page-section-content">
            <div class="card default-table overflow-x-auto">
              <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                <thead class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                  <tr>
                    <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                    <th scope="col" class="p-2 pb-0 heading-font text-left">Sale Code</th>
                    <th scope="col" class="p-2 pb-0 heading-font text-left">Client</th>
                    <!--                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Collected By</th>-->
                    <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                    <th scope="col" class="p-2 pb-0 heading-font text-left">Collected By</th>
                    <th scope="col" class="p-2 pb-0 heading-font text-left">Quantity</th>
                    <th scope="col" class="p-2 pb-0 heading-font text-left">Balance</th>
                  </tr>
                </thead>
                <tbody class="pt-8">
                  <tr v-for="(item, index) in collections.data" :key="index">
                    <td @click="navigateToCollection(item.code)" class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">{{
                      item.code }}</td>
                    <td @click="navigateToSale(item.siteSaleSummary.sale.id)"
                      class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">{{
                        item.siteSaleSummary.sale.code }}</td>
                    <td @click="navigateToClient(item.client.id)" class="p-2 text-left ">{{ item.client.name }}</td>
                    
                    <td @click="navigateToInventory(item.inventory.id)"
                      class="p-2 text-left  cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">{{
                        item.inventory.name
                      }}
                    </td>
                    <td class="p-2 text-left ">{{item.collectedBy }}</td>
                    <td class="p-2 text-left ">
                      {{ numberWithCommas(item.quantity) }}
                    </td>
                    <td class="p-2 text-left ">
                      {{ numberWithCommas(item.balance) }}
                    </td>
                  </tr>

                </tbody>
              </table>
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
import JetInput from "@/Jetstream/Input.vue";
import Collection from "@/Components/Collection.vue";

export default {
  props: [
    'site',
    'pending',
    'collections',
  ],
  components: {
    JetInput, DeliveryStatus, SaleStatus,
    Pagination,
    RequestStatus,
    AppLayout,
    Collection,
    PrimaryButton
  },
  data() {
    return {
      form: this.$inertia.form({
        name: ""
      }),
      listOfClients: []
    }
  },
  computed: {
    filteredClients() {
      let filtered = this.clients.data

      /* Filter Sales By Client*/
      if (this.form.name.length !== 0) {
        filtered = (filtered).filter((client) => {
          if (client.alias) {
            return client.name.toLowerCase().includes(this.form.name.toLowerCase()) || client.alias.toLowerCase().includes(this.form.name.toLowerCase())
          } else {
            return client.name.toLowerCase().includes(this.form.name.toLowerCase())
          }
        })
      }

      return filtered
    },
    list() {
      return this.listOfClients
    }
  },
  methods: {
    navigateToClient(id) {
      this.$inertia.get(this.route('clients.show', { 'id': id }))
    },
    selectClient(id) {
      (this.listOfClients).push(id)
      console.log(id)
    },
    navigateToSale(id) {
      this.$inertia.get(this.route('sites.sales.show', { 'code': this.site.code, 'id': id }))
    },
    navigateToInventory(id) {
      this.$inertia.get(this.route('sites.inventories.show', { 'code': this.site.code, 'id': id }))
    },
    navigateToCollection(code) {
      this.$inertia.get(this.route('sites.collections.show', { 'code': this.site.code, 'collection_code': code }))
    },

  }
}
</script>
