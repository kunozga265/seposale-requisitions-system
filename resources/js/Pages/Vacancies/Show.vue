<template>
  <app-layout>
    <template #header>
      Vacancy - {{ vacancy.data.title }}
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
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            {{ vacancy.data.slug }}
          </span>
        </div>
      </li>
    </template>

    <template #actions>
      <!-- <a :href="route('vacancys.edit', { 'id': vacancy.data.id })"> -->
      <!-- <primary-button>Edit</primary-button> -->
      <!-- </a> -->
    </template>

    <dialog-modal :show="deleteDialog" @close="deleteDialog = false">
      <template #title>
        Delete Vacancy
      </template>

      <template #content>
        Are you sure you want to delete this quotation?
        Once you delete, this quotation will no longer be available.
      </template>

      <template #footer>
        <secondary-button @click.native="deleteDialog = false">
          Cancel
        </secondary-button>

        <danger-button class="ml-2" @click.native="deleteQuotation">
          <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
              fill="#E5E7EB" />
            <path
              d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
              fill="currentColor" />
          </svg>
          Proceed
        </danger-button>
      </template>
    </dialog-modal>

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
                  <div class="text-gray-600 font-semibold">Date</div>
                  <div>{{ getDate(vacancy.data.date * 1000) }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Title</div>
                  <div>{{ vacancy.data.title }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Description</div>
                  <div>{{ vacancy.data.description }}</div>
                </div>

              </div>
            </div>
          </div>


          <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              Applications
            </div>
          </div>
          <div class="page-section-content">

            <div v-if="applications.data.length === 0"
                 class="text-center text-gray-400 md:col-span-2 text-sm">
              No Applications Found
            </div>
            <div v-else>
              <div class="card">
                <div class="p-2 mb-2 relative ">
                  <div  class="p-2 pb-4 heading-font text-left relative">
                    <button v-show="form.name.length > 0" @click="form.name = ''"
                            class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                      <i class="mdi mdi-close"></i>
                    </button>
                    <jet-input id="code" type="text" class="block w-full"
                               placeholder="Search Name..."
                               v-model="form.name"
                               autocomplete="seposale-filter-code"/>

                  </div>
                  <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                    <thead
                        class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                    <tr>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Name</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Gender</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Date of Birth</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Qualifications</th>
                    </tr>

                    </thead>
                    <tbody class="pt-8">

                    <tr
                        @click="navigateToApplication(application.id)"
                        class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(application,index) in applications.data" :key="index">
<!--                      <td class="p-2 text-left ">-->
<!--                        <input type="checkbox" @click="selectVacancy(vacancy.id)"-->
<!--                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"-->
<!--                        >-->
<!--                      </td>-->
                      <!-- <td class="p-2 text-left ">{{ index+1 }}</td> -->
                      <td class="p-2 text-left ">{{ application.firstName }} {{ application.lastName }}</td>
                      
                      <td class="p-2 text-left ">{{ application.gender }}</td>
                      <td class="p-2 text-left ">{{ getDate(application.dateOfBirth*1000)  }}</td>
                      <td class="p-2 text-left ">{{ application.qualifications }}</td>
                      
                    
                    </tr>
                    </tbody>
                  </table>
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
  props: ['vacancy', 
    'applications',],
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
        name:''
      }),


    }
  },
  created() {

  },
  computed: {},
  methods: {
    navigateToApplication(id) {
      this.$inertia.get(this.route('vacancies.application', {'id': id}))
    },
  }
}
</script>