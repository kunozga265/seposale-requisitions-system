<template>
    <app-layout>
        <template #header>
            Send Pricelist
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
                    <a :href="route('clients.index')"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Clients
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        Pricelist
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
                        <jet-validation-errors class="mb-4" />
                        <div class="page-section-content flex justify-center">


                            <div class="card w-full sm:max-w-md md:max-w-3xl">

                                <jet-validation-errors class="mb-4" />

                                <div class="flex items-center mb-4">
                                    <input id="default-radio-1" type="radio" value="existing" v-model="checkClient"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="default-radio-1"
                                        class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Existing</label>

                                    <input checked id="default-radio-2" type="radio" value="new" v-model="checkClient"
                                        class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="default-radio-2"
                                        class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">New</label>

                                    <input checked id="default-radio-2" type="radio" value="upload"
                                        v-model="checkClient"
                                        class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="default-radio-2"
                                        class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Upload
                                        File</label>
                                </div>
                                <div v-if="checkClient === 'existing'">

                                    <div class="p-2 mb-2">
                                        <jet-label for="clientIndex" value="Client" />
                                        <select v-model="clientIndex" id="clientIndex"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            required>
                                            <option value="-1">Select Client</option>
                                            <option v-for="(client, index) in clients.data" :value="index" :key="index">
                                                {{ client.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div v-if="client != null" class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="p-2 mb-2 md:col-span-2" v-show="client.organisation">
                                            <jet-label for="alias-name" value="Alias Name" />
                                            <jet-input id="alias-name" type="text" class="block w-full"
                                                v-model="client.alias" autocomplete="seposale-customer-alias-name"
                                                disabled />
                                        </div>
                                        <div v-if="client.type != null" class="p-2 mb-2">
                                            <jet-label for="type" value="Type" />
                                            <jet-input id="type" type="text" class="block w-full"
                                                v-model="client.type.name" autocomplete="seposale-customer-type"
                                                disabled />
                                        </div>
                                        <div class="p-2 mb-2">
                                            <whatsapp-label title="Phone Number" />
                                            <jet-input id="phoneNumber" type="text" class="block w-full"
                                                v-model="client.phoneNumber"
                                                autocomplete="seposale-customer-phone-number" disabled />
                                        </div>
                                        <div class="p-2 mb-2">
                                            <jet-label for="phoneNumber" value="Phone Number (Secondary)" />
                                            <jet-input id="phoneNumber" type="text" class="block w-full"
                                                v-model="client.phoneNumberOther"
                                                autocomplete="seposale-customer-phone-number" disabled />
                                        </div>
                                        <div class="p-2 mb-2">
                                            <jet-label for="email" value="Email" />
                                            <jet-input id="email" type="email" class="block w-full"
                                                v-model="client.email" autocomplete="seposale-customer-email"
                                                disabled />
                                        </div>
                                        <div class="p-2 mb-2">
                                            <jet-label for="address" value="Address" />
                                            <jet-input id="address" type="text" class="block w-full"
                                                v-model="client.address" autocomplete="seposale-customer-address"
                                                disabled />
                                        </div>
                                    </div>

                                </div>
                                <div v-else-if="checkClient === 'new'" class="grid grid-cols-1 md:grid-cols-2">

                                    <div class="p-2 mb-2 md:col-span-2">
                                        <div class="flex justify-between">
                                            <jet-label for="name" value="Name" />
                                            <div class="flex items-center mb-2">
                                                <input checked id="backdate" type="checkbox" value=""
                                                    v-model="form.organisation"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="backdate"
                                                    class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Organisation</label>
                                            </div>
                                        </div>

                                        <jet-input id="name" type="text" class="block w-full" v-model="form.name"
                                            autocomplete="seposale-customer-name" />
                                    </div>

                                    <div class="p-2 mb-2" :class="{ 'md:col-span-2': form.clientTypeId != 0 }">
                                        <jet-label for="clientType" value="Select Type" />
                                        <select v-model="form.clientTypeId" id="clientType"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                            <option v-for="(type, index) in clientTypes" :value="type.id" :key="index">
                                                {{ type.name }}
                                            </option>
                                            <option value="0">Other</option>
                                        </select>
                                    </div>

                                    <div v-show="form.clientTypeId == 0" class="p-2 mb-2">
                                        <jet-label for="other" value="Type (Other)" />
                                        <jet-input id="other" type="text" class="block w-full" v-model="form.clientType"
                                            autocomplete="seposale-customer-type" />
                                    </div>

                                    <div v-show="form.organisation" class="p-2 mb-2 md:col-span-2">
                                        <jet-label for="alias=name" value="Alias Name" />
                                        <jet-input id="alias-name" type="text" class="block w-full" v-model="form.alias"
                                            autocomplete="seposale-customer-alias-name" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <whatsapp-label title="Phone Number" />
                                        <jet-input id="phoneNumber" type="text" class="block w-full"
                                            v-model="form.phoneNumber" autocomplete="seposale-customer-phone-number" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="phoneNumber" value="Phone Number (Secondary)" />
                                        <jet-input id="phoneNumber" type="text" class="block w-full"
                                            v-model="form.phoneNumberOther"
                                            autocomplete="seposale-customer-phone-number-other" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="email" value="Email" />
                                        <jet-input id="email" type="email" class="block w-full" v-model="form.email"
                                            autocomplete="seposale-customer-email" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="address" value="Address" />
                                        <jet-input id="address" type="text" class="block w-full" v-model="form.address"
                                            autocomplete="seposale-customer-address" />
                                    </div>


                                </div>
                                <div v-else-if="checkClient === 'upload'" class="">
                                    <div class="mb-4 md:col-span-2">
                                        <div class="text-mute text-sm mb-1">
                                            Upload List of Clients
                                        </div>
                                        <input type="file" id="photo" @input="photoUpload($event.target.files[0])"
                                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                            class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                        <div class="text-red-500 text-xs" v-if="form.errors.file">Required
                                        </div>
                                    </div>

                                    <div class="my-4 text-xs">
                                        Ensure you have the following columns; <b>Name</b> & <b>Phone Number</b>. <br/>"Phone Number Other" and "Email" are optional.
                                    </div>



                                </div>

                                <div class="flex items-center mb-4">
                                    <input checked id="backdate" type="checkbox" v-model="form.referred"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="backdate"
                                        class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Referred</label>
                                </div>

                                <div v-if="form.referred">

                                    <div class="p-2 mb-2">
                                        <jet-label for="clientType" value="Select User" />
                                        <select v-model="form.userId" id="clientType"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            required>
                                            <option v-for="(user, index) in users.data" :value="user.id" :key="index">
                                                {{ user.fullName }}
                                            </option>
                                        </select>
                                    </div>


                                </div>

                                <div>

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
                                Send Pricelist
                            </jet-button>
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
import { Money } from "v-money";
import WhatsappLabel from "@/Components/WhatsappLabel.vue";

export default {
    props: ["users", "clients", "clientTypes"],
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
            clientIndex: -1,

            form: this.$inertia.form({
                referred: false,
                userId: null,
                name: '',
                phoneNumber: '',
                phoneNumberOther: '',
                email: '',
                address: '',
                organisation: false,
                alias: '',
                clientTypeId: null,
                clientType: '',
                file: null,
            }),
            error: '',
        }
    },
    created() {


    },
    computed: {
        client() {
            if (this.clientIndex === -1 || this.clientIndex === '-1')
                return null
            else
                return this.clients.data[this.clientIndex]
        },
        validation() {
            if (this.checkClient === "new") {
                if (this.form.name.length === 0) {
                    this.error = "Enter customer name"
                    return false
                } else if (this.form.clientTypeId != null) {
                    if (this.form.clientTypeId == 0 && this.form.clientType.length === 0) {
                        this.error = "Please enter customer (other) type"
                        return false
                    }
                } else if (this.form.phoneNumber.length === 0 && this.form.phoneNumberOther.length === 0) {
                    this.error = "Enter at least one phone number"
                    return false
                }
            } else if (this.checkClient === "existing") {
                if (parseInt(this.clientIndex) < 0 || this.client == null) {
                    this.error = "Select client"
                    return false
                }
            }else{
                  if (this.form.file?.length === 0 || this.form.file == null) {
                    this.error = "Select file"
                    return false
                } 

            }

            if (this.form.referred && this.form.userId == null) {
                this.error = "Select referred user"
                return false
            }

            this.error = ""
            return true

        },
    },
    watch: {
        checkClient() {
            this.clientIndex = -1
        },

    },
    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    type: this.checkClient,
                    user_id: this.form.userId,
                    client_id: this.client == null ? null : this.client.id,
                    client_type_id: this.form.clientTypeId,
                    client_type: this.form.clientType
                }))
                .post(this.route('clients.pricelist.send'))
        },
        photoUpload(file) {
            const reader = new FileReader();
            if (file) {
                reader.readAsDataURL(file);
                reader.onload = (e) => {

                    this.form.file = e.target.result
                    // axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
                    //     type: "PRICELIST",
                    //     file: e.target.result
                    // }).then(res => {
                    //     this.form.file = res.data.file

                    // }).catch(function (res) {
                    //     this.form.errors.push(res.data.message)
                    // })
                };
            }
        },
    }

}
</script>
