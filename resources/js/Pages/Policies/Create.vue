<template>
    <app-layout>
        <template #header>
            New Policy
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
                    <a :href="route('policies.index')"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Policies
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

                                <div class="grid grid-cols-1 md:grid-cols-2">

                                    <div class="p-2 mb-2 md:col-span-2">
                                        <jet-label for="title" value="Title" />
                                        <jet-input id="title" type="text" class="block w-full" v-model="form.title"
                                            placeholder="e.g. Delivery Policy"
                                            autocomplete="seposale-policy-title" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="sort_order" value="Sort Order" />
                                        <jet-input id="sort_order" type="number" class="block w-full"
                                            v-model="form.sort_order" autocomplete="seposale-policy-sort-order" />
                                    </div>

                                    <div class="p-2 mb-2 flex items-center">
                                        <label class="inline-flex items-center mt-6">
                                            <input type="checkbox" v-model="form.active"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-900">Active (visible on the website)</span>
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Content
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class=" w-full sm:max-w-md md:max-w-3xl">

                                <vue2-tinymce-editor v-model="form.body"></vue2-tinymce-editor>
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
import { Vue2TinymceEditor } from "vue2-tinymce-editor";

export default {
    components: {
        AppLayout,
        JetInput,
        JetLabel,
        JetButton,
        JetValidationErrors,
        Vue2TinymceEditor,
    },
    data() {
        return {
            form: this.$inertia.form({
                title: '',
                body: '',
                sort_order: 0,
                active: true,
            }),
            error: '',
        }
    },
    computed: {
        validation() {
            if (this.form.title.length === 0) {
                this.error = "Enter policy title"
                return false
            } else if (this.form.body.length === 0) {
                this.error = "Enter policy content"
                return false
            } else
                return true

        },
    },
    methods: {
        submit() {
            this.form.post(this.route('policies.store'))
        },
    }

}
</script>
