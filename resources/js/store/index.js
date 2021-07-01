import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

// Modules
import reservation from './modules/reservation';

export const store = new Vuex.Store({
  state: {
    //
  },
  modules: {
    reservation,
  }
})