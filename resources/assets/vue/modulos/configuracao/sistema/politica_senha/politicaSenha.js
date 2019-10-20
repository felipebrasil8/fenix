import Vue from 'vue';

window.axios = require('axios');

Vue.component('VcIndex', require('./index.vue'));

Vue.component('VcMigalha', require('./../../../util/Migalha.vue'));

const app = new Vue({
    el: '#app_politicaSenha',
});