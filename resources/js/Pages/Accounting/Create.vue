<template>
    <app-layout>
        <template #header>
            New Account
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
                    <a :href="route('accounts.index')"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Accounts
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        New
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

                                <jet-validation-errors class="mb-4" />

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">


                                    <div class="mb-2 ">
                                        <jet-label for="name" value="Account Name" />
                                        <jet-input id="name" type="text" class="block w-full" v-model="form.name"
                                            autocomplete="seposale-customer-name" />
                                    </div>

                                    <div class="mb-2">
                                        <jet-label for="number" value="Account Number" />
                                        <jet-input id="number" type="text" class="block w-full"
                                            v-model="form.number" autocomplete="seposale-customer-number" />
                                    </div>


                                    <div class=" mb-2">
                                        <jet-label for="branch" value="Branch" />
                                        <jet-input id="branch" type="text" class="block w-full" v-model="form.branch"
                                            autocomplete="seposale-customer-branch" />
                                    </div>

                                    <div class="mb-2">
                                        <jet-label for="type" value="Type" />
                                        <jet-input id="type" type="text" class="block w-full" v-model="form.type"
                                            autocomplete="seposale-customer-type" placeholder="e.g. Savings"/>
                                    </div>

                                    <div class="mb-2">
                                        <jet-label for="balance" value="Account Balance" />
                                        <money
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            v-bind="moneyMaskOptions" v-model="form.balance" />
                                    </div>

                                    <div class="mb-4">
                                        <div class="text-mute text-sm mb-1">
                                            Upload Photo
                                        </div>
                                        <input type="file" id="photo" @input="photoUpload($event.target.files[0])"
                                            class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                        <div class="text-red-500 text-xs" v-if="form.errors.photo">Required
                                        </div>
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
import PrimaryButton from "@/Jetstream/Button.vue";
import { Money } from "v-money";

export default {
    props: ["products", "clients"],
    components: {
        Money,
        PrimaryButton,
        AppLayout,
        JetInput,
        JetLabel,
        JetButton,
        JetValidationErrors,
        SecondaryButton,
        Money,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: "",
                number: "",
                photo: null,
                branch: "",
                type: "",
                balance: 0,
            }),
            error: '',
            moneyMaskOptions: {
                decimal: '.',
                thousands: ',',
                prefix: 'MK ',
                suffix: '',
                precision: 2,
                masked: false
            },
        }
    },
    created() {


    },
    computed: {
        validation() {
            if (this.form.name.length === 0) {
                this.error = "Enter account name"
                return false
            } else if (this.form.number.length === 0) {
                this.error = "Enter account number"
                return false
            } if (this.form.name.type === 0) {
                this.error = "Enter branch name"
                return false
            } else if (this.form.balance < 0) {
                this.error = "Enter account balance"
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
                .post(this.route('accounts.store'))
        },
        photoUpload(file) {
            const reader = new FileReader();
            if (file) {
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
                        type: "OTHER",
                        file: e.target.result
                    }).then(res => {
                        this.form.photo = res.data.file

                    }).catch(function (res) {
                        // this.form.errors.push(res.data.message)
                    })
                };
            }
        },
    }

}
</script>
