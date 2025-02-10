<template xmlns="http://www.w3.org/1999/html">
    <app-layout>
        <template #header>
            Delivery Summary
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
                    <a :href="route('deliveries.index')"
                       class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Deliveries
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        #{{ delivery.data.code }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>
            <div class="md:flex grid grid-cols-2 md:grid-cols-5 gap-1">
                <whatsapp v-show="delivery.data.status === 4" template="delivery" :serial="delivery.data.serial"
                          :sent="delivery.data.whatsapp"/>

                <!--                <a :href="route('deliveries.print',{'id':delivery.data.id})" target="_blank">-->
                <!--                    <primary-button>Print</primary-button>-->
                <!--                </a>-->
                <!--            &lt;!&ndash;           <a :href="route('invoices.edit',{'id':delivery.data.id})">&ndash;&gt;-->
                <!--                <primary-button v-show="delivery.data.status === 1" @click.native="requisitionDialog=true">Make Request-->
                <!--                </primary-button>-->
                <!--                <primary-button v-show="delivery.data.status === 1" @click.native="showDialog=true">Add Note-->
                <!--                </primary-button>-->
                <!--                <primary-button v-show="delivery.data.status === 2" @click.native="completeDialog=true">Complete-->
                <!--                </primary-button>-->
                <!--           </a>-->


                <jet-dropdown align="right" width="48">
                    <template #trigger>
                        <primary-button>
                            Actions
                        </primary-button>
                    </template>

                    <template #content>
                        <!--                    &lt;!&ndash; Account Management &ndash;&gt;-->
                        <!--                    <div class="block px-4 py-2 text-xs text-gray-400">-->
                        <!--                        Quick Actions-->
                        <!--                    </div>-->

                        <a :href="route('deliveries.print',{'id':delivery.data.id})" target="_blank"
                           class="heading-font block w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            PRINT
                        </a>

                        <div class="border-t border-gray-100"></div>

                        <jet-dropdown-link v-show="delivery.data.status === 1" @click.native="requisitionDialog=true"
                                           as="button" class="text-left">
                            Make Request
                        </jet-dropdown-link>
                        <div class="border-t border-gray-100"></div>

                        <jet-dropdown-link v-show="delivery.data.status === 1" @click.native="showDialog=true"
                                           as="button" class="text-left">
                            Add Note
                        </jet-dropdown-link>
                        <div class="border-t border-gray-100"></div>

                        <jet-dropdown-link v-show="delivery.data.status === 2" @click.native="completeDialog=true"
                                           as="button" class="text-left">
                            Complete
                        </jet-dropdown-link>


                    </template>
                </jet-dropdown>

                <danger-button v-if="delivery.data.status === 1" @click.native="deleteDialog=true">Cancel
                </danger-button>
            </div>
        </template>

        <dialog-modal :show="showDialog" @close="showDialog=false">
            <template #title>
                Add Note
            </template>

            <template #content>
                <div class="delivery-profile grid grid-cols-1 md:grid-cols-2 gap-2">


                    <div v-show="delivery.data.status === 1" class="mb-4">
                        <div class="text-mute text-sm">
                            Quantity Delivered
                        </div>
                        <jet-input min="0" :max="quantityBalance" type="number" step="0.01" class="block w-full"
                                   v-model="form.quantity"/>
                        <!--                        <div class="text-mute text-xs">-->
                        <!--                            Hello World-->
                        <!--                        </div>-->
                    </div>

                    <div v-show="delivery.data.status === 1" class="mb-4">
                        <div class="text-mute text-sm">
                            Recipient Name
                        </div>
                        <jet-input type="text" class="block w-full" v-model="form.collectedBy"/>
                        <div class="text-red-500 text-xs" v-if="form.errors.recipient_name">Required
                        </div>
                    </div>

                    <div v-show="delivery.data.status === 1" class="mb-4">
                        <div class="text-mute text-sm">
                            Recipient Phone Number
                        </div>
                        <jet-input type="text" class="block w-full" v-model="form.recipientPhoneNumber"/>
                        <div class="text-red-500 text-xs" v-if="form.errors.recipient_phone_number">Required
                        </div>
                    </div>

                    <div v-if="delivery.data.status === 1" class="mb-4 md:col-span-2">
                        <div class="text-mute text-sm mb-1">
                            Upload Delivery Note
                        </div>
                        <input type="file" id="photo" @input="photoUpload($event.target.files[0])"
                               class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                        <div class="text-red-500 text-xs" v-if="form.errors.photo">Required
                        </div>
                    </div>

                </div>
            </template>

            <template #footer>
                <!--                <danger-button @click.native="showDialog=false">-->
                <!--                    Cancel-->
                <!--                </danger-button>-->
                <secondary-button @click.native="showDialog=false">
                    close
                </secondary-button>
                <primary-button v-show="delivery.data.status === 1" @click.native="submit">
                    <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
                         viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="#E5E7EB"/>
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentColor"/>
                    </svg>
                    Submit
                </primary-button>
            </template>
        </dialog-modal>

        <dialog-modal :show="requisitionDialog" @close="requisitionDialog=false">
            <template #title>
                Make Request
            </template>

            <template #content>
                <div class="mb-4">
                    <div>
                        Please fill in the following details to complete the requisition.
                    </div>
                    <div v-show="!requisitionValidation" class="flex items-center w-full text-red">
                        <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ error }}</div>
                    </div>
                </div>

                <div class="mb-4">
                    <jet-label for="personCollectingAdvance" value="Person Collecting"/>
                    <jet-input id="personCollectingAdvance" type="text" class="block w-full"
                               v-model="form.personCollectingAdvance"
                               autocomplete="geoserve-person-collecting-advance"/>
                </div>

                <div>
                    <div class="flex justify-start items-center">
                        <input checked id="backdate" type="checkbox" value=""
                               v-model="form.expenses.transportation.check"
                               class="w-4 h-4 mb-2 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label @click="form.expenses.transportation.check = !form.expenses.transportation.check"
                               for="amount"
                               class="ml-2 block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Transportation <span class="text-gray-500 text-sm">(To {{ delivery.data.location }})</span>
                        </label>
                    </div>
                    <div v-show="form.expenses.transportation.check" class="grid grid-cols-2 gap-2 mb-4">
                        <div>
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                v-model="form.expenses.transportation.transporterId"
                            >
                                <option value="0">
                                    Select Transporter
                                </option>
                                <option :key="index" v-for="(transporter,index) in transporters"
                                        :value="transporter.id">
                                    {{ transporter.name }}
                                </option>
                            </select>
                        </div>

                        <money
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            v-bind="moneyMaskOptions" v-model="form.expenses.transportation.amount"/>
                    </div>
                </div>
                <div>
                    <div class="flex justify-start items-center">
                        <input checked id="backdate" type="checkbox" value=""
                               v-model="form.expenses.product.check"
                               class="w-4 h-4 mb-2 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label @click="form.expenses.product.check = !form.expenses.product.check" for="amount"
                               class="ml-2 block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Product <span
                            class="text-gray-500 text-sm">({{ productName(delivery.data.summary) }})</span>
                        </label>

                    </div>
                    <div v-show="form.expenses.product.check" class="grid grid-cols-2 gap-2 mb-4">
                        <div>
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                v-model="form.expenses.product.supplierId"
                            >
                                <option value="0">
                                    Select Supplier
                                </option>
                                <option :key="index" v-for="(supplier,index) in suppliers" :value="supplier.id">
                                    {{ supplier.name }}
                                </option>
                            </select>
                        </div>

                        <money
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            v-bind="moneyMaskOptions" v-model="form.expenses.product.amount"/>
                    </div>
                </div>
                <div>
                    <div class="flex justify-start items-center">
                        <input checked id="backdate" type="checkbox" value=""
                               v-model="form.expenses.other.check"
                               class="w-4 h-4 mb-2 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <jet-label @click="form.expenses.other.check = !form.expenses.other.check" for="amount"
                                   value="Other Cost"
                                   class="ml-2"/>
                    </div>
                    <div v-show="form.expenses.other.check" class="grid grid-cols-2 gap-2 mb-4">

                        <div class="mb-2">
                            <jet-input type="text" class="block w-full" v-model="form.expenses.other.description"
                                       placeholder="Enter Description"/>

                        </div>

                        <div class="mb-2">
                            <money
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                v-bind="moneyMaskOptions" v-model="form.expenses.other.amount"/>

                        </div>

                        <div class="mb-4 md:col-span-2">
            <textarea v-model="form.expenses.other.comments" placeholder="Leave a comment (optional)"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>


            </template>

            <template #footer>
                <!--                <danger-button @click.native="showDialog=false">-->
                <!--                    Cancel-->
                <!--                </danger-button>-->
                <secondary-button @click.native="requisitionDialog=false">
                    close
                </secondary-button>
                <primary-button v-show="delivery.data.status === 1 && requisitionValidation"
                                @click.native="submitRequest">
                    <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
                         viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="#E5E7EB"/>
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentColor"/>
                    </svg>
                    Submit
                </primary-button>
            </template>
        </dialog-modal>

        <dialog-modal :show="completeDialog" @close="completeDialog=false">
            <template #title>
                Record Payables
            </template>

            <template #content>
                <div class="mb-4">
                    <div class="text-sm text-gray-500">
                        Please record all associated payables
                    </div>
                    <div v-if="!payableValidation" class="flex items-center w-full text-red">
                        <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ payableError }}</div>
                    </div>
                    <div v-else-if="form.payables.length == 0" class="flex items-center w-full text-red">
                        <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> You have not added any payables</div>
                    </div>
                </div>

                <div class="flex items-center mb-4">
                    <input id="default-radio-1" type="radio" value="transporter" v-model="payableCheck"
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-1"
                           class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Transporter</label>

                    <input checked id="default-radio-2" type="radio" value="supplier" v-model="payableCheck"
                           class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-2"
                           class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Supplier</label>
                </div>

                <div v-if="payableCheck === 'transporter'" class="grid grid-cols-2 gap-2 mb-4">
                    <div class="md:col-span-2">
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            v-model="transporter.transporterId"
                        >
                            <option value="0">
                                Select Transporter
                            </option>
                            <option :key="index" v-for="(transporter,index) in transporters"
                                    :value="transporter.id">
                                {{ transporter.name }}
                            </option>
                        </select>
                    </div>

                    <money
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        v-bind="moneyMaskOptions" v-model="transporter.amount"/>

                    <vue-date-time-picker
                        color="#1a56db"
                        v-model="transporter.date"
                        :min-date="minDate"
                    />

                    <div class="mb-4" v-show="payableValidation">
                        <primary-button @click.native="addPayable(transporter)">Add</primary-button>
                    </div>
                </div>

                <div v-else-if="payableCheck == 'supplier'" class="grid grid-cols-2 gap-2 mb-4">
                    <div class="md:col-span-2">
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            v-model="supplier.supplierId"
                        >
                            <option value="0">
                                Select Supplier
                            </option>
                            <option :key="index" v-for="(supplier,index) in suppliers" :value="supplier.id">
                                {{ supplier.name }}
                            </option>
                        </select>
                    </div>

                    <money
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        v-bind="moneyMaskOptions" v-model="supplier.amount"/>

                    <vue-date-time-picker
                        color="#1a56db"
                        v-model="supplier.date"
                        :min-date="minDate"
                    />

                    <div class="mb-4" v-show="payableValidation">
                        <primary-button @click.native="addPayable(supplier)">Add</primary-button>
                    </div>
                </div>

                <div v-show="form.payables.length > 0" class="p-2 mb-2 relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="heading-font">

                            </th>
                            <th scope="col" class="heading-font">
                                Name
                            </th>
                            <th scope="col" class="heading-font">
                               Amount
                            </th>

                            <th scope="col" class="heading-font">
                                Date
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                            v-for="(payable, index) in form.payables" :key="index">
                            <th scope="row" class="px-2">
                                <i @click="removePayable(index)"
                                   class="mdi mdi-close-circle text-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 cursor"></i>
                            </th>
                            <td scope="row"
                                class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{payable.name}}
                            </td>
                            <td  class="py-2 pr-1">
                                {{numberWithCommas(payable.amount)}}
                            </td>
                            <td class="py-2 pr-1">
                                {{getDate(payable.date*1000)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </template>

            <template #footer>
                <!--                <danger-button @click.native="showDialog=false">-->
                <!--                    Cancel-->
                <!--                </danger-button>-->
                <secondary-button @click.native="completeDialog=false">
                    close
                </secondary-button>
                <primary-button v-show="delivery.data.status === 2 && payableValidation"
                                @click.native="complete">
                    <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
                         viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="#E5E7EB"/>
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentColor"/>
                    </svg>
                    Submit
                </primary-button>
            </template>
        </dialog-modal>

        <!--        <dialog-modal :show="completeDialog" @close="completeDialog=false">

                    <template #title>
                        Complete Delivery
                    </template>

                    <template #content>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div class="mb-4">
                                <div class="text-mute text-sm">
                                    Product Cost
                                </div>
                                <money
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    v-bind="moneyMaskOptions" v-model="form.product"/>
                                <div class="text-red-500 text-xs" v-if="form.errors.product || productValidation">Required</div>
                            </div>
                            <div class="mb-4">
                                <div class="text-mute text-sm">
                                    Transportation Cost
                                </div>
                                <money
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    v-bind="moneyMaskOptions" v-model="form.transportation"/>
                                <div class="text-red-500 text-xs" v-if="form.errors.transportation || transportationValidation">
                                    Required
                                </div>
                            </div>
                            <div class="mb-4 md:col-span-2">
                                <div class="text-mute text-sm">
                                    Other Costs
                                </div>
                                <money
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    v-bind="moneyMaskOptions" v-model="form.other"/>
                            </div>
                            <div class="mb-4 md:col-span-2">
                                <div class="text-mute text-sm">
                                    Comments
                                </div>
                                <textarea
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    v-model="form.comments"> </textarea>
                            </div>


                        </div>
                    </template>

                    <template #footer>
                        &lt;!&ndash;                <danger-button @click.native="showDialog=false">&ndash;&gt;
                        &lt;!&ndash;                    Cancel&ndash;&gt;
                        &lt;!&ndash;                </danger-button>&ndash;&gt;
                        <secondary-button @click.native="completeDialog=false">
                            close
                        </secondary-button>
                        <primary-button v-show="delivery.data.status === 2" @click.native="complete">
                            <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
                                 viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="#E5E7EB"/>
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentColor"/>
                            </svg>
                            Submit
                        </primary-button>
                    </template>
                </dialog-modal>-->

        <dialog-modal :show="deleteDialog" @close="deleteDialog=false">
            <template #title>
                Cancel Delivery of {{ productName(delivery.data.summary) }}
            </template>

            <template #content>
                Are you sure you want to cancel this delivery?
            </template>

            <template #footer>
                <secondary-button @click.native="deleteDialog=false">
                    Cancel
                </secondary-button>

                <danger-button class="ml-2" @click.native="cancelDelivery">
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


                <!--              <div v-if="delivery.data.status === 0" class="mb-4 flex justify-start items-center approval-pending">-->
                <!--                <div>-->
                <!--                  <i class="mdi text-xl mdi-alert-circle"></i>-->
                <!--                </div>-->
                <!--                <div class="ml-3 text-sm">-->
                <!--                  Unpaid-->
                <!--                </div>-->
                <!--              </div>-->

                <!--              <div v-else class="flex justify-start items-center approved">-->
                <!--                <div>-->
                <!--                  <i class="mdi text-xl mdi-check-circle"></i>-->
                <!--                </div>-->
                <!--                <div class="ml-3 text-sm">-->
                <!--                  Paid-->
                <!--                </div>-->
                <!--              </div>-->

                <div>
                    <delivery-status :product-compound="delivery.data.summary" :due="delivery.data.due"
                                     :overdue="delivery.data.overdue"/>
                </div>


                <div>
                    <div class="page-section">
                        <div class="page-section-content">

                            <div class="card p-8 md:p-10 ">
                                <div class="mb-4 font-bold">
                                    {{ productName(delivery.data.summary) }}
                                </div>

                                <div class="delivery-profile grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <inertia-link :href="route('clients.show',{id:delivery.data.client.id})">
                                        <div class="mb-4">
                                            <div class="text-mute text-sm">
                                                Client
                                            </div>
                                            <div class="text-gray-500 text-sm">
                                                {{ delivery.data.client.name }}
                                            </div>
                                        </div>
                                    </inertia-link>
                                    <div class="mb-4">
                                        <div class="text-mute text-sm">
                                            Site Location
                                        </div>
                                        <div class="text-gray-500 text-sm">
                                            {{ delivery.data.location }}
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="text-mute text-sm">
                                            Quantity Delivered
                                        </div>
                                        <!--                        <div class="currency ">MK</div>-->
                                        <div>
                            <span class="total">{{
                                    numberWithCommas(delivery.data.quantityDelivered)
                                }}/{{ numberWithCommas(delivery.data.summary.quantity) }}</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-section" v-if="requestForms.data.length > 0">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Requisitions
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-2">
                                <request
                                    v-for="(request,index) in requestForms.data"
                                    :key="index"
                                    :request="request"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Details
                            </div>
                        </div>

                        <div class="page-section-content">
                            <div class="card p-0">
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Tracking Number</div>
                                    <div>{{ delivery.data.trackingNumber }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Ordered Date</div>
                                    <div>{{ getDate(delivery.data.summary.date * 1000) }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Due Date</div>
                                    <div>{{ getDate(delivery.data.date * 1000) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-section md:col-span-2" v-if="delivery.data.expense != null">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Expenses
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="card">
                                <div class="p-2 relative overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class=" text-gray-600  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="heading-font">
                                                Details
                                            </th>
                                            <th scope="col" class="heading-font text-right">
                                                Cost
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr
                                            class="cursor-pointer hover:bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                        >
                                            <th scope="row"
                                                class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                Product
                                            </th>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(delivery.data.expense.contents.product) }}
                                            </td>
                                        </tr>
                                        <tr
                                            class="cursor-pointer hover:bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                        >
                                            <th scope="row"
                                                class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                Transportation
                                            </th>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(delivery.data.expense.contents.transportation) }}
                                            </td>
                                        </tr>
                                        <tr
                                            class="cursor-pointer hover:bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                        >
                                            <th scope="row"
                                                class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                Other
                                            </th>
                                            <td class="py-2 pr-1 text-right">
                                                {{ numberWithCommas(delivery.data.expense.contents.other) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="pt-4 pr-1 text-base heading-font font-bold text-right"></th>
                                            <td class="pt-4 pr-1 text-base font-bold text-right">
                                                {{ numberWithCommas(delivery.data.expense.total) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                {{ delivery.data.expense.contents.comments }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div v-if="delivery.data.notes != null" class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Notes Summary
                            </div>
                        </div>

                        <div v-if="delivery.data.notes.length > 0" class="page-section-content">
                            <div class="">
                                <div class="card p-0"
                                     v-for="(note,index) in delivery.data.notes"
                                     :key="index"
                                >
                                    <div class="flex justify-between border-b p-3 text-sm ">
                                        <div>
                                            Delivered: {{ note.quantity }}
                                        </div>
                                        <div>
                                            <a :href="fileUrl(note.photo)" target="_blank">
                                                <!--                                                <div class=" h-10 w-10 flex justify-center items-center rounded-full bg-blue-700 cursor">-->
                                                <span class="text-blue-700 text-xs font-bold">Download</span>
                                                <i class="text-blue-700 font-bold mdi mdi-download text-white"></i>
                                                <!--                                                </div>-->
                                            </a>
                                        </div>
                                    </div>
                                    <div
                                        class="px-3 py-2 bg-text-gray-400 bg-gray-50 rounded-b-xl flex justify-between text-xs">
                                        <div>
                                            <div class="font-semibold">{{ note.recipientName }}</div>
                                            <div>{{ note.recipientPhoneNumber }}</div>

                                        </div>
                                        <div>
                                            {{ getDate(note.date * 1000, true) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-400 md:col-span-2 text-sm">
                            No delivery notes found
                        </div>
                    </div>
                    <!--                    <inertia-link :href="route('clients.show',{id:delivery.data.client.id})">
                                            <div class="page-section">
                                                <div class="page-section-header">
                                                    <div class="page-section-title">
                                                        Client Information
                                                    </div>
                                                </div>
                                                <div class="page-section-content">
                                                    <div class="card profile">
                                                        <div class="p-8 md:p-10 grid grid-cols-1 sm:grid-cols-2">
                                                            <div class="mb-4">
                                                                <div class="text-sm text-gray-600">Name</div>
                                                                <span
                                                                    class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                            {{ delivery.data.client.name }}
                                                            </span>
                                                            </div>
                                                            <div v-show="delivery.data.client.phone_number != null" class="mb-4">
                                                                <div class="text-sm text-gray-600">Phone Number</div>
                                                                <span
                                                                    class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                            {{ delivery.data.client.phone_number }}
                                                            </span>
                                                            </div>
                                                            <div v-show="delivery.data.client.email != null" class="mb-4">
                                                                <div class="text-sm text-gray-600">Email</div>
                                                                <span
                                                                    class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                             {{ delivery.data.client.email }}
                                                            </span>
                                                            </div>
                                                            <div v-show="delivery.data.client.address != null" class="mb-4">
                                                                <div class="text-sm text-gray-600">Address</div>
                                                                <span
                                                                    class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                            {{ delivery.data.client.address }}
                                                            </span>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </inertia-link>-->


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
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import {Money} from 'v-money'
import SaleStatus from "@/Components/SaleStatus.vue";
import Whatsapp from "@/Components/Whatsapp.vue";
import Request from "@/Components/Request.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";

export default {
    props: ['delivery', 'transporters', 'suppliers', 'requestForms'],
    components: {
        JetDropdownLink, JetDropdown,
        Request,
        Whatsapp,
        SaleStatus,
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
        JetValidationErrors,
        JetLabel,
        JetInput,
        Money
    },
    data() {
        return {
            loading: false,
            attachmentDialog: false,
            attachmentIndex: null,
            attachmentType: '',
            denyDialog: false,
            deleteDialog: false,
            showDialog: false,
            completeDialog: false,
            requisitionDialog: false,
            payableDialog: false,
            payableCheck: "",
            error: "",
            payableError: "",
            showLogs: false,
            minDate: new Date(this.delivery.data.summary.date * 1000).toISOString().substr(0, 10),
            form: this.$inertia.form({
                quantity: 0,
                photo: "",
                recipientName: "",
                recipientPhoneNumber: "",
                product: 0,
                transportation: 0,
                other: 0,
                comments: "",
                personCollectingAdvance: "",

                expenses: {
                    transportation: {
                        check: false,
                        transporterId: 0,
                        amount: 0,
                    },
                    product: {
                        check: false,
                        supplierId: 0,
                        amount: 0,
                    },
                    other: {
                        check: false,
                        description: "",
                        amount: 0,
                        comments: "",
                    },
                },
                payables: []
            }),
            moneyMaskOptions: {
                decimal: '.',
                thousands: ',',
                prefix: 'MK ',
                suffix: '',
                precision: 2,
                masked: false
            },
            quantityValidation: null,
            productValidation: false,
            transportationValidation: false,
            transporter: {
                transporterId: 0,
                amount: 0,
                date: null
            },
            supplier: {
                supplierId: 0,
                amount: 0,
                date: null
            },
        }
    },
    created() {

    },
    computed: {
        quantityBalance() {
            return this.delivery.data.summary.quantity - this.delivery.data.quantityDelivered

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

        requisitionValidation() {
            if (!this.form.expenses.transportation.check && !this.form.expenses.product.check && !this.form.expenses.other.check) {
                this.error = "Enter at least one cost item"
                return false
            }
            if (this.form.expenses.transportation.check) {
                if (this.form.expenses.transportation.transporterId == 0) {
                    this.error = "Select transporter"
                    return false
                } else if (this.form.expenses.transportation.amount <= 0) {
                    this.error = "Enter transportation cost"
                    return false
                }
            }

            if (this.form.expenses.product.check) {
                if (this.form.expenses.product.supplierId == 0) {
                    this.error = "Select supplier"
                    return false
                } else if (this.form.expenses.product.amount <= 0) {
                    this.error = "Enter supplier cost"
                    return false
                }
            }

            if (this.form.expenses.other.check) {
                if (this.form.expenses.other.description.length === 0 || this.form.expenses.other.description == "") {
                    this.error = "Enter description"
                    return false
                } else if (this.form.expenses.other.amount <= 0) {
                    this.error = "Enter other cost"
                    return false
                } else {
                    return true
                }
            }

            return true

        },

        payableValidation() {

            if (this.payableCheck==='transporter') {
                if (this.transporter.transporterId == 0) {
                    this.payableError = "Select transporter"
                    return false
                } else if (this.transporter.amount <= 0) {
                    this.payableError = "Enter transportation cost"
                    return false
                }else if (this.transporter.date == null) {
                    this.payableError = "Enter date"
                    return false
                }
            }else if (this.payableCheck==='supplier') {
                if (this.supplier.supplierId == 0) {
                    this.payableError = "Select supplier"
                    return false
                } else if (this.supplier.amount <= 0) {
                    this.payableError = "Enter supplier cost"
                    return false
                }else if (this.supplier.date == null) {
                    this.payableError = "Enter date"
                    return false
                }
            }

            return true






        }

    },
    watch: {
        payableCheck() {
         this.clearPayables()
        }
    },
    methods: {
        addPayable(payable){
            const item = this.payableCheck === 'transporter' ? this.transporters.find((x) => x.id === payable.transporterId) : this.suppliers.find((x) => x.id === payable.supplierId)
            console.log(item)
            this.form.payables.push({
                name: item.name,
                supplierId: payable.supplierId,
                transporterId: payable.transporterId,
                amount: payable.amount,
                date: this.getTimestampFromDate(payable.date)
            })
            this.payableCheck = ''
            this.clearPayables()

        },
        removePayable(index) {
            this.form.payables.splice(index, 1)
        },
        clearPayables(){
            this.transporter = {
                transporterId: 0,
                amount: 0,
                date: null
            }
            this.supplier = {
                supplierId: 0,
                amount: 0,
                date: null
            }
        },
        printInvoice() {
            this.$inertia.get(this.route('invoices.print', {'id': this.delivery.data.id}))
        },
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    recipient_name: this.form.collectedBy,
                    recipient_phone_number: this.form.recipientPhoneNumber,
                }))
                .post(this.route('deliveries.update', {'id': this.delivery.data.summary.id}), {
                    // preserveScroll: true,
                    onSuccess: () => {
                        this.showDialog = false
                        this.form.quantity = 0
                        this.form.collectedBy = ""
                        this.form.recipientPhoneNumber = ""
                        document.getElementById('photo').value = ""
                    },
                })
        },
        submitRequest() {
            this.form
                .transform(data => ({
                    ...data,
                }))
                .post(this.route('request-forms.store.delivery', {'id': this.delivery.data.summary.id}), {
                    // preserveScroll: true,
                    onSuccess: () => {
                        this.requisitionDialog = false
                        this.form.expenses = {
                            transportation: {
                                check: false,
                                transporterId: 0,
                                amount: 0,
                            },
                            product: {
                                check: false,
                                supplierId: 0,
                                amount: 0,
                            },
                            other: {
                                check: false,
                                description: "",
                                amount: 0,
                                comments: "",
                            },
                        }
                    },
                })
        },
        complete() {
            this.form
                .transform(data => ({
                    ...data,

                }))
                .post(this.route('deliveries.complete', {'id': this.delivery.data.id}), {
                    // preserveScroll: true,
                    onSuccess: () => {
                        this.completeDialog = false
                        this.form.product = 0
                        this.form.transportation = 0
                        this.form.other = 0
                        this.form.comments = ""
                        document.getElementById('photo').value = ""
                    },
                })
        },
        cancelDelivery() {
            this.form
                .transform(data => ({
                    ...data,
                    recipient_name: this.form.collectedBy,
                    recipient_phone_number: this.form.recipientPhoneNumber,
                }))
                .post(this.route('deliveries.cancel', {'id': this.delivery.data.id}), {
                    // preserveScroll: true,
                    onSuccess: () => {
                        this.deleteDialog = false
                    },
                })
        },
        photoUpload(file) {
            const reader = new FileReader();
            if (file) {
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
                        type: "DELIVERY_NOTE",
                        file: e.target.result
                    }).then(res => {
                        this.form.photo = res.data.file

                    }).catch(function (res) {
                        // this.form.errors.push(res.data.message)
                    })
                };
            }
        },
    }
}
</script>
