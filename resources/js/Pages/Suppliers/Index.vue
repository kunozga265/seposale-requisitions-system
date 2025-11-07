<template>
  <app-layout>
    <template #header>
      Suppliers
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Suppliers</span>
        </div>
      </li>
    </template>

    <template #actions>
      <inertia-link :href="route('suppliers.create')">
        <primary-button>
          New Supplier
        </primary-button>
      </inertia-link>

      <secondary-button @click.native="editDialog = true">
        Edit Supplier
      </secondary-button>
    </template>

    <dialog-modal :show="editDialog" @close="editDialog = false">
      <template #title>
        Edit Supplier
      </template>

      <template #content>
        <jet-validation-errors class="mb-4" />

        <div class="mb-4">
          <div v-show="!editValidation" class="flex items-center w-full text-red">
            <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ error }}</div>
          </div>
        </div>


        <div class="p-2 mb-2 md:col-span-2">
          <jet-label for="supplier" value="Supplier" />
          <select v-model="supplierIndex"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
            <option :value="-1">Select Supplier</option>
            <option :value="index" v-for="(supplier, index) in filteredSuppliers" :key="index">
              {{ supplier.name }}
            </option>
          </select>
        </div>

        <div v-if="supplier != null" class="grid grid-cols-1 md:grid-cols-2">

          <div class="p-2 mb-2 ">
            <jet-label for="name" value="Name" />
            <jet-input id="name" type="text" class="block w-full" v-model="form.name" placeholder="" required
              autocomplete="seposale-Supplier-name" />
          </div>

          <div class="p-2 mb-2">
            <jet-label for="phone_number" value="Phone Number" />
            <jet-input id="phone_number" type="text" class="block w-full" v-model="form.phone_number" placeholder=""
              required autocomplete="seposale-Supplier-phone_number" />
          </div>

          <div class="p-2 mb-2">
            <jet-label for="phone_number_other" value="Phone Number (Other)" />
            <jet-input id="phone_number_other" type="text" class="block w-full" v-model="form.phone_number_other"
              placeholder="" autocomplete="seposale-Supplier-phone_number_other" />
          </div>

          <div class="p-2 mb-2">
            <jet-label for="email" value="Email" />
            <jet-input id="email" type="email" class="block w-full" v-model="form.email"
              autocomplete="seposale-Supplier-email" />
          </div>

          <div class="p-2 mb-2">
            <jet-label for="location" value="Address" />
            <jet-input id="location" type="text" class="block w-full" v-model="form.address"
              autocomplete="seposale-Supplier-address" />
          </div>


        </div>


      </template>

      <template #footer>
        <secondary-button @click.native="editDialog = false">
          Cancel
        </secondary-button>

        <primary-button v-show="editValidation" class="ml-2" @click.native="editSupplier" :disabled="form.processing">
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

            <div v-if="suppliers.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
              No Suppliers Found
            </div>
            <div v-else>
              <div class="card">
                <div class="p-2 mb-2 relative ">

                  <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                    <thead class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                      <tr>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Name</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Phone Number</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Phone Number (Other)</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Email</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Address</th>



                      </tr>

                    </thead>
                    <tbody class="pt-8">

                      <tr @click="navigateToSupplier(supplier.id)"
                        class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(supplier, index) in filteredSuppliers" :key="index">
                        <td class="p-2 text-left ">{{ index + 1 }}. {{ supplier.name }}</td>
                        <td class="p-2 text-left ">{{ supplier.phoneNumber }}</td>
                        <td class="p-2 text-left ">{{ supplier.phoneNumberOther }}</td>
                        <td class="p-2 text-left ">{{ supplier.email }}</td>
                        <td class="p-2 text-left ">{{ supplier.address }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

              </div>
              <!--              <pagination :object="suppliers"/>-->
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
    'suppliers',
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
      editDialog: false,
      error: "",
      supplier: null,
      supplierIndex: -1,
      form: this.$inertia.form({
        name: '',
        phone_number: '',
        phone_number_other: '',
        email: '',
        address: '',
      }),

    }
  },
  computed: {
    filteredSuppliers() {
      let arr = [];

      arr = this.suppliers.data;

      //filter

      return arr;
    },
    validation() {
      if (this.form.id === 0) {
        this.error = "Select supplier"
        return false
      } else
        return true

    },
    editValidation() {
      if (this.supplierIndex < 0) {
        this.error = "Select supplier"
        return false
      } else if (this.form.name.length == 0) {
        this.error = "Enter name"
        return false
      } else if (this.form.phone_number.length == 0) {
        this.error = "Enter phone number"
        return false
      } else
        return true

    },
  },
  watch: {
    supplierIndex() {
      if (this.supplierIndex < 0) {
        this.supplier = null
      } else {
        const supplier = this.filteredSuppliers[this.supplierIndex]
        this.supplier = supplier
        this.form.name = supplier.name
        this.form.phone_number = supplier.phoneNumber
        this.form.phone_number_other = supplier.phoneNumberOther
        this.form.email = supplier.email
        this.form.address = supplier.address
      }
    }
  },

  methods: {
    navigateToSupplier(id) {
      // this.$inertia.get(this.route('suppliers.show', {'id': id}))
    },

    addVariant() {
      this.form
        .transform(data => ({
          ...data,
        }))
        .post(this.route('suppliers.add-variant'), {
          onSuccess: () => this.addVariantDialog = false,
        })
    },
    editSupplier() {
      this.form
        .transform(data => ({
          ...data,
        }))
        .post(this.route('suppliers.update', { 'id': this.supplier.id }), {
          onSuccess: () => {
            this.editDialog = false
            this.supplierIndex = -1
          },
        })
    },

  }
}
</script>
