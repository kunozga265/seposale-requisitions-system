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
            <div class="md:flex grid grid-cols-2 md:grid-cols-5 gap-1">
                <whatsapp template="sales_order" :serial="sale.data.serial" :sent="sale.data.whatsapp" />

                <a :href="route('sales.print', { 'id': sale.data.id })" target="_blank">
                    <primary-button>Print</primary-button>
                </a>
                <a v-if="sale.data.editable" :href="route('sales.edit', { 'id': sale.data.id })">
                    <primary-button>Edit</primary-button>
                </a>
                <danger-button v-if="sale.data.status == 1" @click.native="closeDialog = true">Close</danger-button>
                <danger-button v-if="sale.data.editable" @click.native="deleteDialog = true">Delete</danger-button>
            </div>

        </template>

        <dialog-modal :show="deleteDialog" @close="deleteDialog = false">
            <template #title>
                Delete Sale
            </template>

            <template #content>
                Are you sure you want to delete this sale?
                Once you delete, this sale will no longer be available.
            </template>

            <template #footer>
                <secondary-button @click.native="deleteDialog = false">
                    Cancel
                </secondary-button>

                <danger-button class="ml-2" @click.native="deleteSale">
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
                </danger-button>
            </template>
        </dialog-modal>

        <dialog-modal :show="closeDialog" @close="closeDialog = false">
            <template #title>
                Close Sale
            </template>

            <template #content>
                Are you sure you want to close this sale?
                This sale has only been partially paid.
            </template>

            <template #footer>
                <secondary-button @click.native="closeDialog = false">
                    Cancel
                </secondary-button>

                <danger-button class="ml-2" @click.native="closeSale">
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
                </danger-button>
            </template>
        </dialog-modal>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">


                <sale-status class="mb-4" :status="sale.data.status" />

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
                                        <inertia-link :href="route('invoices.show', { id: sale.data.invoice.id })">
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


                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Balance</div>
                                    <div>MK{{ numberWithCommas(sale.data.balance) }}</div>
                                </div>
                                <!--                <div v-if="sale.data.expense != null" class="border-b px-4 py-3 flex justify-between text-sm">-->
                                <!--                  <div class="text-gray-600 font-semibold">Profit</div>-->
                                <!--                  <div-->
                                <!--                      :class="{'text-red-500':profit(sale.data.expense.total)<0, 'text-green-500':profit(sale.data.expense.total)>0, }">-->
                                <!--                    MK{{ profit(sale.data.expense.total) }}-->
                                <!--                  </div>-->
                                <!--                </div>-->

                                <!--                <div class="border-b px-4 py-3 flex justify-between text-sm">-->
                                <!--                  <div class="text-gray-600 font-semibold">Site Location</div>-->
                                <!--                  <div>{{ sale.data.location }}</div>-->
                                <!--                </div>-->
                            </div>
                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Site Details
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div class="card p-0">
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Location</div>
                                    <div>{{ sale.data.location }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Recipient Name</div>
                                    <div>{{ sale.data.recipientName }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Recipient Profession</div>
                                    <div>{{ sale.data.recipientProfession }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Recipient Phone Number</div>
                                    <div>{{ sale.data.recipientPhoneNumber }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <dialog-modal v-if="selectedProduct != null" :show="updateDeliveryDialog"
                        @close="closeUpdateDeliveryDialog">
                        <template #title>
                            Process Sale
                        </template>

                        <template #content>
                            <jet-validation-errors class="mb-4" />
                            <div v-if="selectedProduct.paymentStatus == 0 && (checkRole($page.props.auth.data, 'accountant') || checkRole($page.props.auth.data, 'management'))"
                                class="flex items-center mb-4">
                                <input checked id="backdate" type="checkbox" v-model="form.waiver"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="backdate"
                                    class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Waiver</label>
                            </div>

                            <div v-if="selectedProduct.paymentStatus == 0 && !form.waiver">

                                <div class="mb-4">
                                    Product has not been paid for. Please contact the accounts department to update
                                    payment status.
                                </div>
                            </div>


                            <div v-else class="mb-4">
                                <div class="flex items-center mb-4">
                                    <input checked id="backdate" type="checkbox" v-model="outsource"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="backdate"
                                        class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Outsource
                                        Products?</label>
                                </div>

                                <div v-if="outsource">
                                    <div class="mb-2">
                                        Give the operations department a go ahead to deliver <span class="font-bold">{{
                                            selectedProduct.description }}</span>?
                                    </div>

                                    <div class="mb-4">
                                        <label
                                            class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-300">Delivery
                                            Date</label>
                                        <vue-date-time-picker color="#1a56db" v-model="deliveryDate"
                                            :min-date="minDate" />
                                    </div>
                                </div>

                                <div v-else>
                                    <div class="mb-2">
                                        Select item under respective <span class="font-bold">One Stop Shop</span>
                                    </div>

                                    <table class="w-full mb-4">
                                        <th class="text-left"></th>
                                        <th class="text-left">Product</th>
                                        <th class="text-left">Quantity Available</th>
                                        <th class="text-left">Site Name</th>
                                        <tbody>
                                            <tr @click="form.inventoryId = inventory.id"
                                                class="border-t-1 cursor-pointer hover:bg-gray-50"
                                                v-for="(inventory, index) in selectedProduct.product.inventories"
                                                :key="index">
                                                <td class="text-left">

                                                    <!-- <i v-show="form.inventoryId == inventory.id"
                                                        class="mdi mdi-check-circle text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"></i> -->
                                                    <!-- <span v-show="form.inventoryId == inventory.id"    class="mdi mdi-check-circle text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">Check!</span> -->
                                                    <input id="default-radio-1"
                                                        :checked="form.inventoryId == inventory.id" type="checkbox"
                                                        disabled value="deliver"
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
                                        <input id="default-radio-1" type="radio" value="delivery"
                                            v-model="form.deliveryMethod"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="default-radio-1"
                                            class="ml-1 text-xs font-medium text-gray-900 dark:text-gray-300">To
                                            Deliver</label>

                                        <input checked id="default-radio-2" type="radio" value="collection"
                                            v-model="form.deliveryMethod"
                                            class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="default-radio-2"
                                            class="ml-1 text-xs font-medium text-gray-900 dark:text-gray-300">Self
                                            Collection</label>
                                    </div>
                                    <div v-show="form.deliveryMethod == 'delivery'" class="mb-4">
                                        <label
                                            class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-300">Delivery
                                            Date</label>
                                        <vue-date-time-picker color="#1a56db" v-model="deliveryDate"
                                            :min-date="minDate" />
                                    </div>



                                </div>

                            </div>


                        </template>

                        <template #footer>
                            <secondary-button @click.native="closeUpdateDeliveryDialog">
                                Cancel
                            </secondary-button>

                            <primary-button v-if="selectedProduct.paymentStatus != 0 || form.waiver" class="ml-2"
                                @click.native="updateDelivery">
                                <svg v-show="form.processing" role="status"
                                    class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
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

                    <!--                        </div>-->

                    <!--                    </div>-->
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Client Information
                            </div>
                        </div>
                        <div class="page-section-content">

                            <inertia-link :href="route('clients.show', { id: sale.data.client.id })">
                                <div class="card profile">
                                    <div class="p-8 md:p-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
                                        <div class="mb-4">
                                            <div class="text-sm text-gray-600">Name</div>
                                            <span
                                                class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                {{ sale.data.client.name }}
                                            </span>
                                        </div>

                                        <div v-show="sale.data.client.phone_number != null" class="mb-4">
                                            <div class="text-sm text-gray-600 flex items-center">Phone Number
                                                <svg class="ml-1" height="24px" width="20px" version="1.1" id="Layer_1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                                    xml:space="preserve">
                                                    <path style="fill:#fbfbfb;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
	S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z" />
                                                    <path style="fill:#55c76a;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
	c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
	c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z" />
                                                    <path style="fill:#FEFEFE;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
	c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
	c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
	c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
	c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
	l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945" />
                                                </svg>
                                            </div>
                                            <span
                                                class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                {{ sale.data.client.phone_number }}
                                            </span>
                                        </div>
                                        <div v-show="sale.data.client.phone_number_other != null" class="mb-4">
                                            <div class="text-sm text-gray-600">Phone Number (Secondary)</div>
                                            <span
                                                class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                {{ sale.data.client.phone_number_other }}
                                            </span>
                                        </div>
                                        <div v-show="sale.data.client.email != null" class="mb-4">
                                            <div class="text-sm text-gray-600">Email</div>
                                            <span
                                                class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                {{ sale.data.client.email }}
                                            </span>
                                        </div>
                                        <div v-show="sale.data.client.address != null" class="mb-4">
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
                                    <table
                                        class="w-full  default-table text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class=" text-gray-600  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="heading-font">
                                                    Payment Status
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
                                                <th scope="col" class="heading-font text-right">
                                                    Balance
                                                </th>
                                                <th scope="col" class="heading-font px-2">
                                                    Delivery Status
                                                </th>
                                                <th scope="col" class="heading-font px-2">
                                                    Collection Status
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="cursor-pointer hover:bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                                v-for="(productCompound, index) in sale.data.products" :key="index"
                                                @click="navigateToDelivery(productCompound)">
                                                <td>
                                                    <sale-status :status="productCompound.paymentStatus"
                                                        :is-solo="true" />
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
                                                <td class="py-2 pr-1 text-right">
                                                    {{
                                                        productCompound.balance != null ?
                                                            numberWithCommas(productCompound.balance)
                                                            : "-"
                                                    }}
                                                </td>
                                                <td class="px-2">
                                                    <delivery-status v-if="productCompound.delivery != null"
                                                        class="mr-1" :productCompound="productCompound"
                                                        :overdue="productCompound.overdue" :is-solo="true" />
                                                    <span v-else>-</span>
                                                </td>


                                                <td class="px-2">
                                                    <collection v-if="productCompound.status == 2"
                                                        class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                        :client="sale.data.client"
                                                        :product="productCompound.siteSaleSummary" :is-solo="true"
                                                        :disabled="true" />
                                                    <span v-else>-</span>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                                <th class="pt-4 pr-1 text-base heading-font font-bold text-right">Total
                                                </th>
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
                                <inertia-link :href="route('receipts.show', { id: receipt.id })"
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


                            <primary-button
                                v-if="checkRole($page.props.auth.data, 'accountant') || checkRole($page.props.auth.data, 'management')"
                                @click.native="newReceiptDialog = true" class="ml-3">New Receipt
                            </primary-button>

                            <dialog-modal :show="newReceiptDialog" @close="newReceiptDialog = false">
                                <template #title>
                                    New Receipt
                                </template>

                                <template #content>
                                    <jet-validation-errors class="mb-4" />

                                    <div class="mb-4">
                                        <!--                                    <jet-label for="lastRefillDate" value="Backdate" />-->
                                        <div class="flex items-center mb-2">
                                            <input checked id="backdate" type="checkbox" value=""
                                                v-model="backdateCheck"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="backdate"
                                                class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Backdate</label>
                                        </div>
                                        <vue-date-time-picker v-if="backdateCheck" color="#1a56db" v-model="date"
                                            :max-date="maxDate" />
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                                        <div class="mb-4">
                                            <jet-label for="paymentMethod" value="Select Account" />
                                            <select v-model="accountIndex" id="paymentMethod"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                                <option v-for="(account, index) in accounts" :value="index"
                                                    :key="index">
                                                    {{ account.name }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <jet-label for="paymentMethod" value="Select Payment Method" />
                                            <select v-model="paymentMethodIndex" id="paymentMethod"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                                <option v-for="(paymentMethod, index) in paymentMethods" :value="index"
                                                    :key="index">
                                                    {{ paymentMethod.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <jet-label for="reference" value="Reference" />
                                        <jet-input type="text" class="block w-full" v-model="form.reference" />
                                    </div>
                                    <div class="mb-4">
                                        <div class="heading-font text-">Payment Summary;</div>
                                    </div>

                                    <div class="mb-4" v-for="(product, index) in form.information" :key="index">
                                        <div class="flex justify-between">
                                            <jet-label for="amount" :value="product.name" />
                                            <div class="flex items-center mb-2">
                                                <div @click="product.amount = product.balance"
                                                    class="flex items-center rounded-full py-2 px-3 bg-gray-200 text-gray-600 text-xs font-bold "
                                                    :class="{ 'info': product.amount == product.balance }">
                                                    <div>Full Payment</div>
                                                    <i v-show="product.amount == product.balance"
                                                        class="ml-2 mdi mdi-check-circle text-gray-600  cursor"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <money
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            v-bind="moneyMaskOptions" v-model="product.amount" />
                                        <!--                    <jet-input type="text" class="block w-full" v-model="form.amount"/>-->
                                        <div class="mt-1 text-xs text-gray-500"
                                            :class="{ 'text-red-500': !balanceValidate(product) }">Balance:
                                            MK{{ numberWithCommas(product.balance) }}
                                        </div>
                                    </div>

                                    <div class="flex justify-between">
                                        <div class="mb-4">
                                            <div class="heading-font text-lg ">MK
                                                {{ numberWithCommas(receiptAmount.toFixed(2)) }}
                                            </div>
                                            <div class="heading-font text-xs">Total Amount</div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="heading-font text-lg"
                                                :class="{ 'text-red-500': !amountValidation }">MK
                                                {{ numberWithCommas(receiptBalance.toFixed(2)) }}
                                            </div>
                                            <div class="heading-font text-xs">Balance</div>
                                        </div>
                                    </div>


                                </template>

                                <template #footer>
                                    <secondary-button @click.native="newReceiptDialog = false">
                                        Cancel
                                    </secondary-button>

                                    <primary-button class="ml-2" @click.native="storeReceipt">
                                        <svg v-show="form.processing" role="status"
                                            class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
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
import { Money } from 'v-money'
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import { red } from "tailwindcss/colors";
import Whatsapp from "@/Components/Whatsapp.vue";
import Collection from "@/Components/Collection.vue";


export default {
    props: ['sale', 'paymentMethods', 'accounts'],
    components: {
        Whatsapp,
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
        Money,
        Collection,
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
            closeDialog: false,
            paymentMethodIndex: -1,
            accountIndex: -1,
            backdateCheck: false,

            fullPaymentCheck: false,
            date: null,

            backdateDeliveryCheck: false,
            deliveryDate: null,

            userCheck: false,
            userIndex: -1,

            maxDate: new Date().toISOString().substr(0, 10),
            // minDate: new Date().toISOString().substr(0, 10),
            minDate: null,
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
                information: [],
                inventoryId: 0,
                deliveryMethod: null,
                waiver: false,
            }),
            selectedProduct: null,
            outsource: false,
        }
    },
    created() {

        for (let x in this.sale.data.products) {

            const balance = this.sale.data.products[x].balance != null && this.sale.data.products[x].balance >= 0 ? this.sale.data.products[x].balance : this.sale.data.products[x].amount;
            if (balance != 0) {
                this.form.information.push({
                    id: this.sale.data.products[x].id,
                    name: this.sale.data.products[x].description,
                    balance: balance,
                    amount: 0,
                    cost: this.sale.data.products[x].amount,
                    units: this.sale.data.products[x].units,
                })
            }
        }

    },
    computed: {
        red() {
            return red
        },
        userId() {
            if (parseInt(this.userIndex) >= 0) {
                return this.users.data[this.userIndex].id
            } else
                return null
        },
        paymentMethod() {
            if (parseInt(this.paymentMethodIndex) >= 0) {
                return this.paymentMethods[this.paymentMethodIndex]
            } else
                return null
        },
        account() {
            if (parseInt(this.accountIndex) >= 0) {
                return this.accounts[this.accountIndex]
            } else
                return null
        },
        receiptAmount() {
            let sum = 0;
            for (let x in this.form.information) {
                sum += this.form.information[x].amount;
            }
            return sum;
        },
        receiptBalance() {
            return (this.sale.data.balance - this.receiptAmount);
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
            return this.sale.data.balance >= this.receiptAmount;
        },
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
        profit(expenses) {
            let total = []

            for (let x in this.sale.data.products) {
                if (this.sale.data.products[x].paymentStatus > 0) {
                    total += this.sale.data.products[x].amount
                }
            }

            return total - expenses;
        },
        generateInvoice() {
            this.$inertia.post(this.route('invoices.generate', { 'id': this.sale.data.id }))
        },
        printInvoice() {
            this.$inertia.get(this.route('invoices.print', { 'id': this.sale.data.id }))
        },
        storeReceipt() {
            this.form
                .transform(data => ({
                    ...data,
                    payment_method_id: this.paymentMethod == null ? null : this.paymentMethod.id,
                    account_id: this.account == null ? null : this.account.id,
                    date: this.getTimestampFromDate(this.date),
                    type: "ORDINARY",
                }))
                .post(this.route('receipts.store', { 'id': this.sale.data.id }), {
                    preserveScroll: true,
                    onSuccess: () => this.newReceiptDialog = false,
                })
        },
        attachReceipt() {
            this.form
                .transform(data => ({
                    ...data,
                    payment_method_id: this.paymentMethod == null ? null : this.paymentMethod.id,
                    account_id: this.account == null ? null : this.account.id,
                    date: this.getTimestampFromDate(this.date),
                    type: "ORDINARY",
                }))
                .post(this.route('receipts.attach', { 'id': this.sale.data.id }), {
                    preserveScroll: true,
                    onSuccess: () => this.newReceiptDialog = false,
                })
        },
        updateDelivery() {

            if (this.outsource) {
                this.form
                    .transform(data => ({
                        ...data,
                        delivery_date: this.getTimestampFromDate(this.deliveryDate),
                    }))
                    .post(this.route('deliveries.update', { 'id': this.selectedProduct.id }), {
                        // preserveScroll: true,
                        onSuccess: () => {
                            this.updateDeliveryDialog = false
                            this.selectedProduct = null
                        },
                    })
            } else {
                this.form
                    .transform(data => ({
                        ...data,
                        inventory_id: this.form.inventoryId,
                        summary_id: this.selectedProduct.id,
                        delivery_date: this.getTimestampFromDate(this.deliveryDate),
                        delivery_method: this.form.deliveryMethod,
                    }))
                    .post(this.route('sales.make-site-sale'), {
                        // preserveScroll: true,
                        onSuccess: () => {
                            this.updateDeliveryDialog = false
                            this.selectedProduct = null
                        },
                    })
            }
        },
        deleteSale() {
            this.form
                .post(this.route('sales.delete', { 'id': this.sale.data.id }), {
                    preserveScroll: true,
                    onSuccess: () => this.deleteDialog = false,
                })
        },
        closeSale() {
            this.form
                .post(this.route('sales.close', { 'id': this.sale.data.id }), {
                    preserveScroll: true,
                    onSuccess: () => this.closeDialog = false,
                })
        },

        getTimestampFromDate(date) {
            return date != null ? new Date(date).getTime() / 1000 : null
        },

        navigateToDelivery(productCompound) {


            if (!productCompound.isService) {


                if (productCompound.status == 2) {
                    if (productCompound.siteSaleSummary != null) {
                        this.$page.props.flash.warning = productCompound.description + " is being processed under " + productCompound.siteSaleSummary.site.name
                        this.$inertia.get(this.route('sites.sales.show', { 'code': productCompound.siteSaleSummary.site.code, 'id': productCompound.siteSaleSummary.sale.id }))
                    } else {
                        this.$page.props.flash.warning = productCompound.description + " is being processed under local branch"
                    }
                }
                //if delivery is initiated it will take them to the delivery page
                else if (productCompound.delivery != null) {
                    if (parseInt(productCompound.delivery.status) == 0) {
                        if (productCompound.collection != null) {
                            //collection page
                        } else {
                            this.selectedProduct = productCompound
                            this.updateDeliveryDialog = true
                        }
                    } else {
                        this.$inertia.get(this.route('deliveries.show', { 'id': productCompound.delivery.id }))
                    }
                } else {
                    if (productCompound.collection != null) {
                        //collection page
                    } else {
                        this.selectedProduct = productCompound
                        this.updateDeliveryDialog = true
                    }
                }
            }

        },

        closeUpdateDeliveryDialog() {
            this.updateDeliveryDialog = false;
            this.selectedProduct = null
        },

        balanceValidate(product) {
            return product.balance >= product.amount
        }
    }
}
</script>
