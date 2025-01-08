<template>
  <app-layout>
    <template #header>
      Receipt
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"></path>
          </svg>
          <a :href="route('receipts.index')"
             class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Receipts
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        #{{ receipt.data.code }}
                    </span>
        </div>
      </li>
    </template>

    <template #actions>
      <div class="flex items-center">
      <whatsapp template="receipt" :serial="receipt.data.serial" :sent="receipt.data.whatsapp"/>
      <a :href="route('receipts.print',{'id':receipt.data.id})" target="_blank">
        <primary-button class="ml-1">Print</primary-button>
      </a>
      </div>
      <!--           <a :href="route('invoices.edit',{'id':invoice.data.id})">-->
      <!--               <primary-button>Edit</primary-button>-->
      <!--           </a>-->
      <!--         <danger-button @click.native="deleteDialog=true">Delete</danger-button>-->

    </template>

    <!--      <dialog-modal :show="deleteDialog" @close="deleteDialog=false">-->
    <!--        <template #title>-->
    <!--          Delete Invoice-->
    <!--        </template>-->

    <!--        <template #content>-->
    <!--          Are you sure you want to delete this invoice?-->
    <!--          Once you delete, this invoice will no longer be available.-->
    <!--        </template>-->

    <!--        <template #footer>-->
    <!--          <secondary-button @click.native="deleteDialog=false">-->
    <!--            Cancel-->
    <!--          </secondary-button>-->

    <!--          <danger-button class="ml-2" @click.native="deleteInvoice">-->
    <!--            <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"-->
    <!--                 viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">-->
    <!--              <path-->
    <!--                  d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"-->
    <!--                  fill="#E5E7EB"/>-->
    <!--              <path-->
    <!--                  d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"-->
    <!--                  fill="currentColor"/>-->
    <!--            </svg>-->
    <!--            Proceed-->
    <!--          </danger-button>-->
    <!--        </template>-->
    <!--      </dialog-modal>-->

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div>
          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Overview
              </div>
            </div>

            <div class="page-section-content">
              <div class="card p-0">
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Date</div>
                  <div>{{ getDate(receipt.data.date * 1000) }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Receipt Number</div>
                  <div>#{{ receipt.data.code }}</div>
                </div>

                <div v-if="sale != null">
                  <div v-if="sale.data.invoice != null">
                    <inertia-link :href="route('invoices.show',{id:sale.data.invoice.id})">
                      <div class="border-b px-4 py-3 flex justify-between text-sm">
                        <div class="text-gray-600 font-semibold">Invoice Number</div>
                        <div>#{{ sale.data.invoice.code }}</div>
                      </div>
                    </inertia-link>
                  </div>
                  <inertia-link :href="route('sales.show',{id:sale.data.id})">
                    <div class="border-b px-4 py-3 flex justify-between text-sm">
                      <div class="text-gray-600 font-semibold">Sale Code</div>
                      <div>{{ sale.data.code }}</div>
                    </div>
                  </inertia-link>
                </div>
                <div v-if="siteSale != null">
                  <inertia-link :href="route('sites.sales.show', {'code':siteSale.data.site.code, 'id': siteSale.data.id})">
                    <div class="border-b px-4 py-3 flex justify-between text-sm">
                      <div class="text-gray-600 font-semibold">Sale Code</div>
                      <div>{{ siteSale.data.code }}</div>
                    </div>
                  </inertia-link>
                </div>

                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Payment Method</div>
                  <div>{{ receipt.data.paymentMethod }}</div>
                </div>

                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Reference</div>
                  <div>{{ receipt.data.reference }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Amount Received</div>
                  <div>MK{{ numberWithCommas(receipt.data.amount) }}</div>
                </div>

              </div>
            </div>
          </div>
          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Client Information
              </div>
            </div>
            <div class="page-section-content">
              <div class="card profile">
                <div class="p-8 md:p-10 grid grid-cols-1 sm:grid-cols-2">
                  <div class="mb-4">
                    <div class="text-sm text-gray-600">Name</div>
                    <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ receipt.data.client.name }}
                                        </span>
                  </div>
                  <div v-show="receipt.data.client.phone_number != null" class="mb-4">
                    <div class="text-sm text-gray-600">Phone Number</div>
                    <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ receipt.data.client.phone_number }}
                                        </span>
                  </div>
                  <div v-show="receipt.data.client.email != null" class="mb-4">
                    <div class="text-sm text-gray-600">Email</div>
                    <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ receipt.data.client.email }}
                                        </span>
                  </div>
                  <div v-show="receipt.data.client.address != null" class="mb-4">
                    <div class="text-sm text-gray-600">Address</div>
                    <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ receipt.data.client.address }}
                                        </span>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div v-if="receipt.data.information != null" class="page-section md:col-span-2">
            <div class="page-section-header">
              <div class="page-section-title">
                Products and Services
              </div>
            </div>
            <div class="page-section-content">
              <div class="card">
                <div class="p-2 relative overflow-x-auto">
                  <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class=" text-gray-600  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                      <th scope="col" class="heading-font">
                        Details
                      </th>
                      <th scope="col" class="heading-font">
                        Total Cost
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                        v-for="(info,index) in receipt.data.information"
                        :key="index"
                    >
                      <th scope="row" class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                        {{ info.name }}
                      </th>
                      <td class="py-2 pr-1">
                        {{ numberWithCommas(info.amount) }}
                      </td>
                    </tr>
                    <tr>
                      <th class="pt-4 pr-1 text-base heading-font font-bold">Total</th>
                      <td class="pt-4 pr-1 text-base font-bold">{{ numberWithCommas(receipt.data.amount) }}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="page-section">
            <div class="page-section-content">
              <div class="card p-0">
                <div class="p-3 text-white text-sm font-semibold bg-system heading-font uppercase rounded-t-lg">
                  Generated By
                </div>
                <div class="border-b px-3 py-2 flex justify-between text-sm">
                  <div class="font-semibold">Name</div>
                  <div>{{ receipt.data.generatedBy.fullName }}
                  </div>
                </div>
                <div class="border-b px-3 py-2 flex justify-between text-sm">
                  <div class="font-semibold">Position</div>
                  <div>{{ receipt.data.generatedBy.position.title }}</div>
                </div>
                <div class="px-3 py-2 flex justify-between text-sm">
                  <div class="font-semibold">Date</div>
                  <div>{{ getDate(receipt.data.date * 1000) }}</div>
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
import PrimaryButton from "@/Jetstream/Button";
import SecondaryButton from "@/Jetstream/SecondaryButton";
import DangerButton from "@/Jetstream/DangerButton";
import DialogModal from "@/Jetstream/DialogModal";
import pdf from 'vue-pdf-embed/dist/vue2-pdf-embed'
import requestStatus from "@/Components/RequestStatus";
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import Whatsapp from "@/Components/Whatsapp.vue";
// import {Head} from '@inertiajs/inertia-vue'

export default {
  props: ['receipt', 'sale','siteSale'],
  components: {
    Whatsapp,
    AppLayout,
    DoughnutChart,
    PieChart,
    PrimaryButton,
    SecondaryButton,
    DangerButton,
    DialogModal,
    pdf,
    requestStatus,
    JetValidationErrors,
    JetLabel,
    JetInput,
  },
  data() {
    return {
      loading: false,
      attachmentDialog: false,
      attachmentIndex: null,
      attachmentType: '',
      denyDialog: false,
      deleteDialog: false,
      form: this.$inertia.form({}),


    }
  },
  created() {

  },
  computed: {
    attachment() {
      if (this.attachmentIndex !== null) {
        if (this.attachmentType === 'quote')
          return this.quotes[this.attachmentIndex]
        else if (this.attachmentType === 'receipt')
          return this.receipts[this.attachmentIndex]

      }

      return null
    },

  },
  methods: {
    printReceipt() {
      this.$inertia.get(this.route('receipts.print', {'id': this.receipt.data.id}))
    },

  }
}
</script>
