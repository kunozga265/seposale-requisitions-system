<template>
  <app-layout>
    <template #header>
      Credit Voucher
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('credit-vouchers.index')"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Credit Vouchers
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            #{{ creditVoucher.data.code }}
          </span>
        </div>
      </li>
    </template>

    <template #actions>
      <div class="flex items-center">
        <whatsapp template="credit_voucher" :serial="creditVoucher.data.serial" :sent="false" />
        <a :href="route('credit-vouchers.print', { 'id': creditVoucher.data.id })" target="_blank">
          <primary-button class="ml-1">Print</primary-button>
        </a>
      
      </div>
      <!--           <a :href="route('credit-vouchers.edit',{'id':creditVoucher.data.id})">-->
      <!--               <primary-button>Edit</primary-button>-->
      <!--           </a>-->
      <!--         <danger-button @click.native="deleteDialog=true">Delete</danger-button>-->

    </template>




    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">


        <!--              <div v-if="creditVoucher.data.status === 0" class="mb-4 flex justify-start items-center approval-pending">-->
        <!--                <div>-->
        <!--                  <i class="mdi text-xl mdi-alert-circle"></i>-->
        <!--                </div>-->
        <!--                <div class="ml-3 text-sm">-->
        <!--                  Unpaid-->
        <!--                </div>-->
        <!--              </div>-->

        <!--              <div v-else class="flex justify-start items-center approved">-->
        <!--                <div>-->
        <!--                  <i class="mdi text-xl mdi-check-circle"></i>-->
        <!--                </div>-->
        <!--                <div class="ml-3 text-sm">-->
        <!--                  Paid-->
        <!--                </div>-->
        <!--              </div>-->


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
                  <div>{{ getDate(creditVoucher.data.date * 1000) }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Code</div>
                  <div>{{ creditVoucher.data.code }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Name</div>
                  <div>{{ creditVoucher.data.name }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Product</div>
                  <div>{{ creditVoucher.data.details }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Amount</div>
                  <div>MK{{ numberWithCommas(creditVoucher.data.amount) }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Site Location</div>
                  <div>{{ creditVoucher.data.delivery.location }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Status</div>
                  <div>{{ creditVoucher.data.paid ? "Paid" : "Unpaid" }}</div>
                </div>

              </div>
            </div>
          </div>

          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                References
              </div>
            </div>

            <div class="page-section-content">
              <div class="card p-0">
                <inertia-link v-if="creditVoucher.data.sale != null"
                  :href="route('sales.show', { id: creditVoucher.data.sale?.id })">
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Sale Code</div>
                    <div>{{ creditVoucher.data.sale?.code }}</div>
                  </div>
                </inertia-link>
                <inertia-link v-if="creditVoucher.data.delivery != null"
                  :href="route('deliveries.show', { id: creditVoucher.data.delivery?.id })">
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Delivery Code</div>
                    <div>{{ creditVoucher.data.delivery?.code }}</div>
                  </div>
                </inertia-link>
                <inertia-link v-if="creditVoucher.data.requestForm != null"
                  :href="route('request-forms.show', { id: creditVoucher.data.requestForm?.id })">
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Requisition Code</div>
                    <div>{{ creditVoucher.data.requestForm?.code }}</div>
                  </div>
                </inertia-link>
                <inertia-link v-if="creditVoucher.data.payable != null"
                  :href="route('payables.index', { id: creditVoucher.data.payable?.id })">
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Payable Code</div>
                    <div>{{ creditVoucher.data.payable?.code }}</div>
                  </div>
                </inertia-link>
                
              </div>
            </div>
          </div>

          <!-- <div class="page-section">
            <div class="page-section-content">

              <div class="card p-8 md:p-10 ">
                <div class="mb-4 font-bold">
                  {{ creditVoucher.data.details }}
                </div>

                <div class="delivery-profile grid grid-cols-1 md:grid-cols-2 gap-2">
                  <inertia-link :href="route('sales.show', { id: creditVoucher.data.delivery.summary.sale.id })">
                    <div class="mb-4">
                      <div class="text-mute text-sm">
                        Sales Order
                      </div>
                      <div class="text-gray-500 text-sm">
                        {{ creditVoucher.data.delivery.summary.sale.code }}
                      </div>
                    </div>
                  </inertia-link>
                  <inertia-link :href="route('clients.show', { id: creditVoucher.data.delivery.client.id })">
                    <div class="mb-4">
                      <div class="text-mute text-sm">
                        Client
                      </div>
                      <div class="text-gray-500 text-sm">
                        {{ creditVoucher.data.delivery.client.name }}
                      </div>
                    </div>
                  </inertia-link>

                  <div class="mb-4">
                    <div class="text-mute text-sm">
                      Site Location
                    </div>
                    <div class="text-gray-500 text-sm">
                      {{ creditVoucher.data.delivery.location }}
                    </div>
                  </div>
                  <div class="mb-4">
                    <div class="text-mute text-sm">
                      Quantity Delivered
                    </div>
                   
          <div>
            <span class="total">{{
              numberWithCommas(creditVoucher.data.delivery.quantityDelivered)
            }}/{{ numberWithCommas(creditVoucher.data.delivery.summary.quantity) }}</span>

          </div>
        </div>
      </div>
    </div>
    </div>
    </div>

    <inertia-link v-if="creditVoucher.data.sale != null"
      :href="route('clients.show', { id: creditVoucher.data.sale?.client.id })">
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
                  {{ creditVoucher.data.sale?.client.name }}
                </span>
              </div>
              <div v-show="creditVoucher.data.sale?.client.phone_number != null" class="mb-4">
                <div class="text-sm text-gray-600 flex items-center">Phone Number
                  <svg class="ml-1" height="24px" width="20px" version="1.1" id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                    xml:space="preserve">
                    <path style="fill:#fbfbfb;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
	S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z" />
                    <path style="fill:#55c76a;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
	c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
	c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z" />
                    <path style="fill:#FEFEFE;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
	c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
	c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
	c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
	c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
	l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945" />
                  </svg>
                </div>
                <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                  {{ creditVoucher.data.sale?.client.phone_number }}
                </span>
              </div>
              <div v-show="creditVoucher.data.sale?.client.phone_number_other != null" class="mb-4">
                <div class="text-sm text-gray-600">Phone Number (Secondary)</div>
                <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                  {{ creditVoucher.data.sale?.client.phone_number_other }}
                </span>
              </div>
              <div v-show="creditVoucher.data.sale?.client.email != null" class="mb-4">
                <div class="text-sm text-gray-600">Email</div>
                <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                  {{ creditVoucher.data.sale?.client.email }}
                </span>
              </div>
              <div v-show="creditVoucher.data.sale?.client.address != null" class="mb-4">
                <div class="text-sm text-gray-600">Address</div>
                <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                  {{ creditVoucher.data.sale?.client.address }}
                </span>
              </div>

            </div>

          </div>
        </div>
      </div>
    </inertia-link> -->



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

export default {
  props: ['creditVoucher'],
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
      attachPurchaseOrderDialog: false,
      form: this.$inertia.form({
        localPurchaseOrder: this.creditVoucher.data.sale?.localPurchaseOrder != null ? this.creditVoucher.data.sale?.localPurchaseOrder : "",

      }),


    }
  },
  created() {

  },
  computed: {

  },
  methods: {

  }
}
</script>
