import Vue from 'vue';
import Store from './store/index'
import RulesMaster from './Components/Act/RulesMaster'


Vue.component('RulesMaster',RulesMaster);
const eventBus = new Vue({
    el:'#act',
    store:Store
});
