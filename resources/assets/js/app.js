
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';

import VueGoodTablePlugin from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css'
import moment from 'moment';

import VueRouter from 'vue-router';
import {routes} from './routes';


import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

import 'material-design-icons-iconfont/dist/material-design-icons.css' // Ensure you are using css-loader

// import '@syncfusion/ej2-base/styles/material.css'

Vue.use(VueRouter);

Vue.use(require('vue-moment'));

Vue.use(VueGoodTablePlugin);

Vue.use(Vuetify);
//
Vue.use(Vuex);
Vue.component('App', require('./components/app'));

const router = new VueRouter({
    routes
})

const store = new Vuex.Store({
    // ...
    plugins: [createPersistedState()],
    state: {
        patient: {}
    },
    getters() {


    },
    mutations: {
        setCurrentPatient(state, payload) {
            state.patient = payload
        }
    }
})

const app = new Vue({
    el: '#app',
    router,
    Vuetify,
    store: store,
    template: '<App></App>',


});
