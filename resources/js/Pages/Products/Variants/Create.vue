<template>
    <app-layout>
        <template #header>
            New Variant
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
                    <a :href="route('products.index')"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Products
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <a :href="route('products.show', { id: product.data.id })"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        {{ product.data.name }}
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        New Variant
                    </span>
                </div>
            </li>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">Details</div>
                        </div>
                        <div class="page-section-content flex justify-center">
                            <div class="card w-full sm:max-w-md md:max-w-3xl">
                                <jet-validation-errors class="mb-4" />

                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <div class="p-2 mb-2">
                                        <jet-label value="Variant Name (short)" />
                                        <jet-input type="text" class="block w-full" v-model="form.description"
                                            placeholder="e.g. 25 Tonnes" />
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label value="Full Site Name" />
                                        <jet-input type="text" class="block w-full" v-model="form.variant_name"
                                            placeholder="e.g. Quarry Stone 25 Tonnes Aggregate 19/20mm Premium Quality" />
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label value="Unit" />
                                        <jet-input type="text" class="block w-full" v-model="form.unit"
                                            placeholder="e.g. Tonne" />
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label value="Quantity" />
                                        <jet-input type="number" step="0.01" class="block w-full" v-model="form.quantity" />
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label value="Cost" />
                                        <jet-input type="number" step="0.01" class="block w-full" v-model="form.cost" />
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label value="Original Cost (before discount)" />
                                        <jet-input type="number" step="0.01" class="block w-full" v-model="form.cost_original" />
                                    </div>
                                    <div class="p-2 mb-2 md:col-span-2">
                                        <jet-label value="Full Description" />
                                        <textarea rows="4" v-model="form.description_full"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"></textarea>
                                    </div>
                                    <div class="p-2 mb-2 md:col-span-2">
                                        <jet-label value="About" />
                                        <textarea rows="4" v-model="form.about"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"></textarea>
                                    </div>
                                    <div class="p-2 mb-2 flex items-center">
                                        <input id="featured" type="checkbox" v-model="form.featured"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="featured" class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Featured</label>
                                    </div>
                                    <div class="p-2 mb-2 flex items-center">
                                        <input id="transport_inclusive" type="checkbox" v-model="form.transport_inclusive"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="transport_inclusive" class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Transport Inclusive</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">Photos</div>
                        </div>
                        <div class="page-section-content flex justify-center">
                            <div class="card w-full sm:max-w-md md:max-w-3xl">
                                <div class="flex flex-wrap gap-3 mb-3">
                                    <div v-for="(path, index) in form.photos" :key="index" class="relative">
                                        <img :src="path" class="h-24 w-24 object-cover rounded-md">
                                        <button type="button" @click="removeNewPhoto(index)"
                                            class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-600 text-white flex items-center justify-center text-xs">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="file" multiple accept="image/*" @input="handlePhotoUpload($event.target.files)"
                                    class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <p class="text-xs text-gray-500 mt-1">You can select multiple photos.</p>
                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">Specifications</div>
                        </div>
                        <div class="page-section-content flex justify-center">
                            <div class="card w-full sm:max-w-md md:max-w-3xl">
                                <div v-for="(spec, index) in form.specifications" :key="index" class="card w-full">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <div>
                                            <jet-label value="Label" />
                                            <jet-input type="text" class="block w-full" v-model="spec.label" />
                                        </div>
                                        <div>
                                            <jet-label value="Value" />
                                            <jet-input type="text" class="block w-full" v-model="spec.value" />
                                        </div>
                                    </div>
                                    <span @click="form.specifications.splice(index, 1)"
                                        class="flex items-center text-red-600 cursor mt-2">
                                        <i class="mdi mdi-close-circle"></i>
                                        <span class="ml-1 text-sm text-red-600">Remove</span>
                                    </span>
                                </div>
                                <div @click="form.specifications.push({ label: '', value: '' })"
                                    class="mt-2 ml-2 flex justify-start items-center cursor w-full">
                                    <i class="mdi mdi-plus-circle text-blue-600"></i>
                                    <div class="ml-2 text-blue-600 text-sm">Add Specification</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">Product Information</div>
                        </div>
                        <div class="page-section-content flex justify-center">
                            <div class="card w-full sm:max-w-md md:max-w-3xl">
                                <div v-for="(info, index) in form.product_information" :key="index" class="card w-full">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <div>
                                            <jet-label value="Label" />
                                            <jet-input type="text" class="block w-full" v-model="info.label" />
                                        </div>
                                        <div>
                                            <jet-label value="Value" />
                                            <jet-input type="text" class="block w-full" v-model="info.value" />
                                        </div>
                                    </div>
                                    <span @click="form.product_information.splice(index, 1)"
                                        class="flex items-center text-red-600 cursor mt-2">
                                        <i class="mdi mdi-close-circle"></i>
                                        <span class="ml-1 text-sm text-red-600">Remove</span>
                                    </span>
                                </div>
                                <div @click="form.product_information.push({ label: '', value: '' })"
                                    class="mt-2 ml-2 flex justify-start items-center cursor w-full">
                                    <i class="mdi mdi-plus-circle text-blue-600"></i>
                                    <div class="ml-2 text-blue-600 text-sm">Add Info</div>
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

export default {
    props: ["product"],
    components: {
        AppLayout,
        JetInput,
        JetLabel,
        JetButton,
        JetValidationErrors,
    },
    data() {
        return {
            form: this.$inertia.form({
                description: '',
                variant_name: '',
                unit: '',
                quantity: 1,
                cost: 0,
                cost_original: 0,
                description_full: '',
                about: '',
                featured: false,
                transport_inclusive: false,
                specifications: [],
                product_information: [],
                photos: [],
            }),
            error: '',
        }
    },
    computed: {
        validation() {
            if (this.form.description.length === 0) {
                this.error = "Enter variant name"
                return false
            } else if (this.form.variant_name.length === 0) {
                this.error = "Enter full variant name"
                return false
            } else if (this.form.cost === 0) {
                this.error = "Enter cost"
                return false
            } else
                return true
        },
    },
    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    id: this.product.data.id,
                    cost_original: this.form.cost_original || this.form.cost,
                }))
                .post(this.route('products.variants.store'))
        },
        handlePhotoUpload(files) {
            for (const file of files) {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
                        type: "PRODUCTS",
                        file: e.target.result
                    }).then(res => {
                        this.form.photos.push(res.data.file)
                    }).catch(function (res) {
                        // this.form.errors.push(res.data.message)
                    })
                };
            }
        },
        removeNewPhoto(index) {
            this.form.photos.splice(index, 1)
        },
    }
}
</script>
