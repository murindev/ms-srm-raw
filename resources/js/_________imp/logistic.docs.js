import Vue from 'vue';
import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';
import Store from './store/index'
Vue.use(HeyUI, {locale: en});


import LogisticDocs from "./Components/LogisticDocs";
Vue.component('LogisticDocs',LogisticDocs);

const eventBus = new Vue({
    el:'#logisticDocs',
    store:Store,
});
