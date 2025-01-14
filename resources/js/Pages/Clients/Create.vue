<template>
    <app-layout>
        <template #header>
            New Client
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
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        Clients
                    </span>
                </div>
            </li>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Details
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="card w-full sm:max-w-md md:max-w-3xl">

                                <jet-validation-errors class="mb-4"/>

                                <div class="grid grid-cols-1 md:grid-cols-2">


                                  <div class="p-2 mb-2 md:col-span-2">
                                    <div class="flex justify-between">
                                      <jet-label for="name" value="Name"/>
                                      <div class="flex items-center mb-2">
                                        <input checked id="backdate" type="checkbox" value=""
                                               v-model="form.organisation"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="backdate"
                                               class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Organisation</label>
                                      </div>
                                    </div>

                                    <jet-input id="name" type="text" class="block w-full"
                                               v-model="form.name"
                                               autocomplete="seposale-customer-name"/>
                                  </div>

                                  <div v-show="form.organisation" class="p-2 mb-2 md:col-span-2">
                                    <jet-label for="alias=name" value="Alias Name"/>
                                    <jet-input id="alias-name" type="text" class="block w-full"
                                               v-model="form.alias"
                                               autocomplete="seposale-customer-alias-name"/>
                                  </div>

                                  <div class="p-2 mb-2">
                                    <whatsapp-label title="Phone Number"/>
                                    <jet-input id="phoneNumber" type="text" class="block w-full"
                                               v-model="form.phoneNumber"
                                               autocomplete="seposale-customer-phone-number"/>
                                  </div>

                                  <div class="p-2 mb-2">
                                    <jet-label for="phoneNumber" value="Phone Number (Secondary)"/>
                                    <jet-input id="phoneNumber" type="text" class="block w-full"
                                               v-model="form.phoneNumberOther"
                                               autocomplete="seposale-customer-phone-number-other"/>
                                  </div>

                                  <div class="p-2 mb-2">
                                    <jet-label for="email" value="Email"/>
                                    <jet-input id="email" type="email" class="block w-full"
                                               v-model="form.email"
                                               autocomplete="seposale-customer-email"/>
                                  </div>

                                  <div class="p-2 mb-2">
                                    <jet-label for="address" value="Address"/>
                                    <jet-input id="address" type="text" class="block w-full"
                                               v-model="form.address"
                                               autocomplete="seposale-customer-address"/>
                                  </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="fixed right-6 bottom-6 md:right-10 md:bottom-10">
                        <div v-show="!validation" id="toast-danger"
                             class="flex items-center w-full max-w-xs p-4 mb-4 text-red-700 bg-red-100 rounded-lg shadow dark:text-red-400 dark:bg-red-800"
                             role="alert">
                            <div
                                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                                <i class="mdi mdi-alert-circle text-2xl"></i>
                            </div>
                            <div class="ml-3 text-sm font-normal">{{ error }}</div>
                        </div>
                    </div>


                    <div class="text-center mt-8">
                        <div v-show="validation">
                            <jet-button class="ml-4 text-center" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                Create
                            </jet-button>
                            <div class="text-gray-600 text-sm">Please confirm all details before submission</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from "@/Jetstream/Button";
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import SecondaryButton from '@/Jetstream/SecondaryButton'
import pdf from 'vue-pdf-embed/dist/vue2-pdf-embed'
import PrimaryButton from "@/Jetstream/Button.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";
import {Money} from "v-money";
import WhatsappLabel from "@/Components/WhatsappLabel.vue";

export default {
    props: ["products", "clients"],
    components: {
      WhatsappLabel,
        Money,
        DialogModal, PrimaryButton,
        AppLayout,
        JetInput,
        JetLabel,
        JetButton,
        JetValidationErrors,
        SecondaryButton,
        pdf,
    },
    data() {
        return {
            checkClient: "existing",
            addRecordDialog: false,
            productIndex: -1,
            clientIndex: -1,
            addRecordUnits: "",
            addRecordQuantity: 0,
            addRecordUnitCost: 0,
            form: this.$inertia.form({
                name: '',
                phoneNumber: '',
                phoneNumberOther: '',
                email: '',
                address: '',
              organisation: false,
              alias: '',
            }),
            error: '',
        }
    },
    created() {


    },
    computed: {
        validation() {
            if (this.form.name.length === 0) {
                this.error = "Enter customer name"
                return false
            } else
                return true

        },
    },
    watch: {

    },
    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    total: this.totalCost,
                    quotes: this.quoteFiles,
                    client_id: this.client == null ? null : this.client.id
                }))
                .post(this.route('clients.store'))
        },
    }

}
</script>
