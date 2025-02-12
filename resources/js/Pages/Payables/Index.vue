<template>
    <app-layout>
        <template #header>
            Payables
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
                        class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Payables</span>
                </div>
            </li>
        </template>

        <template #actions>
            <inertia-link :href="route('clients.create')">
                <primary-button>
                    New Payable
                </primary-button>
            </inertia-link>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Suppliers
                        </div>
                    </div>
                    <div class="page-section-content">
                        <div class="">

                            <payable
                                v-for="(payable,index) in suppliers"
                                :key="index"
                                :payable="payable"
                            />
                        </div>

                    </div>
                </div>

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Transporters
                        </div>
                    </div>
                    <div class="page-section-content">
                        <div class="">

                            <payable
                                v-for="(payable,index) in transporters"
                                :key="index"
                                :payable="payable"
                            />
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
import RequestStatus from "@/Components/RequestStatus.vue";
import Pagination from "@/Components/Pagination.vue";
import SaleStatus from "@/Components/SaleStatus.vue";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import JetInput from "@/Jetstream/Input.vue";
import Payable from "@/Components/Payable.vue";

export default {
    props: [
        'transporters',
        'suppliers',
    ],
    components: {
        Payable,
        JetInput, DeliveryStatus, SaleStatus,
        Pagination,
        RequestStatus,
        AppLayout,
        PrimaryButton
    },
    data() {
        return {
            form: this.$inertia.form({
                name: ""
            }),
            transportersList: [],
            suppliersList: []
        }
    },
    computed: {},
    methods: {
        getTotal(arr) {
            let sum = 0;
            for (let x in arr) {
                sum += arr[x].total
            }
            return this.numberWithCommas(sum)
        }

    }
}
</script>
