
import NotFound from "./views/NotFound";

require('./bootstrap');

import Vue from 'vue'
Vue.component('add-order-component', require('./components/AddOrderComponent.vue').default);

const app = new Vue({
    el: '#app',
});
