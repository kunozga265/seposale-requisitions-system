<template>
    <app-layout>
        <template #header>
            Quotation
        </template>

        <template #breadcrumbs>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a :href="route('quotations.index')" class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Quotations
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        #{{ quotation.data.code }}
                    </span>
                </div>
            </li>
        </template>

       <template #actions >
           <a :href="route('quotations.print',{'id':quotation.data.id})" target="_blank">
               <primary-button>Print</primary-button>
           </a>
       </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1">
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Customer Details
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div class="card p-0">
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Quotation Number</div>
                                    <div>{{quotation.data.code}}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Name</div>
                                    <div>{{quotation.data.name}}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Phone Number</div>
                                    <div>{{quotation.data.phoneNumber}}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Email</div>
                                    <div>{{quotation.data.email}}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Address</div>
                                    <div>{{quotation.data.address}}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Location</div>
                                    <div>{{quotation.data.location}}</div>
                                </div>
                            </div>
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
                                                Details
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Units
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Quantity
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Unit Cost
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Total Cost
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr
                                            class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                            v-for="(info,index) in quotation.data.information"
                                            :key="index"
                                        >
                                            <th scope="row" class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{info.details}}
                                            </th>
                                            <td class="py-2 pr-1">
                                                {{info.units}}
                                            </td>
                                            <td class="py-2 pr-1">
                                                {{numberWithCommas(info.quantity)}}
                                            </td>
                                            <td class="py-2 pr-1">
                                                {{numberWithCommas(info.unitCost)}}
                                            </td>
                                            <td class="py-2 pr-1">
                                                {{numberWithCommas(info.totalCost)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <th class="pt-4 pr-1 text-base heading-font font-bold">Total</th>
                                            <td class="pt-4 pr-1 text-base font-bold">{{numberWithCommas(quotation.data.total)}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="quotation.data.quotes" class="page-section md:col-span-2">
                        <div v-show="quotation.data.quotes.length > 0" class="page-section-header">
                            <div class="page-section-title">
                                Attached Quotes
                            </div>
                        </div>
                        <div v-show="quotation.data.quotes.length > 0" class="page-section-content">
                            <div class="card">

                                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
                                    <div
                                        v-for="(quote,index) in this.quotes"
                                        :key="index"
                                    >
                                        <div v-if="quote.ext === 'pdf'" @click="displayAttachment(index,'quote')" class="cursor" >
                                            <pdf  class="w-32" :source="fileUrl(quote.file)"/>
                                        </div>
                                        <div v-else @click="displayAttachment(index,'quote')" class="cursor" >
                                            <img class="w-32" :src="fileUrl(quote.file)" alt="Quote Image">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="page-section">
                            <div class="page-section-content">
                                <div class="card p-0">
                                    <div class="p-3 text-white text-sm font-semibold bg-system heading-font uppercase rounded-t-lg">
                                        Generated By
                                    </div>
                                    <div class="border-b px-3 py-2 flex justify-between text-sm">
                                        <div class="font-semibold">Name</div>
                                        <div>{{quotation.data.requestedBy.firstName}} {{quotation.data.requestedBy.middleName}} {{quotation.data.requestedBy.lastName}}</div>
                                    </div>
                                    <div class="border-b px-3 py-2 flex justify-between text-sm">
                                        <div class="font-semibold">Position</div>
                                        <div>{{quotation.data.requestedBy.position.title}}</div>
                                    </div>
                                    <div class="px-3 py-2 flex justify-between text-sm">
                                        <div class="font-semibold">Date</div>
                                        <div>{{getDate(quotation.data.date*1000,true)}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <dialog-modal v-if="attachment" :show="attachmentDialog" @close="attachmentDialog=false">

                        <template #content>
                            <div v-if="attachment.ext === 'pdf'">
                                <pdf  class="w-full" :source="fileUrl(attachment.file)"/>
                            </div>
                            <div v-else>
                                <img class="w-full" :src="fileUrl(attachment.file)" alt="Quote Image">
                            </div>
                        </template>

                        <template #footer>
                            <secondary-button @click.native="attachmentDialog=false">
                                close
                            </secondary-button>
                            <a :href="fileUrl(attachment.file)" target="_blank">
                                <primary-button @click.native="attachmentDialog=false">
                                    Print
                                </primary-button>
                            </a>
                        </template>
                    </dialog-modal>

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

    export default {
        props:['quotation'],
        components: {
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


          }
        },
        created(){

        },
        computed:{
            attachment(){
                if(this.attachmentIndex !== null) {
                    if (this.attachmentType === 'quote')
                        return this.quotes[this.attachmentIndex]
                    else if (this.attachmentType === 'receipt')
                        return this.receipts[this.attachmentIndex]

                }

                return null
            },
            quotes(){
                let quotes=[]
                let split=null

                for(let x in this.quotation.data.quotes){
                    split=this.quotation.data.quotes[x].split('.')
                    quotes.push({
                        file:this.quotation.data.quotes[x],
                        ext:split[1]
                    })
                }
                return quotes
            },

        },
        methods:{
            printQuotation(){
                this.$inertia.get(this.route('quotations.print',{'id':this.quotation.data.id}))
            },
            displayAttachment(index,type){
                this.attachmentIndex=index
                this.attachmentType=type
                this.attachmentDialog=true
            },
        }
    }
</script>
