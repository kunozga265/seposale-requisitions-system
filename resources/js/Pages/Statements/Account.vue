<template>
    <app-layout>
        <template #header>
             {{ statement.type == "balance-sheet" ? "Balance Sheet " : "Income Statement " }}[{{ statement.name
            }}]
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
                    <a :href="route('statements.index')"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Statements
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                       <a :href="route('statements.show',{serial:statement.serial})"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        {{ statement.serial }}
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ account.name }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>

        </template>




        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1">
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Account Details
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div class="card p-0">
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Account Name</div>
                                    <div>{{ account.name }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Code</div>
                                    <div>{{ account.code }}</div>
                                </div>
                                <!-- <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Group</div>
                                    <div>{{ account.group.type.name }}</div>
                                </div> -->
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold ">Type</div>
                                    <div>{{ account.type }}</div>
                                </div>
                                <!-- <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Balance</div>
                                    <div>MK{{ numberWithCommas(account.balance.toFixed(2)) }}</div>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Records
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div class="card">
                                <div class="p-2 mb-2 relative ">
                                    <div class="p-2 pb-4 heading-font text-left relative">
                                        <vue-date-time-picker v-model="form.dates" range />
                                    </div>
                                    <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                                            <tr>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Reference</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Name</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Description</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Debit</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-left">Credit</th>
                                                <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>
                                            </tr>

                                        </thead>
                                        <tbody class="pt-8">
                                            <tr @click="navigateToTransaction(record.serial)" :id="record.serial"  v-for="(record, index) in filteredRecords.data" :key="index"
                                                class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                                <td class="p-2 text-left ">
                                                    {{ getDate(record.date * 1000) }}
                                                </td>
                                                <td class="p-2 text-left ">{{ record.reference }}</td>
                                                <td class="p-2 text-left ">{{ record.name }}</td>
                                                <td class="p-2 text-left ">{{ record.description }}</td>
                                                <td class="p-2 text-left " :class="{'text-green-500 font-bold': account.type == 'DEBIT', 'text-red-500 font-bold': account.type != 'DEBIT'}">{{
                                                    record.type === "DEBIT" ? numberWithCommas(record.amount.toFixed(2))
                                                    : ''
                                                    }}
                                                </td>
                                                <td class="p-2 text-left " :class="{'text-green-500 font-bold': account.type == 'CREDIT', 'text-red-500 font-bold': account.type != 'CREDIT'}">{{
                                                    record.type === "CREDIT" ? numberWithCommas(record.amount.toFixed(2)) :
                                                    ''
                                                    }}
                                                </td>
                                                <td class="p-2 text-right ">{{ numberWithCommas(record.closingBalance.toFixed(2)) }}
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
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import SaleStatus from "@/Components/SaleStatus.vue";
import Pagination from "@/Components/Pagination.vue";
import Transaction from "@/Components/Transaction.vue";
import { Money } from "v-money";

export default {
    props: ['statement', 'account', 'records', ],
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
            loading: false,
            attachmentDialog: false,
            attachmentIndex: null,
            attachmentType: '',
            addTransationDialog: false,
            transferDialog: false,
            accountIndex: -1,
            error: "",
            transferError: "",
            denyDialog: false,
            deleteDialog: false,
            form: this.$inertia.form({
                dates: "",
                type: "",
                reference: "",
                reference_to: "",
                reference_from: "",
                reference_fee: "",
                fromTo: "",
                description: "",
                total: 0,
                amount: 0,
                fee: 0,
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
    mounted() {
        // document.getElementById("25SDI2WA31NEFYUX63BO").scrollIntoView();
    },
    computed: {
       
        filteredRecords() {
            let filtered = this.records.data

            /* Filter Sales By Date */
            if (this.form.dates != null) {
                if (this.form.dates.start != null) {
                    filtered = (filtered).filter((record) => {
                        return record.date >= this.getTimestampFromDate(this.form.dates.start)
                    })
                }
                if (this.form.dates.end != null) {
                    filtered = (filtered).filter((record) => {
                        return record.date <= this.getTimestampFromDate(this.form.dates.end)
                    })
                }
            }

            const credit = (filtered).filter((record) => {
                return record.type == "CREDIT"
            })
            const debit = (filtered).filter((record) => {
                return record.type == "DEBIT"
            })



            return {
                "credit": credit,
                "debit": debit,
                "data": filtered
            }
        },
      
     
    },
    methods: {
        
        navigateToTransaction(serial){
            this.$inertia.get(this.route('accounts.transaction', { 'serial': serial }))
        }
    }
}
</script>
