<template>
    <app-layout>
        <template #header>
            Production
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
                    <a :href="route('sites.overview',{code:site.code})"
                       class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        {{site.name}}
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span
                        class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        Production
                    </span>
                </div>
            </li>
        </template>

        <template #actions>
<!--            <inertia-link :href="route('expenses.create')">-->
                <primary-button @click.native="addItemDialog = true">
                    Add Stock
                </primary-button>
<!--            </inertia-link>-->
            <secondary-button @click.native="addItemDialog = true">
                Add Item
            </secondary-button>

        </template>

        <dialog-modal :show="addItemDialog" @close="addItemDialog=false">
            <template #title>
                Add Stock Item
            </template>

            <template #content>
                <jet-validation-errors class="mb-4"/>

                <div class="grid grid-cols-1 md:grid-cols-2">

                    <div class="p-2 mb-2">
                        <jet-label for="name" value="Name"/>
                        <jet-input id="name" type="text" class="block w-full"
                                   v-model="form.name"
                                   autocomplete="seposale-stock-name"/>
                    </div>

                    <div class="p-2 mb-2">
                        <jet-label for="units" value="Units"/>
                        <jet-input id="units" type="text" class="block w-full"
                                   v-model="form.units"
                                   autocomplete="seposale-stock-unit"/>
                    </div>

                    <div class="p-2 mb-2">
                        <jet-label for="quantity" value="Quantity"/>
                        <jet-input id="quantity" type="number" step="0.01" class="block w-full"
                                   v-model="form.quantity"
                                   autocomplete="seposale-stock-quantity"/>
                    </div>

                    <div class="p-2 mb-2">
                        <jet-label for="threshold" value="Threshold"/>
                        <jet-input id="threshold" type="number" step="0.01" class="block w-full"
                                   v-model="form.threshold"
                                   autocomplete="seposale-stock-threshold"/>
                    </div>


                </div>
            </template>

            <template #footer>
                <secondary-button @click.native="addItemDialog=false">
                    Cancel
                </secondary-button>

                <primary-button  class="ml-2" @click.native="addItem" :disabled="form.processing">
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
                            Overview
                        </div>
                    </div>
<!--                    <div class="page-section-content">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-4">
                            <div class="card mb-0 md:col-span-2 lg:col-span-3">
                                <div class="flex justify-between mb-4">
                                    <div class="heading-font mb-4">Expenses</div>
                                    <div>
                                        <vue-date-time-picker
                                            v-model="form.dates"
                                            range
                                        />
                                    </div>
                                </div>
                                <div class="text-xs mb-1 text-gray-500">Total Expenses</div>
                                <div class="heading-font font-bold text-xl mb-4">
                                    MK{{ numberWithCommas(expensesTotal) }}
                                </div>

                                <div>
                                    <div id="chart">
                                        <vue-apex-charts type="area" :options="chartOptionsApex"
                                                   height="180"
                                                   :series="expensesData"></vue-apex-charts>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-0 profit-loss">
                                <div class="heading-font mb-4">Categories</div>

                                <div>
                                    <PieChart
                                        :chart-options="categoriesChartOptions"
                                        :chart-data="categoriesData"
                                        chart-id="categories"
                                        dataset-id-key="categories"
                                        :height="200"
                                    />
                                </div>
                            </div>

                            <div class="card mb-0 md:col-span-2">
                                <div class="heading-font mb-4">Breakdown</div>

                                <div v-for="(category,index) in listOfCategories" :index="index"
                                     class="record px-2 mb-1 rounded flex justify-between items-center ">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 bg-gray-100 rounded-full flex justify-center items-center">
                                            <div class="text-xs text-gray-500">{{ index + 1 }}</div>
                                        </div>
                                        <div class="ml-3 text-sm ">
                                            <div class="text-sm">{{ category.name }}</div>
                                        </div>
                                    </div>
                                    <div class="heading-font text-xs text-gray-500">
                                        MK{{ numberWithCommas(category.total) }}
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>-->
                </div>


            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PrimaryButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input.vue";
import PieChart from "@/Components/Charts/PieChart.vue";
import VueApexCharts from "vue-apexcharts";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import SaleStatus from "@/Components/SaleStatus.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";
import JetLabel from "@/Jetstream/Label.vue";

export default {
    props: [
        'site',
        'stocks',
    ],
    components: {
        JetLabel, DialogModal, JetValidationErrors, SecondaryButton,
        SaleStatus, DeliveryStatus,
        JetInput,
        AppLayout,
        PrimaryButton,
        PieChart,
        VueApexCharts

    },
    data() {
        return {
            addItemDialog: false,
            form: this.$inertia.form({
                dates: null,
                name: "",
                units: "",
                quantity: 0,
                threshold: 0,
            }),
        }
    },
    computed: {


    },
    methods: {
        navigateToRequisition(id) {
            this.$inertia.get(this.route('request-forms.show', {'id': id}))
        },
        addItem() {
            this.form
                .transform(data => ({
                    ...data,
                    "site_id" : this.site.id
                }))
                .post(this.route('materials.store'))
        },
    }
}
</script>
