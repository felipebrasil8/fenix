import Vue from 'vue';

// https://github.com/euvl/vue-js-modal
import VueJsModal from 'vue-js-modal';

// https://www.npmjs.com/package/cookie-in-vue
import VueCookie from 'cookie-in-vue'; 

// https://github.com/zdy1988/vue-jstree
import VJstree from 'vue-jstree';

// https://github.com/ankurk91/vue-bootstrap-datetimepicker
import DatePicker from 'vue-bootstrap-datetimepicker';

window.axios = require('axios');

Vue.use(VueJsModal, {
	dialog: true
}); 
Vue.use(VueCookie);
Vue.use(VJstree);
Vue.use(DatePicker);



// Vue.component('VcCategoria', require('./Categoria.vue'));
Vue.component('VcIndex', require('./index.vue'));
Vue.component('VcCadastrar', require('./cadastrar.vue'));
Vue.component('VcEditar', require('./editar.vue'));
Vue.component('VcVisualizar', require('./visualizar.vue'));
Vue.component('VcCopiar', require('./copiar.vue'));


Vue.component('VcForm', require('./form.vue'));

Vue.component('VcFiltroPesquisa', require('./../../util/FiltroPesquisa.vue'));

Vue.component('VcDatatable', require('./../../util/Datatable.vue'));

Vue.component('VcMigalha', require('./../../util/Migalha.vue'));

Vue.component('VcModalConfirme', require('./../../util/ModalConfirmeEvent.vue'));

const app = new Vue({
    el: '#app_perfil',
});
