<template>
  <app-layout>
    <template #header>
      Application Information
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('vacancies.index')"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Vacancies
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('vacancies.show',{'id':application.data.vacancy.id})"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            {{ application.data.vacancy.title }}
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            Application
          </span>
        </div>
      </li>
    </template>

    <template #actions>

    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1">
          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Details
              </div>
            </div>
            <div class="page-section-content">

              <div class="card p-0">

                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Name</div>
                  <div>{{ application.data.firstName }} {{ application.data.lastName }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Gender</div>
                  <div>{{ application.data.gender }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Date</div>
                  <div>{{ getDate(application.data.dateOfBirth * 1000) }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Email</div>
                  <div>{{ application.data.email }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Phone Number</div>
                  <div>{{ application.data.phoneNumber }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Qualifications</div>
                  <div>{{ application.data.qualifications }}</div>
                </div>

              </div>
            </div>
          </div>


          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Responses
              </div>
            </div>
            <div class="page-section-content">


              <div>
                <div class="card">
                  <div class="p-2 mb-2 relative ">

                    <div class="mb-4" v-for="(field, index) in application.data.fields" :key="index">
                      <div class="text-xs text-gray-500">{{field.label}}</div>

                      <div v-if="field.type == 'text'" class="">{{field.value}}</div>
                      <a v-else-if="field.type == 'file'" :href="siteUrl(field.value)" target="_blank">
                        <primary-button>Download</primary-button>
                      </a>
                    </div>


                  </div>

                </div>
                <!--              <pagination :object="vacancies"/>-->
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
import SaleStatus from "@/Components/SaleStatus.vue";
import Pagination from "@/Components/Pagination.vue";

export default {
  props: ['application',
    ],
  components: {
    Pagination,
    SaleStatus,
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
      form: this.$inertia.form({
        name: ''
      }),


    }
  },
  created() {

  },
  computed: {},
  methods: {
    printQuotation() {
      this.$inertia.get(this.route('quotations.print', { 'id': this.quotation.data.id }))
    },
    // deleteQuotation() {
    //     this.form
    //         .post(this.route('quotations.delete', {'id': this.quotation.data.id}), {
    //             preserveScroll: true,
    //             onSuccess: () => this.deleteDialog = false,
    //         })
    // },

  }
}
</script>