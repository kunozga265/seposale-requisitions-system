<template>
    <app-layout>
        <template #header>
            Sales Order
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
                    <a :href="route('sales.index')"
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
                        {{ sale.data.code }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>
            <!--      <span v-if="sale.data.invoice">-->
            <!--        <a :href="route('invoices.print',{'id':sale.data.invoice.id})" target="_blank">-->
            <!--        <primary-button>Print Invoice</primary-button>-->
            <!--      </a>-->
            <!--      </span>-->

            <a :href="route('sales.print',{'id':sale.data.id})" target="_blank">
                <primary-button>Print</primary-button>
            </a>
            <a v-if="sale.data.editable" :href="route('sales.edit',{'id':sale.data.id})">
                <primary-button>Edit</primary-button>
            </a>
            <danger-button v-if="sale.data.editable" @click.native="deleteDialog=true">Delete</danger-button>

        </template>

        <dialog-modal :show="deleteDialog" @close="deleteDialog=false">
            <template #title>
                Delete Sale
            </template>

            <template #content>
                Are you sure you want to delete this sale?
                Once you delete, this sale will no longer be available.
            </template>

            <template #footer>
                <secondary-button @click.native="deleteDialog=false">
                    Cancel
                </secondary-button>

                <danger-button class="ml-2" @click.native="deleteInvoice">
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
                </danger-button>
            </template>
        </dialog-modal>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">


                <sale-status class="mb-4" :sale="sale.data"/>

                <div class="">
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Overview
                            </div>
                        </div>

                        <div class="page-section-content">
                            <div class="card p-0">
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Date</div>
                                    <div>{{ getDate(sale.data.date * 1000) }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Code</div>
                                    <div>{{ sale.data.code }}</div>
                                </div>

                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Invoice Number</div>

                                    <div v-if="sale.data.invoice != null">
                                        <inertia-link :href="route('invoices.show',{id:sale.data.invoice.id})">
                                            {{ sale.data.invoice.code }}
                                        </inertia-link>
                                    </div>
                                    <div v-else>
                                        <div @click="generateInvoice" class="flex justify-start items-center cursor">
                                            <div>
                                                <i class="mdi mdi-plus-circle text-blue-600"></i>
                                            </div>
                                            <div class="ml-2 text-blue-600 text-sm">
                                                Generate Invoice
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--                <div class="border-b px-4 py-3 flex justify-between text-sm">-->
                                <!--                  <div class="text-gray-600 font-semibold">Site Location</div>-->
                                <!--                  <div>{{ sale.data.location }}</div>-->
                                <!--                </div>-->
                            </div>
                        </div>
                    </div>
<!--                    <div class="page-section">-->
<!--                        <div class="page-section-header">-->
<!--                            <div class="page-section-title">-->
<!--                                Deliveries-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="page-section-content">-->
<!--                            <div class="card profile">-->
<!--                                <div class="p-8 md:p-10 grid grid-cols-1 sm:grid-cols-2">-->
<!--                                    <div class="mb-4">-->
<!--                                        <div class="text-sm text-gray-600">Site Location</div>-->
<!--                                        <span-->
<!--                                            class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">-->
<!--                                        {{ sale.data.location }}-->
<!--                                        </span>-->
<!--                                    </div>-->
<!--                                    <div class="mb-4">-->
<!--                                        <div class="text-sm text-gray-600">Status</div>-->
<!--                                        <span-->
<!--                                            class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">-->
<!--                                        {{ getDeliveryStatusMessage(sale.data.delivery.status) }}-->
<!--                                        </span>-->
<!--                                    </div>-->
<!--                                    <div class="mb-4" v-if="sale.data.delivery.dateInitiated">-->
<!--                                        <div class="text-sm text-gray-600">Date Initiated</div>-->
<!--                                        <span-->
<!--                                            class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">-->
<!--                                        {{ getDate(sale.data.delivery.dateInitiated * 1000) }}-->
<!--                                        </span>-->
<!--                                    </div>-->
<!--                                    <div class="mb-4" v-if="sale.data.delivery.initiatedBy">-->
<!--                                        <div class="text-sm text-gray-600">Initiated By</div>-->
<!--                                        <span-->
<!--                                            class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">-->
<!--                                        {{ sale.data.delivery.initiatedBy.fullName }}-->
<!--                                        </span>-->
<!--                                    </div>-->
<!--                                    <div class="mb-4" v-if="sale.data.delivery.dateDelivered">-->
<!--                                        <div class="text-sm text-gray-600">Date Delivered</div>-->
<!--                                        <span-->
<!--                                            class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">-->
<!--                                        {{ getDate(sale.data.delivery.dateDelivered * 1000) }}-->
<!--                                        </span>-->
<!--                                    </div>-->
<!--                                    <div class="mb-4" v-if="sale.data.delivery.deliveredBy">-->
<!--                                        <div class="text-sm text-gray-600">Delivered By</div>-->
<!--                                        <span-->
<!--                                            class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">-->
<!--                                        {{ sale.data.delivery.deliveredBy.fullName }}-->
<!--                                        </span>-->
<!--                                    </div>-->

<!--                                </div>-->

<!--                            </div>-->

<!--                            <primary-button v-if="sale.data.delivery.status < 2"-->
<!--                                            @click.native="updateDeliveryDialog = true" class="ml-3 mt-2">-->
<!--                                {{ sale.data.delivery.status == 0 ? "Initiate" : "Close" }} Deliveries-->
<!--                            </primary-button>-->

                            <dialog-modal v-if="selectedProduct != null" :show="updateDeliveryDialog" @close="closeUpdateDeliveryDialog">
                                <template #title>
                                    {{ selectedProduct.delivery.status == 0 ? "Initiate" : "Close" }} Delivery
                                </template>

                                <template #content>
                                    <jet-validation-errors class="mb-4"/>

                                    <div class="mb-4">
                                        Give the operations department a go ahead to deliver <span class="font-bold">{{productName(selectedProduct)}} ({{selectedProduct.quantity}})</span>?
                                    </div>

                                    <div class="mb-4">
                                        <!--                                    <jet-label for="lastRefillDate" value="Backdate" />-->
<!--                                        <div class="flex items-center mb-2">-->
<!--                                            <input checked id="backdate" type="checkbox" value=""-->
<!--                                                   v-model="backdateDeliveryCheck"-->
<!--                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">-->
<!--                                            <label for="backdate"-->
<!--                                                   class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Delivery Due Date</label>-->
<!--                                        </div>-->
                                      <label
                                             class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Delivery Date</label>
                                        <vue-date-time-picker

                                            color="#1a56db"
                                            v-model="deliveryDate"
                                            :min-date="minDate"

                                        />
                                    </div>

<!--                                    <div class="mb-4">-->
<!--                                        <div class="flex items-center mb-2">-->
<!--                                            <input checked id="backdate" type="checkbox" value="" v-model="userCheck"-->
<!--                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">-->
<!--                                            <label for="backdate"-->
<!--                                                   class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">{{ selectedProduct.delivery.status == 0 ? "Initiated By" : "Delivered By" }}</label>-->
<!--                                        </div>-->
<!--                                        <select v-if="userCheck" v-model="userIndex" id="paymentMethod"-->
<!--                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"-->
<!--                                                required>-->
<!--                                            <option v-for="(user, index) in users.data" :value="index" :key="index">-->
<!--                                                {{ user.fullName }}-->
<!--                                            </option>-->
<!--                                        </select>-->
<!--                                    </div>-->

                                </template>

                                <template #footer>
                                    <secondary-button @click.native="closeUpdateDeliveryDialog">
                                        Cancel
                                    </secondary-button>

                                    <primary-button class="ml-2" @click.native="updateDelivery">
                                        <svg v-show="form.processing" role="status"
                                             class="inline w-4 h-4 mr-3 text-white animate-spin"
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

<!--                        </div>-->

<!--                    </div>-->
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Client Information
                            </div>
                        </div>
                        <div class="page-section-content">

                          <inertia-link :href="route('clients.show',{id:sale.data.client.id})">
                            <div class="card profile">
                              <div class="p-8 md:p-10 grid grid-cols-1 sm:grid-cols-2">
                                <div class="mb-4">
                                  <div class="text-sm text-gray-600">Name</div>
                                  <span
                                      class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ sale.data.client.name }}
                                        </span>
                                </div>
                                <div class="mb-4">
                                  <div class="text-sm text-gray-600">Phone Number</div>
                                  <span
                                      class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ sale.data.client.phoneNumber }}
                                        </span>
                                </div>
                                <div class="mb-4">
                                  <div class="text-sm text-gray-600">Email</div>
                                  <span
                                      class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ sale.data.client.email }}
                                        </span>
                                </div>
                                <div class="mb-4">
                                  <div class="text-sm text-gray-600">Address</div>
                                  <span
                                      class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ sale.data.client.address }}
                                        </span>
                                </div>
                              </div>

                            </div>
                          </inertia-link>
                        </div>
                    </div>

                    <div class="page-section md:col-span-2">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Products and Services
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="card">
                                <div class="p-2 relative overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class=" text-gray-600  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="heading-font">
                                                Delivery Status
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Details
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Units
                                            </th>
                                            <th scope="col" class="heading-font text-right">
                                                Quantity
                                            </th>
                                            <th scope="col" class="heading-font text-right">
                                                Unit Cost
                                            </th>
                                            <th scope="col" class="heading-font text-right">
                                                Total Cost
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr
                                            class="cursor-pointer hover:bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                            v-for="(productCompound,index) in sale.data.products"
                                            :key="index"
                                            @click="navigateToDelivery(productCompound)"
                                        >
                                            <td>
                                                <delivery-status v-if="productCompound.delivery != null" class="mr-1" :productCompound="productCompound" :overdue="productCompound.overdue" :is-solo="true"/>
                                            </td>
                                            <th scope="row"
                                                class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{ productCompound.description }}
                                            </th>
                                            <td class="py-2 pr-1">
                                                {{ productCompound.units }}
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(productCompound.quantity) }}
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                {{
                                                    numberWithCommas(productCompound.amount / productCompound.quantity)
                                                }}
                                            </td>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(productCompound.amount) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <th class="pt-4 pr-1 text-base heading-font font-bold text-right">Total</th>
                                            <td class="pt-4 pr-1 text-base font-bold text-right">
                                                {{ numberWithCommas(sale.data.total) }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-section md:col-span-2">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Receipts
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div class="grid grid-cols-1 md:grid-cols-2">
                                <inertia-link :href="route('receipts.show', {id:receipt.id})"
                                              v-for="(receipt, index) in sale.data.receipts" :key="index">
                                    <div class="app-card">
                                        <div class="header justify-between items-center border-b">
                                            <div>
                                                <div>
                          <span
                              class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                                  getDate(receipt.date * 1000)
                              }}</span>
                                                </div>
                                                <div class="type">#{{ receipt.code }}</div>


                                            </div>
                                            <div class="flex items-center ">
                                                <div class="currency ">MK</div>
                                                <div class="total">{{ numberWithCommas(receipt.amount) }}</div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="name font-normal ml-3 flex justify-between">
                                                <div>{{ receipt.paymentMethod }}</div>
                                                <div>Issued By {{ receipt.generatedBy.firstName }}
                                                    {{ receipt.generatedBy.middleName }}
                                                    {{ receipt.generatedBy.lastName }}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </inertia-link>
                            </div>


                            <primary-button v-if="checkRole($page.props.auth.data,'accountant') || checkRole($page.props.auth.data,'management')" @click.native="newReceiptDialog = true" class="ml-3">New Receipt
                            </primary-button>

                            <dialog-modal :show="newReceiptDialog" @close="newReceiptDialog=false">
                                <template #title>
                                    New Receipt
                                </template>

                                <template #content>
                                    <jet-validation-errors class="mb-4"/>

                                    <div class="mb-4">
                                        <!--                                    <jet-label for="lastRefillDate" value="Backdate" />-->
                                        <div class="flex items-center mb-2">
                                            <input checked id="backdate" type="checkbox" value=""
                                                   v-model="backdateCheck"
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="backdate"
                                                   class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Backdate</label>
                                        </div>
                                        <vue-date-time-picker
                                            v-if="backdateCheck"
                                            color="#1a56db"
                                            v-model="date"
                                            :max-date="maxDate"

                                        />
                                    </div>

                                    <div class="mb-4">
                                        <jet-label for="paymentMethod" value="Select Payment Method"/>
                                        <select v-model="paymentMethodIndex" id="paymentMethod"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                            <option v-for="(paymentMethod, index) in paymentMethods" :value="index"
                                                    :key="index">
                                                {{ paymentMethod.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <jet-label for="reference" value="Reference"/>
                                        <jet-input type="text" class="block w-full" v-model="form.reference"/>
                                    </div>
                                    <div class="mb-4">
                                        <div class="flex justify-between">
                                            <jet-label for="amount" value="Amount"/>
                                            <div class="flex items-center mb-2">
                                                <input checked id="fullPaymentCheck" type="checkbox" value=""
                                                       v-model="fullPaymentCheck"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="fullPaymentCheck"
                                                       class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Full
                                                    Payment</label>
                                            </div>
                                        </div>
                                        <money
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            v-bind="moneyMaskOptions" v-model="form.amount"/>
                                        <!--                    <jet-input type="text" class="block w-full" v-model="form.amount"/>-->
                                        <div class="mt-1 text-xs text-gray-500"
                                             :class="{'text-red-500':!amountValidation}">Balance:
                                            MK{{ numberWithCommas(sale.data.balance) }}
                                        </div>
                                    </div>

                                </template>

                                <template #footer>
                                    <secondary-button @click.native="newReceiptDialog=false">
                                        Cancel
                                    </secondary-button>

                                    <primary-button class="ml-2" @click.native="storeReceipt">
                                        <svg v-show="form.processing" role="status"
                                             class="inline w-4 h-4 mr-3 text-white animate-spin"
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

                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-content">
                            <div class="card p-0">
                                <div
                                    class="p-3 text-white text-sm font-semibold bg-system heading-font uppercase rounded-t-lg">
                                    Generated By
                                </div>
                                <div class="border-b px-3 py-2 flex justify-between text-sm">
                                    <div class="font-semibold">Name</div>
                                    <div>{{ sale.data.generatedBy.firstName }} {{ sale.data.generatedBy.middleName }}
                                        {{ sale.data.generatedBy.lastName }}
                                    </div>
                                </div>
                                <div class="border-b px-3 py-2 flex justify-between text-sm">
                                    <div class="font-semibold">Position</div>
                                    <div>{{ sale.data.generatedBy.position.title }}</div>
                                </div>
                                <div class="px-3 py-2 flex justify-between text-sm">
                                    <div class="font-semibold">Date</div>
                                    <div>{{ getDate(sale.data.createdDate * 1000) }}</div>
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
import {Money} from 'v-money'
import DeliveryStatus from "@/Components/DeliveryStatus.vue";


export default {
    props: ['sale', 'paymentMethods', 'users'],
    components: {
        DeliveryStatus,
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
            loading: false,
            updateDeliveryDialog: false,
            newReceiptDialog: false,
            attachmentDialog: false,
            attachmentIndex: null,
            attachmentType: '',
            denyDialog: false,
            deleteDialog: false,
            paymentMethodIndex: -1,
            backdateCheck: false,

            fullPaymentCheck: false,
            date: null,

            backdateDeliveryCheck: false,
            deliveryDate: null,

            userCheck: false,
            userIndex: -1,

            maxDate: new Date().toISOString().substr(0, 10),
            minDate: new Date(this.sale.data.date*1000).toISOString().substr(0, 10),
            moneyMaskOptions: {
                decimal: '.',
                thousands: ',',
                prefix: 'MK ',
                suffix: '',
                precision: 2,
                masked: false
            },
            form: this.$inertia.form({
                amount: 0,
                reference: "",

            }),
            selectedProduct:null,
        }
    },
    created() {

    },
    computed: {
        userId() {
            if (parseInt(this.userIndex) > 0) {
                return this.users.data[this.userIndex].id
            } else
                return null
        },
        paymentMethod() {
            if (parseInt(this.paymentMethodIndex) > 0) {
                return this.paymentMethods[this.paymentMethodIndex]
            } else
                return null
        },
        attachment() {
            if (this.attachmentIndex !== null) {
                if (this.attachmentType === 'quote')
                    return this.quotes[this.attachmentIndex]
                else if (this.attachmentType === 'receipt')
                    return this.receipts[this.attachmentIndex]

            }

            return null
        },
        amountValidation() {
            return this.sale.data.balance >= this.form.amount;
        }
    },
    watch: {
        fullPaymentCheck() {
            if (this.fullPaymentCheck) {
                this.form.amount = this.sale.data.balance
            }
        },
        backdateDeliveryCheck() {
            this.deliveryDate = null
        }
    },
    methods: {
        generateInvoice() {
            this.$inertia.post(this.route('invoices.generate', {'id': this.sale.data.id}))
        },
        printInvoice() {
            this.$inertia.get(this.route('invoices.print', {'id': this.sale.data.id}))
        },
        storeReceipt() {
            this.form
                .transform(data => ({
                    ...data,
                    payment_method_id: this.paymentMethod == null ? null : this.paymentMethod.id,
                    date: this.getTimestampFromDate(this.date),
                }))
                .post(this.route('sales.store.receipt', {'id': this.sale.data.id}), {
                    preserveScroll: true,
                    onSuccess: () => this.newReceiptDialog = false,
                })
        },
        updateDelivery() {
            this.form
                .transform(data => ({
                    ...data,
                  delivery_date: this.getTimestampFromDate(this.deliveryDate),
                    user_id: this.userId,
                }))
                .post(this.route('deliveries.update', {'id': this.selectedProduct.id}), {
                    // preserveScroll: true,
                    onSuccess: () => {
                        this.updateDeliveryDialog = false
                        this.selectedProduct = null
                    },
                })
        },
        deleteInvoice() {
            this.form
                .post(this.route('sales.delete', {'id': this.sale.data.id}), {
                    preserveScroll: true,
                    onSuccess: () => this.deleteDialog = false,
                })
        },

        getTimestampFromDate(date) {
            return date != null ? new Date(date).getTime() / 1000 : null
        },

        navigateToDelivery(productCompound){

            if(productCompound.delivery != null){
              if(productCompound.delivery.status === 0){
                this.selectedProduct = productCompound
                this.updateDeliveryDialog = true
              }else{
                this.$inertia.get(this.route('deliveries.index', {'id': this.sale.data.id}))
              }
            }
        },

        closeUpdateDeliveryDialog(){
            this.updateDeliveryDialog = false;
            this.selectedProduct = null
        }
    }
}
</script>
