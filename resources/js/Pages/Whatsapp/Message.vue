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


            </template>

            <template #content>

            </template>

            <template #footer>

                <secondary-button @click.native="showDialog = false">
                    close
                </secondary-button>

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
            showLogs: true,
            form: this.$inertia.form({

            }),
        }
    },
    computed: {

    },
    methods: {
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
                case 'Delivered':
                    statusClass = "approved";
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
