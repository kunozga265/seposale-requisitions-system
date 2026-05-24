<template>
  <app-layout>
    <template #header>
      Edit Zone
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('clients.index')"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Zones
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('clients.show',{id:client.data.id})"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
             {{ zone.data.name }}
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
           Edit
          </span>
        </div>
      </li>
    </template>

     <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="page-section">
                        <!-- <div class="page-section-header">
                            <div class="page-section-title">
                                Details
                            </div>
                        </div> -->
                        <div class="page-section-content flex justify-center">

                            <div class="card w-full sm:max-w-md md:max-w-3xl">

                                <jet-validation-errors class="mb-4" />

                                <div class="grid grid-cols-1 md:grid-cols-2">




                                    <div class="p-2 mb-2 md:col-span-2">
                                        <jet-label for="name" value="Name" />
                                        <jet-input id="name" type="text" class="block w-full" v-model="form.name"
                                            autocomplete="seposale-customer-zone-name" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="level" value="Level" />
                                        <jet-input id="level" type="number" class="block w-full"
                                            v-model="form.level"
                                            autocomplete="seposale-customer-phone-number-other" />
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="cost" value="Extra Transport" />
                                        <jet-input id="cost" type="number" class="block w-full"
                                            v-model="form.cost"
                                            autocomplete="seposale-customer-phone-number-other" />
                                    </div>

                                </div>

                                <div class="p-6">

                                    <ZonePicker @zone-saved="handleZoneSaved" />

                                    <div v-if="form.coordinates.length > 0">
                                        <h3>Saved Zone Polygon Coordinates:</h3>
                                        <ul>
                                            <li v-for="(point, index) in form.coordinates" :key="index">
                                                Point {{ index + 1 }}: Lat {{ point.lat.toFixed(5) }}, Lng {{
                                                    point.lng.toFixed(5) }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="fixed right-6 bottom-6 md:right-10 md:bottom-10">
                        <div v-show="!validation" id="toast-danger"
                            class="flex items-center w-full max-w-xs p-4 mb-4 text-red-700 bg-red-100 rounded-lg shadow dark:text-red-400 dark:bg-red-800"
                            role="alert">
                            <div
                                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                                <i class="mdi mdi-alert-circle text-2xl"></i>
                            </div>
                            <div class="ml-3 text-sm font-normal">{{ error }}</div>
                        </div>
                    </div>


                    <div class="text-center mt-8">
                        <div v-show="validation">
                            <jet-button class="ml-4 text-center" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Create
                            </jet-button>
                            <div class="text-gray-600 text-sm">Please confirm all details before submission</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from "@/Jetstream/Button";
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import SecondaryButton from '@/Jetstream/SecondaryButton'
import pdf from 'vue-pdf-embed/dist/vue2-pdf-embed'
import PrimaryButton from "@/Jetstream/Button.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";
import { Money } from "v-money";
import WhatsappLabel from "@/Components/WhatsappLabel.vue";

import ZonePicker from '../../Components/ZonePicker.vue';
import axios from 'axios';

export default {
    props: ["products", "zones", "zoneTypes"],
    components: {
        WhatsappLabel,
        Money,
        DialogModal, PrimaryButton,
        AppLayout,
        JetInput,
        JetLabel,
        JetButton,
        JetValidationErrors,
        SecondaryButton,
        pdf,
        ZonePicker,
        AppLayout
    },
    data() {
        return {
            checkZone: "existing",
            addRecordDialog: false,
            productIndex: -1,
            zoneIndex: -1,
            addRecordUnits: "",
            addRecordQuantity: 0,
            addRecordUnitCost: 0,
            form: this.$inertia.form({
                name: '',
                level: 1,
                cost: 0,
                coordinates: []
            }),
            error: '',
        }
    },
    created() {


    },
    computed: {
        validation() {
            if (this.form.name.length === 0) {
                this.error = "Enter zone name"
                return false
            } else if (this.form.level <= 0) {
                this.error = "Please enter level"
                return false
            } else if (this.form.cost < 0) {
                this.error = "Please enter extra cost"
                return false
            } else if (this.form.coordinates.length == 0) {
                this.error = "Please select and save zone coordinates"
                return false
            }
            return true

        },
    },
    watch: {

    },
    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    
                }))
                .post(this.route('zones.store'))
        },

        handleZoneSaved(polygonData) {
            console.log("Polygon data received:", polygonData);
            this.form.coordinates = polygonData;
        },
        // async submitToBackend() {
        //     try {
        //         await axios.post('/api/zones', {
        //             name: this.form.zone_name,
        //             polygon: this.form.coordinates
        //         });
        //         alert('Zone saved successfully!');
        //     } catch (error) {
        //         console.error(error);
        //     }
        // }
    }

}
</script>
