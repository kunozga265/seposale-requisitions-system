<template>
    <app-layout>
        <template #header>
            {{ site.data.name }}
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
                        Sites
                    </span>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ site.data.name }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>
            <div class="md:flex grid grid-cols-2 md:grid-cols-5 gap-1">
                <inertia-link :href="route('sites.sales.create', { code: site.data.code })">
                    <primary-button>
                        Record Sale
                    </primary-button>
                </inertia-link>
                <inertia-link :href="route('production.index', { code: site.data.code })">
                    <secondary-button>
                        Production
                    </secondary-button>
                </inertia-link>

                <jet-dropdown align="right" width="48">
                    <template #trigger>
                        <secondary-button>
                            Actions
                        </secondary-button>
                    </template>

                    <template #content>

                        <!-- <jet-dropdown-link :href="route('sites.sales.create', { code: site.data.code })"
                            class="text-left">

                            RECORD SALE
                        </jet-dropdown-link> -->
                        <!-- <div class="border-t border-gray-100"></div> -->

                        <jet-dropdown-link @click.native="addStockDialog = true" as="button" class="text-left">
                            Add Stock
                        </jet-dropdown-link>
                        <div class="border-t border-gray-100"></div>

                        <jet-dropdown-link @click.native="addInventoryDialog = true" as="button" class="text-left">
                            Add Inventory
                        </jet-dropdown-link>
                        <div class="border-t border-gray-100"></div>

                        <jet-dropdown-link @click.native="makeRequestDialog = true" as="button" class="text-left">
                            Make Request
                        </jet-dropdown-link>
                        <div class="border-t border-gray-100"></div>



                    </template>
                </jet-dropdown>
            </div>

            <dialog-modal :show="addStockDialog" @close="cancelAddStockDialog">
                <template #title>
                    Add Stock
                </template>

                <template #content>
                    <jet-validation-errors class="mb-4" />

                    <div v-show="!addStockValidation" class="flex items-center w-full text-red md:col-span-2 mb-4">
                        <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{
                            addStockErrorMessage }}</div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                        <div class="mb-4" :class="{ 'md:col-span-2': form.inventoryId == 0 }">
                            <jet-label for="product" value="Select Product" />
                            <select v-model="form.inventoryId" id="product"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                                <option value="0">---</option>
                                <option v-for="(inventory, index) in site.data.inventories" :value="inventory.id"
                                    :key="index">
                                    {{ inventory.name }}
                                </option>
                            </select>
                        </div>

                        <div v-if="form.inventoryId !== 0" class="mb-2">
                            <jet-label for="date" value="Date" />
                            <vue-date-time-picker color="#1a56db" v-model="form.date" :max-date="maxDate" />
                        </div>
                        <div v-if="form.inventoryId !== 0" class="mb-2">
                            <jet-label for="quantity" value="Quantity" />
                            <jet-input type="number" step="0.01" class="block w-full" v-model="form.quantity"
                                required />
                            <div class="text-gray-500 text-xs flex justify-between">
                                <span>MK{{ numberWithCommas(unitCost.toFixed(1)) }} Per {{ inventory.units }}</span>

                            </div>
                        </div>
                        <div v-if="form.inventoryId !== 0" class="mb-2">
                            <jet-label for="quantity" value="Total Cost" />
                            <!-- <jet-input type="number" step="0.01" class="block w-full" v-model="form.total" required /> -->
                            <money
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                v-bind="moneyMaskOptions" v-model="form.total" />
                            <div class="text-gray-500 text-xs flex justify-between">
                                <span>Upto MK{{ numberWithCommas(inventory.inventoryValue.toFixed(1)) }}</span>
                                <span>Product + Transport Cost</span>
                            </div>
                        </div>
                        <div v-if="form.inventoryId !== 0" class="mb-2 md:col-span-2">
                            <jet-label for="comments" value="Comments" />
                            <textarea
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                type="text" v-model="form.comments"></textarea>
                        </div>
                        <div v-if="form.inventoryId !== 0" class="mb-2 md:col-span-2">
                            <div class="text-mute text-sm mb-1">
                                Upload Photo
                            </div>
                            <input type="file" id="photo" @input="photoUpload($event.target.files[0])"
                                accept="image/png, image/gif, image/jpeg"
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

                    <primary-button :disabled="form.processing" v-if="form.inventoryId !== 0 && addStockValidation"
                        class="ml-2" @click.native="addStock">
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

            <dialog-modal :show="addInventoryDialog" @close="addInventoryDialog = false">
                <template #title>
                    Add Inventory
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

                        <div class="flex items-center mb-2 md:col-span-2">
                            <input checked id="producible" type="checkbox" value="" v-model="form.producible"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="producible"
                                class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Producible?</label>
                        </div>


                    </div>
                </template>

                <template #footer>
                    <secondary-button @click.native="addInventoryDialog = false">
                        Cancel
                    </secondary-button>

                    <primary-button class="ml-2" @click.native="addInventory" :disabled="form.processing">
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

            <dialog-modal :show="makeRequestDialog" @close="makeRequestDialog = false">
                <template #title>
                    Make Request
                </template>

                <template #content>
                    <div class="mb-4">
                        <div>
                            Please fill in the following details to complete the requisition.
                        </div>
                        <div v-show="!requestValidation" class="flex items-center w-full text-red">
                            <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{
                                requestErrorMessage }}
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <jet-label for="personCollectingAdvance" value="Person Collecting" />
                        <jet-input id="personCollectingAdvance" type="text" class="block w-full"
                            v-model="form.personCollectingAdvance" autocomplete="geoserve-person-collecting-advance" />
                    </div>

                    <div class="p-2 mb-2 ">
                        <div class="relative overflow-x-auto">
                            <table class="w-full default-table text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="heading-font">

                                        </th>
                                        <th scope="col" class="heading-font">
                                            Product
                                        </th>
                                        <th scope="col" class="heading-font">
                                            Quantity
                                        </th>
                                        <th scope="col" class="heading-font">
                                            Unit Cost
                                        </th>
                                        <th scope="col" class="heading-font">
                                            Total Cost
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                        v-for="(info, index) in form.items" :key="index">
                                        <th scope="row" class="px-2">
                                            <i @click="removeRecord(index)"
                                                class="mdi mdi-close-circle text-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 cursor"></i>
                                        </th>
                                        <td scope="row"
                                            class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">

                                            <select v-model="info.inventoryId" id="product"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                                <option v-for="(inventory, index) in site.data.inventories"
                                                    :value="inventory.id" :key="index">
                                                    {{ inventory.name }}
                                                </option>
                                                <option value="0">Transportation</option>
                                            </select>

                                        </td>
                                        <td v-if="form.type === 'MATERIALS'" class="py-2 pr-1">
                                            <jet-input type="text" class="block w-full" v-model="info.units" />
                                        </td>
                                        <td class="py-2 pr-1">
                                            <!-- <money
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                        v-bind="moneyMaskOptions" v-model="info.quantity" /> -->
                                            <jet-input type="number" class="block w-full" step="0.01"
                                                v-model="info.quantity" />
                                        </td>
                                        <td class="py-2 pr-1">
                                            <money
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                v-bind="moneyMaskOptions" v-model="info.unitCost" />
                                            <!-- <jet-input type="text" class="block w-full"
                                                        v-model="info.unitCost" /> -->
                                        </td>
                                        <td class="py-2 pr-1">
                                            <money
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                v-bind="moneyMaskOptions" :value="(info.quantity * info.unitCost)" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div @click="addRecord" class="mt-2 ml-2 flex justify-start items-center cursor">
                            <div>
                                <i class="mdi mdi-plus-circle text-blue-600"></i>
                            </div>
                            <div class="ml-2 text-blue-600 text-sm">
                                Add Record
                            </div>
                        </div>
                        <div class="text-center">
                            <div v-if="isNaN(totalCost)" class="text-red-600 uppercase font-semibold heading-font">
                                Enter valid total cost
                            </div>
                            <div v-else class="flex justify-center items-center ">
                                <div class="currency ">MK</div>
                                <div class="total">{{ numberWithCommas(totalCost) }}</div>
                            </div>
                            <div class="text-gray-600 text-xs">Total Cost</div>
                        </div>
                        <!-- <div class="mt-4 text-gray-600 text-sm">
                                        I accept the advances listed above and I acknowledge that I must return the full amount or account for it on a company expense form within 3 days of returning to Geoserve from this assignment.
                                    </div> -->
                    </div>




                </template>

                <template #footer>
                    <secondary-button @click.native="makeRequestDialog = false">
                        close
                    </secondary-button>
                    <primary-button v-show="requestValidation" @click.native="makeRequest">
                        <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="#E5E7EB" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentColor" />
                        </svg>
                        Submit
                    </primary-button>
                </template>
            </dialog-modal>
        </template>

        <div class="mx-9 flex">

            <div @click="section = 'overview'"
                class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                :class="{ 'info': section === 'overview' }">
                <div>Overview</div>
                <i v-show="section === 'overview'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
            </div>

            <inertia-link :href="route('sites.collections', { code: site.data.code })"
                class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                <div>Collections</div>

            </inertia-link>
            <!-- 
            <div @click="section = 'collections'"
                class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                :class="{ 'info': section === 'collections' }">
                <div>Collections</div>
                <i v-show="section === 'collections'" class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
            </div> -->
        </div>

        <div v-if="section === 'overview'" class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Overview
                        </div>
                    </div>
                    <div class="page-section-content ">
                        <div class="card mb-0 md:col-span-2  xl:col-span-4">
                            <div class="flex justify-between mb-4">
                                <div class="heading-font mb-4">Sales</div>
                                <div>
                                    <vue-date-time-picker v-model="form.dates" range />
                                </div>
                            </div>
                            <div class="md:flex md:justify-between">

                                <div>

                                    <div class="text-xs mb-1 text-gray-500">Total Sales</div>
                                    <div class="heading-font font-bold text-xl mb-4">
                                        MK{{ numberWithCommas(metrics.total.toFixed(2)) }}
                                        (
                                        <Profit class="heading-font font-bold" :value="metrics.profit" />)

                                    </div>
                                </div>
                                <div class="md:text-right" v-show="metrics.pendingPayments > 0">
                                    <div class="text-xs mb-1 text-gray-500">Pending Payments</div>
                                    <div class="heading-font font-bold text-xl text-red mb-4">
                                        MK{{ numberWithCommas(metrics.pendingPayments.toFixed(2)) }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div id="chart">
                                    <apexchart type="area" :options="chartOptionsApex" height="180" :series="salesData">
                                    </apexchart>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Inventory
                        </div>
                    </div>
                    <div class="page-section-content ">
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <inertia-link
                                :href="route('sites.inventories.show', { code: site.data.code, id: inventory.id })"
                                v-for="(inventory, index) in site.data.inventories" :key="index">
                                <div class="app-card">
                                    <div class="header lg:flex justify-between items-center border-b">
                                        <div>

                                            <div class="type">{{ inventory.name }}</div>
                                            <!--                      <div class="name">{{ inventory.units }}s</div>-->
                                            <div class="lg:flex">
                                                <div class="flex items-center justify-e">
                                                    <div class="name">Available:</div>
                                                    <div class="ml-2">
                                                        <span
                                                            class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                                                                numberWithCommas((inventory.stock -
                                                                    inventory.uncollectedStock).toFixed(1))
                                                            }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-e">
                                                    <div class="name">Uncollected:</div>
                                                    <div class="mx-2"><span
                                                            class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                                                                numberWithCommas(inventory.uncollectedStock.toFixed(1))
                                                            }}</span></div>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="total">
                                            {{
                                                numberWithCommas(inventory.availableStock + inventory.uncollectedStock)
                                            }}
                                        </div>


                                    </div>

                                    <div>
                                        <inventory-status :available="inventory.availableStock"
                                            :threshold="inventory.threshold" />
                                    </div>
                                </div>
                            </inertia-link>
                        </div>
                    </div>
                </div>

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Pending Collections
                        </div>
                    </div>
                    <div class="page-section-content ">
                        <div class="card default-table">
                            <div class="p-2 relative overflow-x-auto">
                                <table class="overflow-auto w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class=" text-gray-600  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Collection Status
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Client</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Amount</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Payment Status</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Quantity</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Collected</th>

                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                            v-for="(productCompound, index) in collectionsCompound.main" :key="index">
                                            <td class="">
                                                <collection
                                                    class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                    :client="productCompound.sale.client" :product="productCompound"
                                                    :is-solo="true" />
                                            </td>
                                            <td @click="navigateToSale(productCompound.sale.id)"
                                                class="py-2 pr-1 cursor-pointer hover:bg-gray-50">
                                                {{ productCompound.sale.code }}
                                            </td>

                                            <td @click="navigateToClient(productCompound.sale.client.id)"
                                                class="py-2 pr-1 cursor-pointer hover:bg-gray-50">
                                                {{ productCompound.sale.client.name }}
                                            </td>
                                            <th @click="navigateToInventory(productCompound.inventory.id)" scope="row"
                                                :class="{ 'strike-through': productCompound.trashed }"
                                                class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap cursor-pointer hover:bg-gray-50">
                                                {{ productCompound.inventory.name }}
                                            </th>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(productCompound.amount) }}
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(productCompound.balance) }}
                                            </td>
                                            <td @click="navigateToSale(productCompound.sale.id)"
                                                class="cursor-pointer hover:bg-gray-50">
                                                <sale-status :status="productCompound.paymentStatus" :is-solo="true" />
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                {{
                                                    numberWithCommas(productCompound.quantity)
                                                }}
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(productCompound.collected) }}
                                            </td>


                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="page-section-content ">
                        <div class="card default-table">
                            <div class="p-2 relative overflow-x-auto">
                                <table class="overflow-auto w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class=" text-gray-600  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Collection Status
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Client</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Product</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Amount</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Payment Status</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Quantity</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Collected</th>

                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                            v-for="(productCompound, index) in collectionsCompound.production" :key="index">
                                            <td class="">
                                                <collection
                                                    class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                    :client="productCompound.sale.client" :product="productCompound"
                                                    :is-solo="true" />
                                            </td>
                                            <td @click="navigateToSale(productCompound.sale.id)"
                                                class="py-2 pr-1 cursor-pointer hover:bg-gray-50">
                                                {{ productCompound.sale.code }}
                                            </td>

                                            <td @click="navigateToClient(productCompound.sale.client.id)"
                                                class="py-2 pr-1 cursor-pointer hover:bg-gray-50">
                                                {{ productCompound.sale.client.name }}
                                            </td>
                                            <th @click="navigateToInventory(productCompound.inventory.id)" scope="row"
                                                :class="{ 'strike-through': productCompound.trashed }"
                                                class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap cursor-pointer hover:bg-gray-50">
                                                {{ productCompound.inventory.name }}
                                            </th>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(productCompound.amount) }}
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(productCompound.balance) }}
                                            </td>
                                            <td @click="navigateToSale(productCompound.sale.id)"
                                                class="cursor-pointer hover:bg-gray-50">
                                                <sale-status :status="productCompound.paymentStatus" :is-solo="true" />
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                {{
                                                    numberWithCommas(productCompound.quantity)
                                                }}
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(productCompound.collected) }}
                                            </td>


                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="page-section">

                    <div class="page-section-header">
                        <div class="page-section-title">
                            Summary
                        </div>
                    </div>
                    <div class="page-section-content ">

                        <!--
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
                                    <div class="card no-shadow blue">
                                        <div class="flex justify-start items-center">
                                            <div class="">
                                                <div class="heading-font" style="font-weight: 600;">Sales Made</div>
                                                <div class="text-sm">MK {{ numberWithCommas(metrics.total.toFixed(2)) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card no-shadow green">
                                        <div class="flex justify-between items-center">

                                            <div class="mr-4">
                                                <div class="heading-font" style="font-weight: 600;">Collected</div>
                                                <div class="text-sm">MK {{ numberWithCommas(metrics.collected.toFixed(2)) }}
                                                </div>
                                            </div>
                                            <div class="h-5 w-5 relative">
                                                <div style="font-size:12px;"
                                                     class="absolute h-full w-full font-bold flex justify-center items-center">
                                                    {{ Math.floor((metrics.collected / metrics.total) * 100) }}%
                                                </div>
                                                &lt;!&ndash;                    <DoughnutChart&ndash;&gt;
                                                &lt;!&ndash;                        :chart-options="chartOptions"&ndash;&gt;
                                                &lt;!&ndash;                        :chart-data="collectedSalesData"&ndash;&gt;
                                                &lt;!&ndash;                        chart-id="awaitingReconciliation"&ndash;&gt;
                                                &lt;!&ndash;                        dataset-id-key="awaitingReconciliation"&ndash;&gt;
                                                &lt;!&ndash;                    />&ndash;&gt;
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card no-shadow red">
                                        <div class="flex justify-between items-center">
                                            <div class="mr-4">
                                                <div class="heading-font" style="font-weight: 600;">
                                                    Balance
                                                </div>
                                                <div class="text-sm">MK {{ numberWithCommas(metrics.balance.toFixed(2)) }}
                                                </div>
                                            </div>
                                            <div class="h-5 w-5 relative">
                                                <div style="font-size:12px;"
                                                     class="absolute h-full w-full font-bold flex justify-center items-center text-red-500">
                                                    {{ Math.floor((metrics.balance / metrics.total) * 100) }}%
                                                </div>
                                                &lt;!&ndash;                    <DoughnutChart&ndash;&gt;
                                                &lt;!&ndash;                        :chart-options="chartOptions"&ndash;&gt;
                                                &lt;!&ndash;                        :chart-data="uncollectedSalesData"&ndash;&gt;
                                                &lt;!&ndash;                        chart-id="awaitingReconciliation"&ndash;&gt;
                                                &lt;!&ndash;                        dataset-id-key="awaitingReconciliation"&ndash;&gt;
                                                &lt;!&ndash;                    />&ndash;&gt;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        -->

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
                            <div class="p-2 mb-2 relative ">
                                <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Collections</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Sales</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Profit</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Pending Payments
                                            </th>
                                            <!--                                        <th scope="col" class="p-2 pb-0 heading-font text-right">Amount</th>-->
                                            <!--                                        <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>-->
                                            <!--                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Payment Status</th>-->
                                            <!--                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Delivery Status</th>-->
                                        </tr>


                                    </thead>
                                    <tbody class="pt-8">

                                        <tr @click="navigateToSummary(summary.id)"
                                            class="cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                            v-for="(summary, index) in filteredRecords" :key="index">
                                            <td class="p-2 text-left">{{ getDate(summary.date * 1000) }}</td>
                                            <td class="p-2 text-left">{{ summary.code }}
                                            </td>
                                            <td class="p-2 text-right ">{{
                                                numberWithCommas(summary.collections.toFixed(2)) }}
                                            </td>
                                            <td class="p-2 text-right ">{{ numberWithCommas(summary.sales.toFixed(2)) }}
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                <profit :value="summary.profit" />
                                            </td>



                                            <td class="p-2 text-right ">
                                                <profit :value="summary.pendingPayments" />
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
import InventoryStatus from "@/Components/InventoryStatus.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";
import Collection from "@/Components/Collection.vue";
import Profit from "@/Components/Profit.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import { Money } from 'v-money'
import { Inertia } from '@inertiajs/inertia';
import VueApexCharts from "vue-apexcharts";


export default {
    props: [
        'site',
        'collections',
        'products',
    ],
    components: {
        JetDropdown, JetDropdownLink,
        Collection,
        DialogModal,
        SecondaryButton,
        BarChart,
        JetValidationErrors, JetInput, JetLabel,
        InventoryStatus,
        AppLayout,
        PrimaryButton,
        DoughnutChart,
        SaleStatus,
        Money,
        Profit,
        "apexchart": VueApexCharts,
    },
    data() {
        return {
            search: "",
            section: "overview",
            addStockDialog: false,
            addInventoryDialog: false,
            makeRequestDialog: false,
            requestErrorMessage: "",
            chartYear: 0,
            maxDate: new Date().toISOString().substr(0, 10),
            addStockErrorMessage: "",
            form: this.$inertia.form({
                dates: {
                    start: null,
                    end: null,
                },
                code: "",
                client: "",
                product: "",


                inventoryId: 0,
                quantity: 0,
                total: 0,
                comments: "",
                date: "",
                photo: "",

                name: "",
                units: "",
                cost: 0,
                threshold: 0,
                producible: false,
                productId: 0,

                type: 'INVENTORY',
                personCollectingAdvance: '',
                items: [
                    {
                        "inventoryId": null,
                        "details": '',
                        "units": '',
                        "quantity": 0,
                        "unitCost": 0,
                        "totalCost": 0,
                    },

                ],

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
            moneyMaskOptions: {
                decimal: '.',
                thousands: ',',
                prefix: 'MK ',
                suffix: '',
                precision: 2,
                masked: false
            },
        }
    },
    mounted() {
        //Yearly Sales Chart
        // for (let x in this.chartData) {
        // for (let y in this.chartData[0].data) {
        //     this.yearlySalesData.datasets[0].data.push(this.chartData[0].data[y].total)
        //     this.yearlySalesData.labels.push(this.chartData[0].data[y].month)
        //     // }
        // }

        const start = new Date();
        start.setDate(1);
        start.setHours(0, 0, 0, 0);
        this.form.dates.start = start.toISOString()

    },
    computed: {
        inventory() {
            return this.site.data.inventories.find(({ id }) => id === this.form.inventoryId);
        },
        unitCost() {
            if (this.form.quantity == 0 && this.form.total == 0) {
                return 0
            } else {
                return this.form.total / this.form.quantity
            }
        },
        dates() {
            return {
                start: this.getTimestampFromDate(this.form.dates.start),
                end: this.getTimestampFromDate(this.form.dates.end),
            }
        },
        collectionsCompound() {
            let main = (this.collections).filter((collection) => {
                return collection.inventory.producible != true
            })
            let production = (this.collections).filter((collection) => {
                return collection.inventory.producible == true
            })

            return {
                main: main,
                production: production,
            }
        },
        filteredRecords() {
            let filtered = []

            for (let x in this.site.data.summaries) {
                filtered.push({
                    id: this.site.data.summaries[x].id,
                    code: this.site.data.summaries[x].code,
                    date: this.site.data.summaries[x].date,
                    sales: this.site.data.summaries[x].totalSales,
                    profit: this.site.data.summaries[x].profit,
                    pendingPayments: this.site.data.summaries[x].pendingPayments,
                    collections: this.site.data.summaries[x].collections,
                })
            }

            /* Filter Sales By Code*/
            if (this.search.length !== 0) {
                filtered = (filtered).filter((sale) => {
                    return sale.code.toLowerCase().includes(this.search.toLowerCase())
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
        salesData() {
            let receipts = [{
                name: 'Sales',
                data: []
            }];

            for (let x in this.filteredRecords) {
                receipts[0].data.push({
                    x: this.filteredRecords[x].date * 1000,
                    y: this.filteredRecords[x].sales
                })
            }

            return receipts
        },
        metrics() {
            let total = 0
            let profit = 0
            let pendingPayments = 0

            for (let x in this.filteredRecords) {
                total += this.filteredRecords[x].sales
                profit += this.filteredRecords[x].profit
                pendingPayments += this.filteredRecords[x].pendingPayments
            }
            return {
                total: total,
                profit: profit,
                pendingPayments: pendingPayments,
            }
        },
        addStockValidation() {
            if (this.form.inventoryId == 0 || this.form.inventoryId == null) {
                this.addStockErrorMessage = "Select inventory product"
                return false
            }
            if (this.form.date == null) {
                this.addStockErrorMessage = "Select date"
                return false
            }
            if (!this.form.quantity || this.form.quantity <= 0) {
                this.addStockErrorMessage = "Enter quantity"
                return false
            }
            if (this.form.total <= 0) {
                this.addStockErrorMessage = "Enter total cost"
                return false
            }
            if (this.form.total > this.inventory.inventoryValue) {
                this.addStockErrorMessage = "Total cost cannot be greater than amount released by the accounts"
                return false
            }
            if (this.form.photo.length === 0 || this.form.photo == "") {
                this.addStockErrorMessage = "Upload delivery note photo"
                return false
            }
            if (this.form.photo.length > 0 && !this.form.photo.match(/\.(jpg|jpeg|png|gif)$/)) {
                this.addStockErrorMessage = "Invalid photo format"
                return false
            }
            this.addStockErrorMessage = ""

            return true

        },
        totalCost() {
            let totalCost = 0
            let currentTotal = 0
            for (let x in this.form.items) {
                currentTotal = parseFloat(this.form.items[x].quantity * this.form.items[x].unitCost)
                totalCost += currentTotal
                this.form.items[x].totalCost = parseFloat(currentTotal.toFixed(2))

                //convert to numbers
                this.form.items[x].quantity = parseFloat(this.form.items[x].quantity)
                this.form.items[x].unitCost = parseFloat(this.form.items[x].unitCost)

            }
            return parseFloat(totalCost.toFixed(2))
        },
        requestValidation() {

            if (isNaN(this.totalCost)) {
                this.requestErrorMessage = "Enter valid details"
                return false
            } else if (this.totalCost <= 0) {
                this.requestErrorMessage = "Enter details"
                return false
            } else
                return true
        },
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
        addStock() {
            this.form
                .transform(data => ({
                    ...data,
                    date: (new Date(this.form.date).getTime()) / 1000,
                    inventory_id: this.form.inventoryId
                }))
                .post(this.route('inventories.update'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.cancelAddStockDialog()
                    },
                })

        },
        addInventory() {
            this.form
                .transform(data => ({
                    ...data,
                    product_id: this.form.productId
                }))
                .post(this.route('sites.inventories.store', { 'code': this.site.data.code }), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.addInventoryDialog = false
                    },
                })
        },
        makeRequest() {
            this.form
                .transform(data => ({
                    ...data,
                    total: this.totalCost,
                    site_id: this.site.data.id
                }))
                .post(this.route('request-forms.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.makeRequestDialog = false
                        this.form.reset()
                    },
                })
        },
        navigateToClient(id) {
            this.$inertia.get(this.route('clients.show', { 'id': id }))
        },
        navigateToSale(id) {
            this.$inertia.get(this.route('sites.sales.show', { 'code': this.site.data.code, 'id': id }))
        },
        navigateToInventory(id) {
            this.$inertia.get(this.route('sites.inventories.show', { 'code': this.site.data.code, 'id': id }))
        },
        navigateToSite(id) {
            this.$inertia.get(this.route('sites.show', { 'id': id }))
        },
        navigateToSummary(id) {
            this.$inertia.get(this.route('sites.summaries.show', { 'code': this.site.data.code, 'id': id }))
        },
        navigateToDelivery(id) {

            this.$inertia.get(this.route('deliveries.show', { 'id': id }))
        },
        getTimestampFromDate(date) {
            return new Date(date).getTime() / 1000
        },
        cancelAddStockDialog() {
            this.form.inventoryId = 0
            this.addStockDialog = false
            this.form.quantity = 0
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
        addRecord() {
            this.form.items.push({
                "inventoryId": null,
                "details": '',
                "units": '',
                "quantity": 0,
                "unitCost": 0,
                "totalCost": 0,
            })
        },
        removeRecord(index) {
            this.form.items.splice(index, 1)
        },
    }
}
</script>
