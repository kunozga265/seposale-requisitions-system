<template>
    <app-layout>
        <template #header>
            Accounting Centre
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
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Accounting
                        Centre</span>
                </div>
            </li>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                <inertia-link :href="route('settings.balance-sheet')" class="card">
                    <div class="flex justify-between items-center">

                        <div class="ml-3">
                            <div class="text-sm">
                                Balance Sheet
                            </div>
                            <div class="text-xs text-gray-400">
                                Create and manage associated accounts
                            </div>

                        </div>

                        <div>
                            <i class="mdi mdi-arrow-right text-xl"></i>
                        </div>
                    </div>
                </inertia-link>

                <inertia-link :href="route('settings.income-statement')" class="card">
                    <div class="flex justify-between items-center">

                        <div class="ml-3">
                            <div class="text-sm">
                                Income Statement
                            </div>
                            <div class="text-xs text-gray-400">
                                Create and manage associated accounts
                            </div>
                        </div>
                        <div>
                            <i class="mdi mdi-arrow-right text-xl"></i>
                        </div>
                    </div>
                </inertia-link>

                <div class="card">
                    <div class="flex justify-between items-center">
                        <div class="ml-3">
                            <div class="text-sm">
                                Trial Balance
                            </div>
                            <div class="text-xs text-gray-400">
                                Verify the accuracy of your accounts
                            </div>

                        </div>
                        <div>
                            <i class="mdi mdi-arrow-right text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- <jet-section-border /> -->

                <div class="page-section-header">
                    <div class="page-section-title">
                        One Stop Shops
                    </div>
                </div>
                <div class="page-section-content">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <inertia-link :href="route('settings.site', { code: site.code })" v-for="(site, index) in sites"
                            class="card">
                            <div class="flex justify-between items-center">

                                <div class="ml-3 flex items-center">
                                    <div class="">
                                        <span
                                            class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                            {{ index + 1 }}
                                        </span>

                                    </div>
                                    <div class="ml-3 text-sm">
                                        <div>
                                            {{ site.name }} Site
                                        </div>
                                        <div class="text-xs text-gray-400 text-capitalize">

                                            {{ site.code }}
                                        </div>
                                    </div>


                                </div>

                                <div>
                                    <i class="mdi mdi-arrow-right text-xl"></i>
                                </div>
                            </div>
                        </inertia-link>

                </div>


            </div>


        </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetSectionBorder from '@/Jetstream/SectionBorder'

export default {
    props: ['sessions', 'sites'],

    components: {
        AppLayout,
        JetSectionBorder,
    },
}
</script>
