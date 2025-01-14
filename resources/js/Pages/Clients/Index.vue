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
                      <th scope="col" class="p-2 pb-0 heading-font text-center">Organisation</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left flex items-center">Phone Number
                        <svg class="ml-1" height="24px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                                                                                       xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                                                       viewBox="0 0 512 512" xml:space="preserve">
<path style="fill:#fbfbfb;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
	S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z"/>
                        <path style="fill:#55c76a;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
	c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
	c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z"/>
                        <path style="fill:#FEFEFE;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
	c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
	c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
	c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
	c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
	l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945"/></svg>
                      </th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Other Number</th>
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Email</th>
<!--                      <th scope="col" class="p-2 pb-0 heading-font text-left">Address</th>-->
                      <th scope="col" class="p-2 pb-0 heading-font text-left">Alias</th>
<!--                      <th scope="col" class="p-2 pb-0 heading-font text-left">Actions</th>-->
                    </tr>

                    </thead>
                    <tbody class="pt-8">

                    <tr
                        @click="navigateToClient(client.id)"
                        class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(client,index) in filteredClients" :key="index">
<!--                      <td class="p-2 text-left ">-->
<!--                        <input type="checkbox" @click="selectClient(client.id)"-->
<!--                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"-->
<!--                        >-->
<!--                      </td>-->
                      <td class="p-2 text-left ">{{ client.name }}</td>
                      <td class="p-2 text-center ">

                          <input checked id="backdate" type="checkbox" disabled
                                 v-model="client.organisation"
                                 class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                      </td>
                      <td class="p-2 text-left ">{{ client.phoneNumber }}</td>
                      <td class="p-2 text-left ">{{ client.phoneNumberOther }}</td>
                      <td class="p-2 text-left ">{{ client.email }}</td>
<!--                      <td class="p-2 text-left ">{{ client.address }}</td>-->
                      <td class="p-2 text-left ">{{ client.alias }}</td>
<!--                      <td   @click="navigateToClient(client.id)" class="p-2 text-left cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">-->
<!--                          -->
<!--                      </td>-->
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
      }),
      listOfClients:[]
    }
  },
  computed:{
    filteredClients() {
      let filtered = this.clients.data

      /* Filter Sales By Client*/
      if (this.form.name.length !== 0) {
        filtered = (filtered).filter((client) => {
          if(client.alias){
            return client.name.toLowerCase().includes(this.form.name.toLowerCase()) || client.alias.toLowerCase().includes(this.form.name.toLowerCase())
          }else {
            return client.name.toLowerCase().includes(this.form.name.toLowerCase())
          }
        })
      }

      return filtered
    },
    list(){
      return this.listOfClients
    }
  },
  methods: {
    navigateToClient(id) {
      this.$inertia.get(this.route('clients.show', {'id': id}))
    },
    selectClient(id){
      (this.listOfClients).push(id)
      console.log(id)
    }
  }
}
</script>
