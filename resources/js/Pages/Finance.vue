<template>
    <app-layout>
        <template #header>
            Requisitions
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
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        Accounts
                    </span>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        Requisitions
                    </span>
                </div>
            </li>
        </template>

<!--        <template #actions>
            <button type="submit" class="text-center inline-flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                New Request
            </button>
        </template>-->

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Overview
                        </div>
                    </div>
                    <div class="page-section-content grid grid-cols-1 lg:grid-cols-2 gap-2">
                        <div class="card flex justify-center">
                            <div class="h-64 w-64 sm:h-80 sm:w-80 mx-auto relative">
                                <div class="pie-chart absolute font-bold flex justify-center items-center">
                                    <div class="heading">
                                        <div class="font-bold heading-font text-center">{{ totalRequests }}</div>
                                        <div class="xl font-bold heading-font text-center">{{ totalRequests==1?'Request':'Requests' }}</div>
                                    </div>
                                </div>
                                <PieChart
                                    :chart-options="typesChartOptions"
                                    :chart-data="typesData"
                                    chart-id="types"
                                    dataset-id-key="types"
                                />
                            </div>
                        </div>
                        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:block">
                            <div class="card">
                                <div class="flex justify-start items-center">
                                    <div v-if="awaitingInitiationCount>0" class="overview-chart relative">
                                        <div style="font-size:12px;" class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((awaitingInitiationCount/totalRequests)*100) }}%
                                        </div>
                                        <DoughnutChart
                                            :chart-options="chartOptions"
                                            :chart-data="awaitingInitiationData"
                                            chart-id="initiated"
                                            dataset-id-key="initiated"
                                        />
                                    </div>
                                    <div class="ml-4">
                                        <div class="heading-font" style="font-weight: 600;">Awaiting Initiation</div>
                                        <div class="text-sm text-gray-400">{{ awaitingInitiationCount }} {{ awaitingInitiationCount ==1?'Request':'Requests'}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="flex justify-start items-center">
                                    <div v-if="awaitingReconciliationCount>0" class="overview-chart relative">
                                        <div style="font-size:12px;" class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((awaitingReconciliationCount/totalRequests)*100) }}%
                                        </div>
                                        <DoughnutChart
                                            :chart-options="chartOptions"
                                            :chart-data="awaitingReconcilationData"
                                            chart-id="initiated"
                                            dataset-id-key="initiated"
                                        />
                                    </div>
                                    <div class="ml-4">
                                        <div class="heading-font" style="font-weight: 600;">Awaiting Reconciliation</div>
                                        <div class="text-sm text-gray-400">{{ awaitingReconciliationCount }} {{ awaitingReconciliationCount ==1?'Request':'Requests'}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card ">
                                <div class="flex justify-start items-center">
                                    <div v-if="reconciledCount>0" class="overview-chart relative">
                                        <div style="font-size:12px;" class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((reconciledCount/totalRequests)*100) }}%
                                        </div>
                                        <DoughnutChart
                                            :chart-options="chartOptions"
                                            :chart-data="reconciledData"
                                            chart-id="reconciled"
                                            dataset-id-key="reconciled"
                                        />
                                    </div>
                                    <div class="ml-4">
                                        <div class="heading-font" style="font-weight: 600;">Reconciled</div>
                                        <div class="text-sm text-gray-400">{{ reconciledCount }} {{ reconciledCount ==1?'Request':'Requests'}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Awaiting Initiation
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-2">
                            <request
                                v-for="(request,index) in awaitingInitiation.data"
                                :key="index"
                                :request="request"
                            />
                            </div>
                            <div v-if="awaitingInitiation.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
                                No Requests Awaiting Initiation
                            </div>

                        </div>
                    </div>
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Awaiting Reconciliation
                            </div>
                        </div>
                        <div class="page-section-content">
                            <div class="grid grid-cols-1 md:grid-cols-2">
                            <request
                                v-for="(request,index) in awaitingReconciliation.data"
                                :key="index"
                                :request="request"
                            />
                            </div>
                            <div v-if="awaitingReconciliation.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
                                No Requests Awaiting Reconciliation
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Reconciled Requests
                        </div>
                    </div>
                    <div class="page-section-content grid grid-cols-1 md:grid-cols-2">
                        <request
                            v-for="(request,index) in reconciled.data"
                            :key="index"
                            :request="request"
                        />

                    </div>
                    <div v-if="reconciled.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
                        No Requests Reconciled
                    </div>
                    <div v-else>
                        <div class="flex flex-col items-center">
                            <!-- Help text -->
                            <span class="text-sm text-gray-700 dark:text-gray-400">
                                    Showing <span class="font-semibold text-gray-900 dark:text-white">{{ reconciled.meta.from }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ reconciled.meta.to }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ reconciled.meta.total }}</span> Requests
                                </span>
                            <div class="inline-flex mt-2 xs:mt-0">
                                <!-- Previous Button -->
                                <a :href="reconciled.links.prev" class="inline-flex items-center px-4 py-2 mr-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <svg aria-hidden="true" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                                    Previous
                                </a>
                                <a :href="reconciled.links.next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    Next
                                    <svg aria-hidden="true" class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </a>
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

    export default {
        props:[
            'totalRequests',
            'pettyCashRequestsCount',
            'requisitionRequestsCount',
            'vehicleMaintenanceRequestsCount',
            'fuelRequestsCount',
            'awaitingInitiationCount',
            'awaitingReconciliationCount',
            'reconciledCount',
            'awaitingInitiation',
            'awaitingReconciliation',
            'reconciled',
        ],
        components: {
            AppLayout,
            DoughnutChart,
            PieChart,
            Request
        },
        data(){
          return{
              chartOptions:{
                  plugins: {
                      tooltip: {
                          enabled: false
                      },
                      legend:{
                          display:false
                      }
                  }, cutout:20
              },
              awaitingInitiationData:{
                  datasets: [{
                      data: [this.awaitingInitiationCount, (this.totalRequests - this.awaitingInitiationCount)],
                      backgroundColor: ['#22c55e','#e3ebf6'],
                  }],
              },
              awaitingReconcilationData:{
                  datasets: [{
                      data: [this.awaitingReconciliationCount, (this.totalRequests - this.awaitingReconciliationCount)],
                      backgroundColor: ['#22c55e','#e3ebf6'],
                  }],
              },
              reconciledData:{
                  datasets: [{
                      data: [this.reconciledCount, (this.totalRequests - this.reconciledCount)],
                      backgroundColor: ['#303840','#e3ebf6'],
                  }],
              },
              typesData:{
                  datasets: [{
                      data: [
                          this.pettyCashRequestsCount,
                          this.requisitionRequestsCount,
                         // this.vehicleMaintenanceRequestsCount,
                        //  this.fuelRequestsCount
                      ],
                      backgroundColor: ['#1a56db','#ed0b4b','#b1bbc9','#e3ebf6'],
                  }],
                   labels: ['Petty Cash', 'Requisitions']
              },
              typesChartOptions:{
                  plugins: {
                      tooltip: {
                          enabled: true
                      },
                      legend:{
                          display:true,
                          position:'bottom'
                      }
                  },
                  cutout:70
              },
          }
        },

    }
</script>
