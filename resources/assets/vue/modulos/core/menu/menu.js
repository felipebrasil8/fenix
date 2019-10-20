import Vue from 'vue';

// https://github.com/shentao/vue-multiselect
import Multiselect from 'vue-multiselect';

// https://github.com/euvl/vue-js-modal
/*import VueJsModal from 'vue-js-modal';

Vue.use(VueJsModal, {
    dialog: true
}); */

window.axios = require('axios');

Vue.component('VcMenu', require('./menu.vue'));
Vue.component('VcMenuItem', require('./menuItem.vue'));

Vue.component('multiselect', Multiselect);

const app = new Vue({
    el: '#app_menu',
});
