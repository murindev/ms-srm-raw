import Vue from 'vue';
import Store from './store/index'
import Implement from './Components/Act/Implement'


Vue.component('Implement',Implement);
const eventBus = new Vue({
    el:'#implement',
    store:Store
});
