import Vue from 'vue';
import Store from './store/index'
import FixedExpenses from './Components/Crew/DailyExpenses'

Vue.component('FixedExpenses',FixedExpenses);
const eventBus = new Vue({
    el:'#dixedExpenses',
    store:Store
});
