import Vue from 'vue';
import DGMap from "./Components/DGMap";
import Store from "./store/index"

import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';
import VueTheMask from 'vue-the-mask'
import DG from "2gis-maps";

Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});
Vue.use(DG);

Vue.component('DGMap',DGMap);
const eventBus = new Vue({
    el:'#DGMap',
    store:Store
});



