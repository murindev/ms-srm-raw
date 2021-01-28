import Vue from 'vue';
import Store from './store/index'
import Act from "./Components/Act";


Vue.component('Act',Act);
const eventBus = new Vue({
    el:'#act',
    store:Store
});





