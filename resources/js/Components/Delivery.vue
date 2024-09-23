<template>
  <div>

    <div class="app-card cursor-pointer hover:shadow-lg" @click="showDialog=true">
      <div class="header justify-between items-center border-b">
        <div class="grid grid-cols-1 md:grid-cols-3 w-full items-center">
          <div class="mb-4 md:m-0">

                  <div class="type">{{ productName(delivery.summary) }}</div>


            <!--                          <div class="name">{{ delivery.summary.variant.unit }}</div>-->
            <div class="text-sm">{{ delivery.client.name }}</div>
            <div class="name" v-if="delivery.location"><i class="mdi mdi mdi-map-marker-outline"></i>
              {{ delivery.location }}
            </div>
          </div>


          <div class="mb-4 md:m-0">
            <div class="grid grid-cols-2 align-center">
              <div class="name">Ordered Date:</div>
              <div>
                          <span
                              class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                              getDate(delivery.summary.date * 1000)
                            }}</span>
              </div>
            </div>
            <div class="grid grid-cols-2 align-center">
              <div class="name">Due Date:</div>
              <div>
                <div><span
                    class="date rounded py-1 px-2 bg-gray-200 text-gray-600 text-xs font-bold uppercase">{{
                    getDate(delivery.date * 1000)
                  }}</span></div>
                <!--                              <div class="text-sm text-gray-500">{{ delivery.due }}</div>-->
              </div>
            </div>


          </div>

          <div class="md:text-right">
            <!--                        <div class="currency ">MK</div>-->
            <div>
                            <span class="total">{{
                                numberWithCommas(delivery.quantityDelivered)
                              }}/{{ numberWithCommas(delivery.summary.quantity) }}</span>

            </div>
            <div class="name">Quantity Delivered</div>

          </div>
        </div>

      </div>
      <div>
        <delivery-status :product-compound="delivery.summary" :due="delivery.due" :overdue="delivery.overdue"/>
      </div>


    </div>


    <dialog-modal :show="showDialog" @close="showDialog=false">

      <template #title>
          <div class="flex justify-between">
              {{ productName(delivery.summary) }}
              <inertia-link :href="route('deliveries.show',{id:delivery.id})">
                  <span class="text-blue-700 text-xs font-bold">View Summary</span>
              </inertia-link>
          </div>

      </template>

      <template #content>
        <div class="delivery-profile grid grid-cols-1 md:grid-cols-2 gap-2">
          <div class="mb-4">
            <div class="text-mute text-sm">
              Client
            </div>
            <div class="text-gray-500 text-sm">
              {{ delivery.client.name }}
            </div>
          </div>
          <div class="mb-4">
            <div class="text-mute text-sm">
              Site Location
            </div>
            <div class="text-gray-500 text-sm">
              {{ delivery.location }}
            </div>
          </div>
          <div class="mb-4">
            <div class="text-mute text-sm">
              Quantity Delivered
            </div>
            <!--                        <div class="currency ">MK</div>-->
            <div>
                            <span class="total">{{
                                numberWithCommas(delivery.quantityDelivered)
                              }}/{{ numberWithCommas(delivery.summary.quantity) }}</span>

            </div>
          </div>

          <div v-show="delivery.status === 1" class="mb-4">
            <div class="text-mute text-sm">
              Quantity Delivered
            </div>
            <jet-input min="0" :max="quantityBalance" type="number" class="block w-full" v-model="form.quantity"/>
            <!--                        <div class="text-mute text-xs">-->
            <!--                            Hello World-->
            <!--                        </div>-->
          </div>

          <div v-show="delivery.status === 1" class="mb-4">
            <div class="text-mute text-sm">
              Recipient Name
            </div>
            <jet-input type="text" class="block w-full" v-model="form.recipientName"/>
            <div class="text-red-500 text-xs" v-if="form.errors.recipient_name">Required
            </div>
          </div>

          <div v-show="delivery.status === 1" class="mb-4">
            <div class="text-mute text-sm">
              Recipient Phone Number
            </div>
            <jet-input type="text" class="block w-full" v-model="form.recipientPhoneNumber"/>
            <div class="text-red-500 text-xs" v-if="form.errors.recipient_phone_number">Required
            </div>
          </div>

          <div v-if="delivery.status === 1" class="mb-4 md:col-span-2">
            <div class="text-mute text-sm mb-1">
              Upload Delivery Note
            </div>
            <input type="file" id="photo" @input="photoUpload($event.target.files[0])"
                   class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
            <div class="text-red-500 text-xs" v-if="form.errors.photo">Required
            </div>
          </div>

          <div class="mb-4 md:col-span-2">
            <div @click="showLogs = !showLogs" class="text-mute transition-all ease-in">
              <i class="text-lg mdi" :class="{'mdi-menu-down':!showLogs, 'mdi-menu-up': showLogs}"></i> Logs
              ({{ delivery.logs.length }})
            </div>
            <div v-show="showLogs" class=" transition-all ease-in">
              <div class="ml-5 mb-1 flex text-xs text-mute" v-for="(log, index) in delivery.logs" :key="index">
                <div style="width:120px" class="text-gray-500">{{ log.date }}:</div>
                <div class="">{{ log.message.trim() }} ({{ log.user }})</div>
              </div>
            </div>
          </div>

          <div v-if="delivery.status === 1" @click="openDeleteDialog"
               class="mb-4 md:col-span-2 text-red-500 text-sm cursor">
            <i
                class="mdi mdi-close-circle text-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 "></i>
            Cancel Delivery
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
        <primary-button v-show="delivery.status === 1" @click.native="submit">
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

    <dialog-modal :show="deleteDialog" @close="deleteDialog=false">
      <template #title>
        Cancel Delivery of {{ productName(delivery.summary) }}
      </template>

      <template #content>
        Are you sure you want to cancel this delivery?
      </template>

      <template #footer>
        <secondary-button @click.native="deleteDialog=false">
          Cancel
        </secondary-button>

        <danger-button class="ml-2" @click.native="cancelDelivery">
          <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
               viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                fill="#E5E7EB"/>
            <path
                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                fill="currentColor"/>
          </svg>
          Proceed
        </danger-button>
      </template>
    </dialog-modal>
  </div>

</template>

<script>
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import pdf from "vue-pdf-embed/dist/vue2-pdf-embed";
import DialogModal from "@/Jetstream/DialogModal.vue";
import PrimaryButton from "@/Jetstream/Button.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetInput from "@/Jetstream/Input.vue";
import DangerButton from "@/Jetstream/DangerButton.vue";
import JetLabel from "@/Jetstream/Label.vue";

export default {
  name: "Delivery",
  props: ['delivery'],
  components: {
    JetLabel,
    DangerButton,
    JetInput,
    SecondaryButton, PrimaryButton, DialogModal, pdf,
    DeliveryStatus,
  },

  data() {
    return {
      deleteDialog: false,
      showDialog: false,
      showLogs: false,
      form: this.$inertia.form({
        quantity: 0,
        photo: "",
        recipientName: "",
        recipientPhoneNumber: "",
      }),
      quantityValidation: null,
    }
  },

  computed: {
    quantityBalance() {
      return this.delivery.summary.quantity - this.delivery.quantityDelivered

    },
      notes() {
          let data = []
          let split = null

          for (let x in this.delivery.notes) {
              split = this.delivery.notes[x].split('.')
              data.push({
                  file: this.delivery.data.notes[x],
                  ext: split[1]
              })
          }
          return data
      },
  },

  methods: {
    openDeleteDialog() {
      this.showDialog = false
      this.deleteDialog = true
    },
    submit() {
      this.form
          .transform(data => ({
            ...data,
            recipient_name: this.form.recipientName,
            recipient_phone_number: this.form.recipientPhoneNumber,
          }))
          .post(this.route('deliveries.update', {'id': this.delivery.summary.id}), {
            // preserveScroll: true,
            onSuccess: () => {
              this.showDialog = false
              this.form.quantity = 0
              this.form.recipientName = ""
              this.form.recipientPhoneNumber = ""
              document.getElementById('photo').value = ""
            },
          })
    },
    cancelDelivery() {
      this.form
          .transform(data => ({
            ...data,
            recipient_name: this.form.recipientName,
            recipient_phone_number: this.form.recipientPhoneNumber,
          }))
          .post(this.route('deliveries.cancel', {'id': this.delivery.id}), {
            // preserveScroll: true,
            onSuccess: () => {
              this.deleteDialog = false
            },
          })
    },
    photoUpload(file) {
      const reader = new FileReader();
      if (file) {
        reader.readAsDataURL(file);
        reader.onload = (e) => {
          axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
            type: "DELIVERY_NOTE",
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
