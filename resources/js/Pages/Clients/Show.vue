<template>
    <app-layout>
        <template #header>
            Client
        </template>

        <template #breadcrumbs>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a :href="route('clients.index')" class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Clients
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ client.data.name }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions >

<!--            <primary-button  v-if="!client.data.hasSale" @click.native="generateSale">Generate Sale</primary-button>-->

<!--            <a :href="route('clients.print',{'id':client.data.id})" target="_blank">-->
<!--                <primary-button>Print</primary-button>-->
<!--            </a>-->
<!--            <a :href="route('clients.edit',{'id':client.data.id})">-->
<!--                <primary-button>Edit</primary-button>-->
<!--            </a>-->
<!--            <danger-button @click.native="deleteDialog=true">Delete</danger-button>-->

        </template>

        <dialog-modal :show="deleteDialog" @close="deleteDialog=false">
            <template #title>
                Delete Client
            </template>

            <template #content>
                Are you sure you want to delete this quotation?
                Once you delete, this quotation will no longer be available.
            </template>

            <template #footer>
                <secondary-button @click.native="deleteDialog=false">
                    Cancel
                </secondary-button>

                <danger-button class="ml-2" @click.native="deleteQuotation">
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

                <div class="grid grid-cols-1">
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Client Details
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div class="card p-0">
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Name</div>
                                    <div>{{client.data.name}}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Phone Number</div>
                                    <div>{{client.data.phoneNumber}}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Email</div>
                                    <div>{{client.data.email}}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Address</div>
                                    <div>{{client.data.address}}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Sales
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div v-if="sales.data.length === 0"
                                 class="text-center text-gray-400 md:col-span-2 text-sm">
                                No Sales Found
                            </div>
                            <div v-else>

                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    <inertia-link :href="route('sales.show',{id:sale.id})"
                                                  v-for="(sale, index) in sales.data" :key="index">
                                        <div class="app-card">
                                            <div class="header justify-between items-center border-b">
                                                <div>
                                                    <div>
                          <span
                                  class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                                  getDate(sale.date * 1000)
                              }}</span>
                                                    </div>
                                                    <div class="type">{{ sale.code }}</div>
                                                    <div class="name">{{sale.client.name}}</div>


                                                </div>
                                                <div class="flex items-center ">
                                                    <div class="currency ">MK</div>
                                                    <div class="total">{{ numberWithCommas(sale.total) }}</div>
                                                </div>
                                            </div>
                                            <div>
                                                <sale-status :sale="sale"/>
                                            </div>
                                        </div>
                                    </inertia-link>
                                </div>

                                <pagination :object="sales"/>
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

export default {
    props:['client','sales'],
    components: {
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
    },
    data(){
        return{
            loading:false,
            attachmentDialog:false,
            attachmentIndex:null,
            attachmentType:'',
            denyDialog:false,
            deleteDialog: false,
            form: this.$inertia.form({

            }),


        }
    },
    created(){

    },
    computed:{

    },
    methods:{
        printQuotation(){
            this.$inertia.get(this.route('quotations.print',{'id':this.quotation.data.id}))
        },
        // deleteQuotation() {
        //     this.form
        //         .post(this.route('quotations.delete', {'id': this.quotation.data.id}), {
        //             preserveScroll: true,
        //             onSuccess: () => this.deleteDialog = false,
        //         })
        // },

    }
}
</script>
