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
              <div class="card">
                <div class="p-2 mb-2 relative ">
                  <div  class="p-2 pb-4 heading-font text-left relative">
                    <button v-show="form.name.length > 0" @click="form.name = ''"
                            class="absolute top-5 right-4 h-5 w-5 close-field rounded-full bg-white p-1 hover:bg-gray-300 flex justify-center items-center transition ease-out duration-500">
                      <i class="mdi mdi-close"></i>
                    </button>
                    <jet-input id="code" type="text" class="block w-full"
                               placeholder="Search Name..."
                               v-model="form.name"
                               autocomplete="seposale-filter-code"/>

                  </div>
                  <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                    <thead
                        class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                    <tr>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Name</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Phone Number</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Email</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Address</th>
                    </tr>

                    </thead>
                    <tbody class="pt-8">

                    <tr
                        @click="navigateToClient(client.id)"
                        class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(client,index) in filteredClients" :key="index">
                      <td class="p-2 text-left ">{{ client.name }}</td>
                      <td class="p-2 text-left ">{{ client.phoneNumber }}</td>
                      <td class="p-2 text-left ">{{ client.email }}</td>
                      <td class="p-2 text-left ">{{ client.address }}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>

              </div>
<!--              <pagination :object="clients"/>-->
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
import SaleStatus from "@/Components/SaleStatus.vue";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import JetInput from "@/Jetstream/Input.vue";

export default {
  props: [
    'clients',
  ],
  components: {
    JetInput, DeliveryStatus, SaleStatus,
    Pagination,
    RequestStatus,
    AppLayout,
    PrimaryButton
  },
  data() {
    return {
      form:this.$inertia.form({
        name:""
      })
    }
  },
  computed:{
    filteredClients() {
      let filtered = this.clients.data

      /* Filter Sales By Client*/
      if (this.form.name.length !== 0) {
        filtered = (filtered).filter((client) => {
          return client.name.toLowerCase().includes(this.form.name.toLowerCase())
        })
      }

      return filtered
    },
  },
  methods: {
    navigateToClient(id) {
      this.$inertia.get(this.route('clients.show', {'id': id}))
    }
  }
}
</script>
