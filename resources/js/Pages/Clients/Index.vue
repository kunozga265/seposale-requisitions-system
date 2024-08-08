<template>
  <app-layout>
    <template #header>
      Clients
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
              class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Clients</span>
        </div>
      </li>
    </template>

    <template #actions>
            <inertia-link :href="route('clients.create')">
              <primary-button>
                New Client
              </primary-button>
            </inertia-link>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              All
            </div>
          </div>
          <div class="page-section-content">

            <div v-if="clients.data.length === 0"
                 class="text-center text-gray-400 md:col-span-2 text-sm">
              No Clients Found
            </div>
            <div v-else>
              <div class="">
                <div
                    class="user"
                    v-for="(client,index) in clients.data"
                    :key="index"
                    @click="navigateToClient(client.id)"
                >
                  <div class="flex justify-between items-center">
                    <div class="">
                      <div class="name">{{ client.name }}</div>
                      <div class="position">{{ client.phoneNumber }}</div>
                    </div>
                    <div
                        class=" h-10 w-10 flex justify-center items-center rounded-full text-sm bg-blue-50 text-blue-600 border-blue-600 font-bold cursor">
                      {{ client.sales.length }}
                    </div>
                  </div>
                  <div>
                                <span
                                    v-if="client.email"
                                    class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase"
                                >
                                    {{ client.email }}
                                </span>
                    <span
                        v-if="client.address"
                        class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase"
                    >
                                    {{ client.address }}
                                </span>
                  </div>
                </div>


              </div>
              <pagination :object="clients"/>
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
import RequestStatus from "@/Components/RequestStatus.vue";
import Pagination from "@/Components/Pagination.vue";

export default {
  props: [
    'clients',
  ],
  components: {
    Pagination,
    RequestStatus,
    AppLayout,
    PrimaryButton
  },
  data() {
    return {}
  },
  methods: {
    navigateToClient(id) {
      this.$inertia.get(this.route('clients.show', {'id': id}))
    }
  }
}
</script>
