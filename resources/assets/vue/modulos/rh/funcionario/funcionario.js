import Vue from 'vue';

// https://github.com/euvl/vue-js-modal
import VueJsModal from 'vue-js-modal';

// https://www.npmjs.com/package/cookie-in-vue
import VueCookie from 'cookie-in-vue'; 

// https://kabbouchi.github.io/vue-tippy/
import VueTippy from 'vue-tippy';

// https://github.com/ankurk91/vue-bootstrap-datetimepicker
import DatePicker from 'vue-bootstrap-datetimepicker';

// https://github.com/vuejs-tips/vue-the-mask
import VueTheMask from 'vue-the-mask'

window.axios = require('axios');

Vue.use(VueJsModal, {
    dialog: true
}); 
Vue.use(VueCookie);
Vue.use(DatePicker);
Vue.use(VueTheMask);
Vue.use(VueTippy);

Vue.component('VcMigalha', require('./../../util/Migalha.vue'));
Vue.component('VcIndex', require('./index.vue'));
Vue.component('VcFiltroPesquisa', require('./../../util/FiltroPesquisa.vue'));
Vue.component('VcVisualizar', require('./visualizar.vue'));

Vue.component('VcCadastrar', require('./cadastrar.vue'));
Vue.component('VcEditar', require('./editar.vue'));
Vue.component('VcForm', require('./form.vue'));
Vue.component('VcModalChangeAvatar', require('./ModalChangeAvatar.vue'));

Vue.component('VcDatatable', require('./../../util/Datatable.vue'));

Vue.component('VcModalConfirme', require('./../../util/ModalConfirmeEvent.vue'));
Vue.component('VcVisualizar', require('./visualizar.vue'));
// COMPONENTES VUE PERSONALIZADO
Vue.component('VcModalLink', require('./../../util/ModalLink.vue'));
Vue.component('VcModalConfirm', require('./../../util/ModalConfirm.vue'));
Vue.component('VcModalImagem', require('./../../util/ShowImagemModal.vue'));

const app = new Vue({
    el: '#app_funcionario',
});
