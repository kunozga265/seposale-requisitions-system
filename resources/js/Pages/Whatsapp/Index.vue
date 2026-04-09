<template>
    <app-layout>
        <template #header>
            Whatsapp Messages
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
                        Whatsapp Messages
                    </span>
                </div>
            </li>
        </template>

        <template #actions>
             <inertia-link :href="route('whatsapp.templates.index')">
                <primary-button>
                    Templates
                </primary-button>
            </inertia-link>


        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 mb-4">

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                All
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div class="card">
                                <div class="p-2 mb-2 relative ">
                                    <!-- <div class="p-2 pb-4 heading-font text-left relative">
                                        <vue-date-time-picker v-model="form.dates" range />
                                    </div> -->
                                    <div class="p-2 relative overflow-x-auto">
                                        <table
                                            class="overflow-auto w-full default-table text-left text-gray-500 dark:text-gray-400">
                                            <thead
                                                class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                                                <tr>
                                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Date
                                                    </th>
                                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Status</th>
                                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Name
                                                    </th>
                                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Phone Number
                                                    </th>
                                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Type
                                                    </th>

                                                </tr>

                                            </thead>
                                            <tbody class="pt-8">
                                                <Message :message="message" v-for="(message, index) in messages.data"
                                                    :key="index" />

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <pagination :object="messages" />
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
import Message from './Message.vue';
import { Money } from "v-money";

export default {
    props: ['messages'],
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
        Message,
    },
    data() {
        return {

            form: this.$inertia.form({
                dates: "",

            }),



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
        navigateToTransaction(serial) {
            this.$inertia.get(this.route('accounts.transaction', { 'serial': serial }))
        },
        navigateToAccount(code) {
            this.$inertia.get(this.route('accounts.show', { 'code': code }))
        },
    }
}
</script>
