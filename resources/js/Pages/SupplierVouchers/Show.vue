<template>
  <app-layout>
    <template #header>
      Supplier Voucher
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('supplier-vouchers.index')"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Supplier Vouchers
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            #{{ supplierVoucher.data.code }}
          </span>
        </div>
      </li>
    </template>

    <template #actions>
      <div class="flex items-center">
        <whatsapp template="supplier_voucher" :serial="supplierVoucher.data.serial" :sent="false" />
        <a :href="route('supplier-vouchers.print', { 'id': supplierVoucher.data.id })" target="_blank">
          <primary-button class="ml-1">Print</primary-button>
        </a>

      </div>
    </template>




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
                  <div>{{ getDate(supplierVoucher.data.date * 1000) }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Code</div>
                  <div>{{ supplierVoucher.data.code }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Name</div>
                  <div>{{ supplierVoucher.data.name }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Product</div>
                  <div>{{ supplierVoucher.data.details }}</div>
                </div>
                   <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Quantity</div>
                  <div>{{ supplierVoucher.data.readableQuantity }} </div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Amount</div>
                  <div>MK{{ numberWithCommas(supplierVoucher.data.amount) }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Site Name</div>
                  <div>{{ supplierVoucher.data.site?.name ?? 'Njewa' }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Status</div>
                  <div>{{ supplierVoucher.data.paid ? "Paid" : "Unpaid" }}</div>
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
                <inertia-link v-if="supplierVoucher.data.requestForm != null"
                  :href="route('request-forms.show', { id: supplierVoucher.data.requestForm?.id })">
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Requisition Code</div>
                    <div>{{ supplierVoucher.data.requestForm?.code }}</div>
                  </div>
                </inertia-link>
                <inertia-link v-if="supplierVoucher.data.payable != null"
                  :href="route('payables.index', { id: supplierVoucher.data.payable?.id })">
                  <div class="border-b px-4 py-3 flex justify-between text-sm">
                    <div class="text-gray-600 font-semibold">Payable Code</div>
                    <div>{{ supplierVoucher.data.payable?.code }}</div>
                  </div>
                </inertia-link>

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

export default {
  props: ['supplierVoucher'],
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
        localPurchaseOrder: this.supplierVoucher.data.sale?.localPurchaseOrder != null ? this.supplierVoucher.data.sale?.localPurchaseOrder : "",

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
