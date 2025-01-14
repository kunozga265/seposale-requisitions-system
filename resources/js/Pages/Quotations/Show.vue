<template>
  <app-layout>
    <template #header>
      Quotation
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"></path>
          </svg>
          <a :href="route('quotations.index')"
             class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Quotations
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        #{{ quotation.data.code }}
                    </span>
        </div>
      </li>
    </template>

    <template #actions>
        <div class="md:flex grid grid-cols-2 md:grid-cols-5 gap-1">
           <whatsapp template="quotation" :serial="quotation.data.serial" :sent="quotation.data.whatsapp"/>

            <primary-button class="" v-if="!quotation.data.hasSale" @click.native="generateSale">Generate Sale</primary-button>

            <a class="" :href="route('quotations.print',{'id':quotation.data.id})" target="_blank">
                <primary-button>Print</primary-button>
            </a>
            <a  class="" :href="route('quotations.edit',{'id':quotation.data.id})">
                <primary-button>Edit</primary-button>
            </a>
            <danger-button class="" @click.native="deleteDialog=true">Delete</danger-button>
        </div>



    </template>

    <dialog-modal :show="deleteDialog" @close="deleteDialog=false">
      <template #title>
        Delete Quotation
      </template>

      <template #content>
        Are you sure you want to delete this quotation?
        Once you delete, this quotation will no longer be available.
      </template>

      <template #footer>
        <secondary-button @click.native="deleteDialog=false">
          Cancel
        </secondary-button>

        <danger-button class="ml-2" @click.native="deleteQuotation">
          <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
               viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                fill="#E5E7EB"/>
            <path
                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                fill="currentColor"/>
          </svg>
          Proceed
        </danger-button>
      </template>
    </dialog-modal>

    <div class="py-6">
<!--      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">-->
<!--        <div @click="sendNotification" class="mb-8 flex justify-between items-center cursor-pointer"-->
<!--             :class="{'approval-pending':!quotation.data.whatsapp, 'approved':quotation.data.whatsapp}">-->
<!--          <div class="flex justify-start items-center cursor-pointer">-->
<!--            <div>-->
<!--              <i class="mdi text-xl"-->
<!--                 :class="{'mdi-alert-circle':!quotation.data.whatsapp, 'mdi-check-circle':quotation.data.whatsapp}"></i>-->
<!--            </div>-->
<!--            <div class="ml-3 text-sm letter-spacing-normal" style="letter-spacing: normal">-->
<!--              {{-->
<!--                quotation.data.whatsapp ? "Notification successfully sent. Resend?" : "Notification pending. Would you like to send now?"-->
<!--              }}-->
<!--            </div>-->
<!--          </div>-->

<!--          <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-black animate-spin"-->
<!--               viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--            <path-->
<!--                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"-->
<!--                fill="#E5E7EB"/>-->
<!--            <path-->
<!--                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"-->
<!--                fill="currentColor"/>-->
<!--          </svg>-->

<!--        </div>-->


        <div class="">

          <div class="page-section">
            <inertia-link :href="route('clients.show',{id:quotation.data.client.id})">
              <div class="page-section-header">
                <div class="page-section-title">
                  Customer Details
                </div>
              </div>
              <div class="page-section-content">

                <div class="card p-0">
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Quotation Number</div>
                    <div>{{ quotation.data.code }}</div>
                  </div>
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Name</div>
                    <div>{{ quotation.data.client.name }}</div>
                  </div>
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold flex items-center">Phone Number      <svg class="ml-1" height="24px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                                                                      xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                                      viewBox="0 0 512 512" xml:space="preserve">
<path style="fill:#fbfbfb;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
	S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z"/>
                      <path style="fill:#55c76a;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
	c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
	c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z"/>
                      <path style="fill:#FEFEFE;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
	c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
	c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
	c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
	c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
	l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945"/></svg></div>
                    <div>{{ quotation.data.client.phoneNumber }}</div>
                  </div>
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Phone Number</div>
                    <div>{{ quotation.data.client.phoneNumber }}</div>
                  </div>
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Email</div>
                    <div>{{ quotation.data.client.email }}</div>
                  </div>
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Address</div>
                    <div>{{ quotation.data.client.address }}</div>
                  </div>


                </div>
              </div>
            </inertia-link>

          </div>


          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Site Details
              </div>
            </div>
            <div class="page-section-content">

              <div class="card p-0">
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Location</div>
                  <div>{{ quotation.data.location }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Recipient Name</div>
                  <div>{{ quotation.data.recipientName }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Recipient Profession</div>
                  <div>{{ quotation.data.recipientProfession }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Recipient Phone Number</div>
                  <div>{{ quotation.data.recipientPhoneNumber }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="page-section md:col-span-2">
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
                        Units
                      </th>
                      <th scope="col" class="heading-font">
                        Quantity
                      </th>
                      <th scope="col" class="heading-font">
                        Unit Cost
                      </th>
                      <th scope="col" class="heading-font">
                        Total Cost
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                        v-for="(info,index) in quotation.data.information"
                        :key="index"
                    >
                      <th scope="row" class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                        {{ info.details }}
                      </th>
                      <td class="py-2 pr-1">
                        {{ info.units }}
                      </td>
                      <td class="py-2 pr-1">
                        {{ numberWithCommas(info.quantity) }}
                      </td>
                      <td class="py-2 pr-1">
                        {{ numberWithCommas(info.unitCost) }}
                      </td>
                      <td class="py-2 pr-1">
                        {{ numberWithCommas(info.totalCost) }}
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <th class="pt-4 pr-1 text-base heading-font font-bold">Total</th>
                      <td class="pt-4 pr-1 text-base font-bold">{{ numberWithCommas(quotation.data.total) }}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div v-if="quotation.data.quotes" class="page-section md:col-span-2">
            <div v-show="quotation.data.quotes.length > 0" class="page-section-header">
              <div class="page-section-title">
                Attached Quotes
              </div>
            </div>
            <div v-show="quotation.data.quotes.length > 0" class="page-section-content">
              <div class="card">

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                  <div
                      v-for="(quote,index) in this.quotes"
                      :key="index"
                  >
                    <div v-if="quote.ext === 'pdf'" @click="displayAttachment(index,'quote')" class="cursor">
                      <pdf class="w-32" :source="fileUrl(quote.file)"/>
                    </div>
                    <div v-else @click="displayAttachment(index,'quote')" class="cursor">
                      <img class="w-32" :src="fileUrl(quote.file)" alt="Quote Image">
                    </div>

                  </div>
                </div>

              </div>
            </div>
          </div>

          <div>
            <div class="page-section">
              <div class="page-section-content">
                <div class="card p-0">
                  <div class="p-3 text-white text-sm font-semibold bg-system heading-font uppercase rounded-t-lg">
                    Generated By
                  </div>
                  <div class="border-b px-3 py-2 flex justify-between text-sm">
                    <div class="font-semibold">Name</div>
                    <div>{{ quotation.data.requestedBy.firstName }} {{ quotation.data.requestedBy.middleName }}
                      {{ quotation.data.requestedBy.lastName }}
                    </div>
                  </div>
                  <div class="border-b px-3 py-2 flex justify-between text-sm">
                    <div class="font-semibold">Position</div>
                    <div>{{ quotation.data.requestedBy.position.title }}</div>
                  </div>
                  <div class="px-3 py-2 flex justify-between text-sm">
                    <div class="font-semibold">Date</div>
                    <div>{{ getDate(quotation.data.date * 1000, true) }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <dialog-modal v-if="attachment" :show="attachmentDialog" @close="attachmentDialog=false">

            <template #content>
              <div v-if="attachment.ext === 'pdf'">
                <pdf class="w-full" :source="fileUrl(attachment.file)"/>
              </div>
              <div v-else>
                <img class="w-full" :src="fileUrl(attachment.file)" alt="Quote Image">
              </div>
            </template>

            <template #footer>
              <secondary-button @click.native="attachmentDialog=false">
                close
              </secondary-button>
              <a :href="fileUrl(attachment.file)" target="_blank">
                <primary-button @click.native="attachmentDialog=false">
                  Print
                </primary-button>
              </a>
            </template>
          </dialog-modal>

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

export default {
  props: ['quotation'],
  components: {
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
    Whatsapp,
  },
  data() {
    return {
      loading: false,
      attachmentDialog: false,
      attachmentIndex: null,
      attachmentType: '',
      denyDialog: false,
      deleteDialog: false,
      form: this.$inertia.form({

      }),
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
    quotes() {
      let quotes = []
      let split = null

      for (let x in this.quotation.data.quotes) {
        split = this.quotation.data.quotes[x].split('.')
        quotes.push({
          file: this.quotation.data.quotes[x],
          ext: split[1]
        })
      }
      return quotes
    },

  },
  methods: {
    generateSale() {
      this.$inertia.get(this.route('sales.generate-from-quotation', {'id': this.quotation.data.id}))
    },
    printQuotation() {
      this.$inertia.get(this.route('quotations.print', {'id': this.quotation.data.id}))
    },
    deleteQuotation() {
      this.form
          .post(this.route('quotations.delete', {'id': this.quotation.data.id}), {
            preserveScroll: true,
            onSuccess: () => this.deleteDialog = false,
          })
    },
    displayAttachment(index, type) {
      this.attachmentIndex = index
      this.attachmentType = type
      this.attachmentDialog = true
    },

  }
}
</script>
