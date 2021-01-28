import Vue from 'vue';
import Store from './store/index'
import DadataOrganization from "./Components/DadataOrganization";
Vue.component('DadataOrganization',DadataOrganization);
const eventBus = new Vue({
    el:'#dadataOrganization',
    store:Store
});





