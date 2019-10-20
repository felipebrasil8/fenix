import Vue from 'vue';

// https://github.com/euvl/vue-js-modal
import VueJsModal from 'vue-js-modal';

// https://github.com/davidroyer/vue2-editor
import VueEditor from 'vue2-editor';

// https://www.npmjs.com/package/cookie-in-vue
import VueCookie from 'cookie-in-vue'; 

// https://github.com/ankurk91/vue-bootstrap-datetimepicker
import DatePicker from 'vue-bootstrap-datetimepicker';

window.axios = require('axios');
Vue.use(VueEditor);
Vue.use(VueJsModal, {
	dialog: true
}); 
Vue.use(VueCookie);
Vue.use(DatePicker);

// COMPONENTES BASE DO CONHECIMENTO
Vue.component('VcCategoria', require('./Categoria.vue'));
Vue.component('VcPublicacao', require('./Publicacao.vue'));
Vue.component('VcBuscaPublicacao', require('./BuscaPublicacao.vue'));
Vue.component('VcConteudo', require('./Conteudo.vue'));
Vue.component('VcConteudoVisualizar', require('./ConteudoVisualizar.vue'));
Vue.component('VcDashboard', require('./Dashboard.vue'));

// MODAIS BASE DO CONHECIMENTO
Vue.component('PublicacaoModal', require('./PublicacaoModal.vue'));
Vue.component('PublicacaoDatasModal', require('./PublicacaoDatasModal.vue'));
Vue.component('PublicacaoNovaModal', require('./PublicacaoNovaModal.vue'));
Vue.component('PublicacaoImagemCapaModal', require('./PublicacaoImagemCapaModal.vue'));
Vue.component('PublicacaoRestricaoModal', require('./PublicacaoRestricaoModal.vue'));
Vue.component('TagsModal', require('./TagsModal.vue'));
Vue.component('ColaboradoresModal', require('./ColaboradoresModal.vue'));
Vue.component('TextoModal', require('./TextoModal.vue'));
Vue.component('ImagemModal', require('./ImagemModal.vue'));
Vue.component('ImagemLinkModal', require('./ImagemLinkModal.vue'));
Vue.component('ShowImagemModal', require('./ShowImagemModal.vue'));
Vue.component('VcPublicacaoHistorico', require('./PublicacaoHistorico.vue'));
Vue.component('VcRecomendacaoModal', require('./RecomendacaoModal.vue'));
Vue.component('VcRecomendacaoPublicacaoMensagemModal', require('./RecomendacaoPublicacaoMensagemModal.vue'));
Vue.component('VcComponentePublicacao', require('./ComponentePublicacao.vue'));
Vue.component('VcRespostaMensagemModal', require('./RespostaMensagemModal.vue'));
Vue.component('RascunhoModalConfirm', require('./RascunhoModalConfirm.vue'));
Vue.component('VcExportar', require('./Exportar.vue'));
Vue.component('VcDropdownMenu', require('./DropdownMenuPublicacao.vue'));
Vue.component('VcModalConfirmAcessaRascunho', require('./ModalConfirmAcessaRascunho.vue'));
Vue.component('VcCategoriaModal', require('./CategoriaModal.vue'));

//https://jsfiddle.net/taha_sh/krn9v4vr/
Vue.component('VcAutoComplete', require('./../util/AutoComplete.vue'));

// MENSAGENS PUBLICAÇÕES
Vue.component('VcMensagensFiltros', require('./MensagensFiltros.vue'));
Vue.component('VcMensagensConteudo', require('./MensagensConteudo.vue'));

// COMPONENTES VUE PERSONALIZADO
Vue.component('VcModalLink', require('./../util/ModalLink.vue'));
Vue.component('VcModalConfirm', require('./../util/ModalConfirm.vue'));
Vue.component('VcModalButton', require('./../util/ModalButton.vue'));
Vue.component('VcPagination', require('./../util/Paginacao.vue'));
Vue.component('VcIntervaloDataModal', require('./../util/ModalIntervaloData.vue'));
Vue.component('VcMigalha', require('./../util/Migalha.vue'));
Vue.component('VcModalText', require('./../util/ModalText.vue'));

Vue.component('VcMigalhaFiltroData', require('./../util/MigalhaFiltroData.vue'));

// COMPONENTES DE DASHBOARD
Vue.component('VcBoxInfoMiniDash', require('./../util/dashboard/BoxInfoMini.vue'));
Vue.component('VcBoxInfoMini', require('./../util/BoxInfoMini.vue'));
Vue.component('VcBoxInfoMedio', require('./BoxInfoMedio.vue'));
Vue.component('VcBoxInfoLarge', require('./BoxInfoLarge.vue'));
Vue.component('VcBoxGraficoBarra', require('./BoxGraficoBarra.vue'));
Vue.component('VcBuscasSemResultadoModal', require('./BuscasSemResultadoModal.vue'));


const app = new Vue({
    el: '#app_base_de_conhecimento',
});
