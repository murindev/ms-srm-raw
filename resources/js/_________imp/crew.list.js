import Vue from 'vue';
import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';
import Store from './store/index'
import VueTheMask from 'vue-the-mask'
import _ from 'lodash';
import moment from 'moment';
import {bus} from './bus'

Vue.prototype.bus = bus;
Vue.prototype.moment = moment;
Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});
Object.defineProperty(Vue.prototype, '_', { value: _ });

import CrewList from "./Components/CrewList";
Vue.component('CrewList',CrewList);

const eventBus = new Vue({
    el:'#crewList',
    store:Store,
});


