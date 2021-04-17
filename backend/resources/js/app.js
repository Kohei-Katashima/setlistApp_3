// import "./bootstrap";
import Vue from "vue";
// import draggable from 'vuedraggable'
// import Accordion from "./components/Accordion.vue";
// import Draggable from "./components/Draggable.vue";
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)


require('./bootstrap');
window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('table-draggable', require('./components/TableDraggable.vue').default);
// Vue.component('accordion', require('./components/Accordion.vue').default);
// Vue.component('collapse-transition', require('./components/CollapseTransition.vue').default);


const app = new Vue({ 
    el: '#app' ,
});

// const app = new Vue({
//     el: '#app',
//     components: {
//         Accordion,
//         Accordion_1,
//         Draggable,
//       }
// });
