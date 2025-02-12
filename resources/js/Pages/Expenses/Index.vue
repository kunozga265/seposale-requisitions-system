<template>
    <app-layout>
        <template #header>
            Expenses
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
                    <span
                        class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Expenses</span>
                </div>
            </li>
        </template>

        <template #actions>
<!--            <inertia-link :href="route('expenses.create')">-->
<!--                <primary-button>-->
<!--                    New Expense-->
<!--                </primary-button>-->
<!--            </inertia-link>-->
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Overview
                        </div>
                    </div>
                    <div class="page-section-content">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-4">
                            <div class="card mb-0 md:col-span-2 lg:col-span-3">
                                <div class="flex justify-between mb-4">
                                    <div class="heading-font mb-4">Expenses</div>
                                    <div>
                                        <vue-date-time-picker
                                            v-model="form.dates"
                                            range
                                        />
                                    </div>
                                </div>
                                <div class="text-xs mb-1 text-gray-500">Total Expenses</div>
                                <div class="heading-font font-bold text-xl mb-4">
                                    MK{{ numberWithCommas(expensesTotal) }}
                                </div>

                                <div>
                                    <div id="chart">
                                        <vue-apex-charts type="area" :options="chartOptionsApex"
                                                   height="180"
                                                   :series="expensesData"></vue-apex-charts>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-0 profit-loss">
                                <div class="heading-font mb-4">Categories</div>

                                <div>
                                    <PieChart
                                        :chart-options="categoriesChartOptions"
                                        :chart-data="categoriesData"
                                        chart-id="categories"
                                        dataset-id-key="categories"
                                        :height="200"
                                    />
                                </div>
                            </div>

                            <div class="card mb-0 md:col-span-2">
                                <div class="heading-font mb-4">Breakdown</div>

                                <div v-for="(category,index) in listOfCategories" :index="index"
                                     class="record px-2 mb-1 rounded flex justify-between items-center ">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 bg-gray-100 rounded-full flex justify-center items-center">
                                            <div class="text-xs text-gray-500">{{ index + 1 }}</div>
                                        </div>
                                        <div class="ml-3 text-sm ">
                                            <div class="text-sm">{{ category.name }}</div>
                                        </div>
                                    </div>
                                    <div class="heading-font text-xs text-gray-500">
                                        MK{{ numberWithCommas(category.total) }}
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Browse
                        </div>
                    </div>
                    <div class="page-section-content">

                        <div class="card w-full">
                            <!--                            {{ invoices.data }}-->
                            <div class="p-2 mb-2 relative ">
                                <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Requsition Code</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Payee</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-left">Description</th>
                                        <th scope="col" class="p-2 pb-0 heading-font text-right">Amount</th>
<!--                                        <th scope="col" class="p-2 pb-0 heading-font text-right">Requester</th>-->
                                    </tr>
                                    <tr>
                                        <th scope="col" class="p-2 pb-4 heading-font text-left">
                                            <vue-date-time-picker
                                                v-model="form.dates"
                                                range
                                            />
                                        </th>
                                        <th scope="col" class="p-2 pb-4 heading-font text-left relative">
                                            <button v-show="form.code.length > 0" @click="form.code = ''"
                                                    class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                            <jet-input id="code" type="text" class="block w-full"
                                                       placeholder="Search"
                                                       v-model="form.code"
                                                       autocomplete="seposale-filter-code"/>

                                        </th>
                                        <th scope="col" class="p-2 pb-4 heading-font text-left relative">
                                            <button v-show="form.requisition.length > 0" @click="form.requisition = ''"
                                                    class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                            <jet-input id="code" type="text" class="block w-full"
                                                       placeholder="Search"
                                                       v-model="form.requisition"
                                                       autocomplete="seposale-filter-code"/>

                                        </th>
                                        <th scope="col" class="p-2 pb-4 heading-font text-left relative">
                                            <button v-show="form.payee.length > 0" @click="form.payee = ''"
                                                    class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                            <jet-input id="client" type="text" class="block w-full"
                                                       placeholder="Search"
                                                       v-model="form.payee"
                                                       autocomplete="seposale-filter-client"/>
                                        </th>
                                        <th scope="col" class="p-2 pb-4 heading-font text-left relative">
                                            <button v-show="form.description.length > 0" @click="form.description = ''"
                                                    class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                            <jet-input id="product" type="text" class="block w-full"
                                                       placeholder="Search"
                                                       v-model="form.description"
                                                       autocomplete="seposale-filter-description"/>
                                        </th>
                                        <th scope="col" class="p-2 pb-4 heading-font text-right"></th>
                                        <th scope="col" class="p-2 pb-4 heading-font text-right"></th>
                                    </tr>

                                    </thead>
                                    <tbody class="pt-8">

                                    <tr
                                        v-for="(expense,index) in filteredExpenses" :key="index">
                                        <td class="p-2 text-left">{{ getDate(expense.date * 1000) }}</td>
                                        <td class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                          >{{ expense.code }}
                                        </td>
                                        <td @click="navigateToRequisition(expense.requestForm.id)" class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                          >{{ expense.requestForm.code }}
                                        </td>
                                        <td class="p-2 text-left "
                                            >{{ expense.payee }}
                                        </td>
                                        <td class="p-2 text-left ">{{ expense.description }}</td>
                                        <td class="p-2 text-right">{{
                                                numberWithCommas(expense.total.toFixed(2))
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
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PrimaryButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input.vue";
import PieChart from "@/Components/Charts/PieChart.vue";
import VueApexCharts from "vue-apexcharts";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import SaleStatus from "@/Components/SaleStatus.vue";

export default {
    props: [
        'expenses',
    ],
    components: {
        SaleStatus, DeliveryStatus,
        JetInput,
        AppLayout,
        PrimaryButton,
        PieChart,
        VueApexCharts

    },
    data() {
        return {
            form: this.$inertia.form({
                dates: null,
                code: "",
                requisition: "",
                payee: "",
                description: "",
            }),
            listOfCategories:[],
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
            categoriesChartOptions: {
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: true,
                        position: 'right'
                    }
                },
                cutout: 60,
                responsive: true,
                maintainAspectRatio: false
            },

        }
    },
    computed: {
        filteredExpenses() {

            let filtered = this.expenses.data;


            /* Filter Expenses By Code*/
            if (this.form.code.length !== 0) {
                filtered = (filtered).filter((expense) => {
                    return expense.code.toLowerCase().includes(this.form.code.toLowerCase())
                })
            }

            /* Filter Expenses By Code*/
            if (this.form.requisition.length !== 0) {
                filtered = (filtered).filter((expense) => {
                    return expense.requestForm.code.toLowerCase().includes(this.form.requisition.toLowerCase())
                })
            }

            /* Filter expenses By Client*/
            if (this.form.description.length !== 0) {
                filtered = (filtered).filter((expense) => {
                    return expense.description.toLowerCase().includes(this.form.description.toLowerCase())
                })
            }

            /* Filter expenses By Product Name */
            if (this.form.payee.length !== 0) {
                filtered = (filtered).filter((expense) => {
                    return expense.payee.toLowerCase().includes(this.form.payee.toLowerCase())
                })
            }

            /* Filter expenses By Date */
            if (this.form.dates != null) {
                if (this.form.dates.start != null) {
                    filtered = (filtered).filter((expense) => {
                        return expense.date >= this.getTimestampFromDate(this.form.dates.start)
                    })
                }
                if (this.form.dates.end != null) {
                    filtered = (filtered).filter((expense) => {
                        return expense.date <= this.getTimestampFromDate(this.form.dates.end)
                    })
                }
            }



            return filtered
        },
        expensesData() {
            let sales = [{
                name: 'Expenses',
                data: []
            }];

            for (let x in this.filteredExpenses) {
                sales[0].data.push({
                    x: this.filteredExpenses[x].date * 1000,
                    y: this.filteredExpenses[x].total
                })
            }

            return sales
        },
        expensesTotal() {
            let sum = 0
            for (let x in this.filteredExpenses) {
                sum += this.filteredExpenses[x].total
            }
            return sum
        },
        filteredCategories() {
            return this.filteredExpenses.reduce(function (arr, expense) {
                (arr[expense.expenseType.id] = arr[expense.expenseType.id] || []).push(expense);
                return arr;
            }, {})
        },
        categoriesData() {
            let list = []
            let labels = []
            let data = []
            let sum = 0

            for (let x in this.filteredCategories) {
                sum = 0
                labels.push(this.filteredCategories[x][0].expenseType.name)
                for (let y in this.filteredCategories[x]) {
                    sum += this.filteredCategories[x][y].total
                }
                data.push(sum)
                list.push({
                    "name": this.filteredCategories[x][0].expenseType.name,
                    "total": sum
                })
            }

            this.listOfCategories = list

            return {
                datasets: [{
                    data: data,
                    backgroundColor: ['#4aa4a3', '#492d8a', '#e7632a','#ed0b4b','#62bdf9', '#3375bf', '#e0f96a','#8ef96d'],
                }],
                labels: labels
            }
        },

    },
    methods: {
        navigateToRequisition(id) {
            this.$inertia.get(this.route('request-forms.show', {'id': id}))
        },
    }
}
</script>
