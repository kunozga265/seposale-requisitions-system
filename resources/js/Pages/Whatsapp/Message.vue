<template>
    <tr @click="showDialog = true"
        class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200">
        <td class="p-2 text-left ">
            {{ getDate(message.date * 1000, true) }}
        </td>
        <td class="p-2 text-left ">
            <div class="flex justify-start items-center" :class="getStatusClass()">
                {{ message.status }}
            </div>
        </td>
        <td class="p-2 text-left ">{{ message.name }}</td>
        <td class="p-2 text-left ">{{ message.phoneNumber }}</td>
        <td class="p-2 text-left ">
            <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                {{ message.messageType }}
            </span>
        </td>


        <dialog-modal :show="showDialog" @close="showDialog = false">

            <template #title>
                <div v-if="message.type">
                    Message Content
                </div>
                <div v-else>
                    Message Status Updates
                </div>

            </template>

            <template #content>
                <!-- client message -->
                <div v-if="message.type">
                    <div v-if="loading">
                        <svg role="status" class="inline w-4 h-4 mr-3 text-black animate-spin" viewBox="0 0 100 101"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="#E5E7EB" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentColor" />
                        </svg>
                        Loading Content...
                    </div>
                    <div v-else>
                        <div v-if="message.messageType == 'text'" class="">
                            {{ message.payload.messages[0].text.body }}
                        </div>
                        <div v-else-if="message.messageType == 'contacts'" class="">
                            {{ message.payload.messages[0].contacts }}
                        </div>
                        <div v-else-if="message.messageType == 'location'" class="">
                            {{ message.payload.messages[0].location }}
                        </div>
                        <div v-else-if="message.messageType == 'reaction'" class="">
                            {{ message.payload.messages[0].reaction }}
                        </div>
                        <div v-else class="">
                            <!-- IMAGES -->
                            <img v-if="message.messageType === 'image'" :src="mediaUrl"
                                class="w-64 mx-auto rounded shadow" />

                            <!-- VIDEO -->
                            <video v-else-if="message.messageType === 'video'" :src="mediaUrl" controls
                                class="w-64 rounded shadow"></video>

                            <!-- DOCUMENTS -->
                            <a v-else-if="message.messageType === 'document'" :href="mediaUrl" target="_blank"
                                class="text-blue-600 underline">
                                Download Document
                            </a>

                            <!-- AUDIO -->
                            <audio v-else-if="message.messageType === 'audio'" :src="mediaUrl" controls />

                            <!-- FALLBACK -->
                            <p v-else>Unsupported media type</p>
                        </div>

                    </div>

                </div>
                <div v-else>
                    <div class=" p-2  border-b" v-for="(item, index) in message.statuses">
                        <div class="flex items-center justify-between">

                            <div class="">
                                <div class="text-xs"> {{ getDate(item.date * 1000, true) }}</div>
                                <div class="text-xs text-gray-500" v-if="item.status == 'Failed'">
                                    {{ item.payload.statuses[0].errors[0].message }}
                                </div>
                            </div>
                            <div class="ml-2 text-xs w-32 text-center" :class="getItemStatusClass(item.status)">{{
                                item.status }}</div>
                        </div>


                    </div>
                </div>


            </template>

            <template #footer>

                <secondary-button @click.native="showDialog = false">
                    close
                </secondary-button>

                <a :href="'https://wa.me/' + message.phoneNumber" target="_blank">
                    <primary-button>
                        Reply
                    </primary-button>
                </a>

            </template>
        </dialog-modal>
    </tr>


</template>

<script>

import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetInput from "@/Jetstream/Input.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";
import PrimaryButton from "@/Jetstream/Button.vue";
import DangerButton from "@/Jetstream/DangerButton.vue";

export default {
    name: "Message",
    components: { DangerButton, PrimaryButton, DialogModal, JetInput, SecondaryButton },
    props: ['message',],
    data() {
        return {
            deleteDialog: false,
            showDialog: false,
            mediaUrl: null,
            type: null,
            loading: false,
            showLogs: true,
            form: this.$inertia.form({
            }),
        }
    },
    // created() {
    //     console.log(process.env.MIX_GRAPH_API_TOKEN)
    // },
    computed: {

    },
    watch: {
        showDialog() {
            if (this.mediaUrl == null &&
                this.showDialog == true &&
                this.message.messageType != 'text' &&
                this.message.messageType != 'location' &&
                this.message.messageType != 'contacts' &&
                this.message.messageType != 'reaction' &&
                this.message.messageType != 'unsupported') {
                this.loadMedia()
            }
        }
    },
    methods: {
        async loadMedia() {
            this.loading = true;
            this.mediaUrl = await this.getWhatsappMediaUrl()
            this.loading = false;
        },
        async getWhatsappMediaUrl() {

            let mediaId = "";

            switch (this.message.messageType) {
                case 'image':
                    mediaId = this.message.payload.messages[0].image.id;
                    break;
                case 'document':
                    mediaId = this.message.payload.messages[0].document.id;
                    break;
                case 'audio':
                    mediaId = this.message.payload.messages[0].audio.id;
                    break;
                case 'video':
                    mediaId = this.message.payload.messages[0].video.id;
                    break;
                case 'sticker':
                    mediaId = this.message.payload.messages[0].sticker.id;
                    break;
            }

            const res = await fetch(`https://webhook.seposale.com/media/${mediaId}`);

            console.log(res)

            const blob = await res.blob();



            const url = URL.createObjectURL(blob);

            console.log(url)
            return url
        },

        getStatusClass() {
            let statusClass = "";

            switch (this.message.status) {
                case 'Failed':
                    statusClass = "denied";
                    break;
                case 'Accepted':
                    statusClass = "approval-pending";
                    break;
                case 'Sent':
                case 'Read':
                case 'Delivered':
                    statusClass = "approved";
                    break;
                case 'Received':
                    statusClass = "info";
                    break;
                default:
                    statusClass = "closed";
                    break;
            }

            return statusClass
        },
        getItemStatusClass(status) {
            let statusClass = "";

            switch (status) {
                case 'Failed':
                    statusClass = "denied";
                    break;
                case 'Accepted':
                    statusClass = "approval-pending";
                    break;
                case 'Sent':
                case 'Read':
                case 'Delivered':
                    statusClass = "approved";
                    break;
                case 'Received':
                    statusClass = "info";
                    break;
                default:
                    statusClass = "closed";
                    break;
            }

            return statusClass
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
        getStatusMessage(status) {
            if (this.product.trashed) {
                return "Closed";
            }

            switch (parseInt(status)) {
                case 0:
                    return this.numberWithCommas(this.product.quantity) + " Uncollected";
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

    }
}
</script>

<style scoped></style>
