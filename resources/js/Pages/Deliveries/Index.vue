<template>
  <app-layout>
    <template #header>
      Deliveries
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
              class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Deliveries</span>
        </div>
      </li>
    </template>

    <template #actions>
        <secondary-button @click.native="filterDialog=false" style="padding-top: 0.4rem; padding-bottom: 0.4rem;">
            Filter
            <i class="ml-2 mdi mdi-filter-outline text-lg"></i>
        </secondary-button>
    </template>

      <dialog-modal :show="filterDialog" @close="filterDialog=false">
          <template #title>
              Filter Deliveries
          </template>

          <template #content>

              <div class="mb-4">
<!--                  <jet-label for="requestCode" value="Enter Request Code"/>-->
<!--                  <jet-input id="requestCode" type="text" class="mt-1 block w-full" v-model="form.requestCode"-->
<!--                             autocomplete="geoserve-request-code"/>-->
<!--                  <span v-show="form.requestCode.length !==8" class="text-xs text-red-600">Enter 8 Characters</span>-->
              </div>
          </template>

          <template #footer>
              <secondary-button @click.native="filterDialog=false">
                  Cancel
              </secondary-button>

              <primary-button class="ml-2" @click.native="filterDeliveries"
                              :disabled="form.processing">
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
              </primary-button>
          </template>
      </dialog-modal>

      <div class="mx-9 flex">
          <a :href="route('deliveries.index',{filter:'all'})">
          <div class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold " :class="{'info':headline==='all'}">
              <div>All</div>
              <i v-show="headline === 'all' || headline == null"
                  class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
          </div>
          </a>
          <a :href="route('deliveries.index',{filter:'processing'})">
          <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold" :class="{'warning':headline==='processing'}">
              <div>Processing</div>
              <i  v-show="headline === 'processing'"
                  class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
          </div>
          </a>
          <a :href="route('deliveries.index',{filter:'completed'})">
          <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold " :class="{'success':headline==='completed'}">
              <div>Completed</div>
              <i v-show="headline === 'completed'"
                  class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
          </div>
          </a>
          <a :href="route('deliveries.index',{filter:'cancelled'})">
          <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold " :class="{'error':headline==='cancelled'}">
              <div>Cancelled</div>
              <i v-show="headline === 'cancelled'"
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
<!--                <select v-model="selectedSection" class="">-->
<!--                    <option value="">All</option>-->
<!--                    <option value="processing">Processing</option>-->
<!--                    <option value="completed">Completed</option>-->
<!--                    <option value="cancelled">Cancelled</option>-->
<!--                </select>-->
            </div>
          </div>
          <div class="page-section-content">

            <div v-if="deliveries.data.length === 0"
                 class="text-center text-gray-400 md:col-span-2 text-sm">
              No Deliveries Found
            </div>
            <div v-else>
              <div class="grid grid-cols-1">
                <delivery
                    v-for="(delivery, index) in deliveries.data"
                    :key="index"
                    :delivery="delivery"/>
              </div>
              <pagination :object="deliveries"/>
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
import RequestStatus from "@/Components/RequestStatus.vue";
import Pagination from "@/Components/Pagination.vue";
import SaleStatus from "@/Components/SaleStatus.vue";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import Delivery from "@/Components/Delivery.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";

export default {
  props: [
    'deliveries',
      'headline'
  ],
  components: {
      DialogModal, JetInput, JetLabel,
      SecondaryButton,
      Delivery,
    DeliveryStatus,
    SaleStatus,
    Pagination,
    RequestStatus,
    AppLayout,
    PrimaryButton
  },
  data() {
    return {
        filterDialog: false,
        form:this.$inertia.form({

        }),
        selectedSection:""
    }
  },
  methods: {
    closeDelivery(id) {
      this.$inertia.get(this.route('clients.show', {'id': id}))
    },

      filterDeliveries(){

      }

  }
}
</script>
