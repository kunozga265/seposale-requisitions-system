<template>
  <app-layout>
    <template #header>
      {{ site.name }} - Collection Details
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('sites.overview', { code: site.code })"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            {{ site.name }}
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('sites.collections', { code: site.code })"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Collections
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            #{{ collection.data.code }}
          </span>
        </div>
      </li>
    </template>


    <template #actions>
      <div class="md:flex grid grid-cols-2 md:grid-cols-5 gap-1">
        <whatsapp template="collection" :serial="collection.data.serial" :sent="collection.data.whatsapp" />
        <secondary-button v-if="collection.data.photo != null"
          @click.native="attachmentDialog = true">Attachement</secondary-button>
        <danger-button @click.native="deleteDialog = true">Delete</danger-button>
      </div>
    </template>

    <dialog-modal :show="deleteDialog" @close="deleteDialog = false">
      <template #title>
        Delete Collection
      </template>

      <template #content>
        Are you sure you want to delete this collection? Records will be erased and inventory quantities will be
        reversed.
      </template>

      <template #footer>
        <secondary-button @click.native="deleteDialog = false">
          Cancel
        </secondary-button>

        <danger-button class="ml-2" @click.native="deleteCollection">
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

    <dialog-modal :show="attachmentDialog" @close="attachmentDialog = false">

      <template #content>
        <div>
          <img class="w-full" :src="fileUrl(collection.data.photo)" alt="Quote Image">
        </div>
      </template>

      <template #footer>
        <secondary-button @click.native="attachmentDialog = false">
          close
        </secondary-button>
        <a :href="fileUrl(collection.data.photo)" target="_blank">
          <primary-button @click.native="attachmentDialog = false">
            Print
          </primary-button>
        </a>
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
                  <div class="text-gray-600 font-semibold">Code</div>
                  <div>{{ collection.data.code }}</div>
                </div>

                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Date</div>
                  <div>{{ getDate(collection.data.date * 1000) }}</div>
                </div>

                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Client</div>
                  <div>{{ collection.data.client.name }}</div>
                </div>

                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Product</div>
                  <div>{{ collection.data.inventory.name }}</div>
                </div>

                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Collected By</div>
                  <div>{{ collection.data.collectedBy }}</div>
                </div>

                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Quantity Collected</div>
                  <div>{{ collection.data.quantity }}</div>
                </div>

                <div class="border-b px-4 py-3 flex justify-between text-sm">
                  <div class="text-gray-600 font-semibold">Quantity Remaining</div>
                  <div>{{ collection.data.balance }}</div>
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
import SaleStatus from "@/Components/SaleStatus.vue";
import Pagination from "@/Components/Pagination.vue";
import Whatsapp from "@/Components/Whatsapp.vue";

export default {
  props: ['collection', 'site',],
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
    Whatsapp,
  },
  data() {
    return {
      attachmentDialog: false,
      deleteDialog: false,
      form: this.$inertia.form({}),


    }
  },
  created() {

  },
  computed: {},
  methods: {
    print() {
      this.$inertia.get(this.route('quotations.print', { 'id': this.quotation.data.id }))
    },
    deleteCollection() {
      this.form
        .post(this.route('collections.trash', { 'code': this.site.code, 'code': this.collection.data.code }), {
          preserveScroll: true,
          onSuccess: () => this.deleteDialog = false,
        })
    },

  }
}
</script>
