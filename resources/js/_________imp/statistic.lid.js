import Vue from 'vue';
import Store from './store/index'
import StatisticLid from "./Components/StatisticLid";
// import StatisticLid from "./Components/StatisticLidCSV";
import VueTheMask from 'vue-the-mask'
import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';

Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});
Vue.component('StatisticLid',StatisticLid);
const eventBus = new Vue({
    store:Store,
    // el:'#statisticLid'
    el:'#app'
});



