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
    props:['status','isSolo'],
    methods:{
        getStatusClass(){
          let statusClass = "";

            switch (this.status){
                case 0:
                    statusClass = "denied";
                    break;
                case 1:
                    statusClass = "approval-pending";
                    break;
                case 2:
                    statusClass = "approved";
                    break;
                case 3:
                    statusClass = "closed";
                    break;
                case 4:
                    statusClass = "closed";
                    break;
                case 5:
                    statusClass = "closed";
                    break;
                default:
                    statusClass = "";
            }

          if(this.isSolo){
            return statusClass + " clear-background";
          }else
            return statusClass;
        },
        getStatusMessage(){
            switch (this.status){
                case 0:
                  return "Unpaid";
                case 1:
                    return "Partially Paid";
                case 2:
                  return "Fully Paid";
                case 3:
                    return "Closed";
                default:
                    return "";
            }
        },
        getStatusIcon(){
            switch (this.status){
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
    }
}
</script>

<style scoped>

</style>
