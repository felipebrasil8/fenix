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

// Vue.component('VcCategoria', require('./Categoria.vue'));
Vue.component('VcIndex', require('./index.vue'));

// Vue.component('VcCadastrar', require('./cadastrar.vue'));
Vue.component('VcEditar', require('./editar.vue'));
Vue.component('VcVisualizar', require('./visualizar.vue'));
Vue.component('VcAbaDados', require('./aba_dados.vue'));
Vue.component('VcAbaConfiguracao', require('./aba_configuracao.vue'));
Vue.component('VcAbaAlerta', require('./aba_alerta.vue'));
Vue.component('VcAbaItensMonitorados', require('./aba_itens_monitorados.vue'));
Vue.component('VcAbaChamadosVinculados', require('./aba_chamados_vinculados.vue'));
Vue.component('VcAbaParadaProgramada', require('./aba_parada_programada.vue'));

// Vue.component('VcCopiar', require('./copiar.vue'));

Vue.component('VcFiltroPesquisa', require('./../../util/FiltroPesquisa.vue'));

Vue.component('VcDatatable', require('./../../util/Datatable.vue'));

Vue.component('VcMigalha', require('./../../util/Migalha.vue'));

Vue.component('VcModalConfirme', require('./../../util/ModalConfirmeEvent.vue'));

Vue.component('multiselect', Multiselect);

const app = new Vue({
    el: '#app_servidores',
});
