<template>
    <app-layout>
        <template #header>
            New Product
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
                        Products
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

                                  <div class="p-2 mb-2 md:col-span-2" >
                                    <jet-label for="name" value="Product Name"/>
                                    <jet-input id="name" type="text" class="block w-full"
                                               v-model="form.name" placeholder="e.g. Quarry Stone"
                                               autocomplete="seposale-product-name"/>
                                  </div>

                                  <div class="p-2 mb-2">
                                    <jet-label for="description" value="Varint Name"/>
                                    <jet-input id="description" type="text" class="block w-full"
                                               v-model="form.description" placeholder="e.g. 25 Tonnes"
                                               autocomplete="seposale-product-description"/>
                                  </div>

                                  <div class="p-2 mb-2">
                                    <jet-label for="unit" value="Unit"/>
                                    <jet-input id="unit" type="text" class="block w-full"
                                               v-model="form.unit" placeholder="e.g. Tonne"
                                               autocomplete="seposale-product-unit"/>
                                  </div>

                                  <div class="p-2 mb-2">
                                    <jet-label for="quantity" value="Quantity"/>
                                    <jet-input id="quantity" type="number" step="0.01" class="block w-full"
                                               v-model="form.quantity"
                                               autocomplete="seposale-product-quantity"/>
                                  </div>

                                  <div class="p-2 mb-2">
                                    <jet-label for="cost" value="Cost"/>
                                    <jet-input id="cost" type="number" step="0.01" class="block w-full"
                                               v-model="form.cost"
                                               autocomplete="seposale-product-cost"/>
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
            form: this.$inertia.form({
                name: '',
                description: '',
                unit: '',
                quantity: 1,
                cost: 0,
            }),
            error: '',
        }
    },
    created() {


    },
    computed: {
        validation() {
            if (this.form.name.length === 0) {
                this.error = "Enter product name"
                return false
            }else if (this.form.description.length === 0) {
                this.error = "Enter variant name"
                return false
            }else if (this.form.cost === 0) {
                this.error = "Enter cost "
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
                }))
                .post(this.route('products.store'))
        },
    }

}
</script>
