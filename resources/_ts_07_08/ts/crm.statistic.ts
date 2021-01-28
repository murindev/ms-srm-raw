// app.ts
import "./bootstrap"
/////////////////////////////
import axios from 'axios';
import Echo from 'laravel-echo'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// @ts-ignore
let token =  document.querySelector('input[name="jwtToken"]').value;

// import Pusher from 'pusher-js'
// @ts-ignore
window.Vue = Vue;
// @ts-ignore
window.Pusher = require('pusher-js');

// @ts-ignore
window.Echo =  new Echo({
    broadcaster: 'pusher',
    // @ts-ignore
    key: 1970,
    // @ts-ignore
    cluster: 'mt1',
    wsHost: window.location.hostname,
    wsPort: 6001,
    auth: {
        headers: {
            Authorization: 'Bearer' +   token
        },
    },

    // encrypted: true
});

///////////////////////////
import Vue from "vue"
import store from './store/index'
import CrmStatistic from "./components/CrmStatistic.vue";
Vue.prototype.$bus = new Vue();



// @ts-ignore
import VueTheMask from 'vue-the-mask'
// @ts-ignore
// import money from 'v-money'
// @ts-ignore
import HeyUI from 'heyui';
// @ts-ignore
import en from './locales/heyUI-ru-RU';
// @ts-ignore
// import VueForceNextTick from 'vue-force-next-tick'

// Vue.use(axios);
Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});
// Vue.use(money, {precision: 4})
// Vue.use(VueForceNextTick)


Vue.component('crm-statistic', CrmStatistic)



 new Vue({
    store,
    el: '#app'
})
