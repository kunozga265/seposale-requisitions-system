<template>
    <app-layout>
        <template #header>
            Daily Report - {{ getDate(summary.data.date * 1000) }}
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
                        Daily Report #{{ summary.data.code }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>

            <inertia-link :href="route('sites.sales.create', { code: site.code })">
                <primary-button>
                    Record Sale
                </primary-button>
            </inertia-link>
            <!--      <span v-if="sale.data.invoice">-->
            <!--        <a :href="route('invoices.print',{'id':sale.data.invoice.id})" target="_blank">-->
            <!--        <primary-button>Print Invoice</primary-button>-->
            <!--      </a>-->
            <!--      </span>-->

            <a :href="route('sites.summaries.print', { 'id': summary.data.id })" target="_blank">
                <primary-button>Print</primary-button>
            </a>

        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <div class="">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="page-section">
                            <div class="page-section-header">
                                <div class="page-section-title">
                                    Opening
                                </div>
                            </div>

                            <div class="page-section-content">
                                <div class="card overflow-x-auto">
                                    <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Available</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Uncollecteed
                                                </th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="pt-8">

                                            <tr class="cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                v-for="(item, index) in summary.data.openingStock" :key="index">
                                                <td class="p-2 text-left">{{ item.name }}</td>
                                                <td class="p-2 text-left ">{{ numberWithCommas(item.availableStock) }}
                                                </td>
                                                <td class="p-2 text-left ">{{
                                                    numberWithCommas(item.uncollectedStock)
                                                }}
                                                </td>
                                                <td class="p-2 text-left ">
                                                    {{ numberWithCommas(item.availableStock + item.uncollectedStock) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="page-section">
                            <div class="page-section-header">
                                <div class="page-section-title">
                                    Closing
                                </div>
                            </div>

                            <div class="page-section-content">
                                <div class="card overflow-x-auto">
                                    <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Available</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Uncollecteed
                                                </th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="pt-8">

                                            <tr v-if="summary.data.closingStock.length > 0"
                                                class="cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                v-for="(item, index) in summary.data.closingStock" :key="index">
                                                <td class="p-2 text-left">{{ item.name }}</td>
                                                <td class="p-2 text-left ">{{ numberWithCommas(item.availableStock) }}
                                                </td>
                                                <td class="p-2 text-left ">{{
                                                    numberWithCommas(item.uncollectedStock)
                                                }}
                                                </td>
                                                <td class="p-2 text-left ">
                                                    {{ numberWithCommas(item.availableStock + item.uncollectedStock) }}
                                                </td>
                                            </tr>
                                            <tr v-if="summary.data.closingStock.length === 0"
                                                class="cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                v-for="(item, index) in inventories.data" :key="index">
                                                <td class="p-2 text-left">{{ item.name }}</td>
                                                <td class="p-2 text-left ">{{ numberWithCommas(item.availableStock) }}
                                                </td>
                                                <td class="p-2 text-left ">{{
                                                    numberWithCommas(item.uncollectedStock)
                                                }}
                                                </td>
                                                <td class="p-2 text-left ">
                                                    {{ numberWithCommas(item.availableStock + item.uncollectedStock) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-section" v-if="collections.data.length > 0">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Collections
                            </div>
                        </div>

                        <div class="page-section-content">
                            <div class="card default-table overflow-x-auto">
                                <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Sale</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Client</th>
                                            <!--                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Collected By</th>-->
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Quantity</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody class="pt-8">
                                        <tr v-for="(item, index) in collections.data" :key="index">
                                            <td @click="navigateToCollection(item.code)"
                                                class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                                {{
                                                item.code }}</td>
                                            <td @click="navigateToSale(item.siteSaleSummary.sale.id)"
                                                class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                                {{
                                                    item.siteSaleSummary.sale.code }}</td>
                                            <td @click="navigateToClient(item.client.id)" class="p-2 text-left ">{{
                                                item.client.name }}</td>

                                            <td @click="navigateToInventory(item.inventory.id)"
                                                class="p-2 text-left  cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                                {{
                                                    item.inventory.name
                                                }}
                                            </td>
                                            <!-- <td class="p-2 text-left ">{{item.collectedBy }}</td> -->
                                            <td class="p-2 text-left ">
                                                {{ numberWithCommas(item.quantity) }}
                                            </td>
                                            <td class="p-2 text-left ">
                                                {{ numberWithCommas(item.balance) }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="page-section md:col-span-2" v-if="filteredSales.length > 0">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Sales
                            </div>
                        </div>
                        <div class="page-section-content ">

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                <div class="card no-shadow">
                                    <div class="flex justify-start items-center">
                                        <div class="">
                                            <div class="heading-font" style="font-weight: 600;">Recorded</div>
                                            <div class="text-sm text-gray-400">{{ metrics.recorded }}
                                                {{ metrics.recorded == 1 ? 'Sale' : 'Sales' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="flex justify-start items-center">
                                        <div class="">
                                            <div class="heading-font" style="font-weight: 600;">Sales Made</div>
                                            <div class="text-sm">MK {{ numberWithCommas(metrics.total.toFixed(2)) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

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
                                                <!--                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>-->
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Collection
                                                    Status</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Payment Status
                                                </th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Client</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Amount</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>

                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Quantity</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Collected</th>

                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Delivery Status
                                                </th>
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
                                                <!--                                            <td class="p-2 text-left">{{ getDate(sale.date * 1000) }}</td>-->
                                                <td class="px-2">
                                                    <collection v-if="sale.product.delivery == null"
                                                        class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                        :client="sale.client" :product="sale.product" :is-solo="true" />
                                                    <span v-else>-</span>
                                                </td>
                                                <td class="">
                                                    <sale-status
                                                        class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                        ç :status="sale.product.paymentStatus" :is-solo="true" />
                                                </td>
                                                <td class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                    @click="navigateToSale(sale.id)">{{ sale.code }}
                                                </td>
                                                <td class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                    @click="navigateToClient(sale.client.id)">{{ sale.client.name }}
                                                </td>
                                                <td class="p-2 text-left ">{{ sale.product.inventory.name }}</td>
                                                <td class="p-2 text-right">{{
                                                    numberWithCommas(sale.product.amount.toFixed(2))
                                                }}
                                                </td>
                                                <td class="p-2 text-right">{{
                                                    numberWithCommas(sale.product.balance.toFixed(2))
                                                }}
                                                </td>

                                                <td class="p-2 text-right">{{
                                                    numberWithCommas(sale.product.quantity.toFixed(2))
                                                }}
                                                </td>
                                                <td class="p-2 text-right">{{
                                                    numberWithCommas(sale.product.collected.toFixed(2))
                                                }}
                                                </td>



                                                <td class="px-2" v-if="sale.product.delivery != null">
                                                    <delivery-status class="mr-1" :productCompound="sale.product"
                                                        :overdue="sale.product.overdue" :is-solo="true"
                                                        @clickEvent="navigateToDelivery(sale.product.delivery.id)" />
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
import DeliveryStatus from "@/Components/DeliveryStatus.vue";


export default {
    props: ['site', 'summary', 'inventories', 'sales', 'collections'],
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
        DeliveryStatus

    },
    data() {
        return {
            form: this.$inertia.form({
                dates: null,
                search: "",
                code: "",
                client: "",
                product: "",
                paymentStatus: -1,
                deliveryStatus: -1,
            }),
        }
    },
    created() {


    },
    computed: {
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

            /* Filter Sales*/
            if (this.form.search.length !== 0) {
                filtered = (filtered).filter((sale) => {
                    return sale.code.toLowerCase().includes(this.form.search.toLowerCase()) ||
                        sale.client.name.toLowerCase().includes(this.form.search.toLowerCase()) ||
                        sale.product.inventory.name.toLowerCase().includes(this.form.search.toLowerCase())
                })
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
                total += this.filteredSales[x].product.amount

                // //add to metrics if product was paid for, partially or fully
                // if (this.filteredSales[x].product.paymentStatus > 0) {
                //     total += this.filteredSales[x].product.amount
                //     balance += this.filteredSales[x].product.balance
                // }
            }

            return {
                recorded: recorded,
                total: total,
                collected: total - balance,
                balance: balance,
            }
        },
    },
    watch: {},
    methods: {
        navigateToSale(id) {
            this.$inertia.get(this.route('sites.sales.show', { 'code': this.site.code, 'id': id }))
        },
        navigateToClient(id) {
            this.$inertia.get(this.route('clients.show', { 'id': id }))
        },
        navigateToDelivery(id) {
            console.log(id)
            this.$inertia.get(this.route('deliveries.show', { 'id': id }))
        },
        navigateToInventory(id) {
            this.$inertia.get(this.route('sites.inventories.show', { 'code': this.site.code, 'id': id }))
        },
        navigateToCollection(code) {
            this.$inertia.get(this.route('sites.collections.show', { 'code': this.site.code, 'collection_code': code }))
        },
    }
}
</script>
