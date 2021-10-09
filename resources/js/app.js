import Vue from 'vue'
import App from "./App.vue";
// Vue.component('App', require('./App.vue').default);
import VueCookies from 'vue-cookies';

import vuetify from "./plugins/vuetify";
require('./bootstrap.js')

window.Vue= require('vue');
Vue.config.productionTip = false;
Vue.use(VueCookies);
new Vue({
    vuetify,
    render: h => h(App)
  }).$mount("#app");

