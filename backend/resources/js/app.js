import Vue from "vue";
import axios from "axios";
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)


require('./bootstrap');
window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('table-draggable', require('./components/TableDraggable.vue').default);
Vue.prototype.$axios = axios


const app = new Vue({
    el: '#app',
});

