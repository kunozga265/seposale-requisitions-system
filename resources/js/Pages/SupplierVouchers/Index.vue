<template>
  <app-layout>
    <template #header>
      Supplier Vouchers
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Supplier Vouchers</span>
        </div>
      </li>
    </template>

    <template #actions>

      
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

            <div v-if="supplierVouchers.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
              No Supplier Vouchers Found
            </div>
            <div v-else>
              <div class="card">
                <div class="p-2 mb-2 relative ">
                  <div class="p-2 pb-4 heading-font text-left relative">
                    <button v-show="form.name.length > 0" @click="form.name = ''"
                      class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                      <i class="mdi mdi-close"></i>
                    </button>
                    <jet-input id="code" type="text" class="block w-full" placeholder="Search Name..."
                      v-model="form.name" autocomplete="seposale-filter-code" />

                  </div>
                  <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                    <thead class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                      <tr>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">#</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Name</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-center">Supplier</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-center">Transporter</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left flex items-center">Details
                        </th>
                         <th scope="col" class="p-2 pb-0 heading-font text-right">Amount</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Status</th>
                      </tr>

                    </thead>
                    <tbody class="pt-8">

                      <tr class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(supplierVoucher, index) in filteredSupplierVouchers" :key="index">
                        <td @click="navigateToSupplierVoucher(supplierVoucher.id)" class="p-2 text-left ">{{ supplierVoucher.code }}</td>
                        <td @click="navigateToSupplierVoucher(supplierVoucher.id)" class="p-2 text-left ">{{ getDate(supplierVoucher.date*1000) }}</td>
                        <td @click="navigateToSupplierVoucher(supplierVoucher.id)" class="p-2 text-left ">
                          <div>{{ supplierVoucher.name }}</div>
                        </td>
                        <td class="p-2 text-center ">

                          <input  id="backdate" type="checkbox" disabled :checked="supplierVoucher.transporter != null"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                        </td>
                        <td class="p-2 text-center ">

                          <input  id="backdate" type="checkbox" disabled :checked="supplierVoucher.supplier != null"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                        </td>
                        <td @click="navigateToSupplierVoucher(supplierVoucher.id)" class="p-2 text-left ">{{ supplierVoucher.details }}</td>
                        <td @click="navigateToSupplierVoucher(supplierVoucher.id)" class="p-2 text-right ">{{ numberWithCommas(supplierVoucher.amount) }}</td>
                        <td class="p-2 text-left ">{{ supplierVoucher.paid ? "Paid" : "Unpaid" }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

              </div>
                           <pagination :object="supplierVouchers"/>
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
import SecondaryButton from "@/Jetstream/SecondaryButton";
import RequestStatus from "@/Components/RequestStatus.vue";
import Pagination from "@/Components/Pagination.vue";
import SaleStatus from "@/Components/SaleStatus.vue";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import JetInput from "@/Jetstream/Input.vue";

export default {
  props: [
    'supplierVouchers',
  ],
  components: {
    JetInput, DeliveryStatus, SaleStatus,
    Pagination,
    RequestStatus,
    AppLayout,
    PrimaryButton,
    SecondaryButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        name: ""
      }),
    }
  },
  computed: {
    filteredSupplierVouchers() {
      let filtered = this.supplierVouchers.data

      /* Filter Sales By SupplierVoucher*/
      if (this.form.name.length !== 0) {
        filtered = (filtered).filter((supplierVoucher) => {
          return supplierVoucher.name.toLowerCase().includes(this.form.name.toLowerCase())
        })
      }

      return filtered
    },
  
  },
  methods: {
    navigateToSupplierVoucher(id) {
      this.$inertia.get(this.route('supplier-vouchers.show', { 'id': id }))
    },
   

  }


}
</script>
