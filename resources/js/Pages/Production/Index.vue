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
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        Production
                    </span>
                </div>
            </li>
        </template>

        <template #actions>
            <primary-button @click.native="openProductionReportDialog">Production Report</primary-button>
            <!--            <inertia-link :href="route('expenses.create')">-->
            <primary-button @click.native="addStockDialog = true">
                Add Stock
            </primary-button>
            <!--            </inertia-link>-->
            <secondary-button @click.native="addItemDialog = true">
                Add Item
            </secondary-button>

        </template>

        <dialog-modal :show="addItemDialog" @close="addItemDialog = false">
            <template #title>
                Add Stock Item
            </template>

            <template #content>
                <jet-validation-errors class="mb-4" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                    <div class="mb-2">
                        <jet-label for="product" value="Select Type" />
                        <select v-model="form.materialsTypeId" id="type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required>
                            <option value="null">---</option>
                            <option v-for="(material, index) in filteredTypes" :value="material.id" :key="index">
                                {{ material.name }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-2" v-show="materialsType?.slug == 'other'">
                        <jet-label for="name" value="Name" />
                        <jet-input id="name" type="text" class="block w-full" v-model="form.name"
                            autocomplete="seposale-stock-name" />
                    </div>

                    <div class="mb-2">
                        <jet-label for="units" value="Units" />
                        <jet-input id="units" type="text" class="block w-full" v-model="form.units"
                            autocomplete="seposale-stock-unit" />
                    </div>

                    <!-- <div class="p-2 mb-2">
                        <jet-label for="quantity" value="Quantity"/>
                        <jet-input id="quantity" type="number" step="0.01" class="block w-full"
                                   v-model="form.quantity"
                                   autocomplete="seposale-stock-quantity"/>
                    </div> -->

                    <div class="mb-2">
                        <jet-label for="threshold" value="Threshold" />
                        <jet-input id="threshold" type="number" step="0.01" class="block w-full"
                            v-model="form.threshold" autocomplete="seposale-stock-threshold" />
                    </div>


                </div>
            </template>

            <template #footer>
                <secondary-button @click.native="addItemDialog = false">
                    Cancel
                </secondary-button>

                <primary-button class="ml-2" @click.native="addItem" :disabled="form.processing">
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

        <dialog-modal :show="addStockDialog" @close="cancelAddStockDialog">
            <template #title>
                Add Stock
            </template>

            <template #content>
                <jet-validation-errors class="mb-4" />
                <div class="mb-4">
                    <jet-label for="product" value="Select Product" />
                    <select v-model="form.materialsId" id="product"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        required>
                        <option value="0">---</option>
                        <option v-for="(material, index) in materials.data" :value="material.id" :key="index">
                            {{ material.name }}
                        </option>
                    </select>
                </div>

                <div v-if="form.materialsId !== 0">

                    <div class="mb-4">
                        <jet-label for="date" value="Date" />
                        <vue-date-time-picker color="#1a56db" v-model="form.date" :max-date="maxDate" />
                    </div>
                    <div class="mb-4">
                        <jet-label for="quantity" value="Quantity" />
                        <jet-input type="number" step="0.01" class="block w-full" v-model="form.materialsQuantity"
                            required />
                    </div>
                    <div class="mb-4">
                        <jet-label for="quantity" value="Total Cost" />
                        <jet-input type="number" step="0.01" class="block w-full" v-model="form.total" required />
                        <div class="text-gray-500 text-xs">Product + Transport Cost</div>
                    </div>
                    <div class="mb-4">
                        <jet-label for="comments" value="Comments" />
                        <textarea
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            type="text" v-model="form.comments"></textarea>
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <div class="text-mute text-sm mb-1">
                            Upload Photo
                        </div>
                        <input type="file" id="photo" accept="image/*" @input="photoUpload($event.target.files[0])"
                            class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                        <div class="text-red-500 text-xs" v-if="form.errors.photo">Required
                        </div>
                    </div>
                </div>


            </template>

            <template #footer>
                <secondary-button @click.native="cancelAddStockDialog">
                    Cancel
                </secondary-button>

                <primary-button :disabled="form.processing" class="ml-2" @click.native="addStock">
                    <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="#E5E7EB" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentColor" />
                    </svg>
                    Add
                </primary-button>
            </template>
        </dialog-modal>

        <dialog-modal :show="productionReportDialog" @close="productionReportDialog = false">
            <template #title>
                Production Report
            </template>

            <template #content>
                <jet-validation-errors class="mb-4" />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                    <div class="mb-4">
                        <jet-label for="date" value="Date" />
                        <vue-date-time-picker color="#1a56db" v-model="form.reportDate" />
                    </div>
                    <div class="mb-4">
                        <jet-label for="cementQuantity" value="Cement" />
                        <jet-input type="number" step="0.01" class="block w-full" v-model="cementQuantity" required />
                    </div>



                    <div class="mb-2 md:col-span-2 flex justify-between">

                        <div class=" text-gray-500 text-xs font-bold">Usage Summary </div>

                        <div class="flex items-center mb-2 md:col-span-2">
                            <input checked id="with-sand" type="checkbox" value="" v-model="withSand"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="with-sand" class="ml-1 text-xs font-bold text-blue-600 dark:text-blue-300">With
                                Sand?</label>
                        </div>
                    </div>

                    <div class="mb" v-for="(material, index) in form.materials" :key="index">
                        <jet-label for="quantity" :value="material.name" />
                        <jet-input type="number" step="0.01" class="block w-full" v-model="material.quantity" />
                    </div>

                    <div class="my-2 md:col-span-2 text-gray-500 text-xs font-bold">Production Summary</div>

                    <div class="mb" v-for="(inventory, index) in form.inventories" :key="index + '-inventory'">
                        <jet-label for="quantity" :value="inventory.name" />
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                            <div>
                                <div class="text-xs text-gray-500">Produced</div>
                                <jet-input type="number" step="0.01" class="block w-full"
                                    v-model="inventory.quantity" />
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Damages</div>
                                <jet-input type="number" step="0.01" class="block w-full" v-model="inventory.damages" />
                            </div>
                            <div class="md:col-span-2">
                                <div class="text-xs text-gray-500">Cure Date</div>
                                <vue-date-time-picker color="#1a56db" v-model="inventory.date" />
                            </div>

                        </div>
                    </div>

                    <div class="my-2 md:col-span-2 text-gray-500 text-xs font-bold">Expenses</div>
                    <div class="mb-4">
                        <jet-label for="cementQuantity" value="Labour" />
                        <jet-input type="number" step="0.01" class="block w-full" v-model="form.labour" required />
                    </div>
                    <div class="mb-4">
                        <jet-label for="cementQuantity" value="Food" />
                        <jet-input type="number" step="0.01" class="block w-full" v-model="form.food" required />
                    </div>

                </div>



            </template>

            <template #footer>
                <secondary-button @click.native="productionReportDialog = false">
                    Cancel
                </secondary-button>

                <primary-button :disabled="form.processing" class="ml-2" @click.native="generateProductionReport">
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

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Overview
                        </div>
                    </div>
                    <div class="page-section-content">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-4">
                            <div class="card mb-0 lg:col-span-2">
                                <div class="flex justify-between mb-4">
                                    <div class="heading-font mb-4">Usage Summary</div>
                                    <div>
                                        <vue-date-time-picker v-model="form.dates" range />
                                    </div>
                                </div>


                                <div>
                                    <div id="chart">
                                        <vue-apex-charts type="line" :options="chartOptionsApex" height="300"
                                            :series="usagesData"></vue-apex-charts>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-0">
                                <div class="flex justify-between mb-4">
                                    <div class="heading-font">Stock</div>

                                </div>

                                <inertia-link href="#" v-for="(material, index) in materials.data" :key="index"
                                    class="record p-2 mb-1 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full flex justify-center items-center"
                                            :class="{ 'bg-red': getStatus(material) === 0, 'bg-yellow': getStatus(material) === 1, 'bg-green': getStatus(material) === 2, }">
                                            <div class="text-xs text-white">
                                                {{ index + 1 }}
                                            </div>
                                        </div>
                                        <div class="ml-3 text-sm ">
                                            <div class="text-sm">{{ material.name }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ getInventoryStatus(material) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="heading-font text-xs text-gray-500">
                                        {{ numberWithCommas(material.quantity) }}
                                        {{ material.units }}{{ material.quantity != 1 ? "s" : "" }}
                                    </div>
                                </inertia-link>
                            </div>

                        </div>


                    </div>
                </div>

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Reports
                        </div>
                    </div>
                    <div class="page-section-content">

                        <!-- <div @click="navigateToProductionReport(production.code)" class="app-card cursor-pointer"
                            v-for="(production, index) in productions.data" :key="index">
                            <div class="header justify-between items-center border-b">
                                <div class="">
                                   
                                    <div>
                                        <div class="type">Production #{{ production.code }}</div>
                                        <div class="text-sm">{{ getDate(production.date * 1000) }}</div>
                                    </div>

                                    <div class="md:flex">
                                        <div v-for="(batch, index) in production.batches"
                                            class="flex items-center justify-e">
                                            <div class="name">{{ batch.inventory.name }}</div>
                                            <div class="p-1 ml-2 mr-4">
                                                <span
                                                    class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                                                        numberWithCommas(batch.quantity)
                                                    }}</span>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div>
                                <div class="md:flex px-4">
                                    <div v-for="(usage, index) in production.usages"
                                        class="flex items-center justify-e">
                                        <div class="name">{{ usage.material.name }}</div>
                                        <div class="w-12 ml-2">
                                            <span
                                                class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                                                    numberWithCommas(usage.quantity)
                                                }}</span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div> -->

                        <div class="card w-full">
                            <div class="p-2 mb-2 relative ">
                                <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                                            <th v-for="(inventory, index) in inventories.data" scope="col"
                                                class="p-2 pb-0 heading-font text-left">{{ inventory.name }}</th>

                                        </tr>
                                    </thead>
                                    <tbody class="pt-8">

                                        <tr @click="navigateToProductionReport(production.code)"
                                            v-for="(production, index) in productions.data" :key="index"
                                            class="cursor-pointer  hover:bg-gray-100 transition ease-in-out duration-200">
                                            <td class="p-2 text-left">{{ getDate(production.date * 1000) }}</td>
                                            <td
                                                class="p-2 text-left">
                                                {{ production.code }}
                                            </td>
                                            <td v-for="(inventory, index) in inventories.data" class="p-2 text-left ">

                                                <span v-for="(batch, index) in production.batches">
                                                    <span v-if="batch.inventory.id == inventory.id">
                                                        {{ numberWithCommas(batch.quantity) }}
                                                    </span>
                                                </span>
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
        'materials',
        'materialsTypes',
        'productions',
        'usages',
        'inventories',
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
            addStockDialog: false,
            maxDate: new Date().toISOString().substr(0, 10),
            productionReportDialog: false,
            withSand: false,
            form: this.$inertia.form({
                dates: null,
                name: "",
                units: "",
                quantity: 0,
                threshold: 0,

                materialsId: 0,
                materialsTypeId: null,
                materialsQuantity: 0,
                total: 0,
                comments: "",
                date: "",
                photo: "",

                reportDate: "",
                materials: [],
                inventories: [],
                labour: 0,
                food: 0,


                code: "",
            }),
            cementQuantity: 0,
            chartOptionsApex: {
                chart: {
                    type: 'line',
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

                // fill: {
                //     type: 'gradient',
                //     gradient: {
                //         shadeIntensity: 1,
                //         inverseColors: true,
                //         opacityFrom: 0.8,
                //         opacityTo: 0.6,
                //         stops: [0, 90, 100]
                //     },
                // },
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
        for (let y in this.materials.data) {
            if (this.materials.data[y].type.slug != "cement") {
                this.form.materials.push({
                    "id": this.materials.data[y].id,
                    "type": this.materials.data[y].type.slug,
                    "name": this.materials.data[y].name,
                    "quantity": 0,
                })
            }
        }
        for (let y in this.inventories.data) {

            this.form.inventories.push({
                "id": this.inventories.data[y].id,
                "name": this.inventories.data[y].name,
                "quantity": 0,
                "damages": 0,
                "date": null,
            })

        }
    },
    computed: {
        filteredTypes() {
            let arr = [];

            for (let x in this.materialsTypes) {
                let check = true;
                for (let y in this.materials.data) {
                    if (this.materialsTypes[x].slug == this.materials.data[y].type.slug) {
                        check = false
                        break
                    }
                }
                if (check) {
                    arr.push(this.materialsTypes[x])
                }
            }

            if (arr.length == 0) {
                arr.push(this.materialsTypes.find(({ slug }) => slug === "other"))
            }

            return arr;
        },
        materialsType() {
            return this.materialsTypes.find(({ id }) => id === this.form.materialsTypeId);
        },
        filteredUsages() {

            let filtered = this.usages;

            /* Filter usages By Date */
            // if (this.form.dates != null) {
            //     if (this.form.dates.start != null) {
            //         filtered = (filtered).filter((expense) => {
            //             return expense.date >= this.getTimestampFromDate(this.form.dates.start)
            //         })
            //     }
            //     if (this.form.dates.end != null) {
            //         filtered = (filtered).filter((expense) => {
            //             return expense.date <= this.getTimestampFromDate(this.form.dates.end)
            //         })
            //     }
            // }

            return filtered
        },
        usagesData() {

            let arr = [];
            for (let x in this.filteredUsages) {
                arr.push({
                    name: this.filteredUsages[x].name,
                    data: []
                })

                for (let y in this.filteredUsages[x].usages) {
                    arr[x].data.push({
                        x: this.filteredUsages[x].usages[y].date * 1000,
                        y: this.filteredUsages[x].usages[y].quantity
                    })
                }
            }

            return arr
        },

    },
    watch: {
        materialsType() {
            if (this.materialsType?.slug != "other") {
                this.form.name = this.materialsType.name
            } else {
                this.form.name = ""
            }
        },
        cementQuantity() {
            for (let y in this.form.materials) {
                if (this.form.materials[y].type == "pebble-stone") {
                    this.form.materials[y].quantity = (parseFloat(this.cementQuantity) * 0.2).toFixed(2);
                }

                if (this.withSand) {
                    if (this.form.materials[y].type == "river-sand") {
                        this.form.materials[y].quantity = (parseFloat(this.cementQuantity) * 0.2).toFixed(2);
                    }
                    if (this.form.materials[y].type == "quarry-dust") {
                        this.form.materials[y].quantity = (parseFloat(this.cementQuantity) * 0.4).toFixed(2);
                    }

                } else {
                    if (this.form.materials[y].type == "quarry-dust") {
                        this.form.materials[y].quantity = (parseFloat(this.cementQuantity) * 0.6).toFixed(2);
                    }
                    if (this.form.materials[y].type == "river-sand") {
                        this.form.materials[y].quantity = 0;
                    }
                }

            }
        },
        withSand() {
            for (let y in this.form.materials) {
                if (this.withSand) {
                    if (this.form.materials[y].type == "river-sand") {
                        this.form.materials[y].quantity = (parseFloat(this.cementQuantity) * 0.2).toFixed(2);
                    }
                    if (this.form.materials[y].type == "quarry-dust") {
                        this.form.materials[y].quantity = (parseFloat(this.cementQuantity) * 0.4).toFixed(2);
                    }

                } else {
                    if (this.form.materials[y].type == "quarry-dust") {
                        this.form.materials[y].quantity = (parseFloat(this.cementQuantity) * 0.6).toFixed(2);
                    }
                    if (this.form.materials[y].type == "river-sand") {
                        this.form.materials[y].quantity = 0;
                    }
                }
            }
        }
    },
    methods: {
        navigateToRequisition(id) {
            this.$inertia.get(this.route('request-forms.show', { 'id': id }))
        },
        navigateToProductionReport(code) {
            this.$inertia.get(this.route('production.show', { 'code': code }))
        },
        addItem() {
            this.form
                .transform(data => ({
                    ...data,
                    "site_id": this.site.id,
                    "materials_type_id": this.form.materialsTypeId,
                }))
                .post(this.route('materials.store'), {
                    onSuccess: () => {
                        this.addItemDialog = false
                    }
                })
        },
        addStock() {
            this.form
                .transform(data => ({
                    ...data,
                    date: (new Date(this.form.date).getTime()) / 1000,
                    material_id: this.form.materialsId,
                    quantity: this.form.materialsQuantity,
                }))
                .post(this.route('materials.update'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.cancelAddStockDialog()
                    },
                })

        },
        generateProductionReport() {
            const cementObject = this.materials.data.find(({ type }) => type.slug === "cement");

            let materials = [{
                "id": cementObject.id,
                "type": cementObject.type.slug,
                "name": cementObject.name,
                "quantity": this.cementQuantity,
            }]
            for (let x in this.form.materials) {
                materials.push(this.form.materials[x])
            }

            this.form
                .transform(data => ({
                    ...data,
                    date: (new Date(this.form.reportDate).getTime()) / 1000,
                    materials: materials,
                    inventories: this.refactorInventories(),
                }))
                .post(this.route('production.store', { code: this.site.code }), {
                    preserveScroll: true,
                    onFinish: () => {
                        this.productionReportDialog = false
                        this.cementQuantity = 0
                        for (let x in this.form.materials) {
                            this.form.materials[x].quantity = 0
                        }
                        for (let x in this.form.inventory) {
                            this.form.inventory[x].quantity = 0
                            this.form.inventory[x].damages = 0
                            this.form.inventory[x].date = null
                        }
                    },
                })

        },
        cancelAddStockDialog() {
            this.form.materialsId = 0
            this.addStockDialog = false
            this.form.materialsQuantity = 0
            this.form.comments = ""
            this.form.date = null
            document.getElementById('photo').value = ""
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
        openProductionReportDialog() {
            //check if all mandatory products are added
            for (let x in this.materialsTypes) {
                let check = false;
                for (let y in this.materials.data) {
                    if (this.materialsTypes[x].slug == "other" || this.materialsTypes[x].slug == "river-sand") {
                        check = true;
                        break;
                    }
                    else if (this.materialsTypes[x].slug == this.materials.data[y].type.slug) {
                        check = true
                        break
                    }
                }
                if (!check) {
                    this.$page.props.flash.error = "Please add all mandatory materials"
                    return null
                }
            }

            this.productionReportDialog = true
        },

        getStatus(material) {
            let status = 0;
            if (material.quantity <= 0) {
                status = 0
            } else if (material.quantity <= material.threshold) {
                status = 1
            } else {
                status = 2
            }
            return status
        },
        getInventoryStatus(material) {
            return this.getInventoryStatusMessage(this.getStatus(material))
        },
        refactorInventories() {
            let arr = []

            for (let y in this.form.inventories) {
                arr.push({
                    "id": this.form.inventories[y].id,
                    "name": this.form.inventories[y].name,
                    "quantity": this.form.inventories[y].quantity,
                    "damages": this.form.inventories[y].damages,
                    "date": this.getTimestampFromDate(this.form.inventories[y].date),
                })
            }

            return arr

        }
    }
}
</script>
