import Vue from 'vue';

// https://github.com/euvl/vue-js-modal
import VueJsModal from 'vue-js-modal';

// https://www.npmjs.com/package/cookie-in-vue
import VueCookie from 'cookie-in-vue'; 

// https://github.com/shentao/vue-multiselect
import Multiselect from 'vue-multiselect';

// https://github.com/ankurk91/vue-bootstrap-datetimepicker
import DatePicker from 'vue-bootstrap-datetimepicker';

window.axios = require('axios');

Vue.use(VueJsModal, {
	dialog: true
}); 
Vue.use(VueCookie);

Vue.use(DatePicker);

Vue.component('VcIndex', require('./index.vue'));

Vue.component('VcFiltroPesquisa', require('./../../util/FiltroPesquisa.vue'));

Vue.component('VcDatatable', require('./Datatable.vue'));

Vue.component('VcMigalha', require('./../../util/Migalha.vue'));

Vue.component('VcModalConfirme', require('./../../util/ModalConfirmeEvent.vue'));

Vue.component('multiselect', Multiselect);

Vue.component('VcParadaProgramadaModal', require('./ModalParadaProgramada.vue'));

Vue.component('VcVincularChamadoModal', require('./ModalVincularChamado.vue'));

Vue.component('VcModalConfirme', require('./../../util/ModalConfirmeEvent.vue'));


const app = new Vue({
    el: '#app_itens',
});
	
