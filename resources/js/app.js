import { App, plugin } from '@inertiajs/inertia-vue'
import Vue from 'vue'
import { InertiaProgress } from '@inertiajs/progress'
import vuetify from '@/Plugins/vuetify'

import axios from "axios";
import ReadMore from 'vue-read-more';
import CKEditor from 'ckeditor4-vue';
import Permissions from './mixins/Permissions';
Vue.mixin(Permissions);

import { VueMaskDirective } from 'v-mask'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;
Vue.prototype.$http = axios;

require('@/Plugins/googleMaps')

Vue.use(plugin)
Vue.use(ReadMore)
Vue.use( CKEditor )
Vue.mixin({methods: {route}})
Vue.directive('mask', VueMaskDirective);
const el = document.getElementById('app')

new Vue({
  vuetify,
  render: h => h(App, {
    props: {
      initialPage: JSON.parse(el.dataset.page),
      resolveComponent: name => require(`./Pages/${name}`).default,
    },
  }),
})
.$mount(el)
InertiaProgress.init()
