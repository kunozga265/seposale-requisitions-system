<template>
    <app-layout>
        <template #header>
            Dashboard
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
                        class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Dashboard</span>
                </div>
            </li>
        </template>

        <template #actions
                  v-if="!checkRole($page.props.auth.data,'unverified') && !checkRole($page.props.auth.data,'disabled')">


            <jet-dropdown align="right" width="48">
                <template #trigger>
                    <primary-button>
                        Quick Actions
                    </primary-button>
                </template>

                <template #content>
                    <!--                    &lt;!&ndash; Account Management &ndash;&gt;-->
                    <!--                    <div class="block px-4 py-2 text-xs text-gray-400">-->
                    <!--                        Quick Actions-->
                    <!--                    </div>-->

                    <jet-dropdown-link class="text-left" :href="route('request-forms.create')">
                        Create Requisition
                    </jet-dropdown-link>
                    <div class="border-t border-gray-100"></div>

                    <jet-dropdown-link class="text-left" :href="route('quotations.create')">
                        Generate Quotation
                    </jet-dropdown-link>
                    <div class="border-t border-gray-100"></div>

                    <jet-dropdown-link class="text-left" :href="route('sales.create')">
                        Record Sale
                    </jet-dropdown-link>


                </template>
            </jet-dropdown>

            <!--            <secondary-button @click.native="findRequestDialog=true"-->
            <!--                              style="padding-top: 0.4rem; padding-bottom: 0.4rem;">-->
            <!--                Find Request-->
            <!--                <i class="ml-2 mdi mdi-magnify text-lg"></i>-->
            <!--            </secondary-button>-->
        </template>

        <dialog-modal :show="findRequestDialog" @close="findRequestDialog=false">
            <template #title>
                Find Request
            </template>

            <template #content>

                <div class="mb-4">
                    <jet-label for="requestCode" value="Enter Request Code"/>
                    <jet-input id="requestCode" type="text" class="mt-1 block w-full" v-model="form.requestCode"
                               autocomplete="geoserve-request-code"/>
                    <span v-show="form.requestCode.length !==8" class="text-xs text-red-600">Enter 8 Characters</span>
                </div>
            </template>

            <template #footer>
                <secondary-button @click.native="findRequestDialog=false">
                    Cancel
                </secondary-button>

                <primary-button class="ml-2" @click.native="findRequest"
                                :disabled="form.processing || form.requestCode.length !==8">
                    <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
                         viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="#E5E7EB"/>
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentColor"/>
                    </svg>
                    Search
                </primary-button>
            </template>
        </dialog-modal>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

                <div v-if="checkRole($page.props.auth.data,'unverified')">

                    <!--                    <div class="text-sm text-center">Awaiting Verification</div>-->
                    <div class=" bg-white shadow-md overflow-hidden rounded-xl">
                        <div class="px-6 py-4">
                            <img class="mx-auto max-h-96" :src="fileUrl('images/access-blocked.svg')"
                                 alt="Access Blocked">
                        </div>
                        <div class="px-6 py-4 heading-font bg-blue-700 text-center text-lg text-white">
                            <svg role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101"
                                 fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="#E5E7EB"/>
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentColor"/>
                            </svg>
                            Awaiting Verification
                        </div>
                    </div>
                </div>
                <div v-else-if="checkRole($page.props.auth.data,'disabled')">
                    <div class=" bg-white shadow-md overflow-hidden rounded-xl">
                        <div class="px-6 py-4">
                            <img class="mx-auto max-h-96" :src="fileUrl('images/access-blocked.svg')"
                                 alt="Access Blocked">
                        </div>
                        <div class="px-6 py-4 heading-font bg-rose-600 text-center text-lg text-white">
                            Your account is disabled
                        </div>
                    </div>

                </div>
                <div v-else>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Overview
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-4">
                                <div class="card mb-0" v-if="awaitingApprovalCount>0">
                                    <div class="flex justify-start items-center">
                                        <div class="ml-3 mr-1 relative">
                                            <i class="mdi mdi-book-clock text-yellow" style="font-size: 32px;"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">Awaiting Approval</div>
                                            <div class="text-sm text-gray-400">{{ awaitingApprovalCount }}
                                                {{ awaitingApprovalCount == 1 ? 'Request' : 'Requests' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0" v-if="awaitingInitiationCount>0">
                                    <div class="flex justify-start items-center">
                                        <div class="ml-3 mr-1 relative">
                                            <i class="mdi mdi-book-check text-green" style="font-size: 32px;"></i>
                                        </div>

                                        <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">Awaiting Initiation
                                            </div>
                                            <div class="text-sm text-gray-400">{{ awaitingInitiationCount }}
                                                {{ awaitingInitiationCount == 1 ? 'Request' : 'Requests' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0" v-if="awaitingReconciliationCount>0">
                                    <div class="flex justify-start items-center">
                                        <div class="ml-3 mr-1 relative">
                                            <i class="mdi mdi-book-clock text-green" style="font-size: 32px;"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">Awaiting
                                                Reconciliation
                                            </div>
                                            <div class="text-sm text-gray-400">{{ awaitingReconciliationCount }}
                                                {{ awaitingReconciliationCount == 1 ? 'Request' : 'Requests' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0" v-if="activeCount>0">
                                    <div class="flex justify-start items-center">
                                        <div class="ml-3 mr-1 relative">
                                            <i class="mdi  mdi-book-multiple text-blue" style="font-size: 32px;"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">Active</div>
                                            <div class="text-sm text-gray-400">{{ activeCount }}
                                                {{ activeCount == 1 ? 'Request' : 'Requests' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0">

                                    <div class="flex justify-start items-center">
                                        <div class="ml-3 mr-1 relative">
                                            <i class="mdi mdi-truck-minus text-yellow" style="font-size: 32px;"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">Deliveries Awaiting
                                                Initiation
                                            </div>
                                            <div class="text-sm text-gray-400">{{ salesAwaitingInitiation.data.length }}
                                                {{
                                                    salesAwaitingInitiation.data.length == 1 ? 'Delivery' : 'Deliveries'
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0">

                                    <div class="flex justify-start items-center">
                                        <div class="ml-3 mr-1 relative">
                                            <i class="mdi mdi-truck-fast text-red" style="font-size: 32px;"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">Uncompleted Deliveries
                                            </div>
                                            <div class="text-sm text-gray-400">{{ deliveriesUnderway.data.length }}
                                                {{ deliveriesUnderway.data.length == 1 ? 'Delivery' : 'Deliveries' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0">

                                    <div class="flex justify-start items-center">
                                        <div class="ml-3 mr-1 relative">
                                            <i class="mdi mdi-garage text-yellow" style="font-size: 32px;"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">One Stop Shops
                                            </div>
                                            <div class="text-sm text-gray-400">{{ totalCollectionsPending }}
                                                {{ totalCollectionsPending == 1 ? 'Collection' : 'Collections' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0"
                                     v-if="unverifiedUsersCount>0 && (checkRole($page.props.auth.data,'management') || checkRole($page.props.auth.data,'administrator'))">
                                    <inertia-link :href="route('users')">
                                        <div class="flex justify-start items-center">
                                            <div class="ml-3 mr-1 relative">
                                                <i class="mdi mdi-account-supervisor-circle"
                                                   style="font-size: 32px; color:#eab308"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="heading-font" style="font-weight: 600;">Unverified Users
                                                </div>
                                                <div class="text-sm text-gray-400">{{ unverifiedUsersCount }}
                                                    {{ unverifiedUsersCount == 1 ? 'User' : 'Users' }}
                                                </div>
                                            </div>
                                        </div>
                                    </inertia-link>
                                </div>
                            </div>
                            <!--                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-4">-->

                            <!--                                <div class="card mb-0 lg:col-span-2">-->
                            <!--                                    <div class="flex justify-between mb-4">-->
                            <!--                                        <div class="heading-font mb-4">Tasks</div>-->
                            <!--                                        <div-->
                            <!--                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">-->
                            <!--                                            <div>5+ More</div>-->
                            <!--                                        </div>-->
                            <!--                                    </div>-->
                            <!--                                    <div class="grid grid-cols-1 md:grid-cols-2  gap-4">-->

                            <!--                                        <div v-for="i in 6" class="record mb-3 flex justify-between items-center">-->
                            <!--                                            <div class="flex items-center">-->
                            <!--                                                <div-->
                            <!--                                                    class="h-8 w-8 bg-yellow-300 rounded-full flex justify-center items-center">-->
                            <!--                                                    <div class="text-xs text-white">{{ i }}</div>-->
                            <!--                                                </div>-->
                            <!--                                                <div class="ml-3 text-sm ">-->
                            <!--                                                    <div class="text-sm">John Doe #{{ i }}</div>-->
                            <!--                                                    <div class="text-xs text-gray-500" v-if="true">-->
                            <!--                                                        <i class="mdi mdi mdi-adjust text-xs text-gray-500"></i>-->
                            <!--                                                        Quarry Stone-->
                            <!--                                                        <i class="mdi mdi mdi-map-marker-outline text-xs text-gray-500"></i>-->
                            <!--                                                        Location {{ i }}-->
                            <!--                                                    </div>-->
                            <!--                                                </div>-->
                            <!--                                            </div>-->
                            <!--                                            <div class="heading-font text-xs text-gray-500">25 Tonnes</div>-->
                            <!--                                        </div>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->
                            <!--                                <div class="">-->

                            <!--                                </div>-->

                            <!--                            </div>-->

                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Accounts
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-4">

                                <div class="card mb-0 bank-accounts">
                                    <div class="heading-font mb-4">Bank Accounts</div>
                                    <div class="text-xs mb-1 text-gray-500">Sum Total</div>
                                    <div class="heading-font font-bold text-xl mb-4">
                                        MK{{ numberWithCommas(accountsTotal.toFixed(2)) }}

                                    </div>
                                    <div
                                    @click="navigateToAccount(account.code)"
                                        v-for="(account,index) in accounts" :key="index"
                                        class="record p-2 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                        <div class="flex items-center">
                                            <img :src="fileUrl(account.photo)"
                                                 class="h-8 rounded flex justify-center items-center" alt="">
                                            <!--                      <div-->
                                            <!--                          :style="{ backgroundImage:`url(${fileUrl(account.photo)})` }"-->

                                            <!--                          class="image-placeholder bg-gray-100 rounded-full flex justify-center items-center"-->
                                            <!--                      >-->
                                            <!--                        <div class="text-xs"-->
                                            <!--                        >-->

                                            <!--                        </div>-->
                                            <!--                      </div>-->
                                            <div class="ml-3 text-sm ">
                                                <div class="text-sm">{{ account.name }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ account.number }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            MK{{ numberWithCommas(account.balance) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="card profit-loss mb-0">
                                    <div class="heading-font mb-4">Profit & Loss</div>
                                    <div class="text-xs mb-1 text-gray-500">Net Profit</div>
                                    <div class="heading-font font-bold text-xl mb-1" :class="{'text-red': financialPosition.net < 0, 'text-green': financialPosition.net > 0, }">MK{{ numberWithCommas(financialPosition.net) }}</div>
                                    <div class="text-xs mb-1 text-gray-500">
<!--                                        <span class="font-bold text-green">Up 100%</span> from last month-->
                                    </div>
                                    <div class="mb-4"></div>
                                    <div class="mb-2">
                                        <div class="heading-font text-base">MK{{ numberWithCommas(financialPosition.income) }}</div>
                                        <div class="relative">
                                            <div class="absolute text-sm text-gray-500">Income</div>
                                            <div class="bar positive rounded h-8 "></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="heading-font text-base">MK{{ numberWithCommas(financialPosition.expenditure) }}</div>
                                        <div class="relative">
                                            <div class="absolute text-sm text-gray-500">Expenditure</div>
                                            <div class="bar negative rounded h-8 "></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card mb-0 profit-loss">
                                    <div class="heading-font mb-4">Expenses</div>
                                    <div class="text-xs mb-1 text-gray-500">Sum Total</div>
                                    <div class="heading-font font-bold text-xl mb-4 text-red">MK{{numberWithCommas(expensesTotal)}}</div>

                                    <div>
                                        <PieChart
                                            :chart-options="expensesChartOptions"
                                            :chart-data="expensesData"
                                            chart-id="types"
                                            dataset-id-key="types"
                                            :height="200"
                                        />
                                    </div>
                                </div>



                                <div class="card mb-0 bank-accounts">
                                    <div class="flex justify-between">
                                        <div class="heading-font mb-4">Total Receivables</div>
                                        <inertia-link
                                            :href="route('sales.index',{'section':'tabular'})"
                                            v-show="receivables.length > 5"
                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                                            <div>{{
                                                    receivables.length > 5 ? (receivables.length - 5) + '+ More' : ''
                                                }}
                                            </div>
                                        </inertia-link>
                                    </div>
                                    <div class="text-xs mb-1 text-gray-500">Sum Total</div>
                                    <div class="heading-font font-bold text-xl mb-4 text-green">
                                        MK{{ numberWithCommas(receivablesTotal) }}
                                    </div>

                                    <div v-if="index < 5"
                                         :key="index"
                                         v-for="(info, index) in receivables"
                                         class="record p-2 mb-1 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 bg-gray-100 rounded-full flex justify-center items-center">
                                                <div class="text-xs text-gray-500">{{ index + 1 }}</div>
                                            </div>
                                            <div class="ml-3 text-sm ">{{ info.client.name }}</div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            MK{{ numberWithCommas(info.principal) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0 bank-accounts">
                                    <div class="flex justify-between">
                                        <div class="heading-font mb-4">Payables</div>
                                        <inertia-link
                                            :href="route('payables.index',)"
                                            v-show="payables.all.length > 5"
                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                                            <div>{{
                                                    payables.all.length > 5 ? (payables.all.length - 5) + '+ More' : ''
                                                }}
                                            </div>
                                        </inertia-link>
                                    </div>
                                    <div class="text-xs mb-1 text-gray-500">Total Due</div>
                                    <div class="heading-font font-bold text-xl mb-4 text-red">
                                        MK{{ numberWithCommas(payables.total) }}
                                    </div>

                                    <div v-if="index < 5"
                                         :key="index"
                                         v-for="(info, index) in payables.all"
                                         class="record p-2 mb-1 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 bg-gray-100 rounded-full flex justify-center items-center">
                                                <div class="text-xs text-gray-500">{{ index + 1 }}</div>
                                            </div>
                                            <div class="ml-3 text-sm ">
                                                <div class="text-sm ">{{ info.name }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ info.category }}
                                                </div>
                                            </div>

                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            MK{{ numberWithCommas(info.total) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0 bank-accounts">
                                    <div class="flex justify-between">
                                        <div class="heading-font mb-4">Undelivered Clients</div>
                                        <inertia-link
                                            :href="route('deliveries.index', )"
                                            v-show="undeliveredClients.length > 5"
                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                                            <div>{{
                                                    undeliveredClients.length > 5 ? (undeliveredClients.length - 5) + '+ More' : ''
                                                }}
                                            </div>
                                        </inertia-link>
                                    </div>
                                    <div class="text-xs mb-1 text-gray-500">Total Due</div>
                                    <div class="heading-font font-bold text-xl mb-4 text-red">
                                        MK{{ numberWithCommas(undeliveredClientsTotal) }}
                                    </div>

                                    <div v-if="index < 5"
                                         :key="index"
                                         v-for="(info, index) in undeliveredClients"
                                         class="record p-2 mb-1 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 bg-gray-100 rounded-full flex justify-center items-center">
                                                <div class="text-xs text-gray-500">{{ index + 1 }}</div>
                                            </div>
                                            <div class="ml-3 text-sm ">{{ info.client.name }}</div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            MK{{ numberWithCommas(info.due) }}
                                        </div>
                                    </div>
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
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-4">
                                <div class="card mb-0 md:col-span-2 lg:col-span-3">
                                    <div class="flex justify-between mb-4">
                                        <div class="heading-font mb-4">Sales</div>
                                        <div>
                                            <vue-date-time-picker
                                                v-model="form.dates"
                                                range
                                            />
                                        </div>
                                    </div>
                                    <div class="text-xs mb-1 text-gray-500">Total Sales</div>
                                    <div class="heading-font font-bold text-xl mb-4">
                                        MK{{ numberWithCommas(salesTotal) }}
                                    </div>

                                    <div>
                                        <div id="chart">
                                            <apexchart type="area" :options="chartOptionsApex"
                                                       height="180"
                                                       :series="salesData"></apexchart>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0 profit-loss">
                                    <div class="heading-font mb-4">Product Summary</div>

                                    <div>
                                        <PieChart
                                            :chart-options="productsChartOptions"
                                            :chart-data="productsData"
                                            chart-id="types"
                                            dataset-id-key="types"
                                            :height="280"
                                        />
                                    </div>
                                </div>
                                <div class="card mb-0 profit-loss">
                                    <div class="heading-font mb-4">Breakdown</div>

                                    <div v-for="(product,index) in listOfProducts" :index="index"
                                         class="record px-2 mb-1 rounded flex justify-between items-center ">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 bg-gray-100 rounded-full flex justify-center items-center">
                                                <div class="text-xs text-gray-500">{{ index + 1 }}</div>
                                            </div>
                                            <div class="ml-3 text-sm ">
                                                <div class="text-sm">{{ product.name }}</div>
                                            </div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            MK{{ numberWithCommas(product.total) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-0 md:col-span-2 lg:col-span-1">
                                    <div class="flex justify-between mb-4">
                                        <div class="heading-font mb-4">Pending Deliveries</div>
                                        <inertia-link
                                            :href="route('sales.index',{'section':'tabular'})"
                                            v-show="salesAwaitingInitiation.data.length > 5"
                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                                            <div>{{
                                                    salesAwaitingInitiation.data.length > 5 ? (salesAwaitingInitiation.data.length - 5) + '+ More' : ''
                                                }}
                                            </div>
                                        </inertia-link>
                                    </div>

                                    <div v-if="index < 5" v-for="(sale,index) in salesAwaitingInitiation.data"
                                         :key="index"
                                         class="record p-2 mb-1 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 bg-gray-100 rounded-full flex justify-center items-center">
                                                <div class="text-xs text-gray-500">{{ index + 1 }}</div>
                                            </div>
                                            <div class="ml-3 text-sm ">
                                                <div class="text-sm">{{ sale.client.name }}</div>
                                                <div class="text-xs text-gray-500" v-if="true">
                                                    <i class="mdi mdi mdi-adjust text-xs text-gray-500"></i>
                                                    {{ productName(sale) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">{{ sale.quantity }}
                                            {{ sale.units }}{{ sale.quantity !== 1 ? "s" : "" }}
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                One Stop Shops
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-4">
                                <div class="card mb-0 md:col-span-2 lg:col-span-3">
                                    <div class="flex justify-between mb-4">

                                        <jet-dropdown align="left" width="48">-->
                                            <template #trigger>
                                                <!--                                                                                                <div-->
                                                <!--                                                                                                    class="text-xs text-gray-500 py-1 px-4 border rounded-full cursor-pointer hover:bg-gray-50 transition ease-in-out duration-200">-->
                                                <!--                                                                                                    {{ selectedShop.name }} <i class="mdi mdi-menu-down"></i>-->
                                                <!--                                                                                                </div>-->
                                                <div class="heading-font mb-4 cursor-pointer">{{ selectedShop.name }} <i
                                                    class="mdi mdi-menu-down"></i></div>
                                            </template>

                                            <template #content>
                                                <!--                    &lt;!&ndash; Account Management &ndash;&gt;-->
                                                <!--                    <div class="block px-4 py-2 text-xs text-gray-400">-->
                                                <!--                        Quick Actions-->
                                                <!--                    </div>-->

                                                <div v-for="(shop,index) in shops.data">
                                                    <div
                                                        class="text-left text-sm py-2 px-4 cursor-pointer hover:bg-gray-50 transition ease-in-out duration-200"
                                                        @click="shopIndex = index">
                                                        {{ shop.name }}
                                                    </div>
                                                    <div class="border-t border-gray-100"></div>
                                                </div>
                                            </template>
                                        </jet-dropdown>
                                        <div class="flex">
                                            <vue-date-time-picker
                                                v-model="form.productDates"
                                                range
                                            />
                                        </div>
                                    </div>
                                    <div class="text-xs mb-1 text-gray-500">Total Sales</div>
                                    <div class="heading-font font-bold text-xl mb-4">
                                        MK{{ numberWithCommas(shopTotal) }}
                                    </div>

                                    <div>
                                        <div id="chart">
                                            <apexchart type="area" :options="chartOptionsApex"
                                                       height="180"
                                                       :series="shopSales"></apexchart>
                                        </div>
                                    </div>
                                </div>


                                <div class="card mb-0 profit-loss">
                                    <div class="heading-font mb-4">Product Summary</div>

                                    <div>
                                        <PieChart
                                            :chart-options="productsChartOptions"
                                            :chart-data="shopProductsData"
                                            chart-id="types"
                                            dataset-id-key="types"
                                            :height="280"
                                        />
                                    </div>
                                </div>

                                <div class="card mb-0">
                                    <div class="flex justify-between mb-4">
                                        <div class="heading-font mb-4">Inventory</div>

                                    </div>

                                    <inertia-link
                                        :href="route('sites.inventories.show',{'code':selectedShop.code,'id':inventory.id})"
                                        v-for="(inventory,index) in selectedShop.inventories" :key="index"
                                        class="record p-2 mb-1 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 rounded-full flex justify-center items-center"
                                                :class="{'bg-red':getStatus(inventory) === 0,'bg-yellow':getStatus(inventory) === 1,  'bg-green':getStatus(inventory) === 2, }"
                                            >
                                                <div class="text-xs text-white"
                                                >
                                                    {{ index + 1 }}
                                                </div>
                                            </div>
                                            <div class="ml-3 text-sm ">
                                                <div class="text-sm">{{ inventory.name }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ getInventoryStatus(inventory) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            {{ numberWithCommas(inventory.availableStock) }}
                                            {{ inventory.units }}{{ inventory.availableStock != 1 ? "s" : "" }}
                                        </div>
                                    </inertia-link>
                                </div>


                                <!--                                <div class="card mb-0 profit-loss">-->
                                <!--                                    <div class="heading-font mb-4">Product Summary</div>-->

                                <!--                                    <div>-->
                                <!--                                        <PieChart-->
                                <!--                                            :chart-options="productsChartOptions"-->
                                <!--                                            :chart-data="productsData"-->
                                <!--                                            chart-id="types"-->
                                <!--                                            dataset-id-key="types"-->
                                <!--                                            :height="280"-->
                                <!--                                        />-->
                                <!--                                    </div>-->
                                <!--                                </div>-->

                                <div class="card mb-0 md:col-span-2 lg:col-span-1">
                                    <div class="flex justify-between mb-4">
                                        <div class="heading-font mb-4">Pending Collections</div>
                                        <div
                                            v-show="selectedShop.pendingCollections.length > 5 "
                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                                            <div>{{
                                                    selectedShop.pendingCollections.length > 5 ? (selectedShop.pendingCollections.length - 5) + '+ More' : ''
                                                }}
                                            </div>
                                        </div>
                                    </div>

                                    <div v-show="index < 5"
                                         v-for="(productCompound,index) in selectedShop.pendingCollections" :key="index"
                                         class="record p-2 mb-1 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 bg-gray-100 rounded-full flex justify-center items-center">
                                                <div class="text-xs text-gray-500">{{ index + 1 }}</div>
                                            </div>
                                            <div class="ml-3 text-sm ">
                                                <div class="text-sm">{{ productCompound.sale.client.name }}</div>
                                                <div class="text-xs text-gray-500" v-if="true">
                                                    <i class="mdi mdi mdi-adjust text-xs text-gray-500"></i>
                                                    {{ productCompound.inventory.name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <collection
                                                class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                                                :client="productCompound.sale.client" :product="productCompound"
                                                :is-solo="true"/>

                                        </div>
                                        <!--                                        <div class="heading-font text-xs text-gray-500">-->
                                        <!--                                            {{ numberWithCommas(productCompound.quantity - productCompound.collected) }}-->
                                        <!--                                            {{ productCompound.inventory.units }}{{ (productCompound.quantity - productCompound.collected) !== 1 ? "s" : "" }}-->
                                        <!--                                        </div>-->
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Operations
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-4">

                                <div class="card mb-0 lg:col-span-2">
                                    <div class="flex justify-between mb-4">
                                        <div class="heading-font mb-4">Deliveries Underway</div>
                                        <div
                                            v-show="deliveriesUnderway.data.length > 6"
                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                                            <div>{{
                                                    deliveriesUnderway.data.length > 6 ? (deliveriesUnderway.data.length - 6) + '+ More' : ''
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2  gap-2">

                                        <inertia-link :href="route('deliveries.show',{'id':delivery.id})"
                                                      v-show="index < 6"
                                                      v-for="(delivery, index) in deliveriesUnderway.data"
                                                      class="record p-2 mb-1 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-8 w-8  rounded-full flex justify-center items-center"
                                                    :class="{'bg-yellow':!delivery.overdue, 'bg-red': delivery.overdue}"
                                                >
                                                    <div class="text-xs text-white">{{ index + 1 }}</div>
                                                </div>
                                                <div class="ml-3 text-sm ">
                                                    <div class="text-sm">{{ delivery.client.name }}</div>
                                                    <div class="text-xs text-gray-500" v-if="true">
                                                        <i class="mdi mdi mdi-adjust text-xs text-gray-500"></i>
                                                        {{ productName(delivery.summary) }}
                                                        <!--                                                    <span v-if="delivery.location">-->
                                                        <!--                                                        <i class="mdi mdi mdi-map-marker-outline text-xs text-gray-500"></i>-->
                                                        <!--                                                        {{delivery.location}}-->
                                                        <!--                                                    </span>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="heading-font text-xs text-gray-500">
                                                {{
                                                    numberWithCommas(delivery.summary.quantity - delivery.quantityDelivered)
                                                }}
                                                {{
                                                    delivery.summary.units
                                                }}{{
                                                    (delivery.summary.quantity - delivery.quantityDelivered) != 1 ? "s" : ""
                                                }}
                                            </div>
                                        </inertia-link>
                                    </div>
                                </div>
                                <div class="card mb-0">
                                    <div class="flex justify-between">
                                        <div class="heading-font mb-4">Awaiting Completion</div>
                                        <div
                                            v-show="deliveriesUncompleted.data.length > 4 "
                                            class="flex items-center rounded-full px-3 bg-gray-200 text-gray-600 text-xs font-bold ">
                                            <div>{{
                                                    deliveriesUncompleted.data.length > 4 ? (deliveriesUncompleted.data.length - 4) + '+ More' : ''
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs mb-4 text-gray-500 ">Enter expenses to complete these
                                        deliveries
                                    </div>

                                    <inertia-link :href="route('deliveries.show',{'id':delivery.id})" v-show="index < 4"
                                                  v-for="(delivery,index) in deliveriesUncompleted.data"
                                                  class="record p-2 mb-1 rounded flex justify-between items-center cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 bg-gray-100 rounded-full flex justify-center items-center">
                                                <div class="text-xs text-gray-500">{{ index + 1 }}</div>
                                            </div>
                                            <div class="ml-3 text-sm ">
                                                <div class="text-sm">{{ delivery.client.name }}</div>
                                                <div class="text-xs text-gray-500" v-if="true">
                                                    <i class="mdi mdi mdi-adjust text-xs text-gray-500"></i>
                                                    {{ productName(delivery.summary) }}
                                                    <!--                                                    <span v-if="delivery.location">-->
                                                    <!--                                                        <i class="mdi mdi mdi-map-marker-outline text-xs text-gray-500"></i>-->
                                                    <!--                                                        {{delivery.location}}-->
                                                    <!--                                                    </span>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="heading-font text-xs text-gray-500">
                                            {{ numberWithCommas(delivery.quantityDelivered) }}
                                            {{
                                                delivery.summary.units
                                            }}{{ (delivery.quantityDelivered) != 1 ? "s" : "" }}
                                        </div>
                                    </inertia-link>
                                </div>

                            </div>

                        </div>
                    </div>

                    <!--          <div v-if="dashboardReports.data.length > 0" class="page-section">
                                <div class="page-section-header">
                                  <div class="page-section-title">
                                    Overview
                                  </div>
                                </div>
                                <div class="page-section-content">
                                  <div class="card">
                                    <div class="md:p-8 w-full  mx-auto relative">
                                      <BarChart
                                          :chart-options="positionsChartOptions"
                                          :chart-data="positionsData"
                                      />
                                    </div>
                                  </div>
                                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                    <div class="card" v-if="awaitingApprovalCount>0">
                                      <div class="flex justify-start items-center">
                                        <div class="overview-chart relative">
                                          <div style="font-size:12px;"
                                               class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((awaitingApprovalCount / totalCount) * 100) }}%
                                          </div>
                                          <DoughnutChart
                                              :chart-options="chartOptions"
                                              :chart-data="awaitingApprovalData"
                                              chart-id="awaitingApproval"
                                              dataset-id-key="awaitingApproval"
                                          />
                                        </div>
                                        <div class="ml-4">
                                          <div class="heading-font" style="font-weight: 600;">Awaiting Approval</div>
                                          <div class="text-sm text-gray-400">{{ awaitingApprovalCount }}
                                            {{ awaitingApprovalCount == 1 ? 'Request' : 'Requests' }}
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card" v-if="awaitingInitiationCount>0">
                                      <div class="flex justify-start items-center">
                                        <div class="overview-chart relative">
                                          <div style="font-size:12px;"
                                               class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((awaitingInitiationCount / totalCount) * 100) }}%
                                          </div>
                                          <DoughnutChart
                                              :chart-options="chartOptions"
                                              :chart-data="awaitingInitiationData"
                                              chart-id="awaitingInitiation"
                                              dataset-id-key="awaitingInitiation"
                                          />
                                        </div>
                                        <div class="ml-4">
                                          <div class="heading-font" style="font-weight: 600;">Awaiting Initiation</div>
                                          <div class="text-sm text-gray-400">{{ awaitingInitiationCount }}
                                            {{ awaitingInitiationCount == 1 ? 'Request' : 'Requests' }}
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card" v-if="awaitingReconciliationCount>0">
                                      <div class="flex justify-start items-center">
                                        <div class="overview-chart relative">
                                          <div style="font-size:12px;"
                                               class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((awaitingReconciliationCount / totalCount) * 100) }}%
                                          </div>
                                          <DoughnutChart
                                              :chart-options="chartOptions"
                                              :chart-data="awaitingReconciliationData"
                                              chart-id="awaitingReconciliation"
                                              dataset-id-key="awaitingReconciliation"
                                          />
                                        </div>
                                        <div class="ml-4">
                                          <div class="heading-font" style="font-weight: 600;">Awaiting Reconciliation</div>
                                          <div class="text-sm text-gray-400">{{ awaitingReconciliationCount }}
                                            {{ awaitingReconciliationCount == 1 ? 'Request' : 'Requests' }}
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card" v-if="activeCount>0">
                                      <div class="flex justify-start items-center">
                                        <div class="overview-chart relative">
                                          <div style="font-size:12px;"
                                               class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((activeCount / totalCount) * 100) }}%
                                          </div>
                                          <DoughnutChart
                                              :chart-options="chartOptions"
                                              :chart-data="activeData"
                                              chart-id="active"
                                              dataset-id-key="active"
                                          />
                                        </div>
                                        <div class="ml-4">
                                          <div class="heading-font" style="font-weight: 600;">Active</div>
                                          <div class="text-sm text-gray-400">{{ activeCount }}
                                            {{ activeCount == 1 ? 'Request' : 'Requests' }}
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card"
                                         v-if="unverifiedUsersCount>0 && (checkRole($page.props.auth.data,'management') || checkRole($page.props.auth.data,'administrator'))">
                                      <inertia-link :href="route('users')">
                                        <div class="flex justify-start items-center">
                                          <div class="ml-3 mr-1 relative">
                                            <i class="mdi mdi-account-supervisor-circle" style="font-size: 32px; color:#eab308"></i>
                                          </div>
                                          <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">Unverified Users</div>
                                            <div class="text-sm text-gray-400">{{ unverifiedUsersCount }}
                                              {{ unverifiedUsersCount == 1 ? 'User' : 'Users' }}
                                            </div>
                                          </div>
                                        </div>
                                      </inertia-link>
                                    </div>
                                    <div class="card"
                                         v-if="unverifiedProjectsCount>0 && (checkRole($page.props.auth.data,'management') || checkRole($page.props.auth.data,'administrator'))">
                                      <inertia-link :href="route('projects')">
                                        <div class="flex justify-start items-center">
                                          <div class="ml-3 mr-1 relative">
                                            <i class="mdi mdi-home-group" style="font-size: 32px; color:#eab308"></i>
                                          </div>
                                          <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">Unverified Projects</div>
                                            <div class="text-sm text-gray-400">{{ unverifiedProjectsCount }}
                                              {{ unverifiedProjectsCount == 1 ? 'Project' : 'Projects' }}
                                            </div>
                                          </div>
                                        </div>
                                      </inertia-link>
                                    </div>
                                    <div class="card"
                                         v-if="unverifiedVehiclesCount>0 && (checkRole($page.props.auth.data,'management') || checkRole($page.props.auth.data,'administrator'))">
                                      <inertia-link :href="route('vehicles')">
                                        <div class="flex justify-start items-center">
                                          <div class="ml-3 mr-1 relative">
                                            <i class="mdi mdi-car-multiple" style="font-size: 32px; color:#eab308"></i>
                                          </div>
                                          <div class="ml-4">
                                            <div class="heading-font" style="font-weight: 600;">Unverified Vehicles</div>
                                            <div class="text-sm text-gray-400">{{ unverifiedVehiclesCount }}
                                              {{ unverifiedVehiclesCount == 1 ? 'Vehicle' : 'Vehicles' }}
                                            </div>
                                          </div>
                                        </div>
                                      </inertia-link>
                                    </div>
                                  </div>

                                </div>
                              </div>
                              <div class="page-section">
                                <div class="page-section-header">
                                  <div class="page-section-title">
                                    Pending Sales
                                  </div>
                                </div>
                                <div class="page-section-content">
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
                                            <div class="name">{{ sale.client.name }}</div>
                                          </div>
                                          <div class="flex items-center ">
                                            <div class="currency ">MK</div>
                                            <div class="total">{{ numberWithCommas(sale.total) }}</div>
                                          </div>
                                        </div>
                                        <div>
                                          <sale-status :status="sale.status"/>
                                        </div>
                                      </div>
                                    </inertia-link>
                                    <div v-if="sales.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
                                      No Pending Sales
                                    </div>
                                  </div>
                                </div>
                              </div>
                              -->
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Awaiting your action
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-2">
                                <request
                                    v-for="(request,index) in toApprove.data"
                                    :key="index"
                                    :request="request"
                                />
                                <div v-if="toApprove.data.length === 0"
                                     class="text-center text-gray-400 md:col-span-2 text-sm">
                                    No Requests To Approve
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Active Requests
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-2">
                                <request
                                    v-for="(request,index) in active.data"
                                    :key="index"
                                    :request="request"
                                />
                                <div v-if="active.data.length === 0"
                                     class="text-center text-gray-400 md:col-span-2 text-sm">
                                    No Active Requests
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
import BarChart from "@/Components/Charts/BarChart";
import PrimaryButton from '@/Jetstream/Button'
import SecondaryButton from "@/Jetstream/SecondaryButton";
import Request from "@/Components/Request";
import DialogModal from "@/Jetstream/DialogModal";
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import SaleStatus from "@/Components/SaleStatus.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import LineChart from "@/Components/Charts/LineChart.vue";
import VueApexCharts from "vue-apexcharts";
import PieChart from "@/Components/Charts/PieChart.vue";
import Collection from "@/Components/Collection.vue";

export default {
    props: [
        'toApprove',
        'active',
        'awaitingApprovalCount',
        'awaitingInitiationCount',
        'awaitingReconciliationCount',
        'activeCount',
        'totalCount',
        'unverifiedUsersCount',
        'unverifiedVehiclesCount',
        'unverifiedProjectsCount',
        'dashboardReports',
        // 'sales',
        'deliveriesUnderway',
        'deliveriesUncompleted',
        'shops',
        'allReceipts',
        'allSales',
        'salesAwaitingInitiation',
        'undeliveredClients',
        'accounts',
        'receivables',
        'expenses',
        'payables',
    ],
    components: {
        Collection,
        PieChart,
        JetDropdown, JetDropdownLink,
        SaleStatus,
        AppLayout,
        DoughnutChart,
        BarChart,
        LineChart,
        PrimaryButton,
        SecondaryButton,
        Request,
        DialogModal,
        JetLabel,
        JetInput,
        JetValidationErrors,
        VueApexCharts,
        "apexchart": VueApexCharts,
    },
    data() {
        return {
            shopIndex: 0,
            chartOptionsApex: {
                chart: {
                    type: 'area',
                    stacked: false,
                    // height: 350,
                    zoom: {
                        type: 'x',
                        enabled: true,
                        autoScaleYaxis: true
                    },
                    toolbar: {
                        autoSelected: 'zoom'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 0,
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            // return (val / 1000000).toFixed(0);
                            return val;
                        },
                    },

                },
                xaxis: {
                    type: 'datetime',
                },
                tooltip: {
                    shared: false,
                    y: {
                        formatter: function (val) {
                            // console.log(val)
                            // return (val / 1000000).toFixed(0)
                            return val
                        }
                    }
                }
            },
            expensesChartOptions: {
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: true,
                        position: 'right'
                    }
                },
                cutout: 50,
                height: 20,
                responsive: true,
                maintainAspectRatio: false
            },
            listOfProducts: [],
            // productsData: {
            //     datasets: [{
            //         data: [
            //             650234,
            //             206991,
            //             300450.54,
            //             550992
            //             // this.vehicleMaintenanceRequestsCount,
            //             //  this.fuelRequestsCount
            //         ],
            //         backgroundColor: ['#1a56db', '#ed0b4b', '#b1bbc9', '#e3ebf6'],
            //     }],
            //     labels: ['River Sand', 'Quarry Stone', 'Pebble Stone', 'Other']
            // },
            productsChartOptions: {
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: true,
                        position: 'right'
                    }
                },
                cutout: 60,
                responsive: true,
                maintainAspectRatio: false
            },
            findRequestDialog: false,
            error: false,
            form: this.$inertia.form({
                requestCode: '',
                dates: null,
                productDates: null,
            }),
            chartOptions: {
                plugins: {
                    tooltip: {
                        enabled: false
                    },
                    legend: {
                        display: false
                    }
                }, cutout: 20
            },
            awaitingApprovalData: {
                datasets: [{
                    data: [this.awaitingApprovalCount, (this.totalCount - this.awaitingApprovalCount)],
                    backgroundColor: ['#eab308', '#e3ebf6'],
                }],
            },
            awaitingInitiationData: {
                datasets: [{
                    data: [this.awaitingInitiationCount, (this.totalCount - this.awaitingInitiationCount)],
                    backgroundColor: ['#22c55e', '#e3ebf6'],
                }],
            },
            awaitingReconciliationData: {
                datasets: [{
                    data: [this.awaitingReconciliationCount, (this.totalCount - this.awaitingReconciliationCount)],
                    backgroundColor: ['#22c55e', '#e3ebf6'],
                }],
            },
            activeData: {
                datasets: [{
                    data: [this.activeCount, (this.totalCount - this.activeCount)],
                    backgroundColor: ['#1a56db', '#e3ebf6'],
                }],
            },
            positionsData: {
                labels: [new Date(2020, 1, 1), new Date(2020, 1, 10), new Date(2020, 1, 12), new Date(2020, 2, 2), new Date(2020, 4, 1)],
                datasets: [
                    {
                        label: 'Dataset 1',
                        data: [0.1, 0.2, 0.5, -0.2, 0.05]
                        // borderColor: Utils.CHART_COLORS.red,
                        // backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
                    },
                ]
            },
            positionsChartOptions: {
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    xAxes: [{
                        grid: {
                            display: false
                        },
                        type: 'time',
                        barThickness: 6,
                        distribution: 'series',
                        time: {
                            displayFormats: {
                                'minute': 'MMM DD h:mm',
                                'hour': 'MMM DD hA',
                                'day': 'MMM DD',
                                'week': 'MMM DD',
                                'month': 'MMM DD',
                                'quarter': 'MMM DD',
                                'year': 'MMM DD',
                            }
                        },
                        ticks: {
                            callback: (value) => {
                                console.log(value, this.formatDateTime(value))
                                return this.formatDateTime(value)
                            },
                        }
                    }],
                    yAxes: {
                        grid: {
                            display: false
                        }
                    },
                },
                maintainAspectRatio: false
            },
        }
    },

    created() {
        for (let x in this.dashboardReports.data) {
            console.log(this.dashboardReports.data[x])
            // this.positionsData.datasets[0].data.push(this.dashboardReports.data[x].requestsCount)
            // this.positionsData.labels.push(this.dashboardReports.data[x].month)
        }
    },
    computed: {
        //unfiltered position
        financialPosition(){
            //get sales
            let sales = 0
            for (let x in this.allSales.data) {
                sales += this.allSales.data[x].amount
            }

            //get receivables
            let receivables = this.receivablesTotal

            //get expenses
            let expenses = this.expensesTotal

            //get payables
            let payables = parseFloat(this.payables.total)

            //get undelivered clients
            let undeliveredClientsTotal = this.undeliveredClientsTotal

            const income = sales + receivables
            const expenditure = expenses + payables + undeliveredClientsTotal
            return {
                income: income,
                expenditure: expenditure,
                net: income - expenditure,
            }
        },
        accountsTotal() {
            let total = 0;

            for (let x in this.accounts) {
                total += parseFloat(this.accounts[x].balance)
            }
            return total
        },
        totalCollectionsPending() {
            let total = 0;

            for (let x in this.shops.data) {
                total += this.shops.data[x].pendingCollections.length
            }

            return total
        },
        receivablesTotal() {
            let sum = 0;
            for (let x in this.receivables) {
                sum += parseFloat(this.receivables[x].principal)
            }
            return sum
        },
        undeliveredClientsTotal() {
            let sum = 0;
            for (let x in this.undeliveredClients) {
                sum += this.undeliveredClients[x].due
            }
            return sum
        },
        selectedShop() {
            return this.shops.data[this.shopIndex]
        },
        filteredSales() {

            let filtered = this.allReceipts.data;

            /* Filter Sales By Date */
            if (this.form.dates != null) {
                if (this.form.dates.start != null) {
                    filtered = (filtered).filter((sale) => {
                        return sale.date >= this.getTimestampFromDate(this.form.dates.start)
                    })
                }
                if (this.form.dates.end != null) {
                    filtered = (filtered).filter((sale) => {
                        return sale.date <= this.getTimestampFromDate(this.form.dates.end)
                    })
                }
            }

            return filtered
        },
        salesData() {
            let sales = [{
                name: 'Sales',
                data: []
            }];

            for (let x in this.filteredSales) {
                sales[0].data.push({
                    x: this.filteredSales[x].date * 1000,
                    y: this.filteredSales[x].amount
                })
            }

            return sales
        },
        salesTotal() {
            let sum = 0
            for (let x in this.filteredSales) {
                sum += this.filteredSales[x].amount
            }
            return sum
        },
        filteredProducts() {
            let products = []

            for (let x in this.filteredSales) {
                for (let y in this.filteredSales[x].information) {
                    products.push(this.filteredSales[x].information[y])
                }
            }

            return products.reduce(function (arr, product) {
                (arr[product.product_id] = arr[product.product_id] || []).push(product);
                return arr;
            }, {})
        },
        productsData() {
            let list = []
            let labels = []
            let data = []
            let sum = 0

            for (let x in this.filteredProducts) {
                sum = 0
                labels.push(this.filteredProducts[x][0].product_name)
                for (let y in this.filteredProducts[x]) {
                    sum += this.filteredProducts[x][y].amount
                }
                data.push(sum)
                list.push({
                    "name": this.filteredProducts[x][0].product_name,
                    "total": sum
                })
            }

            this.listOfProducts = list

            return {
                datasets: [{
                    data: data,
                    backgroundColor: ['#3375bf', '#4aa4a3', '#492d8a', '#e7632a', '#ed0b4b', '#62bdf9', '#e0f96a', '#8ef96d'],
                }],
                labels: labels
            }
        },

        filteredShopSales() {
            let filtered = this.selectedShop.receipts

            /* Filter Sales By Date */
            if (this.form.productDates != null) {
                if (this.form.productDates.start != null) {
                    filtered = (filtered).filter((sale) => {
                        return sale.date >= this.getTimestampFromDate(this.form.productDates.start)
                    })
                }
                if (this.form.productDates.end != null) {
                    filtered = (filtered).filter((sale) => {
                        return sale.date <= this.getTimestampFromDate(this.form.productDates.end)
                    })
                }
            }

            return filtered
        },
        shopSales() {
            let sales = [{
                name: 'Sales',
                data: []
            }];
            for (let x in this.filteredShopSales) {
                sales[0].data.push({
                    x: this.filteredShopSales[x].date * 1000,
                    y: this.filteredShopSales[x].amount
                })
            }
            return sales
        },
        shopTotal() {
            let sum = 0
            for (let x in this.filteredShopSales) {
                sum += this.filteredShopSales[x].amount
            }
            return sum
        },
        filteredShopProducts() {
            let products = []

            for (let x in this.filteredShopSales) {
                for (let y in this.filteredShopSales[x].information) {
                    products.push(this.filteredShopSales[x].information[y])
                }
            }

            return products.reduce(function (arr, product) {
                (arr[product.product_id] = arr[product.product_id] || []).push(product);
                return arr;
            }, {})
        },
        shopProductsData() {
            // let list = []
            let labels = []
            let data = []
            let sum = 0

            for (let x in this.filteredShopProducts) {
                sum = 0
                labels.push(this.filteredShopProducts[x][0].product_name)
                for (let y in this.filteredShopProducts[x]) {
                    sum += this.filteredShopProducts[x][y].amount
                }
                data.push(sum)
                // list.push({
                //     "name":this.filteredShopProducts[x][0].product_name,
                //     "total":sum
                // })
            }

            // this.listOfProducts = list

            return {
                datasets: [{
                    data: data,
                    backgroundColor: ['#1a56db', '#ed0b4b', '#b1bbc9', '#e3ebf6'],
                }],
                labels: labels
            }
        },
        filteredCategories() {
            return this.expenses.data.reduce(function (arr, expense) {
                (arr[expense.expenseType.id] = arr[expense.expenseType.id] || []).push(expense);
                return arr;
            }, {})
        },
        expensesData() {
            let labels = []
            let data = []
            let sum = 0

            for (let x in this.filteredCategories) {
                sum = 0
                labels.push(this.filteredCategories[x][0].expenseType.name)
                for (let y in this.filteredCategories[x]) {
                    sum += this.filteredCategories[x][y].total
                }
                data.push(sum)
            }

            return {
                datasets: [{
                    data: data,
                    backgroundColor: ['#4aa4a3', '#492d8a', '#e7632a','#ed0b4b','#62bdf9', '#3375bf', '#e0f96a','#8ef96d'],
                }],
                labels: labels
            }
        },
        expensesTotal() {
            let sum = 0
            for (let x in this.expenses.data) {
                sum += this.expenses.data[x].total
            }
            return sum
        },


    },
    methods: {
        findRequest() {
            this.form
                .post(this.route('request-forms.find', {code: this.form.requestCode}), {
                    onSuccess: () => this.findRequestDialog = false,
                })
        },
        formatDateTime(date) {
            if (!date) return 'NA'
            const options = {year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'}
            return date.toLocaleString(undefined, options)
        },
        getInventoryStatus(inventory) {
            return this.getInventoryStatusMessage(this.getStatus(inventory))
        },
        getStatus(inventory) {
            let status = 0;
            if (inventory.availableStock <= 0) {
                status = 0
            } else if (inventory.availableStock <= inventory.threshold) {
                status = 1
            } else {
                status = 2
            }
            return status
        },
        navigateToAccount(code) {
      this.$inertia.get(this.route('accounts.show', {'code': code}))
    },


    }
}
</script>
