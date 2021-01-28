import Vue from 'vue';
import LogisticMap from "./Components/LogisticMap";
import Store from "./store/index"

import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';
import VueTheMask from 'vue-the-mask'
import DG from "2gis-maps";

Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});
Vue.use(DG);




Vue.component('LogisticMap',LogisticMap);
const eventBus = new Vue({
    el:'#logisticMap',
    store:Store
});



