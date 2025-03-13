<template>
    <app-layout>
        <template #header>
            Account Details
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
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ account.data.name }}
                    </span>
                </div>
            </li>
        </template>

        <template #actions>
            <!--      <a :href="route('accounts.edit',{'id':account.data.id})">-->
            <!--        <primary-button>Edit</primary-button>-->
            <!--      </a>-->
            <primary-button @click.native="addTransationDialog=true">Add Transaction</primary-button>
            <primary-button @click.native="transferDialog=true">Transfer</primary-button>
        </template>


        <dialog-modal :show="addTransationDialog" @close="addTransationDialog=false">
            <template #title>
                Add Transaction
            </template>

            <template #content>
                <jet-validation-errors class="mb-4"/>

                <div class="mb-4">
                    <div class="text-sm text-gray-500">
                        Please enter following details
                    </div>
                    <div v-if="!transactionValidation" class="flex items-center w-full text-red">
                        <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ error }}</div>
                    </div>
                </div>

                <div class="flex items-center mb-4">
                    <input id="default-radio-1" type="radio" value="CREDIT" v-model="form.type"
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-1"
                           class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Credit</label>

                    <input checked id="default-radio-2" type="radio" value="DEBIT" v-model="form.type"
                           class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-2"
                           class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Debit</label>
                </div>
                <div class="mb-4">
                    <jet-label for="reference" value="Reference"/>
                    <jet-input id="reference" type="text" class="block w-full"
                               v-model="form.reference"
                               autocomplete="bank-reference"/>
                </div>
                <div class="mb-4">
                    <jet-label for="person" :value="form.type === 'CREDIT' ? 'From' : 'To'" />
                    <jet-input id="person" type="text" class="block w-full"
                               v-model="form.fromTo"
                               autocomplete="person"/>
                </div>
                <div class="mb-4">
                    <jet-label for="description" value="Description"/>
                    <jet-input id="description" type="text" class="block w-full"
                               v-model="form.description"
                               autocomplete="description"/>
                </div>
                <div class="mb-4">
                    <jet-label for="personCollectingAdvance" value="Total"/>
                    <money
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        v-bind="moneyMaskOptions" v-model="form.total"/>

                </div>


            </template>

            <template #footer>
                <!--                <danger-button @click.native="showDialog=false">-->
                <!--                    Cancel-->
                <!--                </danger-button>-->
                <secondary-button @click.native="addTransationDialog=false">
                    close
                </secondary-button>
                <primary-button v-show="transactionValidation"
                                @click.native="addTransaction">
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

        <dialog-modal :show="transferDialog" @close="transferDialog=false">
            <template #title>
                Transfer Money
            </template>

            <template #content>
                <jet-validation-errors class="mb-4"/>

                <div class="mb-4">
                    <div class="text-sm text-gray-500">
                        Please enter following details
                    </div>
                    <div v-if="!transferValidation" class="flex items-center w-full text-red">
                        <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ transferError }}</div>
                    </div>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="mb-4">
                        <jet-label for="amount" value="Amount"/>
                        <money
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            v-bind="moneyMaskOptions" v-model="form.amount"/>
                        <div v-if="account != null" class="mt-1 text-xs text-gray-500"
                             :class="{'text-red-500':!balanceValidate}">Balance:
                            MK{{ numberWithCommas(account.data.balance) }}
                        </div>

                    </div>
                    <div class="mb-4">
                        <jet-label for="reference" value="Reference"/>
                        <jet-input id="reference" type="text" class="block w-full"
                                   v-model="form.reference_from"
                                   autocomplete="bank-reference"/>
                    </div>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="mb-4">
                        <jet-label for="paymentMethod" value="Select Account"/>
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
                        <jet-label for="reference" value="Reference"/>
                        <jet-input id="reference" type="text" class="block w-full"
                                   v-model="form.reference_to"
                                   autocomplete="bank-reference"/>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="mb-4">
                        <jet-label for="amount" value="Transfer Fee"/>
                        <money
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            v-bind="moneyMaskOptions" v-model="form.fee"/>

                    </div>
                    <div class="mb-4">
                        <jet-label for="reference" value="Reference"/>
                        <jet-input id="reference" type="text" class="block w-full"
                                   v-model="form.reference_fee"
                                   autocomplete="bank-reference"/>
                    </div>
                </div>


            </template>

            <template #footer>
                <!--                <danger-button @click.native="showDialog=false">-->
                <!--                    Cancel-->
                <!--                </danger-button>-->
                <secondary-button @click.native="transferDialog=false">
                    close
                </secondary-button>
                <primary-button v-show="transferValidation"
                                @click.native="transferMoney">
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


        <dialog-modal :show="deleteDialog" @close="deleteDialog=false">
            <template #title>
                Delete Account
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
                                Account Details
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div class="card p-0">
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Name</div>
                                    <div>{{ account.data.name }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Account Name</div>
                                    <div>{{ account.data.number }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Branch</div>
                                    <div>{{ account.data.branch }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Type</div>
                                    <div>{{ account.data.type }}</div>
                                </div>
                                <div class="border-b px-4 py-3 flex justify-between text-sm">
                                    <div class="text-gray-600 font-semibold">Balance</div>
                                    <div>MK{{ numberWithCommas(account.data.balance) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Transactions
                            </div>
                        </div>
                        <div class="page-section-content">

                            <div class="card">
                                <div class="p-2 mb-2 relative ">
                                    <div class="p-2 pb-4 heading-font text-left relative">
                                        <vue-date-time-picker
                                            v-model="form.dates"
                                            range
                                        />
                                    </div>
                                    <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                                        <tr>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Reference</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">From/To</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Description</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Credit</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-left">Debit</th>
                                            <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>
                                        </tr>

                                        </thead>
                                        <tbody class="pt-8">

                                        <tr
                                            class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                            v-for="(transaction,index) in filteredTransactions" :key="index">
                                            <td class="p-2 text-left ">{{ getDate(transaction.date * 1000) }}</td>
                                            <td class="p-2 text-left ">{{ transaction.reference }}</td>
                                            <td class="p-2 text-left ">{{ transaction.from_to }}</td>
                                            <td class="p-2 text-left ">{{ transaction.description }}</td>
                                            <td class="p-2 text-left ">{{
                                                    transaction.type === "CREDIT" ? numberWithCommas(transaction.total) : ''
                                                }}
                                            </td>
                                            <td class="p-2 text-left ">{{
                                                    transaction.type === "DEBIT" ? numberWithCommas(transaction.total) : ''
                                                }}
                                            </td>
                                            <td class="p-2 text-right ">{{ numberWithCommas(transaction.balance) }}</td>
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
import {Money} from "v-money";

export default {
    props: ['account', 'sales', 'accounts'],
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
    created() {

    },
    computed: {
        selectedAccount() {
            if (parseInt(this.accountIndex) >= 0) {
                return this.accounts[this.accountIndex]
            } else
                return null
        },
        balanceValidate() {
            if (this.account != null) {
                return this.account.data.balance >= this.form.amount
            } else {
                return false
            }
        },
        filteredTransactions() {
            let filtered = this.account.data.transactions

            /* Filter Sales By Date */
            if (this.form.dates != null) {
                if (this.form.dates.start != null) {
                    filtered = (filtered).filter((transaction) => {
                        return transaction.date >= this.getTimestampFromDate(this.form.dates.start)
                    })
                }
                if (this.form.dates.end != null) {
                    filtered = (filtered).filter((transaction) => {
                        return transaction.date <= this.getTimestampFromDate(this.form.dates.end)
                    })
                }
            }
            return filtered
        },
        transactionValidation() {

            if (this.form.type.length == 0 || this.form.type == "") {
                this.error = "Select transaction type"
                return false
            } else if (this.form.reference.length == 0 || this.form.reference == "") {
                this.error = "Enter reference"
                return false
            } else if (this.form.fromTo.length == 0 || this.form.fromTo == "") {
                this.error = "Enter source (client, payee etc)"
                return false
            } else if (this.form.description.length == 0 || this.form.description == "") {
                this.error = "Enter description"
                return false
            } else if (this.form.total == 0) {
                this.error = "Enter total"
                return false
            }

            return true
        },
        transferValidation() {

         if (this.form.amount == 0) {
                this.transferError = "Enter amount"
                return false
            }else if (this.form.reference_from.length == 0 || this.form.reference_from == "") {
                this.transferError = "Enter source reference"
                return false
            } else if (this.selectedAccount == null) {
                this.transferError = "Select an account"
                return false
            }else if (this.form.reference_to.length == 0 || this.form.reference_to == "") {
                this.transferError = "Enter destination reference"
                return false
            }

            return true
        }
    },
    methods: {

        addTransaction() {
            this.form
                .transform(data => ({
                    ...data,
                    from_to: this.form.fromTo,
                    account_id: this.account.data.id,
                }))
                .post(this.route('transactions.store',), {
                    // preserveScroll: true,
                    onSuccess: () => {
                        this.addTransationDialog = false
                        this.form.reset()
                    },
                })
        },
        transferMoney() {
            this.form
                .transform(data => ({
                    ...data,
                    account_id: this.selectedAccount.id,
                }))
                .post(this.route('accounts.transfer', {id: this.account.data.id}), {
                    // preserveScroll: true,
                    onSuccess: () => {
                        this.transferDialog = false
                        this.form.reset()
                    },
                })
        },
    }
}
</script>
