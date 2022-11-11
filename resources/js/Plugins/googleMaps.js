import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'
import VueGeolocation from 'vue-browser-geolocation';

Vue.use(VueGeolocation);
Vue.use(VueGoogleMaps, {
    load: {
      key: 'AIzaSyAVRDPLFcDadPbfVRVf_2Sm_HGLSAE7buc',
      libraries: 'places',
    },
})