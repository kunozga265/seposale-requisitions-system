<template>
    <app-layout>
        <template #header>
            Statements
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
                        Statements
                    </span>
                </div>
            </li>
        </template>

        <template #actions>

            <!-- <inertia-link v-if="inventory.data.producible" :href="route('production.index', { code: site.code })">
                <primary-button>
                    Production
                </primary-button>
            </inertia-link>

            <secondary-button @click.native="editInventoryDialog = true">
                Edit
            </secondary-button> -->

        </template>




        <div class="mx-9 flex">

            <a :href="route('statements.index', { section: 'overview' })">
                <div class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'overview' }">
                    <div>Overview</div>
                    <i v-show="section === 'overview'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                </div>
            </a>
            <a :href="route('statements.index', { section: 'balance-sheet' })">
                <div class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'balance-sheet' }">
                    <div>Balance Sheets</div>
                    <i v-show="section === 'balance-sheet'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                </div>
            </a>

            <a :href="route('statements.index', { section: 'income-statement' })">
                <div class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'income-statement' }">
                    <div>Income Statements</div>
                    <i v-show="section === 'income-statement'"
                        class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                </div>
            </a>

        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">


                <div class="page-section" v-if="section === 'overview'">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Overview
                        </div>
                    </div>
                    <div class="page-section-content ">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                           
                            <inertia-link :href="route('statements.show',{serial:balanceSheet.serial})" class="card mb-0 no-shadow">
                                <div class="flex justify-between items-center">
                                    <div class="p-4">
                                        <div class=" text-lg ">Balance Sheet</div>
                                        <div class="text-gray-500 text-xs">{{ balanceSheet.name }}</div>
                                    </div>
                                    <div class="mr-4">
                                        <i class="mdi mdi-arrow-right-circle text-2xl"></i>
                                    </div>
                                </div>
                            </inertia-link>
                            <inertia-link :href="route('statements.show',{serial:incomeStatement.serial})" class="card mb-0 no-shadow">
                                <div class="flex justify-between items-center">
                                    <div class="p-4">
                                        <div class=" text-lg ">Income Statement</div>
                                        <div class="text-gray-500 text-xs">{{ incomeStatement.name }}</div>
                                    </div>
                                    <div class="mr-4">
                                        <i class="mdi mdi-arrow-right-circle text-2xl"></i>
                                    </div>
                                </div>
                            </inertia-link>


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
import PrimaryButton from "@/Jetstream/Button";
import SecondaryButton from "@/Jetstream/SecondaryButton";
import DangerButton from "@/Jetstream/DangerButton";
import DialogModal from "@/Jetstream/DialogModal";
import pdf from 'vue-pdf-embed/dist/vue2-pdf-embed'
import requestStatus from "@/Components/RequestStatus";
import SaleStatus from "@/Components/SaleStatus.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import { Money } from 'v-money'
import Collection from "@/Components/Collection.vue";
import VueApexCharts from "vue-apexcharts";


export default {
    props: [
        'section',
        'balanceSheet',
        'incomeStatement',
    ],
    components: {
        Collection,
        AppLayout,
        DoughnutChart,
        PieChart,
        PrimaryButton,
        SecondaryButton,
        DangerButton,
        DialogModal,
        pdf,
        requestStatus,
        SaleStatus,
        JetValidationErrors,
        JetLabel,
        JetInput,
        Money,
        "apexchart": VueApexCharts,
    },
    data() {
        return {

            form: this.$inertia.form({

            }),
            chartOptionsApex: {
                chart: {
                    type: 'area',
                    stacked: false,
                    // height: 350,
                    zoom: {
                        type: 'x',
                        enabled: true,
                        autoScaleYaxis: true
                    },
                    toolbar: {
                        autoSelected: 'zoom'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 0,
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            // return (val / 1000000).toFixed(0);
                            return val;
                        },
                    },

                },
                xaxis: {
                    type: 'datetime',
                },
                tooltip: {
                    shared: false,
                    y: {
                        formatter: function (val) {
                            // console.log(val)
                            // return (val / 1000000).toFixed(0)
                            return val
                        }
                    }
                }
            },
        }
    },
    created() {


    },
    computed: {

    },
    watch: {},
    methods: {


    }
}
</script>
