<template>

    <div class="page-section">
        <div class="page-section-header">
            <div class="page-section-title">
                Transfers
            </div>
        </div>
        <div class="page-section-content">

            <div class="card">
                <div class="p-2 mb-2 relative ">

                    <div class="p-2 relative overflow-x-auto">
                        <table class="overflow-auto w-full default-table text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                                <tr>
                                    <th scope="col" class="p-2 pb-0 heading-font text-right">
                                    </th>
                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Recorded at
                                    </th>
                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Date</th>
                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Credit Account</th>
                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Debit Account
                                    </th>
                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Recipient</th>
                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Description
                                    </th>
                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Reference</th>

                                    <th scope="col" class="p-2 pb-0 heading-font text-left">Amount</th>

                                </tr>

                            </thead>
                            <tbody class="pt-8">
                                <tr :id="record.serial" v-for="(record, index) in records" :key="index"
                                    :class="{ ' line-through': record.trashed }"
                                    class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                    <td style="max-width: 50px;width: 50px;min-width: 20px;" scope="row" class="px-2">
                                        <div v-if="!record.trashed">

                                            <div v-if="form.processing">
                                                <svg v-show="record.serial == serial" role="status"
                                                    class="inline w-4 h-4 mr-3 text-white animate-spin"
                                                    viewBox="0 0 100 101" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                        fill="#E5E7EB" />
                                                    <path
                                                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                        fill="currentColor" />
                                                </svg>

                                            </div>
                                            <i v-else @click="reverseRecord(record.serial)"
                                                class="mdi mdi-close-circle text-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 cursor"></i>
                                        </div>
                                        <!-- <div v-else></div> -->

                                    </td>
                                    <td class="p-2 text-left ">
                                        {{ getDate(record.createdDate * 1000) }}
                                    </td>
                                    <td class="p-2 text-left ">
                                        {{ getDate(record.date * 1000) }}
                                    </td>
                                    <td @click="navigateToAccount(record.account.code)" class="p-2 text-left ">
                                        {{ record.account.name }}</td>
                                    <td @click="navigateToAccount(record.alternateRecord.account.code)"
                                        class="p-2 text-left ">
                                        {{ record.alternateRecord.account.name }}</td>
                                    <td class="p-2 text-left ">{{ record.name }}</td>
                                    <td class="p-2 text-left ">{{ record.description }}</td>
                                    <td class="p-2 text-left ">{{ record.reference }}</td>

                                    <td class="p-2 text-left ">{{ numberWithCommas(record.amount) }}</td>


                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import DoughnutChart from "@/Components/Charts/DoughnutChart";
import BarChart from "@/Components/Charts/BarChart";
import PieChart from "@/Components/Charts/PieChart";
import JetButton from "@/Jetstream/Button";
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import SecondaryButton from '@/Jetstream/SecondaryButton'
import pdf from 'vue-pdf-embed/dist/vue2-pdf-embed'

export default {
    props: ['records'],
    components: {
        AppLayout,
        DoughnutChart,
        BarChart,
        JetInput,
        PieChart,
        JetLabel,
        JetButton,
        JetValidationErrors,
        SecondaryButton,
        pdf,
    },
    data() {
        return {
            serial: null,
            form: this.$inertia.form({})
        }
    },
    created() {


    },
    computed: {
        filteredRecords() {
            let filtered = this.records.data





            return filtered
        },

    },
    methods: {
        navigateToTransaction(serial) {
            this.$inertia.get(this.route('accounts.transaction', { 'serial': serial }))
        },
        navigateToAccount(code) {
            this.$inertia.get(this.route('accounts.show', { 'code': code }))
        },
        reverseRecord(serial) {
            this.serial = serial
            this.form
                .transform(data => ({
                    ...data,
                    'serial': serial,
                }))
                .delete(this.route('accounts.reverse-record'), {
                    onFinish: () => this.serial = null,
                })
        },

    }
}
</script>
