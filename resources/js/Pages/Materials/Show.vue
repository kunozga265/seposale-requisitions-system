<template>
    <app-layout>
        <template #header>
            {{ site.name }} - {{ material.data.name }}
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
                    <a :href="route('sites.overview', { code: site.code })"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        {{ site.name }}
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <a :href="route('production.index', { code: site.code })"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Production
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ material.data.name }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>
<!-- 
            <inertia-link :href="route('sites.sales.create', { code: site.code })">
                <primary-button>
                    Record Sale
                </primary-button>
            </inertia-link>


            <secondary-button @click.native="editInventoryDialog = true">
                Edit
            </secondary-button> -->

        </template>




        <div class="mx-9 flex">

            <a :href="route('materials.show', { code: site.code, id: material.data.id, section: 'overview' })">
                <div class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'overview' }">
                    <div>Overview</div>
                    <i v-show="section === 'overview'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                </div>
            </a>
            <a :href="route('materials.show', { code: site.code, id: material.data.id, section: 'batches' })">
                <div class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'batches' }">
                    <div>Batches</div>
                    <i v-show="section === 'batches'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
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

                        <div class="grid grid-cols-1 md:grid-cols-2  xl:grid-cols-4 gap-4">
                            <div class="card mb-0 no-shadow">

                                <div class=" text-gray-500 text-xs font-bold">Current Inventory</div>
                                <div class="flex items-end">
                                    <div class="text-2xl font-bold heading-font ">
                                        {{ numberWithCommas((material.data.quantity).toFixed(1)) }}
                                    </div>
                                    <div class="ml-1 mb-1 text-sm text-mute">
                                        // {{ material.data.units }}{{ material.data.quantity != 1 ? "s" : "" }}
                                    </div>
                                </div>

                            </div>

                            <div class="card mb-0 md:col-span-2  xl:col-span-4">
                                <div class="flex justify-between mb-4">
                                    <div class="heading-font mb-4">Usage</div>
                                    <div>
                                        <vue-date-time-picker v-model="form.dates" range />
                                    </div>
                                </div>
                                <div class="md:flex md:justify-between">

                                    <div>
                                        <div class="text-xs mb-1 text-gray-500">
                                            {{ material.data.units }}s Used
                                        </div>
                                        <div class="heading-font font-bold text-xl mb-4">
                                            {{ metrics.total }}
                                        </div>
                                    </div>

                                    <div class="md:text-right">
                                        <div class="text-xs mb-1 text-gray-500">
                                            Total Usage

                                        </div>
                                        <div class="heading-font font-bold text-xl  mb-4">
                                            MK{{ numberWithCommas(metrics.cost.toFixed(2)) }}
                                        </div>
                                    </div>

                                </div>

                                <div>
                                    <div id="chart">
                                        <apexchart type="area" :options="chartOptionsApex" height="180"
                                            :series="usagesData">
                                        </apexchart>
                                    </div>
                                </div>
                            </div>


                        

                        </div>

                    </div>

                     <div class="page-section-content ">
                               <div class="mb-4 sm:flex sm:justify-end">
                                <div>
                                    <vue-date-time-picker key="2" v-model="form.dates" range />
                                </div>
                            </div>
                            <div class="card default-table w-full">

                                <div>

                                    <button v-show="search.length > 0" @click="search = ''"
                                        class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                    <jet-input id="search" type="text" class="block w-full" placeholder="Search"
                                        v-model="search" autocomplete="seposale-filter-search" />


                                </div>
                                <div class="overflow-x-auto p-2 mb-2 relative ">
                                    <table class=" w-full default-table  text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Production Code</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Quantity</th>
                                            </tr>

                                        </thead>
                                        <tbody class="pt-8">

                                            <tr v-for="(usage, index) in filteredUsages" :key="index">
                                                <td class="p-2 text-left">{{ getDate(usage.date * 1000) }}</td>
                                                <td class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                v-if="usage.production != null"
                                                   >
                                                    <span  @click="navigateToProductionReport(usage.production.code)" >{{ usage.production.code }}</span>
                                                </td>
                                                <td v-else></td>
                                               
                                                <td class="p-2 text-right">{{
                                                    numberWithCommas(usage.quantity.toFixed(2))
                                                }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                         </div>
                </div>


                <div class="page-section" v-else-if="section === 'batches'">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Batches
                        </div>
                    </div>

                    <div class="page-section-content">
                        <div class="card default-table overflow-x-auto">
                            <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Unit Price</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Quantity</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Total</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Balance</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">File Download</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Comments</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Recorded By</th>
                                    </tr>
                                </thead>
                                <tbody class="pt-8">
                                    <tr class="cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                        v-for="(item, index) in batches.data" :key="index">
                                        <td class="p-2 text-left">{{ getDate(item.date * 1000) }}</td>
                                        <td class="p-2 text-left ">
                                            {{ numberWithCommas((item.price).toFixed(2)) }}
                                        </td>
                                        <td class="p-2 text-left ">
                                            {{ numberWithCommas(item.quantity) }}
                                        </td>
                                        <td class="p-2 text-left ">
                                            {{ numberWithCommas((item.price * item.quantity).toFixed(2)) }}
                                        </td>
                                        <td class="p-2 text-left ">
                                            {{ numberWithCommas(item.balance) }}
                                        </td>

                                        <td class="p-2 text-left ">
                                            <span v-if="item.photo == null">-</span>
                                            <a v-else :href="fileUrl(item.photo)" target="_blank">
                                                <span class="text-blue-700 text-xs font-bold">Download</span>
                                                <i class="text-blue-700 font-bold mdi mdi-download"></i>
                                            </a>
                                        </td>
                                        <td class="p-2 text-left ">{{
                                            item.comments
                                        }}
                                        </td>
                                        <td class="p-2 text-left ">{{
                                            item.user.fullName
                                        }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
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
    props: ['site',
        'material',
        'section',
        'batches',
        'usages',
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
            search: "",
            editInventoryDialog: false,
            _dates: null,
            form: this.$inertia.form({
                dates: {
                    start: null,
                    end: null,
                },

                batches: []
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
    mounted() {
        // const start = new Date();
        // start.setDate(1);
        // start.setHours(0, 0, 0, 0);
        // this.form.dates.start = start.toISOString()

    },
    computed: {
        filteredUsages() {

            let filtered = this.usages.data;

             /* Filter Sales By Code*/
            if (this.search.length !== 0) {
                filtered = (filtered).filter((usage) => {
                    return usage.code.toLowerCase().includes(this.search.toLowerCase())
                })
            }

            /* Filter By Date */
            if (this.form.dates != null) {
                if (this.form.dates.start != null) {
                    filtered = (filtered).filter((usage) => {
                        return usage.date >= this.getTimestampFromDate(this.form.dates.start)
                    })
                }
                if (this.form.dates.end != null) {
                    filtered = (filtered).filter((usage) => {
                        return usage.date <= this.getTimestampFromDate(this.form.dates.end)
                    })
                }
            }

            return filtered
        },
        usagesData() {
            let usages = [{
                name: 'Usages',
                data: []
            }];

            for (let x in this.filteredUsages) {
                usages[0].data.push({
                    x: this.filteredUsages[x].date * 1000,
                    y: this.filteredUsages[x].quantity
                })
            }

            return usages
        },
        metrics() {
            let total = 0
            let cost = 0

            for (let x in this.filteredUsages) {
                total += this.filteredUsages[x].quantity
                cost += this.filteredUsages[x].cost
            }
            return {
                total: total,
                cost: cost,
            }
        },

    },
    watch: {},
    methods: {
        editInventory() {
            this.form
                .transform(data => ({
                    ...data,
                    inventory_id: this.inventory.data.id,
                    available_stock: this.form.availableStock,
                    uncollected_stock: this.form.uncollectedStock,
                    product_id: this.form.productId,
                }))
                .post(this.route('sites.inventories.edit', { 'code': this.site.code, id: this.inventory.data.id }), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.editInventoryDialog = false
                    },
                })
        },
        navigateToProductionReport(code) {
            this.$inertia.get(this.route('production.show', { 'code': code }))
        },

    }
}
</script>
