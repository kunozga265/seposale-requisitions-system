<template>
  <app-layout>
    <template #header>
      Production Report
      <!-- - {{ getDate(production.data.date * 1000) }} -->
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('sites.overview', { code: production.data.site.code })"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            {{ production.data.site.name }}
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('production.index', { code: production.data.site.code })"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Production
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            Report
          </span>
        </div>
      </li>
    </template>

    <template #actions>

      <a :href="route('production.print', { 'code': production.data.code })">

        <primary-button >Print</primary-button>
      </a>
      <danger-button @click.native="deleteDialog = true">Delete</danger-button>
    </template>

    <dialog-modal :show="deleteDialog" @close="deleteDialog = false">
      <template #title>
        Delete Report
      </template>

      <template #content>
        Are you sure you want to delete this report? All material usage reports and damages will be deleted as well.
      </template>

      <template #footer>
        <secondary-button @click.native="deleteDialog = false">
          Cancel
        </secondary-button>

        <danger-button class="ml-2" @click.native="deleteReport">
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

        <div class="grid grid-cols-1 gap-4">
          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Overview
              </div>
            </div>
            <div class="page-section-content">

              <div class="card p-0">
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">
                    Date
                  </div>
                  <div>{{ getDate(production.data.date * 1000) }}</div>
                </div>
                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">
                    Code
                  </div>
                  <div>{{ production.data.code }}</div>
                </div>
                <div @click="navigateToInventory(batch.inventory.id)" 
                v-for="(batch, index) in production.data.batches"
                  class="border-b px-4 py-3 flex justify-between text-sm cursor-pointer">
                  <div>
                    <span class="text-gray-600 font-semibold">
                      {{ batch.inventory.name }}
                    </span>
                    <span class="text-gray-500 text-xs"> (Curing Date: {{ getDate(batch.readyDate * 1000) }})</span>
                  </div>
                  <div>{{ numberWithCommas(batch.quantity) }} {{ batch.inventory.units }}{{ batch.quantity == 1 ? "" :
                    "s"
                    }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Usage
              </div>
            </div>
            <div class="page-section-content">

              <div class="card p-0" v-if="production.data.usages.length > 0">
                <div v-for="(usage, index) in production.data.usages"
                  class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">{{ usage.material.name }}</div>
                  <div>{{ numberWithCommas(usage.quantity) }} {{ usage.material.units }}{{ usage.quantity == 1 ? "" :
                    "s"
                    }}
                  </div>
                </div>
              </div>
              <div v-else class="text-center text-gray-400 md:col-span-2 text-sm">
                No Records Found

              </div>
            </div>
          </div>

          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Damages
              </div>
            </div>
            <div class="page-section-content">

              <div class="card p-0" v-if="production.data.damages.length > 0">
                <div @click="navigateToInventory(damage.inventory.id)"
                  v-for="(damage, index) in production.data.damages"
                  class="border-b px-4 py-3 flex justify-between text-sm cursor-pointer">
                  <div class="text-gray-600 font-semibold">{{ damage.inventory.name }}</div>
                  <div>{{ numberWithCommas(damage.quantity) }} {{ damage.inventory.units }}{{ damage.quantity == 1 ?
                    "" : "s"
                  }}</div>
                </div>
              </div>
              <div v-else class="text-center text-gray-400 md:col-span-2 text-sm">
                No Records Found

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
  props: ['production', 'site',],
  components: {
    AppLayout,
    DoughnutChart,
    PieChart,
    PrimaryButton,
    SecondaryButton,
    DangerButton,
    DialogModal,
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
  computed: {},
  methods: {
    navigateToInventory(id) {
      this.$inertia.get(this.route('sites.inventories.show', { 'code': this.production.data.site.code, 'id': id }))
    },
    print() {
      this.$inertia.get(this.route('production.print', { 'code': this.production.data.code }))
    },
    deleteReport() {
      this.form
        .post(this.route('production.delete', { 'code': this.production.data.code }), {
          preserveScroll: true,
          onSuccess: () => this.deleteDialog = false,
        })
    },

  }
}
</script>
