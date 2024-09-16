<template>
  <app-layout>
    <template #header>
      Sales
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
               xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"></path>
          </svg>
          <span
              class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            Sales
          </span>
        </div>
      </li>
    </template>

    <template #actions>
      <inertia-link :href="route('sales.create')">
        <primary-button>
          Record Sale
        </primary-button>
      </inertia-link>
    </template>

      <div class="mx-9 flex">
          <a :href="route('sales.index',{filter:'all'})">
              <div class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold " :class="{'info':headline==='all'}">
                  <div>All</div>
                  <i v-show="headline === 'all' || headline == null"
                     class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
              </div>
          </a>
          <a :href="route('sales.index',{filter:'unpaid'})">
              <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold" :class="{'error':headline==='unpaid'}">
                  <div>Unpaid</div>
                  <i  v-show="headline === 'unpaid'"
                      class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
              </div>
          </a>
          <a :href="route('sales.index',{filter:'partially-paid'})">
              <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold " :class="{'warning':headline==='partially-paid'}">
                  <div>Partially Paid</div>
                  <i v-show="headline === 'partially-paid'"
                     class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
              </div>
          </a>
          <a :href="route('sales.index',{filter:'fully-paid'})">
              <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold " :class="{'success':headline==='fully-paid'}">
                  <div>Fully Paid</div>
                  <i v-show="headline === 'fully-paid'"
                     class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
              </div>
          </a>
          <a :href="route('sales.index',{filter:'discarded'})">
              <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold " :class="{'discarded':headline==='discarded'}">
                  <div>Discarded</div>
                  <i v-show="headline === 'discarded'"
                     class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
              </div>
          </a>
      </div>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              All
            </div>
          </div>
          <div class="page-section-content">

            <div v-if="sales.data.length === 0"
                 class="text-center text-gray-400 md:col-span-2 text-sm">
              No Sales Found
            </div>
            <div v-else>

              <div class="grid grid-cols-1 md:grid-cols-2">
                <inertia-link :href="route('sales.show',{id:sale.id})"
                              v-for="(sale, index) in sales.data" :key="index">
                  <div class="app-card">
                    <div class="header justify-between items-center border-b">
                      <div>
                        <div>
                          <span
                              class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                              getDate(sale.date * 1000)
                            }}</span>
                        </div>
                        <div class="type">{{ sale.code }}</div>
                        <div class="name">{{sale.client.name}}</div>


                      </div>
                      <div class="flex items-center ">
                        <div class="currency ">MK</div>
                        <div class="total">{{ numberWithCommas(sale.total) }}</div>
                      </div>
                    </div>
                    <div>
                      <sale-status :status="sale.status"/>
                    </div>
                  </div>
                </inertia-link>
              </div>

<!--              <div class="card w-full">-->
<!--                &lt;!&ndash;                            {{ invoices.data }}&ndash;&gt;-->
<!--                <div class="p-2 mb-2 relative overflow-x-auto">-->
<!--                  <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">-->
<!--                    <thead-->
<!--                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">-->
<!--                    <tr>-->
<!--                      <th scope="col" class="py-2 heading-font text-left">#</th>-->
<!--                      <th scope="col" class="py-2 heading-font text-left">Client</th>-->
<!--                      <th scope="col" class="py-2 heading-font text-right">Amount</th>-->
<!--                      <th scope="col" class="py-2 heading-font text-right">Date</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    <tr class="cursor-pointer" @click="navigateToInvoice(invoice.id)" v-for="invoice in invoices.data">-->
<!--                      <td class="py-2 text-left">{{ invoice.code }}</td>-->
<!--                      <td class="py-2 text-left">{{ invoice.name }}</td>-->
<!--                      <td class="py-2 text-right">{{ numberWithCommas(invoice.total) }}</td>-->
<!--                      <td class="py-2 text-right">{{ getDate(invoice.date * 1000) }}</td>-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                  </table>-->
<!--                </div>-->
<!--              </div>-->


              <div class="flex flex-col items-center">
                <!-- Help text -->
                <span class="text-sm text-gray-700 dark:text-gray-400">
                                    Showing <span class="font-semibold text-gray-900 dark:text-white">{{
                    sales.meta.from
                  }}</span> to <span
                    class="font-semibold text-gray-900 dark:text-white">{{ sales.meta.to }}</span> of <span
                    class="font-semibold text-gray-900 dark:text-white">{{
                        sales.meta.total
                  }}</span> Sales
                                </span>
                <div class="inline-flex mt-2 xs:mt-0">
                  <!-- Previous Button -->
                  <a :href="sales.links.prev"
                     class="inline-flex items-center px-4 py-2 mr-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg aria-hidden="true" class="w-5 h-5 mr-2" fill="currentColor"
                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                            d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Previous
                  </a>
                  <a :href="sales.links.next"
                     class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    Next
                    <svg aria-hidden="true" class="w-5 h-5 ml-2" fill="currentColor"
                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                            d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                  </a>
                </div>
              </div>
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
import SaleStatus from "@/Components/SaleStatus.vue";


export default {
  props: [
    'sales',
    'headline',
  ],
  components: {
    SaleStatus,
    AppLayout,
    PrimaryButton,
  },
  data() {
    return {}
  },
    created() {
        this.sales.links.first = this.sales.links.first + "&filter="+ this.headline
        this.sales.links.last = this.sales.links.last + "&filter="+ this.headline

        if(this.sales.links.prev != null) {
            this.sales.links.prev = this.sales.links.prev + "&filter=" + this.headline
        }
        if(this.sales.links.next != null) {
            this.sales.links.next = this.sales.links.next + "&filter=" + this.headline
        }
    },
    methods: {
    navigateToInvoice(id) {
      console.log(id)
      // this.route("invoices.show",{id:id})
      // window.location.href = this.fileUrl("invoices/view/" + id)
      this.$inertia.get(this.route('sales.show', {'id': id}))
    }
  }
}
</script>
