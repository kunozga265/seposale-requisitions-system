<template>
    <app-layout>
        <template #header>
            {{ site.name }} - {{ inventory.data.name }}
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
                        {{ inventory.data.name }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>

            <!--            <inertia-link :href="route('sites.sales.create',{code:site.code})">-->
            <!--                <primary-button>-->
            <!--                    Record Sale-->
            <!--                </primary-button>-->
            <!--            </inertia-link>-->
            <!--      <span v-if="sale.data.invoice">-->
            <!--        <a :href="route('invoices.print',{'id':sale.data.invoice.id})" target="_blank">-->
            <!--        <primary-button>Print Invoice</primary-button>-->
            <!--      </a>-->
            <!--      </span>-->

            <!--                        <a :href="route('sites.summaries.print',{'id':summary.data.id})" target="_blank">-->
            <!--                            <primary-button>Print</primary-button>-->
            <!--                        </a>-->

            <primary-button @click.native="editInventoryDialog = true">
                Edit
            </primary-button>

        </template>


        <dialog-modal :show="editInventoryDialog" @close="editInventoryDialog = false">
            <template #title>
                Edit Inventory
            </template>

            <template #content>
                <jet-validation-errors class="mb-4" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                    <div class="mb-2">
                        <jet-label for="product" value="Link Product" />
                        <select v-model="form.productId" id="product"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required>
                            <option value="0">---</option>
                            <option v-for="(product, index) in products.data" :value="product.id" :key="index">
                                {{ product.name }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <jet-label for="name" value="Name" />
                        <jet-input id="name" type="text" class="block w-full" v-model="form.name"
                            autocomplete="seposale-inventory-name" />
                    </div>

                    <div class="mb-2">
                        <jet-label for="units" value="Units" />
                        <jet-input id="units" type="text" class="block w-full" v-model="form.units"
                            autocomplete="seposale-inventory-unit" />
                    </div>

                    <div class="mb-2">
                        <jet-label for="cost" value="Cost" />
                        <jet-input id="cost" type="number" step="0.01" class="block w-full" v-model="form.cost"
                            autocomplete="seposale-inventory-cost" />
                    </div>

                    <div class="mb-2">
                        <jet-label for="threshold" value="Threshold" />
                        <jet-input id="threshold" type="number" step="0.01" class="block w-full"
                            v-model="form.threshold" autocomplete="seposale-inventory-threshold" />
                    </div>

                    <!-- <div v-if="checkRole($page.props.auth.data, 'management')" class="mb-2">
                        <jet-label for="available-stock" value="Available Stock" />
                        <jet-input id="available-stock" type="number" step="0.01" class="block w-full"
                            v-model="form.availableStock" autocomplete="seposale-inventory-available-stock" />
                    </div>-->

                    <div v-if="checkRole($page.props.auth.data, 'management')" class="mb-2">
                        <jet-label for="uncollected-stock" value="Uncollected Stock" />
                        <jet-input id="uncollected-stock" type="number" step="0.01" class="block w-full"
                            v-model="form.uncollectedStock" autocomplete="seposale-inventory-uncollected-stock" />
                    </div>

                    <div class="mb-2 md:col-span-2 text-gray-500 text-xs font-bold">Update Batches</div>

                    <div v-for="(batch, index) in form.batches" :key="index"
                        v-if="checkRole($page.props.auth.data, 'management')" class="mb-2">
                        <div>
                            <div class="flex items-center">

                                <div class="mr-4">
                                    <jet-label class="mb-0" for="batch-item" :value="getDate(batch.date * 1000)" />
                                    <div class="text-xs text-gray-500">MK{{ numberWithCommas(batch.price.toFixed(2))
                                        }}/{{
                                            batch.inventory.units }}
                                    </div>
                                </div>
                                <jet-input id="batch-item" type="number" step="0.01" class="block w-full mt-0"
                                    v-model="batch.balance" autocomplete="seposale-inventory-uncollected-stock" />
                            </div>

                        </div>
                    </div>

                    <div class="flex items-center mb-2 md:col-span-2">
                        <input checked id="producible" type="checkbox" value="" v-model="form.producible"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="producible"
                            class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Producible?</label>
                    </div>




                </div>
            </template>

            <template #footer>
                <secondary-button @click.native="editInventoryDialog = false">
                    Cancel
                </secondary-button>

                <primary-button class="ml-2" @click.native="editInventory" :disabled="form.processing">
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

        <div class="mx-9 flex">

            <a :href="route('sites.inventories.show', { code: site.code, id: inventory.data.id, section: 'overview' })">
                <div 
                    class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'overview' }">
                    <div>Overview</div>
                    <i v-show="section === 'overview'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                </div>
            </a>
            <a :href="route('sites.inventories.show', { code: site.code, id: inventory.data.id, section: 'sales' })">
                <div 
                    class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'sales' }">
                    <div>Sales</div>
                    <i v-show="section === 'sales'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                </div>
            </a>

            <a :href="route('sites.inventories.show', { code: site.code, id: inventory.data.id, section: 'collections' })">
                <div 
                    class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'collections' }">
                    <div>Collections</div>
                    <i v-show="section === 'collections'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                </div>
            </a>
            <a :href="route('sites.inventories.show', { code: site.code, id: inventory.data.id, section: 'batches' })">
                <div 
                    class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'batches' }">
                    <div>Batches</div>
                    <i v-show="section === 'batches'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                </div>
            </a>
            <a :href="route('sites.inventories.show', { code: site.code, id: inventory.data.id, section: 'damages' })">
                <div  v-if="inventory.data.producible"
                    class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                    :class="{ 'info': section === 'damages' }">
                    <div>Damages</div>
                    <i v-show="section === 'damages'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
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

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-if="inventory.data.producible" class="card no-shadow">
                                <div class="heading-font mb-2">Curing Schedule</div>
                                <table>
                                    <tr v-for="(batch, index) in awaitingCuration.data">

                                        <!-- <div class="grid grid-cols-2"> -->

                                        <td class="text-sm text-gray-400">{{ getDate(batch.readyDate * 1000) }}
                                        </td>
                                        <td class="heading-font text-base text-right" style="font-weight: 600;">{{
                                            batch.quantity
                                        }}
                                            {{ inventory.data.units }}{{ batch.quantity == 1 ? '' : 's' }}
                                        </td>
                                        <!-- </div> -->
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="page-section" v-if="section === 'sales'">
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

                            <!--                                <div class="card">-->
                            <!--                                    <div class="flex justify-between items-center">-->

                            <!--                                        <div class="mr-4">-->
                            <!--                                            <div class="heading-font" style="font-weight: 600;">Collected</div>-->
                            <!--                                            <div class="text-sm">MK {{ numberWithCommas(metrics.collected.toFixed(2)) }}-->
                            <!--                                            </div>-->
                            <!--                                        </div>-->
                            <!--                                        <div class="h-5 w-5 relative">-->
                            <!--                                            <div style="font-size:12px;"-->
                            <!--                                                 class="absolute h-full w-full font-bold flex justify-center items-center">-->
                            <!--                                                {{ Math.floor((metrics.collected / metrics.total) * 100) }}%-->
                            <!--                                            </div>-->
                            <!--                                            &lt;!&ndash;                    <DoughnutChart&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                        :chart-options="chartOptions"&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                        :chart-data="collectedSalesData"&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                        chart-id="awaitingReconciliation"&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                        dataset-id-key="awaitingReconciliation"&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                    />&ndash;&gt;-->
                            <!--                                        </div>-->
                            <!--                                    </div>-->

                            <!--                                </div>-->
                            <!--                                <div class="card">-->
                            <!--                                    <div class="flex justify-between items-center">-->
                            <!--                                        <div class="mr-4">-->
                            <!--                                            <div class="heading-font" style="font-weight: 600;">-->
                            <!--                                                Balance-->
                            <!--                                            </div>-->
                            <!--                                            <div class="text-sm">MK {{ numberWithCommas(metrics.balance.toFixed(2)) }}-->
                            <!--                                            </div>-->
                            <!--                                        </div>-->
                            <!--                                        <div class="h-5 w-5 relative">-->
                            <!--                                            <div style="font-size:12px;"-->
                            <!--                                                 class="absolute h-full w-full font-bold flex justify-center items-center text-red-500">-->
                            <!--                                                {{ Math.floor((metrics.balance / metrics.total) * 100) }}%-->
                            <!--                                            </div>-->
                            <!--                                            &lt;!&ndash;                    <DoughnutChart&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                        :chart-options="chartOptions"&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                        :chart-data="uncollectedSalesData"&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                        chart-id="awaitingReconciliation"&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                        dataset-id-key="awaitingReconciliation"&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                    />&ndash;&gt;-->
                            <!--                                        </div>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->

                        </div>

                        <div class="card default-table w-full">
                            <!--                            {{ invoices.data }}-->
                            <div class="overflow-x-auto p-2 mb-2 relative ">
                                <table class=" w-full default-table  text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <!--                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>-->
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Client</th>
                                            <!--                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>-->
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Amount</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Payment Status</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Quantity</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Collected</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Collection Status
                                            </th>
                                        </tr>
                                        <tr>
                                            <!--                                            <th scope="col" class="p-2 pb-4 heading-font text-left">-->
                                            <!--                                                <vue-date-time-picker-->
                                            <!--                                                    v-model="form.dates"-->
                                            <!--                                                    range-->
                                            <!--                                                />-->
                                            <!--                                            </th>-->
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
                                            <!--                                            <th scope="col" class="p-2 pb-4 heading-font text-left relative">-->
                                            <!--                                                <button v-show="form.product.length > 0" @click="form.product = ''"-->
                                            <!--                                                        class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">-->
                                            <!--                                                    <i class="mdi mdi-close"></i>-->
                                            <!--                                                </button>-->
                                            <!--                                                <jet-input id="product" type="text" class="block w-full"-->
                                            <!--                                                           placeholder="Search"-->
                                            <!--                                                           v-model="form.product"-->
                                            <!--                                                           autocomplete="seposale-filter-product"/>-->
                                            <!--                                            </th>-->
                                            <th scope="col" class="p-2 pb-4 heading-font text-right"></th>
                                            <th scope="col" class="p-2 pb-4 heading-font text-right"></th>
                                            <!--                                            <th scope="col" class="p-2 pb-4 heading-font text-left"><select-->
                                            <!--                                                v-model="form.paymentStatus"-->
                                            <!--                                                id="payment-status"-->
                                            <!--                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"-->
                                            <!--                                                required>-->
                                            <!--                                                <option v-for="(status, index) in paymentStatuses" :value="status.value"-->
                                            <!--                                                        :key="index">-->
                                            <!--                                                    {{ status.name }}-->
                                            <!--                                                </option>-->
                                            <!--                                            </select></th>-->
                                            <!--                                            <th scope="col" class="p-2 pb-4 heading-font text-left"><select-->
                                            <!--                                                v-model="form.deliveryStatus"-->
                                            <!--                                                id="delivery-status"-->
                                            <!--                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"-->
                                            <!--                                                required>-->
                                            <!--                                                <option v-for="(status, index) in deliveryStatuses" :value="status.value"-->
                                            <!--                                                        :key="index">-->
                                            <!--                                                    {{ status.name }}-->
                                            <!--                                                </option>-->
                                            <!--                                            </select></th>-->
                                        </tr>

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
                                            <!--                                            <td class="p-2 text-left ">{{ sale.product.inventory.name }}</td>-->
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
                                                    @clickEvent="navigateToSale(sale.id)"
                                                    :status="sale.product.paymentStatus" :is-solo="true" />
                                            </td>
                                            <td class="p-2 text-right">{{
                                                numberWithCommas(sale.product.quantity.toFixed(2))
                                            }}
                                            </td>
                                            <td class="p-2 text-right">{{
                                                numberWithCommas(sale.product.collected.toFixed(2))
                                            }}
                                            </td>

                                            <td class="">
                                                <collection
                                                    class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                    :client="sale.client" :product="sale.product" :is-solo="true" />
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="page-section" v-else-if="section === 'collections'">
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
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Sale</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Client</th>
                                        <!--                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Collected By</th>-->
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Quantity</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="pt-8">
                                    <tr class="cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                        v-for="(item, index) in collections.data" :key="index">
                                        <td class="p-2 text-left">{{ getDate(item.date * 1000) }}</td>
                                        <td @click="navigateToSale(item.siteSaleSummary.sale.id)" class="p-2 text-left">
                                            {{
                                                item.siteSaleSummary.sale.code }}</td>
                                        <td @click="navigateToClient(item.client.id)" class="p-2 text-left ">{{
                                            item.client.name
                                        }}</td>
                                        <!--                                        <td class="p-2 text-left ">{{-->
                                        <!--                                                item.collectedBy-->
                                        <!--                                            }}-->
                                        <!--                                        </td> -->
                                        <td class="p-2 text-left ">{{
                                            item.inventory.name
                                        }}
                                        </td>
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


                <div class="page-section" v-else-if="section === 'damages' && inventory.data.producible">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Damages
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
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Recorded By</th>
                                    </tr>
                                </thead>
                                <tbody class="pt-8">
                                    <tr class="cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                        v-for="(item, index) in damages.data" :key="index">
                                        <td class="p-2 text-left">{{ getDate(item.date * 1000) }}</td>
                                        <td class="p-2 text-left ">
                                            {{ numberWithCommas((item.cost / item.quantity).toFixed(2)) }}
                                        </td>
                                        <td class="p-2 text-left ">
                                            {{ numberWithCommas(item.quantity) }}
                                        </td>
                                        <td class="p-2 text-left ">
                                            {{ numberWithCommas((item.cost).toFixed(2)) }}
                                        </td>

                                        <td class="p-2 text-left ">{{

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


export default {
    props: ['site',
        'inventory',
        'section',
        'filteredReceipts',
        'sales', 'collections', 'batches', 'awaitingCuration', 'damages', 'products'],
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
        Money
    },
    data() {
        return {
            editInventoryDialog: false,
            form: this.$inertia.form({
                dates: null,
                code: "",
                client: "",
                product: "",
                paymentStatus: -1,
                deliveryStatus: -1,

                //

                name: this.inventory.data.name,
                units: this.inventory.data.units,
                cost: this.inventory.data.cost,
                threshold: this.inventory.data.threshold,
                availableStock: this.inventory.data.availableStock,
                uncollectedStock: this.inventory.data.uncollectedStock,
                producible: this.inventory.data.producible,
                productId: this.inventory.data.product.id,
                batches: []
            }),
        }
    },
    created() {

        for (let x in this.batches.data) {
            if (this.batches.data[x].balance > 0)
                this.form.batches.push(this.batches.data[x])
        }
    },
    computed: {
        filteredSales() {
            let filtered = []

            for (let x in this.sales) {
                filtered.push({
                    id: this.sales[x].id,
                    code: this.sales[x].code,
                    date: this.sales[x].date,
                    client: this.sales[x].client,
                    product: this.sales[x].products[0].data,
                })
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

            // /* Filter Sales By Product Name */
            // if (this.form.product.length !== 0) {
            //     filtered = (filtered).filter((sale) => {
            //         return sale.product.data.inventory.name.toLowerCase().includes(this.form.product.toLowerCase())
            //     })
            // }


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
        navigateToSale(id) {
            this.$inertia.get(this.route('sites.sales.show', { 'code': this.site.code, 'id': id }))
        },
        navigateToClient(id) {
            this.$inertia.get(this.route('clients.show', { 'id': id }))
        },
    }
}
</script>
