<template>
    <app-layout>
        <template #header>
            {{ statement.data.type == "balance-sheet" ? "Balance Sheet " : "Income Statement " }}[{{ statement.data.name
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
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        Reports
                    </span>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <a :href="route('reports.statements')"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Statements
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ statement.data.serial }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>

            <!-- <inertia-link v-if="inventory.data.producible" :href="route('production.index', { code: site.code })">
                <primary-button>
                    Production
                </primary-button>
            </inertia-link>

            <secondary-button @click.native="editInventoryDialog = true">
                Edit
            </secondary-button> -->

        </template>


        <div v-if="statement.data.type == 'balance-sheet'" class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <statement-line :account-group="currentAssets" />
                <statement-line :account-group="inventory" />
                <div v-if="totalAssets != null" class="mb-8 warning-card"
                    >
                    <table class="w-full">
                        <tr>
                            <td class="ml-6 text-sm font-bold ">
                                Total Assets
                            </td>
                            <td class="py-1 text-sm text-right font-bold ">
                                MK{{ numberWithCommas(totalAssets.toFixed(2)) }}
                            </td>
                        </tr>
                    </table>
                </div>
                <statement-line :account-group="equipment" />
                <statement-line :account-group="intangibleAssets" />
                <statement-line :account-group="currentLiabilities" />
                <statement-line :account-group="longTermLiabilities" />
                <statement-line :account-group="equity" />
                <div v-if="totalLiabilitiesAndEquity != null" class="mb-8 warning-card"
                    >
                    <table class="w-full">
                        <tr>
                            <td class="ml-6 text-sm font-bold ">
                                Total Liabilities and Equity
                            </td>
                            <td class="py-1 text-sm text-right font-bold ">
                                MK{{ numberWithCommas(totalLiabilitiesAndEquity.toFixed(2)) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>



        <div v-if="statement.data.type == 'income-statement'" class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <statement-line :account-group="income" />
                <statement-line :account-group="cogs" />
                <div v-if="grossProfit != null" class="mb-8"
                    :class="{ 'success-card': grossProfit >= 0, 'error-card': grossProfit < 0 }">
                    <table class="w-full">
                        <tr>
                            <td class="ml-6 text-sm font-bold ">
                                Gross Profit
                            </td>
                            <td class="py-1 text-sm text-right font-bold ">
                                MK{{ numberWithCommas(grossProfit.toFixed(2)) }}
                            </td>
                        </tr>
                    </table>
                </div>
                <statement-line :account-group="marketingExpenses" />
                <statement-line :account-group="administrativeExpenses" />
                <statement-line :account-group="otherIncome" />
                <statement-line :account-group="otherExpenses" />
                <div v-if="netProfit != null" class="mb-8"
                    :class="{ 'success-card': netProfit >= 0, 'error-card': netProfit < 0 }">
                    <table class="w-full">
                        <tr>
                            <td class="ml-6 text-sm font-bold ">
                                Net Profit
                            </td>
                            <td class="py-1 text-sm text-right font-bold ">
                                MK{{ numberWithCommas(netProfit.toFixed(2)) }}
                            </td>
                        </tr>
                    </table>
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
import StatementLine from "@/Components/StatementLine.vue";
import VueApexCharts from "vue-apexcharts";


export default {
    props: [
        'statement',
    ],
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
        Money,
        StatementLine,
        "apexchart": VueApexCharts,
    },
    data() {
        return {

            form: this.$inertia.form({

            }),

        }
    },
    created() {


    },
    computed: {
        //income-statement
        groups() {
            let arr = []
            for (let x in this.statement.data.types) {
                for (let y in this.statement.data.types[x].groups) {
                    arr.push(this.statement.data.types[x].groups[y])
                }
            }

            return arr
        },
        income() {
            return this.groups.find(({ data }) => data.code === 4000);
        },
        cogs() {
            return this.groups.find(({ data }) => data.code === 5000);
        },
        marketingExpenses() {
            return this.groups.find(({ data }) => data.code === 6000);
        },
        administrativeExpenses() {
            return this.groups.find(({ data }) => data.code === 7000);
        },
        otherIncome() {
            return this.groups.find(({ data }) => data.code === 8000);
        },
        otherExpenses() {
            return this.groups.find(({ data }) => data.code === 9000);
        },
        grossProfit() {
            let sum = 0
            sum += this.income != null ? this.income.total : 0
            sum -= this.cogs != null ? this.cogs.total : 0

            if (this.income == null && this.cogs == null) {
                return null
            }

            return sum
        },
        netProfit() {
            if (this.grossProfit == null) {
                return null
            }

            let sum = this.grossProfit
            sum += this.otherIncome != null ? this.otherIncome.total : 0
            sum -= this.marketingExpenses != null ? this.marketingExpenses.total : 0
            sum -= this.administrativeExpenses != null ? this.administrativeExpenses.total : 0
            sum -= this.otherExpenses != null ? this.otherExpenses.total : 0

            return sum
        },
        //balance-sheet
        currentAssets() {
            return this.groups.find(({ data }) => data.code === 1000);
        },
        inventory() {
            return this.groups.find(({ data }) => data.code === 1040);
        },
        equipment() {
            return this.groups.find(({ data }) => data.code === 1500);
        },
        intangibleAssets() {
            return this.groups.find(({ data }) => data.code === 1800);
        },
        currentLiabilities() {
            return this.groups.find(({ data }) => data.code === 2000);
        },
        longTermLiabilities() {
            return this.groups.find(({ data }) => data.code === 2500);
        },
        equity() {
            return this.groups.find(({ data }) => data.code === 3000);
        },
        totalAssets() {
            let sum = 0
            sum += this.currentAssets != null ? this.currentAssets.total : 0
            sum += this.inventory != null ? this.inventory.total : 0
            sum += this.equipment != null ? this.equipment.total : 0
            sum += this.intangibleAssets != null ? this.intangibleAssets.total : 0
            return sum
        },
        totalLiabilitiesAndEquity() {
            let sum = 0
            sum += this.currentLiabilities != null ? this.currentLiabilities.total : 0
            sum += this.longTermLiabilities != null ? this.longTermLiabilities.total : 0
            sum += this.equity != null ? this.equity.total : 0
            return sum
        },


    },
    watch: {},
    methods: {


    }
}
</script>
