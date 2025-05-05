<template>


  <tr @click="showDialog = true" class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
    <td class="p-2 text-left ">{{ getDate(transaction.date * 1000) }}</td>
    <td class="p-2 text-left ">{{ transaction.reference }}</td>
    <td class="p-2 text-left ">{{ transaction.fromTo }}</td>
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


    <dialog-modal :show="showDialog" @close="showDialog = false">

      <template #title>
        <div class="flex justify-between">
          Transaction Summary
        </div>

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

            <div class="flex items-center mb-2">
              <label for="backdate" class="text-sm font-medium text-gray-900 dark:text-gray-300">Date</label>
            </div>
            <vue-date-time-picker color="#1a56db" v-model="date" :max-date="maxDate" />
          </div>


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
          <div class="mb-2 md:col-span-2">
            <jet-label for="personCollectingAdvance" value="Total" />
            <money
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
              v-bind="moneyMaskOptions" v-model="form.total" />

          </div>
          <div class="mb-2">
            <jet-label for="receipt_code" value="Receipt Code" />
            <jet-input id="receipt_code" type="text" class="block w-full" v-model="form.receiptCode"
              autocomplete="receipt_code" />
          </div>
          <div class="mb-2">
            <jet-label for="expense_code" value="Expense Code" />
            <jet-input id="expense_code" type="text" class="block w-full" v-model="form.expenseCode"
              autocomplete="expense_code" />
          </div>

        </div>

        <div class="mb-4  text-red-500 text-sm">
          <span  @click="openDeleteDialog" class="cursor">
            <i class="mdi mdi-close-circle text-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 "></i>
            Delete Transaction
          </span>
         
        </div>


      </template>



      <template #footer>
        <!--                <danger-button @click.native="showDialog=false">-->
        <!--                    Cancel-->
        <!--                </danger-button>-->
        <secondary-button @click.native="showDialog = false">
          close
        </secondary-button>
        <primary-button @click.native="update">
          <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
              fill="#E5E7EB" />
            <path
              d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
              fill="currentColor" />
          </svg>
          Update
        </primary-button>
      </template>
    </dialog-modal>

    <dialog-modal :show="deleteDialog" @close="deleteDialog = false">
      <template #title>
        Delete Transaction
      </template>

      <template #content>
        Are you sure you want to delete this transaction?
      </template>

      <template #footer>
        <secondary-button @click.native="deleteDialog = false">
          Cancel
        </secondary-button>

        <danger-button class="ml-2" @click.native="deleteTransaction">
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
        </danger-button>
      </template>
    </dialog-modal>
  </tr>



</template>

<script>
import DialogModal from "@/Jetstream/DialogModal.vue";
import PrimaryButton from "@/Jetstream/Button.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetInput from "@/Jetstream/Input.vue";
import DangerButton from "@/Jetstream/DangerButton.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors';
import { Money } from "v-money";

export default {
  name: "Transaction",
  props: ['transaction'],
  components: {
    JetLabel,
    DangerButton,
    JetInput,
    SecondaryButton, PrimaryButton, DialogModal,
    JetValidationErrors,
    Money,
  },

  data() {
    return {
      deleteDialog: false,
      showDialog: false,
      error: "",
      // date: new Date(this.transaction.date*1000).toISOString().substr(0, 10),
      date: null,
      form: this.$inertia.form({
        type: this.transaction.type,
        reference: this.transaction.reference,
        fromTo: this.transaction.fromTo,
        description: this.transaction.description,
        total: this.transaction.total,
        expenseCode: this.transaction.expense != null ? this.transaction.expense.code : null,
        receiptCode: this.transaction.receipt != null ? this.transaction.receipt.code : null,
      }),
      moneyMaskOptions: {
        decimal: '.',
        thousands: ',',
        prefix: 'MK ',
        suffix: '',
        precision: 2,
        masked: false
      },
      maxDate: new Date().toISOString().substr(0, 10),

    }
  },

  computed: {
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

  },

  methods: {
    openDeleteDialog() {
      this.showDialog = false
      this.deleteDialog = true
    },
    update() {
      this.form
        .transform(data => ({
          ...data,
          date: this.getTimestampFromDate(this.date),
          from_to: this.form.fromTo,
          expense_code: this.form.expenseCode,
          receipt_code: this.form.receiptCode,
        }))
        .post(this.route('transactions.update', { 'id': this.transaction.id }), {
          // preserveScroll: true,
          onSuccess: () => {
            this.showDialog = false
          },
        })
    },
    deleteTransaction() {
      this.form
        .transform(data => ({
          ...data,

        }))
        .post(this.route('transactions.delete', { 'id': this.transaction.id }), {
          // preserveScroll: true,
          onSuccess: () => {
            this.deleteDialog = false
          },
        })
    },

  }
}
</script>

<style scoped></style>
