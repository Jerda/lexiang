import { CodeToText, TextToCode } from 'element-china-area-data'

export default {
    install(Vue,options)
    {
        Vue.prototype.message = function (response) {
            if (response.data.status === undefined) {
                return this.$message(response.data.msg);
            }

            if (response.data.status === 1 && response.data.data === undefined) {
                return this.$message.success(response.data.msg);
            } else {
                return this.$message.error(response.data.msg);
            }
        };
        /*
        将回返的json数据格式化为element-select-options所需格式
         */
        Vue.prototype.formatSelectOptions = function(data) {
            let array = [];
            for(let value in data) {
                array.push({value: value, label: data[value]});
            }
            return array;
        }
        /*
         将回返的json数据格式化为vux-select-options所需格式
         */
        Vue.prototype.formatSelectOptionsForVux = function(data) {
            let array = [];
            for(let value in data) {
                array.push({value: value, name: data[value]});
            }
            return array;
        }
        /*
        格式化地址选择器数据
        return {province: xxx, city: xxx, district: xxx}
         */
        Vue.prototype.formatToTextForAddress = function(address) {
            let data = {};
            address.forEach((value, index) => {
                if (index == 0) {
                    data.province = CodeToText[value]
                }
                if (index == 1) {
                    data.city = CodeToText[value]
                }
                if (index == 2) {
                    data.district = CodeToText[value]
                }
            })
            return data;
        }
        /*
        格式化地址选择器，将地址名称转换为code
        return [xxx,xxx,xxx]
         */
        Vue.prototype.formatToCodeForAddress = function(province, city, district) {
            let arr = [];
            arr[0] = TextToCode[province].code;
            arr[1] = TextToCode[province][city].code;
            arr[2] = TextToCode[province][city][district].code;
            return arr;
        }
        Vue.prototype.getPermission = function(permissions) {
            axios.post('api/frontend_permission', permissions).then(response => {
                return response.data.data
            })
        }
    },
}
