import Vue from 'vue';
import Store from './store/index'
import StafferModal from './Components/Crew/StafferModal'


Vue.component('StafferModal',StafferModal);
const eventBus = new Vue({
    el:'#stafferModal',
    store:Store
});
