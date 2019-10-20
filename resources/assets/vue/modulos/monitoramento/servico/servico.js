import Vue from 'vue';

// https://github.com/euvl/vue-js-modal
import VueJsModal from 'vue-js-modal';

// https://www.npmjs.com/package/cookie-in-vue
import VueCookie from 'cookie-in-vue'; 

// https://github.com/ankurk91/vue-bootstrap-datetimepicker
import DatePicker from 'vue-bootstrap-datetimepicker';

window.axios = require('axios');

Vue.use(VueJsModal, {
	dialog: true
}); 
Vue.use(VueCookie);
Vue.use(DatePicker);

Vue.component('VcIndex', require('./index.vue'));


Vue.component('VcMigalha', require('./../../util/Migalha.vue'));

// Vue.component('VcModalConfirme', require('./../../util/ModalConfirmeEvent.vue'));

Vue.component('VcBoxInfoMini', require('./../../util/BoxInfoMini.vue'));

Vue.component('VcBoxInfoMiniDash', require('./../../util/dashboard/BoxInfoMini.vue'));

Vue.component('VcMigalhaFiltroData', require('./../../util/MigalhaFiltroData.vue'));

Vue.component('VcIntervaloDataModal', require('./../../util/ModalIntervaloData.vue'));


const app = new Vue({
    el: '#app_servico',
});
