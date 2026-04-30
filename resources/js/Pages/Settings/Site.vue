<template>
    <app-layout>
        <template #header>
            One Stop Shop
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
                    <a :href="route('settings.accounting-centre')"
                        class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Accounting Centre
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ site.data.name }} Site</span>
                </div>
            </li>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            {{ site.data.name }} Accounts
                        </div>
                    </div>
                    <div class="page-section-content">
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <div class="card">
                                <div class="flex justify-between items-center">

                                    <div class="ml-3 flex items-center">
                                        <div class="">
                                            <span
                                                class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                1
                                            </span>

                                        </div>
                                        <div class="ml-3 text-sm">
                                            <div>
                                                Wallet Account
                                            </div>
                                        </div>


                                    </div>

                                    <div class="flex items-center">
                                        <div class="text-xs text-gray-400 text-capitalize">
                                            <select v-model="walletId" id="paymentMethod"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                                <option v-for="(account, index) in filteredAccounts.wallet"
                                                    :value="account.id" :key="index">
                                                    {{ account.name }}
                                                </option>
                                            </select>

                                        </div>

                                        <primary-button class="ml-3" @click.native="attachAccount(walletId)">
                                            <i class="mdi mdi-pencil"></i>
                                        </primary-button>

                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="flex justify-between items-center">

                                    <div class="ml-3 flex items-center">
                                        <div class="">
                                            <span
                                                class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                2
                                            </span>

                                        </div>
                                        <div class="ml-3 text-sm">
                                            <div>
                                                Direct Costs
                                            </div>
                                        </div>


                                    </div>

                                    <div class="flex items-center">
                                        <div class="text-xs text-gray-400 text-capitalize">
                                            <select v-model="directCostId" id="paymentMethod"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                                <option v-for="(account, index) in filteredAccounts.cogs"
                                                    :value="account.id" :key="index">
                                                    {{ account.name }}
                                                </option>
                                            </select>

                                        </div>

                                        <primary-button class="ml-3" @click.native="attachAccount(null, directCostId)">
                                            <i class="mdi mdi-pencil"></i>
                                        </primary-button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-section" v-for="(inventory, index) in form.inventories">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            {{ inventory.name }} Linked Accounts
                        </div>
                    </div>
                    <div class="page-section-content">
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <div class="card">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="ml-3 flex items-center">
                                     
                                        <div class="text-sm">
                                            <div>
                                                Inventory Account
                                            </div>
                                        </div>


                                    </div>

                                    <div class="flex items-center">
                                        <div class="text-xs text-gray-400 text-capitalize">
                                            <select v-model="inventory.inventoryAccountId" id="paymentMethod"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                                <option v-for="(account, index) in filteredAccounts.other"
                                                    :value="account.id" :key="index">
                                                    {{ account.name }}
                                                </option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                      
                                <div class="flex justify-between items-center mb-2">

                                    <div class="ml-3 flex items-center">
                                      
                                        <div class="text-sm">
                                            <div>
                                                Revenue Account
                                            </div>
                                        </div>


                                    </div>

                                    <div class="flex items-center">
                                        <div class="text-xs text-gray-400 text-capitalize">
                                            <select v-model="inventory.revenueAccountId" id="paymentMethod"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                                <option v-for="(account, index) in filteredAccounts.other"
                                                    :value="account.id" :key="index">
                                                    {{ account.name }}
                                                </option>
                                            </select>

                                        </div>

                                    </div>
                                </div>
                          
                                <div class="flex justify-between items-center mb-2">

                                    <div class="ml-3 flex items-center">
                                
                                        <div class="text-sm">
                                            <div>
                                                Cost of Goods Account
                                            </div>
                                        </div>


                                    </div>

                                    <div class="flex items-center">
                                        <div class="text-xs text-gray-400 text-capitalize">
                                            <select v-model="inventory.cogsAccountId" id="paymentMethod"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                                <option v-for="(account, index) in filteredAccounts.other"
                                                    :value="account.id" :key="index">
                                                    {{ account.name }}
                                                </option>
                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                       <primary-button :disabled="form.processing" class="ml-3" @click.native="linkAccounts(inventory)">
                                            <i class="mdi mdi-pencil"></i> Link Accounts
                                        </primary-button>
                                </div>

                               

                            </div>

                          

                        </div>
                    </div>
                </div>
            </div>






            <!-- <jet-section-border /> -->



        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import PrimaryButton from "@/Jetstream/Button";

export default {
    props: ['site', 'accounts'],

    components: {
        AppLayout,
        JetSectionBorder,
        PrimaryButton,
    },

    data() {
        return {
            walletId: 0,
            form: this.$inertia.form({
                inventories: []
            }),
        }
    },

    created() {
        this.walletId = this.walletAccount != null ? this.walletAccount.id : 0
        this.directCostId = this.cogsAccount != null ? this.cogsAccount.id : 0

        for(let x in this.site.data.inventories){
            this.form.inventories.push({
                "id" : this.site.data.inventories[x].id,
                "name" : this.site.data.inventories[x].name,
                "cogsAccountId" : this.site.data.inventories[x].cogsAccount != null ? this.site.data.inventories[x].cogsAccount.id : 0,
                "inventoryAccountId" : this.site.data.inventories[x].inventoryAccount != null ? this.site.data.inventories[x].inventoryAccount.id : 0,
                "revenueAccountId" : this.site.data.inventories[x].revenueAccount != null ? this.site.data.inventories[x].revenueAccount.id : 0,
            })
        }
    },

    computed: {
        filteredAccounts() {
            const wallet = this.accounts.filter(account => account.special_type === 'WALLET')
            const cogs = this.accounts.filter(account => account.special_type === 'COGS-DIRECT-OSS')
            const other = this.accounts.filter(account => account.special_type !== 'WALLET')

            return {
                "wallet": wallet,
                "cogs": cogs,
                "other": other
            }
        },
        attachWalletValidation() {
            return this.walletId
        },
        walletAccount() {
            return this.site.data.accounts.find(({ special_type }) => special_type === "WALLET");
        },
        cogsAccount() {
            return this.site.data.accounts.find(({ special_type }) => special_type === "COGS-DIRECT-OSS");
        }
    },

    methods: {
        attachAccount(walletId = null, cogsId = null) {
            this.form
                .transform(data => ({
                    ...data,
                    wallet_id: walletId,
                    cogs_id: cogsId,
                }))
                .post(this.route('settings.site.attach-account', { 'id': this.site.data.code }), {
                    preserveScroll: true,
                    // onSuccess: () => 
                })
        },
        linkAccounts(inventory) {
            this.form
                .transform(data => ({
                    ...data,
                    inventory_id: inventory.inventoryAccountId,
                    revenue_id: inventory.revenueAccountId,
                    cogs_id: inventory.cogsAccountId,
                }))
                .post(this.route('settings.inventory.attach-account', { 'id': inventory.id }), {
                    preserveScroll: true,
                    // onSuccess: () => 
                })
        },
    }
}
</script>
