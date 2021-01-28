import Vue from "vue";

require('./bootstrap');

import DG from "2gis-maps";
import Store from './store/index';
// import moment from 'moment';
import {bus} from './bus'
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
Vue.prototype.moment = moment;
Vue.prototype.bus = bus;


Vue.component('Messenger', require('./Components/Messenger'));
Vue.component('task-tracker',require('./Components/TaskTracker'));
Vue.component('StatisticLid', require('./Components/StatisticLid'));


//
const eventBus = new Vue({
    store:Store,
    el: '#app'
});
