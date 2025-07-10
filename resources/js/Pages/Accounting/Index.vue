<template>
  <app-layout>
    <template #header>
      Accounts
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Accounts</span>
        </div>
      </li>
    </template>

    <template #actions>
      <primary-button @click.native="addTransationDialog = true">Add Transaction</primary-button>
      <!-- <primary-button @click.native="addTransationDialog = true">Transfer</primary-button> -->
      <inertia-link :href="route('settings.accounting-centre')">
        <secondary-button>
          Manage Accounts
        </secondary-button>
      </inertia-link>
    </template>
    <!-- 
    <dialog-modal :show="addTransationDialog" @close="addTransationDialog = false">
      <template #title>
        Add Transaction
      </template>

      <template #content>
        <jet-validation-errors class="mb-4" />

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
          <label for="default-radio-1" class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Credit</label>

          <input checked id="default-radio-2" type="radio" value="DEBIT" v-model="form.type"
            class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="default-radio-2" class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Debit</label>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
          <div class="mb-2">
            <jet-label for="reference" value="Reference" />
            <jet-input id="reference" type="text" class="block w-full" v-model="form.reference"
              autocomplete="bank-reference" />
          </div>
          <div class="mb-2">
            <jet-label for="person" :value="form.type === 'CREDIT' ? 'From' : 'To'" />
            <jet-input id="person" type="text" class="block w-full" v-model="form.fromTo" autocomplete="person" />
          </div>
          <div class="mb-2">
            <jet-label for="description" value="Description" />
            <jet-input id="description" type="text" class="block w-full" v-model="form.description"
              autocomplete="description" />
          </div>
          <div class="mb-2">
            <jet-label for="personCollectingAdvance" value="Total" />
            <money
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
              v-bind="moneyMaskOptions" v-model="form.total" />
          </div>
        </div>


      </template>

      <template #footer>
        <secondary-button @click.native="addTransationDialog = false">
          close
        </secondary-button>
        <primary-button v-show="transactionValidation" @click.native="addTransaction">
          <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
              fill="#E5E7EB" />
            <path
              d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
              fill="currentColor" />
          </svg>
          Submit
        </primary-button>
      </template>
    </dialog-modal> -->

    <dialog-modal :show="addTransationDialog" @close="addTransationDialog = false">
      <template #title>
        Add Transaction
      </template>

      <template #content>

        <div class="flex items-center mb-2">
          <input id="default-radio-1" type="radio" value="DEFAULT" v-model="transactionType"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="default-radio-1" class="ml-1 text-xs font-medium text-gray-900 dark:text-gray-300">
            Default</label>

          <input checked id="default-radio-2" type="radio" value="TRANSFER" v-model="transactionType"
            class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="default-radio-2" class="ml-1 text-xs font-medium text-gray-900 dark:text-gray-300">
            Transfer</label>

          <input checked id="default-radio-2" type="radio" value="BANKCHARGES" v-model="transactionType"
            class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="default-radio-2" class="ml-1 text-xs font-medium text-gray-900 dark:text-gray-300">
            Bank Charges</label>
        </div>

        <div class="mb-4">
          <div class="text-sm text-gray-500">
            Please enter following details
          </div>
          <div v-if="!transactionValidation" class="flex items-center w-full text-red">
            <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ error }}
            </div>
          </div>
          <jet-validation-errors class="mb-4" />

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
          <div class="mb-2">
            <jet-label for="paymentMethod" value="Select Credit Account" />
            <select v-model="form.creditAccountId" id="paymentMethod"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
              required>
              <option v-for="(account, index) in accountsCompound.data" :value="account.id" :key="index">
                {{ account.name }}
              </option>
            </select>
            <div v-if="transferCreditAccount.data != null" class="mt-1 text-xs text-gray-500"
              :class="{ 'text-red-500': !transferCreditAccount.validation }">
              Balance:
              MK{{ numberWithCommas(transferCreditAccount.data.balance) }}
            </div>

          </div>
          <div class="mb-4">
            <div class="flex items-center">
              <jet-label for="reference" value="Reference " />
              <div class="ml-1 mb-1 text-xs text-gray-500">
                (Optional)
              </div>

            </div>
            <jet-input id="reference" type="text" class="block w-full" v-model="form.creditAccountReference"
              autocomplete="bank-reference" />

          </div>


          <div class="mb-2">
            <jet-label for="amount" value="Amount" />
            <money
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
              v-bind="moneyMaskOptions" v-model="form.amount" />
          </div>


          <div class="mb-2">
            <jet-label for="Description" value="Description " />
            <jet-input id="description" type="text" class="block w-full" v-model="form.description"
              autocomplete="description" />
          </div>

          <div class="mb-2">

            <jet-label for="paymentMethod" value="Select Debit Account" />
            <select v-model="form.debitAccountId" id="paymentMethod"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
              :disabled="isBankCharge" required>
              <option v-for="(account, index) in accountsCompound.data" :value="account.id" :key="index">
                {{ account.name }}
              </option>
            </select>
            <div v-if="transferDebitAccount.data != null" class="mt-1 text-xs text-gray-500">
              Balance:
              MK{{ numberWithCommas(transferDebitAccount.data.balance) }}
            </div>

          </div>

          <div class="mb-2">
            <div class="flex items-center">
              <jet-label for="reference" value="Reference " />
              <div class="ml-1 mb-1 text-xs text-gray-500">
                (Optional)
              </div>
            </div>
            <jet-input id="reference" type="text" class="block w-full" v-model="form.debitAccountReference"
              autocomplete="bank-reference" />
          </div>
        </div>





        <!--         
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
          <div class="mb-4">
            <jet-label for="amount" value="Transfer Fee" />
            <money
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
              v-bind="moneyMaskOptions" v-model="form.fee" />

          </div>
          <div class="mb-4">
            <jet-label for="reference" value="Reference" />
            <jet-input id="reference" type="text" class="block w-full" v-model="form.reference_fee"
              autocomplete="bank-reference" />
          </div>
        </div> -->


      </template>

      <template #footer>
        <!--                <danger-button @click.native="showDialog=false">-->
        <!--                    Cancel-->
        <!--                </danger-button>-->
        <secondary-button @click.native="addTransationDialog = false">
          close
        </secondary-button>
        <primary-button v-show="transactionValidation" @click.native="addTransaction">
          <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
              fill="#E5E7EB" />
            <path
              d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
              fill="currentColor" />
          </svg>
          Submit
        </primary-button>
      </template>
    </dialog-modal>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              All
            </div>
          </div>
          <div class="page-section-content">

            <div v-if="accounts.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
              No Accounts Found
            </div>
            <div v-else>
              <div class="card">
                <div class="p-2 mb-2 relative ">
                  <div class="p-2 pb-4 heading-font text-left relative">
                    <button v-show="search.length > 0" @click="search = ''"
                      class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                      <i class="mdi mdi-close"></i>
                    </button>
                    <jet-input id="code" type="text" class="block w-full" placeholder="Search Name..." v-model="search"
                      autocomplete="seposale-filter-code" />

                  </div>
                  <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                    <thead class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                      <tr>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Name</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Financial Statement</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Group</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Normally</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Balance</th>
                      </tr>

                    </thead>
                    <tbody class="pt-8">

                      <tr @click="navigateToAccount(account.code)"
                        class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(account, index) in accountsCompound.filtered" :key="index">

                        <!-- <td class="p-2 text-left flex items-center"><img v-show="account.photo != null" :src="fileUrl(account.photo)" :alt="account.name" class="w-6 rounded-full mr-2"/> {{ account.name }}</td> -->
                        <td class="p-2 text-left ">{{ account.name }}</td>
                        <td class="p-2 text-left ">{{ account.code }}</td>
                        <td class="p-2 text-left ">{{ account.group.type.statement == "balance-sheet" ? "Balance Sheet"
                          :
                          "Income Statement" }}</td>
                        <!-- <td class="p-2 text-left ">{{ account.group.name }}</td> -->
                        <td class="p-2 text-left ">{{ account.group.type.name }}</td>
                        <td class="p-2 text-left ">{{ account.type }}</td>

                        <td class="p-2 text-right ">{{ numberWithCommas(account.balance.toFixed(2)) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

              </div>
              <!--              <pagination :object="accounts"/>-->
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
import SecondaryButton from "@/Jetstream/SecondaryButton";
import RequestStatus from "@/Components/RequestStatus.vue";
import Pagination from "@/Components/Pagination.vue";
import SaleStatus from "@/Components/SaleStatus.vue";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import DialogModal from "@/Jetstream/DialogModal";
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import { Money } from "v-money";


export default {
  props: [
    'accounts',
  ],
  components: {
    JetInput, DeliveryStatus, SaleStatus,
    Pagination,
    RequestStatus,
    AppLayout,
    SecondaryButton,
    PrimaryButton,
    DialogModal,
    JetValidationErrors,
    JetLabel,
    JetInput,
    Money,
  },
  data() {
    return {
      addTransationDialog: false,
      isBankCharge: false,
      transactionType: "DEFAULT",
      error: "",
      search: "",
      form: this.$inertia.form({
        amount: 0,
        debitAccountId: 0,
        debitAccountReference: "",
        creditAccountId: 0,
        creditAccountReference: "",
        description: "",
      }),
      listOfAccounts: [],
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
    accountsCompound() {
      let filtered = this.accounts.data

      /* Filter Sales By Client*/
      if (this.search.length !== 0) {
        filtered = (filtered).filter((account) => {

          return account.name.toLowerCase().includes(this.search.toLowerCase())

        })
      }

      let data = []


      switch (this.transactionType) {
        case "TRANSFER":
          data = this.accounts.data.filter(account => account.special_type === 'WALLET')
          break;
        default:
          data = this.accounts.data
      }

      const other = this.accounts.data.filter(account => account.special_type !== 'WALLET')

      return {
        "data": data,
        "filtered": filtered,
      }
    },

    transferCreditAccount() {
      let acc = null;
      let balanceValidation = false;

      if (parseInt(this.form.creditAccountId) > 0) {
        acc = this.accountsCompound.data.find(({ id }) => id === this.form.creditAccountId);
        balanceValidation = acc.balance >= this.form.amount
      }

      return {
        "data": acc,
        "validation": balanceValidation,
      }
    },
    transferDebitAccount() {
      let acc = null;
      let balanceValidation = false;

      if (parseInt(this.form.debitAccountId) > 0) {
        acc = this.accountsCompound.data.find(({ id }) => id === this.form.debitAccountId);
        balanceValidation = acc.balance >= this.form.amount
      }

      return {
        "data": acc,
        "validation": balanceValidation,
      }
    },

    transactionValidation() {

      if (this.transferCreditAccount.data == null) {
        this.error = "Select an account to credit"
        return false
      } else if (this.form.amount == 0) {
        this.error = "Enter amount"
        return false
      } else if (!this.transferCreditAccount.validation) {
        this.error = "Amount entered is greater than the balance"
        return false
      }
      else if (this.form.description.length === 0 || this.form.description === "") {
        this.error = "Enter a description"
        return false
      } else if (this.transferDebitAccount.data == null) {
        this.error = "Select an account to debit"
        return false
      } else if (this.form.creditAccountId == this.form.debitAccountId) {
        this.error = "You are transferring to the same account"
        return false
      }

      return true
    }

  },
  watch: {
    transactionType() {
      this.form.debitAccountId = 0
      this.isBankCharge = false;

      if (this.transactionType == "BANKCHARGES") {
        const bankChargeAccount = this.accountsCompound.data.find(({ special_type }) => special_type === "BANK-CHARGES");
        this.form.debitAccountId = bankChargeAccount.id
        this.isBankCharge = true;

        if (this.form.description.length == 0 || this.form.description == "Funds Transfer") {
          this.form.description = "Bank Charges"
        }
      } else if (this.transactionType == "TRANSFER") {
        if (this.form.description.length == 0 ||  this.form.description == "Bank Charges") {
          this.form.description = "Funds Transfer"
        }
      }else{
         if (this.form.description == "Funds Transfer" ||  this.form.description == "Bank Charges") {
           this.form.description = ""
        }
      }
    }
  },
  methods: {
    addTransaction() {
      this.form
        .transform(data => ({
          ...data,
          debit_account_id: this.form.debitAccountId,
          debit_account_reference: this.form.debitAccountReference,
          credit_account_id: this.form.creditAccountId,
          credit_account_reference: this.form.creditAccountReference,
        }))
        .post(this.route('accounts.add-transaction'), {
          // preserveScroll: true,
          onSuccess: () => {
            this.addTransationDialog = false
            this.form.reset()
          },
        })
    },
    navigateToAccount(code) {
      this.$inertia.get(this.route('accounts.show', { 'code': code }))
    },
    selectClient(id) {
      (this.listOfAccounts).push(id)
      console.log(id)
    }
  }
}
</script>
