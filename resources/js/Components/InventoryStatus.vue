<template>
    <div class="">
        <div @click="$emit('clickEvent')" class="flex justify-start items-center" :class="getStatusClass()">
            <div>
                <i class="mdi text-xl" :class="getStatusIcon()"></i>
            </div>
            <div class="ml-3 text-sm letter-spacing-normal" style="letter-spacing: normal">
                {{getInventoryStatusMessage(status) }}
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
    name: "InventoryStatus",
    props:['available','threshold','isSolo'],
    emits: ['clickEvent'],
    data() {
        return {
            status:0
        }
    },
    created() {
        if(this.available <= 0){
            this.status = 0
        }else if( this.available <= this.threshold){
            this.status = 1
        }else{
            this.status = 2
        }
    },
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
                default:
                    statusClass = "";
            }

          if(this.isSolo){
            return statusClass + " clear-background";
          }else
            return statusClass;
        },

        getStatusIcon(){
            switch (this.status){
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
