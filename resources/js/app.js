require('./bootstrap');

window.Vue = require('vue');
Vue.config.devtools = true;

const app = new Vue({
    el: '#app'
});
