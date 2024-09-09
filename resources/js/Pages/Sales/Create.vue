<template>
    <app-layout>
        <template #header>
            New Sale
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
                    <a :href="route('sales.index')"
                       class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Sales
                    </a>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                       New
                    </span>
                </div>
            </li>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Customer Details
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="card w-full sm:max-w-md md:max-w-3xl">

                                <jet-validation-errors class="mb-4"/>

                                <div class="flex items-center mb-4">
                                    <input id="default-radio-1" type="radio" value="existing" v-model="checkClient"
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="default-radio-1"
                                           class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Existing</label>

                                    <input checked id="default-radio-2" type="radio" value="new" v-model="checkClient"
                                           class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="default-radio-2"
                                           class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">New</label>
                                </div>
                                <div v-if="checkClient === 'existing'">

                                    <div class="p-2 mb-2">
                                        <jet-label for="clientIndex" value="Client"/>
                                        <select v-model="clientIndex" id="clientIndex"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                required>
                                            <option value="-1">Select Client</option>
                                            <option
                                                v-for="(client,index) in clients.data"
                                                :value="index"
                                                :key="index"
                                            >
                                                {{ client.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div v-if="client != null" class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="p-2 mb-2">
                                            <jet-label for="phoneNumber" value="Phone Number"/>
                                            <jet-input id="phoneNumber" type="text" class="block w-full"
                                                       v-model="client.phoneNumber"
                                                       autocomplete="seposale-customer-phone-number" disabled/>
                                        </div>
                                        <div class="p-2 mb-2">
                                            <jet-label for="email" value="Email"/>
                                            <jet-input id="email" type="email" class="block w-full"
                                                       v-model="client.email"
                                                       autocomplete="seposale-customer-email" disabled/>
                                        </div>
                                        <div class="p-2 mb-2 md:col-span-2">
                                            <jet-label for="address" value="Address"/>
                                            <jet-input id="address" type="text" class="block w-full"
                                                       v-model="client.address"
                                                       autocomplete="seposale-customer-address" disabled/>
                                        </div>
                                    </div>

                                </div>
                                <div v-else class="grid grid-cols-1 md:grid-cols-2">

                                    <div class="p-2 mb-2 md:col-span-2">
                                        <jet-label for="name" value="Name"/>
                                        <jet-input id="name" type="text" class="block w-full"
                                                   v-model="form.name"
                                                   autocomplete="seposale-customer-name"/>
                                    </div>

                                    <div class="p-2 mb-2">
                                        <jet-label for="phoneNumber" value="Phone Number"/>
                                        <jet-input id="phoneNumber" type="text" class="block w-full"
                                                   v-model="form.phoneNumber"
                                                   autocomplete="seposale-customer-phone-number"/>
                                    </div>
                                    <div class="p-2 mb-2">
                                        <jet-label for="email" value="Email"/>
                                        <jet-input id="email" type="email" class="block w-full"
                                                   v-model="form.email"
                                                   autocomplete="seposale-customer-email"/>
                                    </div>
                                    <div class="p-2 mb-2 md:col-span-2">
                                        <jet-label for="address" value="Address"/>
                                        <jet-input id="address" type="text" class="block w-full"
                                                   v-model="form.address"
                                                   autocomplete="seposale-customer-address"/>
                                    </div>


                                </div>

                                <div class="mb-4">
<!--                                    <jet-label for="lastRefillDate" value="Backdate" />-->
                                    <div class="flex items-center mb-2">
                                        <input checked id="backdate" type="checkbox" value="" v-model="backdateCheck" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="backdate" class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Backdate</label>
                                    </div>
                                    <vue-date-time-picker
                                        v-if="backdateCheck"
                                        color="#1a56db"
                                        v-model="date"
                                        :max-date="maxDate"

                                    />
                                </div>


                            </div>
                        </div>
                    </div>


                  <div class="page-section">
                    <div class="page-section-header">
                      <div class="page-section-title">
                        Site Details
                      </div>
                    </div>
                    <div class="page-section-content flex justify-center">

                      <div class="card w-full sm:max-w-md md:max-w-3xl">
                        <div class="grid grid-cols-1 md:grid-cols-2">
                          <div class="p-2 mb-2">
                            <jet-label for="location" value="Location"/>
                            <jet-input id="location" type="text" class="block w-full"
                                       v-model="form.location"
                                       autocomplete="seposale-location"/>
                          </div>
                          <div class="p-2 mb-2">
                            <jet-label for="recipientName" value="Recipient Name"/>
                            <jet-input id="recipientName" type="text" class="block w-full"
                                       v-model="form.recipientName"
                                       autocomplete="seposale-recipient-name"/>
                          </div>
                          <div class="p-2 mb-2">
                            <jet-label for="recipientProfession" value="Recipient Profession"/>
                            <jet-input id="recipientProfession" type="text" class="block w-full"
                                       v-model="form.recipientProfession"
                                       autocomplete="seposale-recipient-profession"/>
                          </div>
                          <div class="p-2 mb-2">
                            <jet-label for="recipientPhoneNumber" value="Recipient Phone Number"/>
                            <jet-input id="recipientPhoneNumber" type="text" class="block w-full"
                                       v-model="form.recipientPhoneNumber"
                                       autocomplete="seposale-recipient-phone-number"/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>


                  <div class="page-section">
                        <div class="page-section-header">
                            <div class="page-section-title">
                                Products and Services
                            </div>
                        </div>
                        <div class="page-section-content flex justify-center">

                            <div class="card w-full sm:max-w-md md:max-w-3xl">

                                <div class="p-2 mb-2 relative overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="heading-font">

                                            </th>
                                            <th scope="col" class="heading-font">
                                                Details
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Units
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Quantity
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Unit Cost
                                            </th>
                                            <th scope="col" class="heading-font">
                                                Total Cost
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700"
                                            v-for="(info, index) in form.information" :key="index">
                                            <th scope="row" class="px-2">
                                                <i @click="removeRecord(index)"
                                                   class="mdi mdi-close-circle text-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 cursor"></i>
                                            </th>
                                            <th scope="row"
                                                class="py-2 pr-1 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                <jet-input type="text" class="block w-full" v-model="info.details"/>
                                            </th>
                                            <td class="py-2 pr-1">
                                                <jet-input type="text" class="block w-full" v-model="info.units"/>
                                            </td>
                                            <td class="py-2 pr-1">
                                                <jet-input type="number" class="block w-full" v-model="info.quantity"/>
                                            </td>
                                            <td class="py-2 pr-1">
                                                <jet-input type="number" class="block w-full" v-model="info.unitCost"/>
                                            </td>
                                            <td class="py-2 pr-1">
                                                <div
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                    {{ numberWithCommas((info.quantity * info.unitCost).toFixed(2)) }}
                                                </div>
                                                <!--                                                <jet-input type="text" class="block w-full" v-model="info.totalCost" value="23" />-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="mt-2 ml-2 flex justify-start items-center">
                                        <div @click="addRecord" class="flex justify-start items-center cursor">
                                            <div>
                                                <i class="mdi mdi-plus-circle text-blue-600"></i>
                                            </div>
                                            <div class="ml-2 text-blue-600 text-sm">
                                                Add Blank
                                            </div>
                                        </div>
                                        <div @click="addRecordDialog = true"
                                             class="ml-3 flex justify-start items-center cursor">
                                            <div>
                                                <i class="mdi mdi-plus-circle text-blue-600"></i>
                                            </div>
                                            <div class="ml-2 text-blue-600 text-sm">
                                                Add Product
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div v-if="isNaN(totalCost)"
                                             class="text-red-600 uppercase font-semibold heading-font">
                                            Enter valid total cost
                                        </div>
                                        <div v-else class="flex justify-center items-center ">
                                            <div class="currency ">MK</div>
                                            <div class="total">{{ numberWithCommas(totalCost) }}</div>
                                        </div>
                                        <div class="text-gray-600 text-xs">Total Cost</div>
                                    </div>
                                    <!-- <div class="mt-4 text-gray-600 text-sm">
                                        I accept the advances listed above and I acknowledge that I must return the full amount or account for it on a company expense form within 3 days of returning to Geoserve from this assignment.
                                    </div> -->
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

        <dialog-modal :show="addRecordDialog" @close="cancelAddRecord">
            <template #title>
                Add Product
            </template>

            <template #content>
                <!--          <div class="mb-2">-->
                <!--            Are you sure you want to approve this request?-->
                <!--          </div>-->
                <div class="mb-4">
                    <jet-label for="product" value="Select Product"/>
                    <select v-model="productIndex" id="product"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required>
                        <option value="-1">Blank</option>

                        <option v-for="(product, index) in allProducts" :value="index" :key="index">
                            {{ product.name }} - {{ product.description }}
                        </option>
                    </select>
                </div>

                <div v-if="productIndex !== -1">
                    <div class="mb-4">
                        <jet-label for="units" value="Units"/>
                        <jet-input type="text" class="block w-full" v-model="addRecordUnits"/>
                    </div>
                    <div class="mb-4">
                        <jet-label for="units" value="Unit Cost"/>
                        <jet-input type="number" class="block w-full" v-model="addRecordUnitCost"/>
                    </div>
                    <div class="mb-4">
                        <jet-label for="quantity" value="Quantity"/>
                        <jet-input type="number" class="block w-full" v-model="addRecordQuantity"/>
                    </div>

                    <div class="mb-4">
                        <jet-label for="total" value="Total"/>
                        <div
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            {{ numberWithCommas(addRecordTotal.toFixed(2)) }}
                        </div>
                    </div>
                </div>



            </template>

            <template #footer>
                <secondary-button @click.native="cancelAddRecord">
                    Cancel
                </secondary-button>

                <primary-button class="ml-2" @click.native="addRecord">
                    Add
                </primary-button>
            </template>
        </dialog-modal>
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

export default {
    props: ["products", "clients"],
    components: {
        DialogModal, PrimaryButton,
        AppLayout,
        JetInput,
        JetLabel,
        JetButton,
        JetValidationErrors,
        SecondaryButton,
        pdf,
    },
    data() {
        return {
            checkClient: "existing",
            addRecordDialog: false,

            addRecordUnits: "",
            addRecordQuantity: 0,
            addRecordUnitCost: 0,

            productIndex: -1,
            clientIndex: -1,

            backdateCheck:false,
          date: null,
            maxDate: new Date().toISOString().substr(0, 10),
            form: this.$inertia.form({

                name: '',
                phoneNumber: '',
                email: '',
                address: '',
                location: '',
              recipientName: '',
              recipientProfession: '',
              recipientPhoneNumber: '',
                information: [

                ],

            }),
            quotes: [],
            error: '',


        }
    },
    created() {


    },
    computed: {
        addRecordTotal() {
            return this.addRecordUnitCost * this.addRecordQuantity
        },
        client() {
            if (this.clientIndex === -1 || this.clientIndex === '-1')
                return null
            else
                return this.clients.data[this.clientIndex]
        },
        allProducts() {
            let products = [];

            for (let x in this.products.data) {
                for (let y in this.products.data[x].variants) {
                    products.push({
                        "id": this.products.data[x].variants[y].id,
                        "name": this.products.data[x].name,
                        "description": this.products.data[x].variants[y].description,
                        "unit": this.products.data[x].variants[y].unit,
                        "cost": this.products.data[x].variants[y].cost,
                        "quantity": this.products.data[x].variants[y].quantity,
                    })
                }
            }

            return products;
        },
        product(){
          return  this.allProducts[this.productIndex];
        },
        totalCost() {
            let totalCost = 0
            let currentTotal = 0
            for (let x in this.form.information) {
                currentTotal = parseFloat(this.form.information[x].quantity * this.form.information[x].unitCost)
                totalCost += currentTotal
                this.form.information[x].totalCost = parseFloat(currentTotal.toFixed(2))

                //convert to numbers
                this.form.information[x].quantity = parseFloat(this.form.information[x].quantity)
                this.form.information[x].unitCost = parseFloat(this.form.information[x].unitCost)

            }
            return parseFloat(totalCost.toFixed(2))
        },
        quoteFiles() {
            let files = []
            for (let x in this.quotes)
                files.push(this.quotes[x].file)

            return files
        },
        validation() {

            if (this.checkClient === "new") {
                if (this.form.name.length === 0) {
                    this.error = "Enter customer name"
                    return false
                }
            }else  {
                if (parseInt(this.clientIndex) < 0 || this.client == null) {
                    this.error = "Select client"
                    return false
                }
            }

            if (this.backdateCheck) {
                if (this.date == null) {
                    this.error = "Enter a backdate"
                    return false
                }
            }

            if (isNaN(this.totalCost)) {
                this.error = "Enter valid product and services details"
                return false
            } else if (this.totalCost <= 0) {
                this.error = "Enter products and services"
                return false
            } else
                return true

        },
        saleDate() {
            return this.date ? (new Date(this.date).getTime()) / 1000 : null
        }
    },
    watch: {
        checkClient() {
            this.clientIndex = -1
        },
        backdateCheck() {
            this.date = null
        },
        productIndex(){
            if(this.productIndex === -1 || this.productIndex === "-1"){
                this.addRecordUnits = ""
                this.addRecordQuantity = 0
                this.addRecordUnitCost = 0
            }else{
                this.addRecordUnits = this.product.unit
                this.addRecordQuantity = this.product.quantity
                this.addRecordUnitCost = this.product.cost/this.product.quantity
            }
        }
    },
    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    total: this.totalCost,
                    quotes: this.quoteFiles,
                    products:this.form.information,
                    date:this.saleDate,
                    client_id:this.client == null ? null : this.client.id,
                  recipient_name: this.form.recipientName,
                  recipient_profession: this.form.recipientProfession,
                  recipient_phone_number: this.form.recipientPhoneNumber,
                }))
                .post(this.route('sales.store'))
        },
        addRecord() {

            if (parseInt(this.productIndex) < 0) {
                this.form.information.push({
                    "id": 0,
                    "details": '',
                    "units": '',
                    "quantity": 0,
                    "unitCost": 0,
                    "totalCost": 0,
                })
            } else {
                const product = this.allProducts[this.productIndex]
                const name = this.product.description == null || this.product.description === "" ? product.name : product.name + " - " + product.description
                this.form.information.push({
                    "id": product.id,
                    "details": name,
                    "units": this.addRecordUnits,
                    "quantity": this.addRecordQuantity,
                    "unitCost": this.addRecordUnitCost,
                    "totalCost": this.addRecordTotal,
                })
            }

            this.productIndex = -1
            this.addRecordDialog = false

        },
        cancelAddRecord() {
            this.productIndex = -1
            this.addRecordDialog = false
        },

        removeRecord(index) {
            this.form.information.splice(index, 1)
        },
        fileUpload(file) {
            const reader = new FileReader();
            if (file) {
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
                        type: "QUOTE",
                        file: e.target.result
                    }).then(res => {
                        this.quotes.push({
                            'file': res.data.file,
                            'ext': res.data.ext,
                        })
                        document.getElementById('quote').value = ""
                    }).catch(function (res) {
                        // this.form.errors.push(res.data.message)
                    })
                };
            }
        },
        removeQuote(index) {
            const file = this.quotes[index].file
            //delete online
            axios.post(this.$page.props.publicPath + "api/1.0.0/upload/delete", {
                'file': file
            })
            this.quotes.splice(index, 1)
        },
    }

}
</script>
