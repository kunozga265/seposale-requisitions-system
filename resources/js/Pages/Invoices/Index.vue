<template>
  <app-layout>
    <template #header>
      Invoices
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
              class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Invoices</span>
        </div>
      </li>
    </template>

    <template #actions>
      <inertia-link :href="route('invoices.create')">
        <primary-button>
          New Invoice
        </primary-button>
      </inertia-link>
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

            <div v-if="invoices.data.length === 0"
                 class="text-center text-gray-400 md:col-span-2 text-sm">
              No Invoices Found
            </div>
            <div v-else>

              <div class="grid grid-cols-1 md:grid-cols-2">
                <inertia-link :href="route('invoices.show',{id:invoice.id})"
                              v-for="(invoice, index) in invoices.data" :key="index">
                  <div class="app-card">
                    <div class="header justify-between items-center border-b">
                      <div>
                        <div>
                          <span
                              class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                              getDate(invoice.date * 1000)
                            }}</span>
                        </div>
                        <div class="type">#{{ invoice.code }}</div>


                      </div>
                      <div class="flex items-center ">
                        <div class="currency ">MK</div>
                        <div class="total">{{ numberWithCommas(invoice.total) }}</div>
                      </div>
                    </div>
                    <div>
                      <div class="name font-normal ml-3">{{ invoice.name }}</div>
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
                    invoices.meta.from
                  }}</span> to <span
                    class="font-semibold text-gray-900 dark:text-white">{{ invoices.meta.to }}</span> of <span
                    class="font-semibold text-gray-900 dark:text-white">{{
                    invoices.meta.total
                  }}</span> Invoices
                                </span>
                <div class="inline-flex mt-2 xs:mt-0">
                  <!-- Previous Button -->
                  <a :href="invoices.links.prev"
                     class="inline-flex items-center px-4 py-2 mr-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg aria-hidden="true" class="w-5 h-5 mr-2" fill="currentColor"
                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                            d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Previous
                  </a>
                  <a :href="invoices.links.next"
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

export default {
  props: [
    'invoices',
  ],
  components: {
    AppLayout,
    PrimaryButton
  },
  data() {
    return {}
  },
  methods: {
    navigateToInvoice(id) {
      console.log(id)
      // this.route("invoices.show",{id:id})
      // window.location.href = this.fileUrl("invoices/view/" + id)
      this.$inertia.get(this.route('invoices.show', {'id': id}))
    }
  }
}
</script>
