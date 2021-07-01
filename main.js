import Vue from 'vue'
import App from './App'

import globalData from '@/common/js/globalData.js'
import zwyPopUp from '@/components/zwy-popup/zwy-popup.vue'
//分享
import share from './common/js/share.js'
Vue.mixin(share)


Vue.prototype.globalData = globalData;
Vue.config.productionTip = false

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
