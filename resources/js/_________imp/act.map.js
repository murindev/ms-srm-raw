import Vue from 'vue';
import Store from './store/index'
import ActMap from "./Components/ActMap";


Vue.component('ActMap',ActMap);
const eventBus = new Vue({
    el:'#actMap',
    store:Store
});





