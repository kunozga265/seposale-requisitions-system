<template>
    <app-layout>
        <template #header>
            {{ site.name }} Sales
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
                        Njewa
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        Sales
                    </span>
                </div>
            </li>
        </template>

        <template #actions>
            <inertia-link :href="route('sales.create')">
                <primary-button>
                    Record Sale
                </primary-button>
            </inertia-link>
        </template>

        <div class="mx-9 flex justify-between">
            <div>
                <div v-if="section === 'block'" class="flex">
                    <a :href="route('sales.index', { section: 'block', filter: 'all' })">
                        <div class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                            :class="{ 'info': headline === 'all' }">
                            <div>All</div>
                            <i v-show="headline === 'all' || headline == null"
                                class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                        </div>
                    </a>
                    <a :href="route('sales.index', { section: 'block', filter: 'unpaid' })">
                        <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold"
                            :class="{ 'error': headline === 'unpaid' }">
                            <div>Unpaid</div>
                            <i v-show="headline === 'unpaid'"
                                class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                        </div>
                    </a>
                    <a :href="route('sales.index', { section: 'block', filter: 'partially-paid' })">
                        <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                            :class="{ 'warning': headline === 'partially-paid' }">
                            <div>Partially Paid</div>
                            <i v-show="headline === 'partially-paid'"
                                class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                        </div>
                    </a>
                    <a :href="route('sales.index', { section: 'block', filter: 'fully-paid' })">
                        <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                            :class="{ 'success': headline === 'fully-paid' }">
                            <div>Fully Paid</div>
                            <i v-show="headline === 'fully-paid'"
                                class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                        </div>
                    </a>
                    <a :href="route('sales.index', { section: 'block', filter: 'closed' })">
                        <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                            :class="{ 'error': headline === 'closed' }">
                            <div>Closed</div>
                            <i v-show="headline === 'closed'"
                                class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                        </div>
                    </a>
                    <a :href="route('sales.index', { section: 'block', filter: 'discarded' })">
                        <div class="ml-1 flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                            :class="{ 'error': headline === 'discarded' }">
                            <div>Discarded</div>
                            <i v-show="headline === 'discarded'"
                                class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                        </div>
                    </a>
                </div>
            </div>
            <div class="view-options">
                <inertia-link :class="{ 'active': section !== 'block' }"
                    :href="route('sites.sales', { code: site.code, section: 'tabular' })">
                    <i class="mdi mdi-table text-lg "></i>
                </inertia-link>
                <inertia-link :class="{ 'active': section === 'block' }"
                    :href="route('sites.sales', { code: site.code, section: 'block' })">
                    <i class="mdi mdi-format-list-text text-lg "></i>
                </inertia-link>

            </div>
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <!-- <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Overview
                        </div>
                    </div>
                    <div class="page-section-content ">
                        <div class="card">
                            <div class="md:p-8 w-full  mx-auto relative">
                                <div class="flex justify-end">
                                    <select v-model="chartYear"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        required>
                                        <option v-for="(data, index) in chartData" :value="index" :key="index">
                                            {{ data.year }}
                                        </option>
                                    </select>
                                </div>
                                <BarChart :chart-options="yearlySalesOptions" :chart-data="yearlySalesData" />
                                {{ chartData }}
                            </div>
                        </div>
                    </div>
                </div> -->

                <div v-if="section === 'block'" class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            All
                        </div>
                    </div>
                    <div class="page-section-content">

                        <div v-if="sales.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
                            No Sales Found
                        </div>
                        <div v-else>

                            <div class="grid grid-cols-1 md:grid-cols-2">
                                <inertia-link :href="route('sales.show', { id: sale.id })"
                                    v-for="(sale, index) in sales.data" :key="index">
                                    <div class="app-card">
                                        <div class="header justify-between items-center border-b">
                                            <div>
                                                <div>
                                                    <span
                                                        class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                                                            getDate(sale.date * 1000)
                                                        }}</span>
                                                </div>
                                                <div class="type">{{ sale.code }}</div>
                                                <div class="name">{{ sale.client.name }}</div>


                                            </div>
                                            <div class="flex items-center ">
                                                <div class="currency ">MK</div>
                                                <div class="total">{{ numberWithCommas(sale.total) }}</div>
                                            </div>
                                        </div>
                                        <div>
                                            <sale-status :status="sale.status" />
                                        </div>
                                    </div>
                                </inertia-link>
                            </div>


                            <div class="flex flex-col items-center">
                                <!-- Help text -->
                                <span class="text-sm text-gray-700 dark:text-gray-400">
                                    Showing <span class="font-semibold text-gray-900 dark:text-white">{{
                                        sales.meta.from
                                        }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{
                                            sales.meta.to
                                        }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{
                                            sales.meta.total
                                        }}</span> Sales
                                </span>
                                <div class="inline-flex mt-2 xs:mt-0">
                                    <!-- Previous Button -->
                                    <a :href="sales.links.prev"
                                        class="inline-flex items-center px-4 py-2 mr-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                        <svg aria-hidden="true" class="w-5 h-5 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Previous
                                    </a>
                                    <a :href="sales.links.next"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                        Next
                                        <svg aria-hidden="true" class="w-5 h-5 ml-2" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>

                    <div class="page-section">

                        <div class="page-section-header">
                            <div class="page-section-title">
                                Records
                            </div>
                        </div>
                        <div class="page-section-content ">

                            <div class="card default-table w-full">
                                <!--                            {{ invoices.data }}-->
                                <div class="relative my-2">
                                    <button v-show="form.code.length > 0" @click="form.search = ''"
                                        class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                    <jet-input id="code" type="text" class="block w-full" placeholder="Search"
                                        v-model="form.search" autocomplete="seposale-filter-code" />

                                </div>
                                <div class="overflow-x-auto p-2 mb-2 relative ">
                                    <table class=" w-full default-table  text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>

                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Client</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Amount</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>

                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Payment Status
                                                </th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Collection
                                                    Status</th>

                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Delivery Status
                                                </th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Profit</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Pending
                                                    Payments</th>

                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Quantity</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Collected</th>
                                            </tr>
                                            <!-- <tr>
                                                <th scope="col" class="p-2 pb-4 heading-font text-left relative">
                                                    <button v-show="form.code.length > 0" @click="form.code = ''"
                                                        class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                                                        <i class="mdi mdi-close"></i>
                                                    </button>
                                                    <jet-input id="code" type="text" class="block w-full"
                                                        placeholder="Search" v-model="form.code"
                                                        autocomplete="seposale-filter-code" />

                                                </th>
                                                <th scope="col" class="p-2 pb-4 heading-font text-left relative">
                                                    <button v-show="form.client.length > 0" @click="form.client = ''"
                                                        class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                                                        <i class="mdi mdi-close"></i>
                                                    </button>
                                                    <jet-input id="client" type="text" class="block w-full"
                                                        placeholder="Search" v-model="form.client"
                                                        autocomplete="seposale-filter-client" />
                                                </th>
                                                <th scope="col" class="p-2 pb-4 heading-font text-left relative">
                                                    <button v-show="form.product.length > 0" @click="form.product = ''"
                                                        class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                                                        <i class="mdi mdi-close"></i>
                                                    </button>
                                                    <jet-input id="product" type="text" class="block w-full"
                                                        placeholder="Search" v-model="form.product"
                                                        autocomplete="seposale-filter-product" />
                                                </th>
                                                <th scope="col" class="p-2 pb-4 heading-font text-right"></th>
                                                <th scope="col" class="p-2 pb-4 heading-font text-right"></th>
                                            
                                            </tr> -->

                                        </thead>
                                        <tbody class="pt-8">

                                            <tr v-for="(sale, index) in filteredSales" :key="index">
                                                <td class="p-2 text-left">{{ getDate(sale.date * 1000) }}</td>

                                                <td class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                    @click="navigateToSale(sale.id)">{{ sale.code }}
                                                </td>
                                                <td class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                    @click="navigateToClient(sale.client.id)">{{ sale.client.name }}
                                                </td>
                                                <td @click="navigateToInventory(sale.product.inventory.id)"
                                                    class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200 ">
                                                    {{ sale.product.inventory.name }}</td>
                                                <td class="p-2 text-right">{{
                                                    numberWithCommas(sale.product.amount.toFixed(2))
                                                }}
                                                </td>
                                                <td class="p-2 text-right">{{
                                                    numberWithCommas(sale.product.balance.toFixed(2))
                                                }}
                                                </td>
                                                <td class="">
                                                    <sale-status
                                                        class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                        ç :status="sale.product.paymentStatus" :is-solo="true" />
                                                </td>
                                                <td class="px-2">
                                                    <collection v-if="sale.product.delivery == null"
                                                        class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                        :client="sale.client" :product="sale.product" :is-solo="true" />
                                                    <span v-else>-</span>
                                                </td>





                                                <td class="px-2" v-if="sale.product.delivery != null">
                                                    <delivery-status class="mr-1" :productCompound="sale.product"
                                                        :overdue="sale.product.overdue" :is-solo="true"
                                                        @clickEvent="navigateToDelivery(sale.product.delivery.id)" />
                                                </td>
                                                <td v-else>
                                                    -
                                                </td>

                                                <td class="p-2 text-right">
                                                    <profit :value="sale.product.profit" />
                                                </td>
                                                <td class="p-2 text-right">
                                                    <profit :value="sale.product.pendingPayments" />
                                                </td>

                                                <td class="p-2 text-right">{{
                                                    numberWithCommas(sale.product.quantity.toFixed(2))
                                                }}
                                                </td>

                                                <td class="p-2 text-right">{{
                                                    numberWithCommas(sale.product.collected.toFixed(2))
                                                }}
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <pagination :object="sales" />
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
import Request from "@/Components/Request";
import PrimaryButton from "@/Jetstream/Button";
import SaleStatus from "@/Components/SaleStatus.vue";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import BarChart from "@/Components/Charts/BarChart.vue";
import Pagination from "@/Components/Pagination.vue";
import Collection from "@/Components/Collection.vue";



export default {
    props: [
        'site',
        'sales',
        'headline',
        'section',
        'chartData',
        'awaitingConfirmation',
    ],
    components: {
        Collection,
        BarChart,
        JetValidationErrors, JetInput, JetLabel,
        DeliveryStatus,
        SaleStatus,
        AppLayout,
        PrimaryButton,
        DoughnutChart,
        Pagination,
    },
    data() {
        return {
            chartYear: 0,
            form: this.$inertia.form({
                dates: null,
                code: "",
                client: "",
                product: "",
                paymentStatus: -1,
                deliveryStatus: -1,
            }),
            paymentStatuses: [
                {
                    'name': 'All',
                    'value': -1
                },
                {
                    'name': 'Unpaid',
                    'value': 0
                },
                {
                    'name': 'Partially Paid',
                    'value': 1
                },
                {
                    'name': 'Fully Paid',
                    'value': 2
                },

            ],
            deliveryStatuses: [
                {
                    'name': 'All',
                    'value': -1
                },
                {
                    'name': 'Not Delivered',
                    'value': 0
                },
                {
                    'name': 'Processing',
                    'value': 1
                },
                {
                    'name': 'Delivered',
                    'value': 2
                },
                {
                    'name': 'Cancelled',
                    'value': 3
                },
            ],
            chartOptions: {
                plugins: {
                    tooltip: {
                        enabled: false
                    },
                    legend: {
                        display: false
                    }
                }, cutout: 20
            },
            yearlySalesData: {
                datasets: [
                    {
                        label: "Paid",
                        data: [],
                        backgroundColor: ['#1a56db', '#ed0b4b', '#b1bbc9', '#e3ebf6'],
                        stack: 'Stack 0',

                    },
                    {
                        label: "Unpaid",
                        data: [],
                        backgroundColor: ['#ef444447'],
                        stack: 'Stack 0',

                    },
                    {
                        label: "Costs",
                        data: [],
                        backgroundColor: ['#ef444447'],
                        stack: 'Stack 1',

                    },
                    {
                        label: "Profit",
                        data: [],
                        backgroundColor: ['#22c55e45'],
                        stack: 'Stack 1',

                    },
                ],
                labels: []
            },
            yearlySalesOptions: {
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    xAxes: {
                        grid: {
                            display: false
                        },
                        stacked: true,
                    },
                    yAxes: {
                        grid: {
                            display: false
                        },
                        stacked: true,
                    },
                },
                maintainAspectRatio: false
            },
        }
    },
    created() {
        if (this.section === 'block') {
            this.sales.links.first = this.sales.links.first + "&filter=" + this.headline
            this.sales.links.last = this.sales.links.last + "&filter=" + this.headline

            if (this.sales.links.prev != null) {
                this.sales.links.prev = this.sales.links.prev + "&filter=" + this.headline
            }
            if (this.sales.links.next != null) {
                this.sales.links.next = this.sales.links.next + "&filter=" + this.headline
            }
        }
        //Yearly Sales Chart
        // for (let x in this.chartData) {
        for (let y in this.chartData[0].data) {
            this.yearlySalesData.datasets[0].data.push(this.chartData[0].data[y].total)
            this.yearlySalesData.datasets[1].data.push(this.chartData[0].data[y].unpaid)
            this.yearlySalesData.datasets[2].data.push(this.chartData[0].data[y].costs)
            this.yearlySalesData.datasets[3].data.push(this.chartData[0].data[y].profit)
            this.yearlySalesData.labels.push(this.chartData[0].data[y].month)
            // }
        }

    },
    computed: {
        dates() {
            return {
                start: this.getTimestampFromDate(this.form.dates.start),
                end: this.getTimestampFromDate(this.form.dates.end),
            }
        },
        filteredSales() {
            let filtered = []

            for (let x in this.sales.data) {
                for (let y in this.sales.data[x].products) {
                    filtered.push({
                        id: this.sales.data[x].id,
                        code: this.sales.data[x].code,
                        date: this.sales.data[x].date,
                        client: this.sales.data[x].client,
                        product: this.sales.data[x].products[y],
                    })
                }
            }

            /* Filter Sales By Code*/
            if (this.form.code.length !== 0) {
                filtered = (filtered).filter((sale) => {
                    return sale.code.toLowerCase().includes(this.form.code.toLowerCase())
                })
            }

            /* Filter Sales By Client*/
            if (this.form.client.length !== 0) {
                filtered = (filtered).filter((sale) => {
                    return sale.client.name.toLowerCase().includes(this.form.client.toLowerCase())
                })
            }

            /* Filter Sales By Product Name */
            if (this.form.product.length !== 0) {
                filtered = (filtered).filter((sale) => {
                    return sale.product.description.toLowerCase().includes(this.form.product.toLowerCase())
                })
            }

            /* Filter Sales By Payment Status */
            if (this.form.paymentStatus >= 0) {
                filtered = (filtered).filter((sale) => {
                    return sale.product.paymentStatus === this.form.paymentStatus
                })
            }
            ''
            /* Filter Sales By Payment Status */
            if (this.form.deliveryStatus >= 0) {
                filtered = (filtered).filter((sale) => {
                    if (sale.product.delivery != null) {
                        return sale.product.delivery.status === this.form.deliveryStatus
                    } else {
                        return false
                    }

                })
            }
            /* Filter Sales By Date */
            if (this.form.dates != null) {
                if (this.form.dates.start != null) {
                    filtered = (filtered).filter((sale) => {
                        return sale.date >= this.getTimestampFromDate(this.form.dates.start)
                    })
                }
                if (this.form.dates.end != null) {
                    filtered = (filtered).filter((sale) => {
                        return sale.date <= this.getTimestampFromDate(this.form.dates.end)
                    })
                }
            }

            return filtered
        },
        metrics() {
            let id = 0
            let recorded = 0
            let total = 0
            let balance = 0

            for (let x in this.filteredSales) {
                //Register sale if it records unique id
                if (this.filteredSales[x].id != id) {
                    id = this.filteredSales[x].id
                    recorded++
                }
                //add to metrics if product was paid for, partially or fully
                if (this.filteredSales[x].product.paymentStatus > 0) {
                    total += this.filteredSales[x].product.amount
                    balance += this.filteredSales[x].product.balance
                }
            }

            return {
                recorded: recorded,
                total: total,
                collected: total - balance,
                balance: balance,
            }
        },
        // collectedSalesData: {
        //   datasets: [{
        //     data: [this.metrics.collected, this.metrics.balance],
        //     backgroundColor: ['#22c55e', '#e3ebf6'],
        //   }],
        // },
        // uncollectedSalesData: {
        //   datasets: [{
        //     data: [this.metrics.balance, this.metrics.collected],
        //     backgroundColor: ['#ed0b4b', '#e3ebf6'],
        //   }],
        // },
    },
    watch: {
        chartYear() {
            this.yearlySalesData.datasets[0].data = [];
            this.yearlySalesData.labels = [];
            for (let y in this.chartData[this.chartYear].data) {
                this.yearlySalesData.datasets[0].data.push(this.chartData[this.chartYear].data[y].total)
                this.yearlySalesData.labels.push(this.chartData[this.chartYear].data[y].month)
                // }
            }
        }
    },

    methods: {
        navigateToSale(id) {
            this.$inertia.get(this.route('sites.sales.show', { 'code': this.site.code, 'id': id }))
        },
        navigateToClient(id) {
            this.$inertia.get(this.route('clients.show', { 'id': id }))
        },
        navigateToDelivery(id) {

            this.$inertia.get(this.route('deliveries.show', { 'id': id }))
        },
        navigateToInventory(id) {
            this.$inertia.get(this.route('sites.inventories.show', { 'code': this.site.code, 'id': id }))
        },

    }
}
</script>
