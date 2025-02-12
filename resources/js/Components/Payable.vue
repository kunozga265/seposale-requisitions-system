<template>
    <div>

        <div class="card">


            <div class="flex justify-between mb-2">
                <div class="heading-font mb-2">{{ payable[0].description }}</div>
                <!--                      <div class="heading-font font-bold text-base mb-4">MK123,456,789.00</div>-->
                <div @click="processPayables">
                    <span class="cursor-pointer text-red text-sm font-bold heading-font">Make Payment</span>
                </div>
            </div>

            <table class="table-fixed w-full text-gray-500 dark:text-gray-400">
                <tr>
                    <th  class="w-10 p-2 text-center">
                        <input type="checkbox"
                               @change="togglePayables"
                              v-model="checkPayables"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        />
                    </th>
                    <th scope="col" class="p-2 heading-font text-left">
                        Date
                    </th>
                    <th scope="col" class="p-2 heading-font text-left">
                        Requisition Code
                    </th>
                    <th scope="col" class="p-2 heading-font text-left">
                        Client
                    </th>
                    <th scope="col" class="p-2 heading-font text-left">
                        Delivery Item
                    </th>
                    <th scope="col" class="p-2 heading-font text-right">
                        Total
                    </th>


                </tr>

                <tr class="border-b" v-for="(item,index) in payable" :key="index">
                    <td class="w-10 text-center">
                        <input type="checkbox"
                               @change="checkSelectAll"
                               v-model="item.checked"
                               :disabled="item.requestForm != null"
                               v-if="item.requestForm == null"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        />
                        <span v-else>
                            <i class="mdi mdi-refresh"></i>
                        </span>
                    </td>
                    <td class="p-2 text-left">

                        <span class="">{{ getDate(item.date * 1000) }}</span>
                    </td>

                    <td  v-if="item.requestForm != null" class="p-2 text-left">
                        <inertia-link :href="route('request-forms.show',{id:item.requestForm.data.id})" class=" p-2">{{ item.requestForm.data.code }}</inertia-link>
                    </td>
                    <td  v-else class="p-2 text-left">
                        <span>-</span>
                    </td>
                    <td class="p-2 text-left">
                        <inertia-link :href="route('clients.show',{id:item.delivery.data.client.id})" class=" p-2">
                            {{ item.delivery.data.client.name }}
                        </inertia-link>

                    </td>
                    <td class="p-2 text-left">
                        <inertia-link :href="route('deliveries.show',{id:item.delivery.data.id})" class=" p-2">
                            {{ item.delivery.data.summary.description }}
                        </inertia-link>

                    </td>
                    <td class="p-2 text-right">{{ numberWithCommas(item.total) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-base p-2 text-right font-bold">MK{{ numberWithCommas(getTotal) }}</td>
                </tr>
            </table>

            <!--                  </div>-->
        </div>


        <dialog-modal :show="showDialog" @close="showDialog=false">

            <template #title>
                Create a requisition for the following

            </template>

            <template #content>
                <div class="mb-4">
                    <jet-label for="personCollectingAdvance" value="Person Collecting"/>
                    <jet-input id="personCollectingAdvance" type="text" class="block w-full"
                               v-model="form.personCollectingAdvance"
                               autocomplete="geoserve-person-collecting-advance"/>
                </div>
                <div class="p-2 mb-2 relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="heading-font">
                                Date
                            </th>
                            <th scope="col" class="heading-font">
                                Name
                            </th>
                            <th scope="col" class="heading-font text-right">
                                Amount
                            </th>


                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                            v-for="(item, index) in form.payables" :key="index">
                            <td class="py-2 pr-1">
                                {{ getDate(item.date * 1000) }}
                            </td>
                            <td scope="row"
                                class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ item.description }}
                            </td>
                            <td class="py-2 pr-1 text-right">
                                {{ numberWithCommas(item.total) }}
                            </td>

                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="font-bold text-sm text-right">{{ numberWithCommas(getItemsTotal) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <template #footer>
                <!--                <danger-button @click.native="showDialog=false">-->
                <!--                    Cancel-->
                <!--                </danger-button>-->
                <secondary-button @click.native="showDialog=false">
                    close
                </secondary-button>
                <primary-button @click.native="submit">
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


    </div>

</template>

<script>
import pdf from "vue-pdf-embed/dist/vue2-pdf-embed";
import DialogModal from "@/Jetstream/DialogModal.vue";
import PrimaryButton from "@/Jetstream/Button.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetInput from "@/Jetstream/Input.vue";
import DangerButton from "@/Jetstream/DangerButton.vue";
import JetLabel from "@/Jetstream/Label.vue";

export default {
    name: "Payable",
    props: ['payable'],
    components: {
        JetLabel,
        DangerButton,
        JetInput,
        SecondaryButton, PrimaryButton, DialogModal, pdf,
    },

    data() {
        return {
            showDialog: false,
            checkPayables: false,
            form: this.$inertia.form({
                quantity: 0,
                photo: "",
                personCollectingAdvance: "",
                payables: [],
            }),

        }
    },

    computed: {
        getTotal() {
            let sum = 0;
            for (let x in this.payable) {
                sum += this.payable[x].total
            }
            return sum
        },
        getItemsTotal() {
            let sum = 0;
            for (let x in this.form.payables) {
                sum += this.form.payables[x].total
            }
            return sum
        }

    },
    watch:{


    },

    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    total: this.getItemsTotal,
                }))
                .post(this.route('request-forms.store.payable'), {
                    // preserveScroll: true,
                    onSuccess: () => {
                        this.showDialog = false

                    },
                })
        },
        processPayables() {
            this.form.payables = []
            for (let x in this.payable) {
                if (this.payable[x].checked) {
                    this.form.payables.push(this.payable[x])
                }
            }
            if (this.form.payables.length > 0) {
                this.showDialog = true;
            }

        },
        togglePayables(){
            for (let x in this.payable) {
                if (this.payable[x].requestForm == null) {
                    this.payable[x].checked = this.checkPayables
                }
            }
        },
        checkSelectAll(){
            console.log()
            let check = true;
            for (let x in this.payable) {
                if (!this.payable[x].checked && this.payable[x].requestForm == null) {
                    check = false
                    break;
                }
            }
            this.checkPayables = check
        }


    }
}
</script>

<style scoped>

</style>
