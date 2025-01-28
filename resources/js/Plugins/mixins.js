import Vue from 'vue';

Vue.mixin({
    methods: {
        fileUrl: function (path) {
            return this.$page.props.publicPath+path
        },
        checkRole(user,role){
            for(let x in user.roles){
                if(user.roles[x].name===role)
                    return true
            }
            return false
        },
        numberWithCommas(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        getRequestName(type){
            switch (type){
                case 'PETTY_CASH':
                    return 'Petty Cash Request'
                case 'REQUISITION':
                    return 'Requisition'
                default:
                    return  ''
            }
        },
        productName(productCompound){
            if(productCompound.variant == null){
                return productCompound.description
            }else {
                return productCompound.variant.description == null || productCompound.variant.description === "" ? productCompound.product.name : productCompound.product.name + " - " + productCompound.variant.description
            }
        },
        getDeliveryStatusMessage(status){
            switch (parseInt(status)){
                case 0:
                    return "Not Delivered";
                case 1:
                    return "Processing";
                case 2:
                    return "Delivered";
                case 3:
                    return "Cancelled";
                case 4:
                    return "Completed";
                default:
                    return "";
            }
        },
        getInventoryStatusMessage(status){
            switch (status){
                case 0:
                    return "Out of stock";
                case 1:
                    return "Need to restock";
                case 2:
                    return "Available";
                default:
                    return "";
            }
        },
        getTimestampFromDate(date) {
            return new Date(date).getTime() / 1000
        },
        getDate(timestamp,time=false){
            let date = new Date(timestamp);
            const month=date.getMonth()+1
            let monthName=""

            switch (month){
                case 1:
                    monthName =  'Jan'
                    break
                case 2:
                    monthName =  'Feb'
                    break
                case 3:
                    monthName =  'Mar'
                    break
                case 4:
                    monthName =  'Apr'
                    break
                case 5:
                    monthName =  'May'
                    break
                case 6:
                    monthName =  'Jun'
                    break
                case 7:
                    monthName =  'Jul'
                    break
                case 8:
                    monthName =  'Aug'
                    break
                case 9:
                    monthName =  'Sep'
                    break
                case 10:
                    monthName =  'Oct'
                    break
                case 11:
                    monthName =  'Nov'
                    break
                case 12:
                    monthName =  'Dec'
                    break
                default:
                    monthName =  ''
            }



            if(time) {
                let hour=""
                switch (date.getHours()){
                    case 0:
                        hour="00"
                        break
                    case 1:
                        hour="01"
                        break
                    case 2:
                        hour="02"
                        break
                    case 3:
                        hour="03"
                        break
                    case 4:
                        hour="04"
                        break
                    case 5:
                        hour="05"
                        break
                    case 6:
                        hour="06"
                        break
                    case 7:
                        hour="07"
                        break
                    case 8:
                        hour="08"
                        break
                    case 9:
                        hour="09"
                        break
                    default:
                        hour=date.getHours()
                }

                let minutes=""
                switch (date.getMinutes()){
                    case 0:
                        minutes="00"
                        break
                    case 1:
                        minutes="01"
                        break
                    case 2:
                        minutes="02"
                        break
                    case 3:
                        minutes="03"
                        break
                    case 4:
                        minutes="04"
                        break
                    case 5:
                        minutes="05"
                        break
                    case 6:
                        minutes="06"
                        break
                    case 7:
                        minutes="07"
                        break
                    case 8:
                        minutes="08"
                        break
                    case 9:
                        minutes="09"
                        break
                    default:
                        minutes=date.getMinutes()
                }

                return date.getDate() + " " + monthName + ", " + date.getFullYear() + " " + hour + ":" + minutes
            }
            else
                return date.getDate()+ " "+ monthName + ", "+date.getFullYear()
        },
    },
})
