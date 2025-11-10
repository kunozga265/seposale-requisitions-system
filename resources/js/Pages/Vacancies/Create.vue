<template>
    <app-layout>
        <template #header>
            New Vacancy
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
                    <a :href="route('vacancies.index')"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Vacancies
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
                                General
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="card w-full sm:max-w-md md:max-w-3xl">

                                <jet-validation-errors class="mb-4" />

                                <div class="p-2 mb-2 md:col-span-2">
                                    <jet-label for="title" value="Title" />
                                    <jet-input id="title" type="text" class="block w-full" v-model="form.title"
                                        autocomplete="seposale-vacancy-title" />
                                </div>

                                <div class="p-2 mb-2 md:col-span-2">
                                    <jet-label for="department" value="Department" />
                                    <jet-input id="department" type="text" class="block w-full"
                                        v-model="form.department" autocomplete="seposale-vacancy-department" />
                                </div>

                                <div class="p-2 mb-2">
                                    <jet-label for="date" value="Date" />
                                    <vue-date-time-picker color="#1a56db" v-model="date" :min-date="minDate" />
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Description
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class=" w-full sm:max-w-md md:max-w-3xl">

                                <vue2-tinymce-editor v-model="form.body"></vue2-tinymce-editor>
                            </div>
                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Questions
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="w-full sm:max-w-md md:max-w-3xl">



                                <div v-for="(field, index) in form.fields" class="card w-full ">

                                    <div class="p-2 mb-2 md:col-span-2">
                                        <jet-label for="question" :value="'Question #' + (index + 1)" />
                                        <jet-input id="question" type="text" class="block w-full" v-model="field.label"
                                            autocomplete="seposale-vacancy-question" />
                                    </div>

                                    <div class="p-2 mb-2 md:col-span-2">
                                        <jet-label for="hint" value="Hint" />
                                        <jet-input id="hint" type="text" class="block w-full"
                                            v-model="field.placeholder" autocomplete="seposale-vacancy-hint" />
                                    </div>

                                    <div>
                                        <div class="text-mute text-sm mb-1">
                                            Who to notify
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <input id="default-radio-1" type="radio" value="text" v-model="field.type"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="default-radio-1"
                                                class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Text
                                                Input</label>

                                            <input checked id="default-radio-2" type="radio" value="file"
                                                v-model="field.type"
                                                class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="default-radio-2"
                                                class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Upload
                                                Document</label>
                                        </div>
                                    </div>

                                    <span @click="removeQuestion(index)"
                                        class="flex items-center text-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 cursor">
                                        <i class="mdi mdi-close-circle "></i>
                                        <span class="ml-1 text-sm  text-red-600">Remove Question</span>
                                    </span>

                                </div>

                                <div @click="addQuestion"
                                    class="mt-2 ml-2 flex justify-start items-center cursor w-full">
                                    <div>
                                        <i class="mdi mdi-plus-circle text-blue-600"></i>
                                    </div>
                                    <div class="ml-2 text-blue-600 text-sm">
                                        Add Question
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
import { Money } from "v-money";
import WhatsappLabel from "@/Components/WhatsappLabel.vue";
import { Vue2TinymceEditor } from "vue2-tinymce-editor";


export default {
    props: ["products", "clients", "clientTypes"],
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
        Vue2TinymceEditor,
    },
    data() {
        return {
            minDate: new Date().toISOString().substr(0, 10),
            date: null,
            form: this.$inertia.form({
                title: '',
                department: '',
                body: '',
                fields: [
                    {
                        label: "",
                        placeholder: "",
                        type: 'text',
                        value: '',
                    }
                ],
            }),
            error: '',
        }
    },
    created() {


    },
    computed: {
        validation() {
            // if (this.form.name.length === 0) {
            //     this.error = "Enter customer name"
            //     return false
            // } else if (this.form.clientTypeId === null) {
            //     this.error = "Please enter customer type"
            //     return false
            // } else if (this.form.clientTypeId == 0 && this.form.clientType.length === 0) {
            //     this.error = "Please enter customer (other) type"
            //     return false
            // } else if (this.form.phoneNumber.length === 0 && this.form.phoneNumberOther.length === 0) {
            //     this.error = "Enter at least one phone number"
            //     return false
            // }
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
                    date: this.getTimestampFromDate(this.date)
                }))
                .post(this.route('vacancies.store'))
        },
        addQuestion() {
            this.form.fields.push({
                label: "",
                placeholder: "",
                type: 'text',
                value: '',
            });
        },
        removeQuestion(index) {
            this.form.fields.splice(index, 1)
        },
    }

}
</script>
