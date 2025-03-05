<template>
  <app-layout>
    <template #header>
      Transporters
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
              class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Transporters</span>
        </div>
      </li>
    </template>

    <template #actions>
      <inertia-link :href="route('transporters.create')">
        <primary-button>
          New Transporter
        </primary-button>
      </inertia-link>
<!--      <secondary-button @click.native="addVariantDialog = true">-->
<!--        Add Variant-->
<!--      </secondary-button>-->
<!--      <secondary-button @click.native="editPriceDialog = true">-->
<!--        Edit Price-->
<!--      </secondary-button>-->
    </template>


    <dialog-modal :show="addVariantDialog" @close="addVariantDialog=false">
      <template #title>
        Add Variant
      </template>

      <template #content>
        <jet-validation-errors class="mb-4"/>

        <div class="grid grid-cols-1 md:grid-cols-2">

          <div class="p-2 mb-2 md:col-span-2">
            <select v-model="form.id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
              <option :value="transporter.id" v-for="(transporter, index) in transporters.data" :key="index">
                {{ transporter.name }}
              </option>
            </select>
          </div>

          <div class="p-2 mb-2">
            <jet-label for="description" value="Variant Name"/>
            <jet-input id="description" type="text" class="block w-full"
                       v-model="form.description" placeholder="e.g. 25 Tonnes"
                       autocomplete="seposale-transporter-description"/>
          </div>

          <div class="p-2 mb-2">
            <jet-label for="unit" value="Unit"/>
            <jet-input id="unit" type="text" class="block w-full"
                       v-model="form.unit" placeholder="e.g. Tonne"
                       autocomplete="seposale-transporter-unit"/>
          </div>

          <div class="p-2 mb-2">
            <jet-label for="quantity" value="Quantity"/>
            <jet-input id="quantity" type="number" step="0.01" class="block w-full"
                       v-model="form.quantity"
                       autocomplete="seposale-transporter-quantity"/>
          </div>

          <div class="p-2 mb-2">
            <jet-label for="cost" value="Cost"/>
            <jet-input id="cost" type="number" step="0.01" class="block w-full"
                       v-model="form.cost"
                       autocomplete="seposale-transporter-cost"/>
          </div>


        </div>
      </template>

      <template #footer>
        <secondary-button @click.native="addVariantDialog=false">
          Cancel
        </secondary-button>

        <primary-button v-show="validation" class="ml-2" @click.native="addVariant" :disabled="form.processing">
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

    <dialog-modal :show="editPriceDialog" @close="editPriceDialog=false">
      <template #title>
        Edit Price
      </template>

      <template #content>
        <jet-validation-errors class="mb-4"/>

        <div class="mb-4">
          <div v-show="!priceValidation" class="flex items-center w-full text-red">
            <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ priceError }}</div>
          </div>
        </div>


        <div class="p-2 mb-2 md:col-span-2">
          <jet-label for="transporter" value="Transporter"/>
          <select v-model="variantIndex"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
            <option :value="index" v-for="(transporter, index) in filteredTransporters" :key="index">
              {{ transporter.name }} - {{ transporter.description }}
            </option>
          </select>
        </div>

        <div class="p-2 mb-2">
          <jet-label for="cost" value="Cost"/>
          <jet-input id="cost" type="number" step="0.01" class="block w-full"
                     v-model="variantCost"
                     autocomplete="seposale-transporter-cost"/>
        </div>


      </template>

      <template #footer>
        <secondary-button @click.native="editPriceDialog=false">
          Cancel
        </secondary-button>

        <primary-button v-show="priceValidation" class="ml-2" @click.native="editPrice" :disabled="form.processing">
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

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              All
            </div>
          </div>
          <div class="page-section-content">

            <div v-if="transporters.data.length === 0"
                 class="text-center text-gray-400 md:col-span-2 text-sm">
              No Transporters Found
            </div>
            <div v-else>
              <div class="card">
                <div class="p-2 mb-2 relative ">

                  <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                    <thead
                        class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                    <tr>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Name</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Phone Number</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Phone Number (Other)</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Email</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Location</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Registration Number</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Type</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Make</th>


                    </tr>

                    </thead>
                    <tbody class="pt-8">

                    <tr
                        @click="navigateToTransporter(transporter.id)"
                        class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(transporter,index) in filteredTransporters" :key="index">
                      <td class="p-2 text-left ">{{ index + 1 }}. {{ transporter.name }}</td>
                      <td class="p-2 text-left ">{{ transporter.phoneNumber }}</td>
                      <td class="p-2 text-left ">{{ transporter.phoneNumberOther }}</td>
                      <td class="p-2 text-left ">{{ transporter.email }}</td>
                      <td class="p-2 text-left ">{{ transporter.location }}</td>
                      <td class="p-2 text-left ">{{ transporter.registrationNumber }}</td>
                      <td class="p-2 text-left ">{{ transporter.type }}</td>
                      <td class="p-2 text-left ">{{ transporter.make }}</td>


                    </tr>
                    </tbody>
                  </table>
                </div>

              </div>
              <!--              <pagination :object="transporters"/>-->
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
import JetInput from "@/Jetstream/Input.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default {
  props: [
    'transporters',
  ],
  components: {
    JetValidationErrors,
    JetLabel,
    DialogModal, SecondaryButton,
    JetInput, DeliveryStatus, SaleStatus,
    Pagination,
    RequestStatus,
    AppLayout,
    PrimaryButton
  },
  data() {
    return {
      addVariantDialog: false,
      editPriceDialog: false,
      priceError: "",
      variantIndex: -1,
      variantCost: 0,
      form: this.$inertia.form({
        id: 0,
        description: '',
        unit: '',
        quantity: 1,
        cost: 0,

      }),

    }
  },
  computed: {
    filteredTransporters() {
      let arr = [];

      arr = this.transporters.data;

      //filter

      return arr;
    },
    validation() {
      if (this.form.id === 0) {
        this.error = "Select transporter"
        return false
      } else if (this.form.description.length === 0) {
        this.error = "Enter variant name"
        return false
      } else if (this.form.cost === 0) {
        this.error = "Enter cost "
        return false
      } else
        return true

    },
    priceValidation() {
      if (this.variantIndex < 0) {
        this.priceError = "Select transporter"
        return false
      } else if (this.variantCost === 0) {
        this.priceError = "Enter cost "
        return false
      } else
        return true

    },
  },
  watch: {
    variantIndex() {
      if (this.variantIndex < 0) {
        this.variantCost = 0
      } else {
        this.variantCost = this.filteredTransporters[this.variantIndex].cost
      }
    }
  },
  methods: {
    navigateToTransporter(id) {
      // this.$inertia.get(this.route('transporters.show', {'id': id}))
    },

    addVariant() {
      this.form
          .transform(data => ({
            ...data,
          }))
          .post(this.route('transporters.add-variant'), {
            onSuccess: () => this.addVariantDialog = false,
          })
    },
    editPrice() {
      this.form
          .transform(data => ({
            ...data,
            id: this.filteredTransporters[this.variantIndex].variant_id,
            cost: this.variantCost,
          }))
          .post(this.route('transporters.edit-price'), {
            onSuccess: () => {
              this.editPriceDialog = false
              this.variantIndex = -1
            },
          })
    },

  }
}
</script>
