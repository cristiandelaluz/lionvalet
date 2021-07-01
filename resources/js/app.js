/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import { store } from './store/index';

import { BootstrapVue, BVConfigPlugin } from 'bootstrap-vue'
// Install BootstrapVue
Vue.use(BootstrapVue);

Vue.use(BVConfigPlugin, {
    BFormDatepicker: {
        "labelNoDateSelected": 'Aucune date',
        "labelHelp": "Utilisez les touches fléchées pour parcourir les dates du calendrier",
        "labelTodayButton": "Aujourd'hui",
        "labelResetButton": "Effacer",
        "labelCloseButton": "Fermer"
    },
    BFormTimepicker: {
        "labelNoTimeSelected": 'Aucune heure', 
        "labelNowButton": 'Maintenant',
        "labelResetButton": 'Effacer',
        "labelCloseButton": 'Fermer'
    },
});

// Validation forms
import { ValidationProvider, ValidationObserver, extend, localize } from 'vee-validate';
import fr from 'vee-validate/dist/locale/fr.json';
import * as rules from 'vee-validate/dist/rules';
import Vue from 'vue';
import moment from 'moment';
moment.locale('fr');

localize('fr', fr);
for (let [rule, validation] of Object.entries(rules)) {
  extend(rule, {
    ...validation,
  });
}
Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);

Vue.config.debug = true;
Vue.config.devtools = true;

// Website
Vue.component('welcome-form', require('./components/WelcomeForm.vue').default);
Vue.component('reservation-manager', require('./components/ReservationManager.vue').default);
Vue.component('reservations-list', require('./components/ReservationsList.vue').default);

Vue.prototype.$moment = moment;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store
});
