import { mutations } from './mutations';
import { actions } from './actions';
import { getters } from './getters';

const reservation = {
  namespaced: true,
  state : {
    departure: null,
    arrival: null,
    selectedServices: [],
  },
  mutations,
  actions,
  getters,
};

export default reservation;
