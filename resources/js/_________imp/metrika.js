import Vue from 'vue';
import Metrika from "./Components/Metrika";
import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';
Vue.use(HeyUI,{locale: en});

Vue.component('Metrika',Metrika);
const eventBus = new Vue({
    el:'#metrika',
});





