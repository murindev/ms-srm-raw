import Vue from 'vue';
import Store from './store/index'
import FixedExpenses from './Components/Crew/FixedExpenses'

Vue.component('FixedExpenses',FixedExpenses);
const eventBus = new Vue({
    el:'#fixedExpenses',
    store:Store
});







