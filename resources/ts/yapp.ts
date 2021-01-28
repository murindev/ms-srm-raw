// app.ts
import "./bootstrap"
import Vue from "vue"
import store from './store/index'
import ExampleComponent from "./components/Store.vue";
// import ExampleComponent from "./components/ExampleComponent.vue"

// @ts-ignore
import VueTheMask from 'vue-the-mask'
// @ts-ignore
import HeyUI from 'heyui';
// @ts-ignore
import en from './locales/heyUI-ru-RU';
Vue.use(VueTheMask);
Vue.use(HeyUI, {locale: en});





Vue.component('example', ExampleComponent)


new Vue({
    store,
    el: '#app'
})
