<template>
  <app-layout>
    <template #header>
      Edit Account
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('accounts.index')"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Accounts
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">


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
                <div>{{ account.data.name }}</div>
              </div>
              <div class="border-b px-4 py-3 flex justify-between text-sm">
                <div class="text-gray-600 font-semibold">Code</div>
                <div>{{ account.data.code }}</div>
              </div>
              <div class="border-b px-4 py-3 flex justify-between text-sm">
                <div class="text-gray-600 font-semibold">Group</div>
                <div>{{ account.data.group.type.name }}</div>
              </div>
              <div class="border-b px-4 py-3 flex justify-between text-sm">
                <div class="text-gray-600 font-semibold ">Type</div>
                <div>{{ account.data.type }}</div>
              </div>
              <div class="border-b px-4 py-3 flex justify-between text-sm">
                <div class="text-gray-600 font-semibold">Balance</div>
                <div>MK{{ numberWithCommas(account.data.balance.toFixed(2)) }}</div>
              </div>
            </div>
          </div>
        </div>



        <form @submit.prevent="submit">
          <div class="page-section">

            <div class="page-section-content flex justify-center">

              <div class="card w-full ">

                <jet-validation-errors class="mb-4" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                  <div class="mb-4">
                    <jet-label for="amount" value="Amount" />
                    <money
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                      v-bind="moneyMaskOptions" v-model="amount" />
                  </div>

                  <div class="mb-4">
                    <jet-label for="balance" value="New Balance" />
                    <money
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                      v-bind="moneyMaskOptions" v-model="balance" />
                  </div>

                  <div class="mb-4 md:col-span-2">
                    <jet-label for="description" value="Description" />
                    <jet-input id="type" type="text" class="block w-full" v-model="form.description"
                      autocomplete="seposale-description" placeholder="Description" />

                  </div>
                </div>


                <div class="text-center" v-show="amount != 0">
                  <div>
                    <Profit class="heading-font font-bold text-2xl" :value="amount" />
                  </div>
                  <div class="text-base font-bold"
                    :class="{ 'text-green-500 font-bold': account.data.type == type, 'text-red-500 font-bold': account.data.type != type }">
                    {{ type }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="fixed right-6 bottom-6 md:right-10 md:bottom-10">
            <div v-show="!validation" id="toast-danger"
              class="flex items-center w-full max-w-xs p-4 mb-4 text-red-700 bg-red-100 rounded-lg shadow dark:text-red-400 dark:bg-red-800"
              role="alert">
              <div
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <i class="mdi mdi-alert-circle text-2xl"></i>
              </div>
              <div class="ml-3 text-sm font-normal">{{ error }}</div>
            </div>
          </div>


          <div class="text-center mt-8">
            <div v-show="validation">
              <jet-button class="ml-4 text-center" :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing">
                Update
              </jet-button>
              <div class="text-gray-600 text-sm">Please confirm all details before submission</div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from "@/Jetstream/Button";
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import SecondaryButton from '@/Jetstream/SecondaryButton'
import PrimaryButton from "@/Jetstream/Button.vue";
import { Money } from "v-money";

export default {
  props: ["account",],
  components: {
    Money,
    PrimaryButton,
    AppLayout,
    JetInput,
    JetLabel,
    JetButton,
    JetValidationErrors,
    SecondaryButton,
    Money,
  },
  data() {
    return {
      balance: this.account.data.balance,
      amount: 0,
      form: this.$inertia.form({
        description: "",
      }),
      error: '',
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

    type() {
      if (this.account.data.type === "DEBIT") {
        return this.amount < 0 ? "CREDIT" : "DEBIT"
      } else {
        return this.amount < 0 ? "DEBIT" : "CREDIT"
      }
    },
    validation() {
      if (this.amount === 0) {
        this.error = "Enter new balance"
        return false
      } else
        return true

    },
  },
  watch: {
    balance() {
      this.amount = this.balance - this.account.data.balance
    },
    amount() {
      this.balance = this.account.data.balance + this.amount
    },
  },
  methods: {
    submit() {
      this.form
        .transform(data => ({
          ...data,
          amount: this.amount,
          type: this.type,

        }))
        .post(this.route('accounts.update-balance', { code: this.account.data.code }))
    },
    photoUpload(file) {
      const reader = new FileReader();
      if (file) {
        reader.readAsDataURL(file);
        reader.onload = (e) => {
          axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
            type: "OTHER",
            file: e.target.result
          }).then(res => {
            this.form.photo = res.data.file

          }).catch(function (res) {
            // this.form.errors.push(res.data.message)
          })
        };
      }
    },
  }

}
</script>