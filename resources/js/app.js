import axios from "axios";

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// @ts-ignore
let token =  document.querySelector('input[name="jwtToken"]').value;

// import Pusher from 'pusher-js'
// @ts-ignore
window.Vue = Vue;
// @ts-ignore
window.Pusher = require('pusher-js');

// @ts-ignore
window.Echo =  new Echo({
    broadcaster: 'pusher',
    // @ts-ignore
    key: 1970,
    // @ts-ignore
    cluster: 'mt1',
    wsHost: window.location.hostname,
    wsPort: 6001,
    auth: {
        headers: {
            Authorization: 'Bearer' +   token
        },
    },

    // encrypted: true
});

import Vue from "vue";
import Store from './store/index';

import Messenger from "./Components/Messenger";
// import StatisticLid from "./Components/StatisticLid";
import LidsList from "./Components/LidsList";
import DGMap from "./Components/DGMap";
import ActMap from "./Components/ActMap";
import CrewList from "./Components/CrewList";
import TaskTracker from "./Components/TaskTracker";
import Lid from "./Components/Lid";





import DG from "2gis-maps";

import moment from 'moment';
import {bus} from './bus'
import Echo from "laravel-echo";

import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';
import VueTheMask from 'vue-the-mask'


var filter = function(text, length, clamp){
    clamp = clamp || '...';
    var node = document.createElement('div');
    node.innerHTML = text;
    var content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
};


Notification.requestPermission(function(permission){
// переменная permission содержит результат запроса
    console.log('Результат запроса прав:', permission);
});


Vue.filter('truncate', filter);
Vue.use(DG);
Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});
Vue.prototype.moment = moment;
Vue.prototype.bus = bus;

Vue.component('Messenger',Messenger);
// Vue.component('StatisticLid', StatisticLid);
Vue.component('LidsList', LidsList);
Vue.component('Lid', Lid);
Vue.component('DGMap', DGMap);
Vue.component('ActMap', ActMap);
Vue.component('CrewList', CrewList);
Vue.component('tasktracker',TaskTracker);

//
new Vue({
    store:Store,
    el: '#app'
});
