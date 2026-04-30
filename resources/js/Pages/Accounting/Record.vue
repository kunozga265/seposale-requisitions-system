<template>
    <app-layout>
        <template #header>
            Transaction Details
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
                    <a :href="route('accounts.index')"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Accounts
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>

                    <a :href="route('accounts.show', { code: record.data.account.code })"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        {{ record.data.account.name }}
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>

                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ record.data.serial }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>

            <primary-button @click.native="editDialog = true">Edit</primary-button>
            <!-- <primary-button @click.native="addTransationDialog = true">Add Transaction</primary-button> -->
            <!-- <a :href="route('accounts.edit', { 'id': account.data.id })">
                <secondary-button>Edit</secondary-button>
            </a> -->
        </template>

        <dialog-modal :show="editDialog" @close="editDialog = false">
            <template #title>
                Edit Transaction
            </template>

            <template #content>
                <jet-validation-errors class="mb-4" />

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">

                    <div class="mb-2 md:col-span-3">
                        <jet-label for="description" value="Description" />
                        <jet-input id="description" type="text" class="block w-full" v-model="form.description"
                            autocomplete="seposale-transaction-description" />
                    </div>

                    <div class="mb-2">
                        <jet-label for="receipt_code" value="Receipt Code" />
                        <jet-input id="receipt_code" type="text" class="block w-full" v-model="form.receiptCode"
                            autocomplete="seposale-transaction-receipt_code" />
                    </div>

                    <!-- <div class="mb-2">
                        <jet-label for="requisition_code" value="Requisition Code" />
                        <jet-input id="requisition_code" type="text" class="block w-full" v-model="form.requistionCode"
                            autocomplete="seposale-transaction-requisition_code" />
                    </div> -->

                    <div class="mb-2">
                        <jet-label for="production_code" value="Production Code" />
                        <jet-input id="production_code" type="text" class="block w-full" v-model="form.productionCode"
                            autocomplete="seposale-transaction-production_code" />
                    </div>

                    <div class="mb-2">
                        <jet-label for="collection_code" value="Collection Code" />
                        <jet-input id="collection_code" type="text" class="block w-full" v-model="form.collectionCode"
                            autocomplete="seposale-transaction-collection_code" />
                    </div>


                </div>
            </template>

            <template #footer>
                <secondary-button @click.native="editDialog = false">
                    Cancel
                </secondary-button>

                <primary-button class="ml-2" @click.native="editTransaction" :disabled="form.processing">
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

                <div class="grid grid-cols-1">
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Details
                            </div>
                        </div>
                        <div class="page-section-content">



                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div class="card p-0 md:col-span-2">
                                    <div class="border-b px-4 py-3 flex justify-between text-sm">
                                        <div class="text-gray-600 font-semibold">Date</div>
                                        <div>{{ getDate(record.data.date * 1000) }}</div>
                                    </div>
                                    <div class="border-b px-4 py-3 flex justify-between text-sm">
                                        <div class="text-gray-600 font-semibold">Amount</div>
                                        <div>MK{{ numberWithCommas(record.data.amount.toFixed(2)) }}</div>
                                    </div>
                                    <div class="border-b px-4 py-3 flex justify-between text-sm">
                                        <div class="text-gray-600 font-semibold">Description</div>
                                        <div>{{ record.data.description }}</div>
                                    </div>

                                </div>

                                <div @click="navigateToAccount(debitRecord)"
                                    class="card mb-0 cursor-pointer hover:shadow-xl transition ease-in-out duration-20"
                                    v-if="debitRecord != null">
                                    <div class="flex justify-between">
                                        <div class="heading-font ">Debit</div>
                                        <div
                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                                            {{ debitRecord.account.type }}
                                        </div>
                                    </div>
                                    <div class="text-xs mb-2 text-gray-500"> {{ debitRecord.account.name }}</div>

                                    <div class="record rounded flex justify-between items-center ">
                                        <div class="flex items-center">

                                            <div class="text-xs ">Opening Balance</div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            MK{{ numberWithCommas(debitRecord.openingBalance.toFixed(2)) }}
                                        </div>
                                    </div>
                                    <div class="record rounded flex justify-between items-center ">
                                        <div class="flex items-center">

                                            <div class="text-xs ">Closing Balance</div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            MK{{ numberWithCommas(debitRecord.closingBalance.toFixed(2)) }}
                                        </div>
                                    </div>
                                </div>
                                <div @click="navigateToAccount(creditRecord)"
                                    class="card mb-0 cursor-pointer hover:shadow-xl transition ease-in-out duration-20"
                                    v-if="creditRecord != null">
                                    <div class="flex justify-between">
                                        <div class="heading-font ">Credit</div>
                                        <div
                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                                            {{ creditRecord.account.type }}
                                        </div>
                                    </div>
                                    <div class="text-xs mb-2 text-gray-500"> {{ creditRecord.account.name }}</div>

                                    <div class="record rounded flex justify-between items-center ">
                                        <div class="flex items-center">

                                            <div class="text-xs ">Opening Balance</div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            MK{{ numberWithCommas(creditRecord.openingBalance.toFixed(2)) }}
                                        </div>
                                    </div>
                                    <div class="record rounded flex justify-between items-center ">
                                        <div class="flex items-center">

                                            <div class="text-xs ">Closing Balance</div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            MK{{ numberWithCommas(creditRecord.closingBalance.toFixed(2)) }}
                                        </div>
                                    </div>
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
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import SaleStatus from "@/Components/SaleStatus.vue";
import Pagination from "@/Components/Pagination.vue";
import Transaction from "@/Components/Transaction.vue";
import { Money } from "v-money";

export default {
    props: ['record',],
    components: {
        Money,
        Pagination,
        SaleStatus,
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
        Transaction,
    },
    data() {
        return {
            editDialog: false,
            form: this.$inertia.form({
                description: this.record.data.description,
                productionCode: this.record.data.productionCode,
                receiptCode: this.record.data.receiptCode,
                requistionCode: this.record.data.requisitionCode,
                collectionCode: this.record.data.collectionCode,
            }),
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

    computed: {
        debitRecord() {
            return this.record.data.type == 'DEBIT' ? this.record.data : this.record.data.alternateRecord
        },
        creditRecord() {
            return this.record.data.type == 'CREDIT' ? this.record.data : this.record.data.alternateRecord
        },

    },
    methods: {
        editTransaction() {
            this.form
                .transform(data => ({
                    ...data,
                    production_code: this.record.data.productionCode,
                    receipt_code: this.record.data.receiptCode,
                    // requistion_code: this.record.data.requisitionCode,
                    collection_code: this.record.data.collectionCode,
                }))
                .post(this.route('accounts.transaction.edit', { 'serial': this.record.data.serial}), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.editDialog = false
                    },
                })
        },
        navigateToAccount(record) {
            this.$inertia.get(this.route('accounts.show', { 'code': record.account.code }))
        }


    }
}
</script>
