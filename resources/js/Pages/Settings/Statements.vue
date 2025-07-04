<template>
    <app-layout>
        <template #header>
            Balance Sheet
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
                        Balance Sheet</span>
                </div>
            </li>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                <div v-for="(accountType, index) in accountTypes.data" :key="index">
                    <div v-for="(accountGroup, index) in accountType.accountsGroups" :key="index" class="page-section mb-8">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                [{{ accountGroup.code }}] {{ accountGroup.name }}
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                <div v-for="(account, index) in accountGroup.accounts" class="card mb-0 mb-4">
                                    <div class="flex justify-between items-center">

                                        <div class="ml-3 flex items-center">
                                            <div class="">
                                                <span
                                                    class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                                    {{ account.code }}
                                                </span>

                                            </div>
                                            <div class="ml-3 text-sm">
                                                <div>
                                                    {{ account.name }}
                                                </div>
                                                <div class="text-xs text-gray-400 text-capitalize">

                                                    {{ account.type }}
                                                </div>
                                            </div>


                                        </div>

                                        <div>
                                            <i class="mdi mdi-arrow-right text-xl"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>





                <!-- <jet-section-border /> -->


            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetSectionBorder from '@/Jetstream/SectionBorder'

export default {
    props: ['accountTypes'],

    components: {
        AppLayout,
        JetSectionBorder,
    },
}
</script>
