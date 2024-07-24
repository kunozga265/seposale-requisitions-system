<template>
        <div class="flex justify-start items-center" :class="getDeliveryStatusClass()" >
            <div>
                <i class="mdi text-xl" :class="getDeliveryStatusIcon()"></i>
            </div>
            <div class="ml-3 text-sm letter-spacing-normal" style="letter-spacing: normal">
                {{getDeliveryStatusMessage(this.productCompound.delivery.status) }} {{due != null ? ", due " + due : ""}}
            </div>
        </div>


</template>

<script>
export default {
    name: "DeliveryStatus",
    props:['productCompound', "due","overdue","isSolo"],
    methods:{
        getDeliveryStatusClass(){
          let statusClass = "";

          if(this.overdue){
            switch (this.productCompound.delivery.status){
              case 0:
                statusClass =   "denied";
                break;
              case 1:
                statusClass =   "denied";
                break;
              case 2:
                statusClass =   "approved";
                break;
              default:
                statusClass =   "closed";
            }
          }else{
            switch (this.productCompound.delivery.status){
              case 0:
                statusClass =   "denied";
                break;
              case 1:
                statusClass =   "approval-pending";
                break;
              case 2:
                statusClass =   "approved";
                break;
              default:
                statusClass =   "closed";
                break;
            }
          }

          if(this.isSolo){
            return statusClass + " clear-background";
          }else
            return statusClass;
        },

        getDeliveryStatusIcon(){
            switch (this.productCompound.delivery.status){
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
    }
}
</script>

<style scoped>

</style>
