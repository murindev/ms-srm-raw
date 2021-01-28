import Vue from "vue";
import ImplementDay from "./Components/ImplementDay";
Vue.component('ImplementDay',ImplementDay);
const eventBus = new Vue({
    el:'#implementDay'
});
