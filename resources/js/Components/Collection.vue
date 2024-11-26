<template>
    <div @click="showDialog=true" class="flex justify-start items-center" :class="getStatusClass()">
        <div>
            <i class="mdi text-xl" :class="getStatusIcon()"></i>
        </div>
        <div class="ml-3 text-sm letter-spacing-normal" style="letter-spacing: normal">
            {{ getStatusMessage(this.product.collectionStatus) }}
        </div>

        <dialog-modal :show="showDialog" @close="showDialog=false">

            <template #title>
                <div class="flex justify-between">
                    {{ product.name }}
                </div>

            </template>

            <template #content>
                <div class="delivery-profile grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="mb-4">
                        <div class="text-mute text-sm">
                            Client
                        </div>
                        <div class="text-gray-500 text-sm">
                            {{ client.name }}
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="text-mute text-sm">
                            Phone Number
                        </div>
                        <div class="text-gray-500 text-sm">
                            {{ client.phone_number }}
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="text-mute text-sm">
                            Quantity Collected
                        </div>
                        <!--                        <div class="currency ">MK</div>-->
                        <div>
                            <span class="total">{{
                                    numberWithCommas(product.collected)
                                }}/{{ numberWithCommas(product.quantity) }}</span>

                        </div>
                    </div>

                    <div v-show="product.collectionStatus < 2" class="mb-4">
                        <div class="text-mute text-sm">
                            Quantity
                        </div>
                        <jet-input min="0" :max="quantityBalance" type="number" step="0.01" class="block w-full" v-model="form.quantity"/>
                    </div>

                    <div v-show="product.collectionStatus < 2" class="mb-4">
                        <div class="text-mute text-sm">
                            Collected By
                        </div>
                        <jet-input type="text" class="block w-full" v-model="form.collectedBy"/>
                        <div class="text-red-500 text-xs" v-if="form.errors.collected_by">Required
                        </div>
                    </div>

                    <div v-show="product.collectionStatus < 2" class="mb-4">
                        <div class="text-mute text-sm">
                            Phone Number
                        </div>
                        <jet-input type="number" class="block w-full" v-model="form.phoneNumber"/>
                        <div class="text-red-500 text-xs" v-if="form.errors.phone_number">Required
                        </div>
                    </div>

                  <div v-if="product.collectionStatus < 2" class="mb-4 md:col-span-2">
                    <div class="text-mute text-sm mb-1">
                      Upload Collection Receipt
                    </div>
                    <input type="file" id="photo" @input="photoUpload($event.target.files[0])"
                           class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                  </div>


                    <div class="mb-4 md:col-span-2">
                        <div @click="showLogs = !showLogs" class="text-mute transition-all ease-in">
                            <i class="text-lg mdi" :class="{'mdi-menu-down':!showLogs, 'mdi-menu-up': showLogs}"></i> Collections
                            ({{ product.collections.length }})
                        </div>
                        <div v-show="showLogs" class=" transition-all ease-in">
                            <div class="ml-5 mb-1 flex text-xs text-mute" v-for="(collection, index) in  product.collections" :key="index">
                                <div style="width:120px" class="text-gray-500">{{ getDate(collection.date*1000) }}:</div>
                                <div class="">{{ collection.message }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </template>

            <template #footer>
                <!--                <danger-button @click.native="showDialog=false">-->
                <!--                    Cancel-->
                <!--                </danger-button>-->
                <secondary-button @click.native="showDialog=false">
                    close
                </secondary-button>
                <primary-button v-show="product.collectionStatus < 2" @click.native="submit">
                    <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
                         viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="#E5E7EB"/>
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentColor"/>
                    </svg>
                    Submit
                </primary-button>
            </template>
        </dialog-modal>

    </div>





</template>

<script>

import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetInput from "@/Jetstream/Input.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";
import PrimaryButton from "@/Jetstream/Button.vue";

export default {
    name: "DeliveryStatus",
    components: {PrimaryButton, DialogModal, JetInput, SecondaryButton},
    props: ['product', "client", "isSolo"],
    emits: ['clickEvent'],
    data(){
      return{
          showDialog:false,
          showLogs: true,
          form: this.$inertia.form({
              quantity: 0,
              photo: "",
              collectedBy: "",
              phoneNumber: "",
          }),
          quantityValidation: null,
      }
    },
    computed: {
        quantityBalance() {
            return this.product.quantity - this.product.collected

        },
    },
    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    collected_by: this.form.collectedBy,
                    collected_by_phone_number: this.form.phoneNumber,
                }))
                .post(this.route('collections.store', {'id': this.product.id}), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showDialog = false
                        this.form.quantity = 0
                        this.form.collectedBy = ""
                        this.form.phoneNumber = ""
                        document.getElementById('photo').value = ""
                    },
                })
        },
        getStatusClass() {
            let statusClass = "";

            switch (parseInt(this.product.collectionStatus)) {
                case 0:
                    statusClass = "denied";
                    break;
                case 1:
                    statusClass = "approval-pending";
                    break;
                case 2:
                    statusClass = "approved";
                    break;
                default:
                    statusClass = "closed";
                    break;
            }

            if (this.isSolo) {
                return statusClass + " clear-background";
            } else
                return statusClass;
        },

        getStatusIcon() {
            switch (parseInt(this.product.collectionStatus)) {
                case 0:
                case 3:
                    return "mdi-close-circle";
                case 1:
                    return "mdi-alert-circle";
                case 2:
                    return "mdi-check-circle";
                default:
                    return "";
            }
        },
        getStatusMessage(status){
            switch (parseInt(status)){
                case 0:
                    return "Not Collected";
                case 1:
                    return this.numberWithCommas(this.product.quantity - this.product.collected) + " Remaining";
                case 2:
                    return "Collected";
                case 3:
                    return "Cancelled";
                default:
                    return "";
            }
        },
      photoUpload(file) {
        const reader = new FileReader();
        if (file) {
          reader.readAsDataURL(file);
          reader.onload = (e) => {
            axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
              type: "COLLECTION_NOTE",
              file: e.target.result
            }).then(res => {
              this.form.photo = res.data.file

            }).catch(function (res) {
              // this.form.errors.push(res.data.message)
            })
          };
        }
      },
    }
}
</script>

<style scoped>

</style>
