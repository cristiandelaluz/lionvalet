export const actions = {
  setDeparture: ({ commit }, payload) => {
    commit('setDeparture', payload);
  },
  setArrival: ({ commit }, payload) => {
    commit('setArrival', payload);
  },
  addService: ({ commit }, payload) => {
    commit('addService', payload);
  },
  removeService: ({ commit }, payload) => {
    commit('removeService', payload);
  }
}
