<template>
    <app-layout>
        <template #header>
            New Sale
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
                    <a :href="route('sales.index', { section: 'tabular' })"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Sales
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        New
                    </span>
                </div>
            </li>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Customer Details
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="card w-full sm:max-w-md md:max-w-3xl">

                                <jet-validation-errors class="mb-4" />

                                <div class="flex items-center mb-4">
                                    <input id="default-radio-1" type="radio" value="existing" v-model="checkClient"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="default-radio-1"
                                        class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Existing</label>

                                    <input checked id="default-radio-2" type="radio" value="new" v-model="checkClient"
                                        class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="default-radio-2"
                                        class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">New</label>
                                </div>
                                <div v-if="checkClient === 'existing'">

                                    <div class="p-2 mb-2">
                                        <jet-label for="clientIndex" value="Client" />
                                        <v-select label="name" :options="clients.data" placeholder="Select Client"
                                            v-model="selectedClient" />


                                        <!-- <select v-model="clientIndex" id="clientIndex"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            required>
                                            <option value="-1">Select Client</option>
                                            <option v-for="(client, index) in clients.data" :value="index" :key="index">
                                                {{ selectedClient.name }}
                                            </option>
                                        </select> -->
                                    </div>
                                    <div v-if="selectedClient != null" class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="p-2 mb-2 md:col-span-2" v-show="selectedClient.organisation">
                                            <jet-label for="alias-name" value="Alias Name" />
                                            <jet-input id="alias-name" type="text" class="block w-full"
                                                v-model="selectedClient.alias"
                                                autocomplete="seposale-customer-alias-name" disabled />
                                        </div>
                                        <div v-if="selectedClient.type != null" class="p-2 mb-2">
                                            <jet-label for="type" value="Type" />
                                            <jet-input id="type" type="text" class="block w-full"
                                                v-model="selectedClient.type.name" autocomplete="seposale-customer-type"
                                                disabled />
                                        </div>
                                        <div class="p-2 mb-2">
                                            <whatsapp-label title="Phone Number" />
                                            <jet-input id="phoneNumber" type="text" class="block w-full"
                                                v-model="selectedClient.phoneNumber"
                                                autocomplete="seposale-customer-phone-number" disabled />
                                        </div>
                                        <div class="p-2 mb-2">
                                            <jet-label for="phoneNumber" value="Phone Number (Secondary)" />
                                            <jet-input id="phoneNumber" type="text" class="block w-full"
                                                v-model="selectedClient.phoneNumberOther"
                                                autocomplete="seposale-customer-phone-number" disabled />
                                        </div>
                                        <div class="p-2 mb-2">
                                            <jet-label for="email" value="Email" />
                                            <jet-input id="email" type="email" class="block w-full"
                                                v-model="selectedClient.email" autocomplete="seposale-customer-email"
                                                disabled />
                                        </div>
                                        <div class="p-2 mb-2">
                                            <jet-label for="address" value="Address" />
                                            <jet-input id="address" type="text" class="block w-full"
                                                v-model="selectedClient.address"
                                                autocomplete="seposale-customer-address" disabled />
                                        </div>
                                    </div>

                                </div>
                                <div v-else class="grid grid-cols-1 md:grid-cols-2">

                                    <div class="p-2 mb-2 md:col-span-2">
                                        <div class="flex justify-between">
                                            <jet-label for="name" value="Name" />
                                            <div class="flex items-center mb-2">
                                                <input checked id="backdate" type="checkbox" value=""
                                                    v-model="form.organisation"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="backdate"
                                                    class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Organisation</label>
                                            </div>
                                        </div>

                                        <jet-input id="name" type="text" class="block w-full" v-model="form.name"
                                            autocomplete="seposale-customer-name" />
                                    </div>

                                    <div class="p-2 mb-2" :class="{ 'md:col-span-2': form.clientTypeId != 0 }">
                                        <jet-label for="clientType" value="Select Type" />
                                        <select v-model="form.clientTypeId" id="clientType"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            required>
                                            <option v-for="(type, index) in clientTypes" :value="type.id" :key="index">
                                                {{ type.name }}
                                            </option>
                                            <option value="0">Other</option>
                                        </select>
                                    </div>

                                    <div v-show="form.clientTypeId == 0" class="p-2 mb-2">
                                        <jet-label for="other" value="Type (Other)" />
                                        <jet-input id="other" type="text" class="block w-full" v-model="form.clientType"
                                            autocomplete="seposale-customer-type" />
                                    </div>

                                    <div v-show="form.organisation" class="p-2 mb-2 md:col-span-2">
                                        <jet-label for="alias=name" value="Alias Name" />
                                        <jet-input id="alias-name" type="text" class="block w-full" v-model="form.alias"
                                            autocomplete="seposale-customer-alias-name" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <whatsapp-label title="Phone Number" />
                                        <jet-input id="phoneNumber" type="text" class="block w-full"
                                            v-model="form.phoneNumber" autocomplete="seposale-customer-phone-number" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="phoneNumber" value="Phone Number (Secondary)" />
                                        <jet-input id="phoneNumber" type="text" class="block w-full"
                                            v-model="form.phoneNumberOther"
                                            autocomplete="seposale-customer-phone-number-other" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="email" value="Email" />
                                        <jet-input id="email" type="email" class="block w-full" v-model="form.email"
                                            autocomplete="seposale-customer-email" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="address" value="Address" />
                                        <jet-input id="address" type="text" class="block w-full" v-model="form.address"
                                            autocomplete="seposale-customer-address" />
                                    </div>


                                </div>

                                <div class="mb-4">
                                    <!--                                    <jet-label for="lastRefillDate" value="Backdate" />-->
                                    <div class="flex items-center mb-2">
                                        <input checked id="backdate" type="checkbox" value="" v-model="backdateCheck"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="backdate"
                                            class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Backdate</label>
                                    </div>
                                    <vue-date-time-picker v-if="backdateCheck" color="#1a56db" v-model="date"
                                        :max-date="maxDate" />
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Agent Commissions
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="card w-full sm:max-w-md md:max-w-3xl">

                                <div v-for="(agentRow, index) in form.agents" :key="index" class="card w-full">
                                    <div class="p-2 mb-2">
                                        <jet-label value="Agent" />
                                        <v-select label="name" :options="agentClients.data" placeholder="Select Agent"
                                            v-model="agentRow.client" />
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label value="Percentage (%)" />
                                        <jet-input type="number" step="0.01" min="0" max="100" class="block w-full"
                                            v-model="agentRow.percentage" />
                                    </div>
                                    <span @click="removeAgent(index)"
                                        class="flex items-center text-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 cursor">
                                        <i class="mdi mdi-close-circle"></i>
                                        <span class="ml-1 text-sm text-red-600">Remove Agent</span>
                                    </span>
                                </div>

                                <div @click="addAgent" class="mt-2 ml-2 flex justify-start items-center cursor w-full">
                                    <div>
                                        <i class="mdi mdi-plus-circle text-blue-600"></i>
                                    </div>
                                    <div class="ml-2 text-blue-600 text-sm">
                                        Add Agent
                                    </div>
                                </div>

                                <div v-if="form.agents.length > 0" class="mt-2 text-sm"
                                    :class="Math.abs(agentPercentageTotal - 100) <= 0.01 ? 'text-green-600' : 'text-red-600'">
                                    Total: {{ agentPercentageTotal }}%
                                    {{ Math.abs(agentPercentageTotal - 100) <= 0.01 ? '' : '(must total 100%)' }}
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Site Details
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="card w-full sm:max-w-md md:max-w-3xl">
                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <div class="p-2 mb-2">
                                        <jet-label for="location" value="Location" />
                                        <jet-input id="location" type="text" class="block w-full"
                                            v-model="form.location" autocomplete="seposale-location" />
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label for="recipientName" value="Recipient Name" />
                                        <jet-input id="recipientName" type="text" class="block w-full"
                                            v-model="form.recipientName" autocomplete="seposale-recipient-name" />
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label for="recipientProfession" value="Recipient Profession" />
                                        <jet-input id="recipientProfession" type="text" class="block w-full"
                                            v-model="form.recipientProfession"
                                            autocomplete="seposale-recipient-profession" />
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label for="recipientPhoneNumber" value="Recipient Phone Number" />
                                        <jet-input id="recipientPhoneNumber" type="text" class="block w-full"
                                            v-model="form.recipientPhoneNumber"
                                            autocomplete="seposale-recipient-phone-number" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Products and Services
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="card default-table w-full sm:max-w-md md:max-w-3xl">

                                <div class="p-2 mb-2 relative overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="heading-font">

                                                </th>
                                                <th scope="col" class="heading-font">
                                                    Details
                                                </th>
                                                <th scope="col" class="heading-font">
                                                    Units
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
                                                v-for="(info, index) in form.information" :key="index">
                                                <th scope="row" class="px-2">
                                                    <i @click="removeRecord(index)"
                                                        class="mdi mdi-close-circle text-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 cursor"></i>
                                                </th>
                                                <td scope="row"
                                                    class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                    <jet-input type="text" class="block w-full"
                                                        v-model="info.details" />
                                                </td>
                                                <td class="py-2 pr-1">
                                                    <jet-input type="text" class="block w-full" v-model="info.units" />
                                                </td>
                                                <td class="py-2 pr-1">
                                                    <jet-input type="number" step="0.01" class="block w-full"
                                                        v-model="info.quantity" />
                                                </td>
                                                <td class="py-2 pr-1">
                                                    <jet-input type="number" step="0.01" class="block w-full"
                                                        v-model="info.unitCost" />
                                                </td>
                                                <td class="py-2 pr-1">
                                                    <div
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                        {{ numberWithCommas((info.quantity * info.unitCost).toFixed(2))
                                                        }}
                                                    </div>
                                                    <!--                                                <jet-input type="text" class="block w-full" v-model="info.totalCost" value="23" />-->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <div class="mt-2 ml-2 flex justify-start items-center">
                                                        <div @click="addRecord"
                                                            class="flex justify-start items-center cursor">
                                                            <div>
                                                                <i class="mdi mdi-plus-circle text-blue-600"></i>
                                                            </div>
                                                            <div class="ml-2 text-blue-600 text-sm">
                                                                Add Blank
                                                            </div>
                                                        </div>
                                                        <div @click="addRecordDialog = true"
                                                            class="ml-3 flex justify-start items-center cursor">
                                                            <div>
                                                                <i class="mdi mdi-plus-circle text-blue-600"></i>
                                                            </div>
                                                            <div class="ml-2 text-blue-600 text-sm">
                                                                Add Product
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr v-show="calculateVat">
                                                <td colspan="5" class="heading-font p-2 uppercase font-bold text-right">
                                                    Sub
                                                    Total</td>
                                                <td>
                                                    <div
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                        {{ numberWithCommas((totalCost).toFixed(2)) }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-show="calculateVat">
                                                <td colspan="5" class="heading-font p-2 uppercase font-bold text-right">
                                                    VAT
                                                    ({{ (vatRate * 100).toFixed(1) }}%)</td>
                                                <td>
                                                    <div
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                        {{ numberWithCommas((vat).toFixed(2)) }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="heading-font p-2 uppercase font-bold text-right">
                                                    Grand
                                                    Total</td>
                                                <td>
                                                    <div
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                        {{ numberWithCommas((totalCost + vat).toFixed(2)) }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="flex items-center mb-2 justify-start">
                                        <input checked id="calculate-vat" type="checkbox" value=""
                                            v-model="calculateVat"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="calculate-vat"
                                            class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Calculate
                                            Vat</label>
                                    </div>

                                    <!-- <div class="mt-2 ml-2 flex justify-start items-center">
                                        <div @click="addRecord" class="flex justify-start items-center cursor">
                                            <div>
                                                <i class="mdi mdi-plus-circle text-blue-600"></i>
                                            </div>
                                            <div class="ml-2 text-blue-600 text-sm">
                                                Add Blank
                                            </div>
                                        </div>
                                        <div @click="addRecordDialog = true"
                                            class="ml-3 flex justify-start items-center cursor">
                                            <div>
                                                <i class="mdi mdi-plus-circle text-blue-600"></i>
                                            </div>
                                            <div class="ml-2 text-blue-600 text-sm">
                                                Add Product
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- <div class="text-center">
                                        <div v-if="isNaN(totalCost)"
                                            class="text-red-600 uppercase font-semibold heading-font">
                                            Enter valid total cost
                                        </div>
                                        <div v-else class="flex justify-center items-center ">
                                            <div class="currency ">MK</div>
                                            <div class="total">{{ numberWithCommas(totalCost) }}</div>
                                        </div>
                                        <div class="text-gray-600 text-xs">Total Cost</div>
                                    </div> -->
                                    <!-- <div class="mt-4 text-gray-600 text-sm">
                                        I accept the advances listed above and I acknowledge that I must return the full amount or account for it on a company expense form within 3 days of returning to Geoserve from this assignment.
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Notes
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">
                            <div class="card w-full sm:max-w-md md:max-w-3xl">

                                <vue2-tinymce-editor v-model="form.notes"></vue2-tinymce-editor>

                            </div>
                        </div>
                    </div>


                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Other Attachments
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="card w-full sm:max-w-md md:max-w-3xl">
                                <div class="p-2 mb-2">
                                    <jet-label for="lpo" value="Local Purchase Order (LPO)" />
                                    <jet-input id="lpo" type="text" class="block w-full"
                                        v-model="form.localPurchaseOrder" autocomplete="local-purchase-order" />
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

        <dialog-modal :show="addRecordDialog" @close="cancelAddRecord">
            <template #title>
                Add Product
            </template>

            <template #content>
                <!--          <div class="mb-2">-->
                <!--            Are you sure you want to approve this request?-->
                <!--          </div>-->
                <div class="mb-4">
                    <jet-label for="product" value="Select Product" />
                    <select v-model="productIndex" id="product"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        required>
                        <option value="-1">Blank</option>

                        <option v-for="(product, index) in allProducts" :value="index" :key="index">
                            {{ product.name }} - {{ product.description }}
                        </option>
                    </select>
                </div>

                <div v-if="productIndex !== -1" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="mb-4">
                        <jet-label for="units" value="Units" />
                        <jet-input type="text" class="block w-full" v-model="addRecordUnits" />
                    </div>
                    <div class="mb-4">
                        <jet-label for="units" value="Unit Cost" />
                        <jet-input type="number" step="0.01" class="block w-full" v-model="addRecordUnitCost" />
                    </div>
                    <div class="mb-4">
                        <jet-label for="quantity" value="Quantity" />
                        <jet-input type="number" step="0.01" class="block w-full" v-model="addRecordQuantity" />
                    </div>

                    <div class="mb-4">
                        <jet-label for="total" value="Total" />
                        <div
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            {{ numberWithCommas(addRecordTotal.toFixed(2)) }}
                        </div>
                    </div>
                </div>

                <!-- <div v-if="productIndex !== -1" class="mb-4">

                    <div class="flex items-center mb-2">
                        <input id="default-radio-1" type="radio" value="outsource" v-model="outsource"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-1"
                            class="ml-1 text-xs font-medium text-gray-900 dark:text-gray-300">Outsource
                        </label>

                        <input checked id="default-radio-2" type="radio" value="oss" v-model="outsource"
                            class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-2"
                            class="ml-1 text-xs font-medium text-gray-900 dark:text-gray-300">One Stop Shop</label>
                    </div>

                    <div v-if="outsource == 'outsource'">
                        <div class="mb-4">
                            <label class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-300">Delivery
                                Date</label>
                            <vue-date-time-picker color="#1a56db" v-model="deliveryDate" :min-date="minDate" />
                        </div>
                    </div>

                    <div v-else-if="product != null">
                        <div class="mb-2">
                            Select item under respective <span class="font-bold">One Stop Shop</span>
                        </div>

                        <table class="w-full mb-4">
                            <th class="text-left"></th>
                            <th class="text-left">Product</th>
                            <th class="text-left">Quantity Available</th>
                            <th class="text-left">Site Name</th>
                            <tbody>
                                <tr @click="inventoryId = inventory.id"
                                    class="border-t-1 cursor-pointer hover:bg-gray-50"
                                    v-for="(inventory, index) in product.inventories" :key="index">
                                    <td class="text-left">
                                        <input id="default-radio-1" :checked="form.inventoryId == inventory.id"
                                            type="checkbox" disabled value="deliver"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    </td>

                                    <td class="text-left">
                                        {{ inventory.name }}
                                    </td>
                                    <td class="text-left">
                                        {{ inventory.readyStock }}
                                    </td>
                                    <td class="text-left">
                                        {{ inventory.site.name }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mb-2 md:col-span-2 text-gray-500 text-xs font-bold">Delivery Method?
                        </div>

                        <div class="flex items-center mb-2">
                            <input id="default-radio-1" type="radio" value="delivery" v-model="deliveryMethod"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="default-radio-1"
                                class="ml-1 text-xs font-medium text-gray-900 dark:text-gray-300">To
                                Deliver</label>

                            <input checked id="default-radio-2" type="radio" value="collection" v-model="deliveryMethod"
                                class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="default-radio-2"
                                class="ml-1 text-xs font-medium text-gray-900 dark:text-gray-300">Self
                                Collection</label>
                        </div>
                        <div v-show="deliveryMethod == 'delivery'" class="mb-4">
                            <label class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-300">Delivery
                                Date</label>
                            <vue-date-time-picker color="#1a56db" v-model="deliveryDate" :min-date="minDate" />
                        </div>



                    </div>

                </div> -->
            </template>

            <template #footer>
                <secondary-button @click.native="cancelAddRecord">
                    Cancel
                </secondary-button>

                <primary-button class="ml-2" @click.native="addRecord">
                    Add
                </primary-button>
            </template>
        </dialog-modal>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from "@/Jetstream/Button";
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import SecondaryButton from '@/Jetstream/SecondaryButton'
import pdf from 'vue-pdf-embed/dist/vue2-pdf-embed'
import PrimaryButton from "@/Jetstream/Button.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";
import WhatsappLabel from "@/Components/WhatsappLabel.vue";
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css"
import { Vue2TinymceEditor } from "vue2-tinymce-editor";

export default {
    props: ["products", "clients", "clientTypes", "agentClients"],
    components: {
        WhatsappLabel,
        DialogModal, PrimaryButton,
        AppLayout,
        JetInput,
        JetLabel,
        JetButton,
        JetValidationErrors,
        SecondaryButton,
        pdf,
        vSelect,
        Vue2TinymceEditor,
    },
    data() {
        return {
            checkClient: "existing",
            addRecordDialog: false,

            addRecordUnits: "",
            addRecordQuantity: 0,
            addRecordUnitCost: 0,

            outsource: "oss",
            deliveryDate: null,
            inventoryId: null,
            deliveryMethod: null,


            productIndex: -1,
            clientIndex: -1,
            selectedClient: null,

            backdateCheck: false,
            date: null,
            maxDate: new Date().toISOString().substr(0, 10),
            minDate: null,
            vatRate: 0.175,
            calculateVat: false,
            form: this.$inertia.form({

                name: '',
                phoneNumber: '',
                phoneNumberOther: '',
                email: '',
                address: '',
                organisation: false,
                alias: '',
                clientTypeId: null,
                clientType: '',
                location: '',
                recipientName: '',
                recipientProfession: '',
                recipientPhoneNumber: '',
                localPurchaseOrder: '',
                notes: '',
                information: [

                ],
                agents: [

                ],

            }),
            quotes: [],
            error: '',


        }
    },
    created() {


    },
    computed: {
        addRecordTotal() {
            return this.addRecordUnitCost * this.addRecordQuantity
        },
        client() {
            if (this.clientIndex === -1 || this.clientIndex === '-1')
                return null
            else
                return this.clients.data[this.clientIndex]
        },
        allProducts() {
            let products = [];

            for (let x in this.products.data) {
                for (let y in this.products.data[x].variants) {
                    products.push({
                        "id": this.products.data[x].variants[y].id,
                        "name": this.products.data[x].name,
                        "description": this.products.data[x].variants[y].description,
                        "unit": this.products.data[x].variants[y].unit,
                        "cost": this.products.data[x].variants[y].cost,
                        "quantity": this.products.data[x].variants[y].quantity,
                        "inventories": this.products.data[x].inventories,
                    })
                }
            }

            return products;
        },
        product() {
            return this.allProducts[this.productIndex];
        },
        totalCost() {
            let totalCost = 0
            let currentTotal = 0
            for (let x in this.form.information) {
                currentTotal = parseFloat(this.form.information[x].quantity * this.form.information[x].unitCost)
                totalCost += currentTotal
                this.form.information[x].totalCost = parseFloat(currentTotal.toFixed(2))

                //convert to numbers
                this.form.information[x].quantity = parseFloat(this.form.information[x].quantity)
                this.form.information[x].unitCost = parseFloat(this.form.information[x].unitCost)

            }
            return parseFloat(totalCost.toFixed(2))
        },
        vat() {
            if (this.calculateVat) {
                return this.totalCost * this.vatRate
            } else {
                return 0
            }

        },
        agentPercentageTotal() {
            return this.form.agents.reduce((sum, a) => sum + (parseFloat(a.percentage) || 0), 0)
        },
        quoteFiles() {
            let files = []
            for (let x in this.quotes)
                files.push(this.quotes[x].file)

            return files
        },
        addProductValidation() {

            if (this.productIndex == 0) {
                this.productError = "Select product"
                return false
            } else if (this.outsource == 'outsource' && this.deliveryDate == null) {
                this.productError = "Enter delivery date"
                return false
            } else if (this.outsource == 'oss' && this.inventoryId == null || this.inventoryId == 0) {
                this.productError = "Select product under one stop shop"
                return false
            } else if (this.outsource == 'oss' && this.deliveryMethod == null) {
                this.productError = "Select delivery method"
                return false
            } else {
                return true
            }
        },
        validation() {

            if (this.checkClient === "new") {
                if (this.form.name.length === 0) {
                    this.error = "Enter customer name"
                    return false
                } else if (this.form.clientTypeId === null) {
                    this.error = "Please enter customer type"
                    return false
                } else if (this.form.clientTypeId == 0 && this.form.clientType.length === 0) {
                    this.error = "Please enter customer (other) type"
                    return false
                } else if (this.form.phoneNumber.length === 0 && this.form.phoneNumberOther.length === 0) {
                    this.error = "Enter at least one phone number"
                    return false
                }
            } else {
                if (this.selectedClient == null) {
                    this.error = "Select client"
                    return false
                }
            }

            if (this.backdateCheck) {
                if (this.date == null) {
                    this.error = "Enter a backdate"
                    return false
                }
            }

            if (this.form.location.length === 0) {
                this.error = "Enter site location"
                return false
            }
            //products and services
            else if (isNaN(this.totalCost)) {
                this.error = "Enter valid product and services details"
                return false
            } else if (this.totalCost <= 0) {
                this.error = "Enter products and services"
                return false
            } else if (this.form.agents.length > 0 && Math.abs(this.agentPercentageTotal - 100) > 0.01) {
                this.error = "Agent percentages must total 100%"
                return false
            }

            return true

        },
        saleDate() {
            return this.date ? (new Date(this.date).getTime()) / 1000 : null
        }
    },
    watch: {
        checkClient() {
            this.clientIndex = -1
        },
        backdateCheck() {
            this.date = null
        },
        productIndex() {
            if (this.productIndex === -1 || this.productIndex === "-1") {
                this.addRecordUnits = ""
                this.addRecordQuantity = 0
                this.addRecordUnitCost = 0
            } else {
                this.addRecordUnits = this.product.unit
                this.addRecordQuantity = this.product.quantity
                this.addRecordUnitCost = this.product.cost / this.product.quantity
            }
        }
    },
    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    total: this.totalCost,
                    quotes: this.quoteFiles,
                    products: this.form.information,
                    date: this.saleDate,
                    client_id: this.selectedClient == null ? null : this.selectedClient.id,
                    recipient_name: this.form.recipientName,
                    recipient_profession: this.form.recipientProfession,
                    recipient_phone_number: this.form.recipientPhoneNumber,
                    local_purchase_order: this.form.localPurchaseOrder,
                    client_type_id: this.form.clientTypeId,
                    client_type: this.form.clientType,
                    vat: this.vat,
                    agents: this.form.agents
                        .filter(a => a.client != null)
                        .map(a => ({ client_id: a.client.id, percentage: parseFloat(a.percentage) })),
                }))
                .post(this.route('sales.store'))
        },
        addAgent() {
            this.form.agents.push({ client: null, percentage: 0 })
        },
        removeAgent(index) {
            this.form.agents.splice(index, 1)
        },
        addRecord() {

            if (parseInt(this.productIndex) < 0) {
                this.form.information.push({
                    "id": 0,
                    "details": '',
                    "units": '',
                    "quantity": 0,
                    "unitCost": 0,
                    "totalCost": 0,
                })
            } else {
                const product = this.allProducts[this.productIndex]
                const name = this.product.description == null || this.product.description === "" ? product.name : product.name + " - " + product.description
                this.form.information.push({
                    "id": product.id,
                    "details": name,
                    "units": this.addRecordUnits,
                    "quantity": this.addRecordQuantity,
                    "unitCost": this.addRecordUnitCost,
                    "totalCost": this.addRecordTotal,
                    "meta": {
                        "outsource": this.outsource,
                        "deliveryDate": this.deliveryDate,
                        "inventoryId": this.inventoryId,
                        "deliveryMethod": this.deliveryMethod,
                    },
                })
            }

            this.productIndex = -1
            this.addRecordDialog = false
            this.outsource = "oss"
            this.deliveryDate = null
            this.inventoryId = 0
            this.deliveryMethod = null

        },
        cancelAddRecord() {
            this.productIndex = -1
            this.addRecordDialog = false
        },

        removeRecord(index) {
            this.form.information.splice(index, 1)
        },
        fileUpload(file) {
            const reader = new FileReader();
            if (file) {
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
                        type: "QUOTE",
                        file: e.target.result
                    }).then(res => {
                        this.quotes.push({
                            'file': res.data.file,
                            'ext': res.data.ext,
                        })
                        document.getElementById('quote').value = ""
                    }).catch(function (res) {
                        // this.form.errors.push(res.data.message)
                    })
                };
            }
        },
        removeQuote(index) {
            const file = this.quotes[index].file
            //delete online
            axios.post(this.$page.props.publicPath + "api/1.0.0/upload/delete", {
                'file': file
            })
            this.quotes.splice(index, 1)
        },
    }

}
</script>
