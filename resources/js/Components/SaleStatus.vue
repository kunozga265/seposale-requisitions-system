<template>
    <div class="">
        <div class="flex justify-start items-center" :class="getStatusClass()">
            <div>
                <i class="mdi text-xl" :class="getStatusIcon()"></i>
            </div>
            <div class="ml-3 text-sm letter-spacing-normal" style="letter-spacing: normal">
                {{getStatusMessage() }}
            </div>
        </div>
<!--        <div class="flex justify-start items-center" :class="getDeliveryStatusClass()">-->
<!--            <div>-->
<!--                <i class="mdi text-xl" :class="getDeliveryStatusIcon()"></i>-->
<!--            </div>-->
<!--            <div class="ml-3 text-sm letter-spacing-normal" style="letter-spacing: normal">-->
<!--                {{getDeliveryStatusMessage(this.sale.delivery.status) }}-->
<!--            </div>-->
<!--        </div>-->
    </div>

</template>

<script>
export default {
    name: "SaleStatus",
    props:['sale'],
    methods:{
        getStatusClass(){
            switch (this.sale.status){
                case 0:
                    return "closed";
                case 1:
                    return "approval-pending";
                case 2:
                    return "approved";
                case 3:
                    return "approved";
                case 4:
                    return "closed";
                case 5:
                    return "closed";
                default:
                    return "";
            }
        },
        getStatusMessage(){
            switch (this.sale.status){
                case 0:
                  return "Under Enquiry";
                case 1:
                    return "Partially Paid";
                case 2:
                  return "Fully Paid";
                case 3:
                    return "";
                default:
                    return "";
            }
        },
        getStatusIcon(){
            switch (this.sale.status){
                case 0:
                    return "mdi-alert-circle";
                case 1:
                    return "mdi-check-circle";
                case 2:
                    return "mdi-check-circle";
                case 3:
                    return "mdi-check-circle";
                case 4:
                    return "mdi-check-circle";
                case 5:
                    return "mdi-close-circle";
                default:
                    return "";
            }
        },
        getDeliveryStatusClass(){
            switch (this.sale.delivery.status){
                case 0:
                    return "denied";
                case 1:
                    return "approval-pending";
                case 2:
                    return "approved";
                default:
                    return "";
            }
        },

        getDeliveryStatusIcon(){
            switch (this.sale.delivery.status){
                case 0:
                    return "mdi-close-circle";
                case 1:
                    return "mdi-alert-circle";
                case 2:
                    return "mdi-check-circle";
                default:
                    return "";
            }
        },
    }
}
</script>

<style scoped>

</style>
