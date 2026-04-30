<template>
    <div>


        <secondary-button @click.native="editVariantDialog = true">
            Edit
        </secondary-button>

        <dialog-modal :show="editVariantDialog" @close="editVariantDialog = false">
            <template #title>
                Edit Variant
            </template>

            <template #content>
                <jet-validation-errors class="mb-4" />

                <div class="mb-4">
                    <div v-show="!validation" class="flex items-center w-full text-red">
                        <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ priceError }}
                        </div>
                    </div>
                </div>


                <div class="p-2 mb-2 md:col-span-2">
                    <jet-label for="product" value="Product" />
                    <select v-model="variantIndex"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option :value="index" v-for="(product, index) in products" :key="index">
                            {{ product.name }} - {{ product.description }}
                        </option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="p-2 mb-2">
                        <jet-label for="description" value="Variant Name" />
                        <jet-input id="description" type="text" class="block w-full" v-model="form.description"
                            placeholder="e.g. 25 Tonnes" autocomplete="seposale-product-description" />
                    </div>
                    <div class="p-2 mb-2">
                        <jet-label for="variant_name" value="Full Site Name" />
                        <jet-input id="variant_name" type="text" class="block w-full" v-model="form.variantName"
                            placeholder="e.g. 25 Tonnes" autocomplete="seposale-product-variant_name" />
                    </div>

                    <div class="p-2 mb-2">
                        <jet-label for="unit" value="Unit" />
                        <jet-input id="unit" type="text" class="block w-full" v-model="form.unit"
                            placeholder="e.g. Tonne" autocomplete="seposale-product-unit" />
                    </div>

                    <div class="p-2 mb-2">
                        <jet-label for="quantity" value="Quantity" />
                        <jet-input id="quantity" type="number" step="0.01" class="block w-full" v-model="form.quantity"
                            autocomplete="seposale-product-quantity" />
                    </div>

                    <div class="p-2 mb-2">
                        <jet-label for="cost" value="Cost" />
                        <jet-input id="cost" type="number" step="0.01" class="block w-full" v-model="form.cost"
                            autocomplete="seposale-product-cost" />
                    </div>
                    <div class="p-2 mb-2">
                        <jet-label for="cost" value="Original Cost" />
                        <jet-input id="cost" type="number" step="0.01" class="block w-full" v-model="form.costOriginal"
                            autocomplete="seposale-product-cost" />
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <div class="text-mute text-sm mb-1">
                            Attach Photo
                        </div>
                        <input type="file" id="photo" @input="photoUpload($event.target.files[0])" accept="image/*"
                            class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                        <div class="text-red-500 text-xs" v-if="form.errors.photo">Required
                        </div>
                    </div>

                </div>

            </template>

            <template #footer>
                <secondary-button @click.native="editVariantDialog = false">
                    Cancel
                </secondary-button>

                <primary-button v-show="validation" class="ml-2" @click.native="submit" :disabled="form.processing">
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

    </div>
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
        'products',
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
            editVariantDialog: false,
            priceError: "",
            variantIndex: -1,
            variantCost: 0,
            variantCostOriginal: 0,
            form: this.$inertia.form({
                id: 0,
                description: '',
                photo: '',
                variantName: '',
                unit: '',
                quantity: 1,
                cost: 0,
                costOriginal: 0,
            }),
        }
    },
    computed: {

        validation() {
            if (this.form.id === 0) {
                this.error = "Select product"
                return false
            } else if (this.form.description.length === 0) {
                this.error = "Enter variant name"
                return false
            } else if (this.form.variantName.length === 0) {
                this.error = "Enter full variant name"
                return false
            } else if (this.form.cost === 0) {
                this.error = "Enter cost "
                return false
            } else
                return true

        },

    },
    watch: {
        variantIndex() {
            if (this.variantIndex < 0) {
                this.form.description = ''
                this.form.variantName = ''
                this.form.unit = ''
                this.form.quantity = 1
                this.form.cost = 0
                this.form.costOriginal = 0
            } else {
                this.form.id = this.products[this.variantIndex].variant_id
                this.form.description = this.products[this.variantIndex].description
                this.form.photo = this.products[this.variantIndex].photo
                this.form.variantName = this.products[this.variantIndex].name
                this.form.unit = this.products[this.variantIndex].unit
                this.form.quantity = this.products[this.variantIndex].quantity
                this.form.cost = this.products[this.variantIndex].cost
                this.form.costOriginal = this.products[this.variantIndex].costOriginal
            }
        }
    },
    methods: {

        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    id: this.products[this.variantIndex].variant_id,
                    variant_name: this.form.variantName,
                    cost_original: this.form.costOriginal,
                }))
                .post(this.route('products.edit-variant'), {
                    onSuccess: () => {
                        this.editVariantDialog = false
                        this.variantIndex = -1
                    },
                })
        },

        photoUpload(file) {
            const reader = new FileReader();
            if (file) {
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
                        type: "PRODUCTS",
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
