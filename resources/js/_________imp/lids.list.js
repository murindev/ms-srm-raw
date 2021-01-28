import Vue from 'vue';
import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';
import Store from './store/index'
import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});
var filter = function(text, length, clamp){
    clamp = clamp || '...';
    var node = document.createElement('div');
    node.innerHTML = text;
    var content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
};

Vue.filter('truncate', filter);

import LidsList from "./Components/LidsList";
Vue.component('LidsList',LidsList);

const eventBus = new Vue({
    el:'#lidsList',
    store:Store,
});


