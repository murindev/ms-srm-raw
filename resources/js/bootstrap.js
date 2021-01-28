
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    // window.$ = window.jQuery = require('jquery');

    // require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.Vue = require('vue');
import Vue from "vue";
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */
let token = document.querySelector('input[name="jwtToken"]').value;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */


import VueTheMask from 'vue-the-mask'
import HeyUI from 'heyui';
import en from './locales/heyUI-ru-RU';

import VueChatScroll from 'vue-chat-scroll';
Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});
Vue.use(VueChatScroll);


import Echo from 'laravel-echo'


window.Pusher = require('pusher-js');

window.Echo =  new Echo({
    broadcaster: 'pusher',
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
