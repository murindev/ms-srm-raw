import Vue from 'vue';
import Manipulation from "./Components/Manipulation";


Vue.component('Manipulation',Manipulation);
const eventBus = new Vue({
    el:'#manipulations'
});





