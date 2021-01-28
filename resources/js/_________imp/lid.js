import Vue from 'vue';
import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';
import Store from './store/index'
import VueTheMask from 'vue-the-mask'
import DG from "2gis-maps";
Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});
Vue.use(DG);

import Lid from "./Components/Lid";
Vue.component('Lid',Lid);

const eventBus = new Vue({
    el:'#lid',
    store:Store,
});


