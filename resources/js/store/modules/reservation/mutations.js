export const mutations = {
  setDeparture: (state, payload) => {
    state.departure = payload;
  },
  setArrival: (state, payload) => {
    state.arrival = payload;
  },
  addService: (state, payload) => {
    state.selectedServices.push(payload);
  },
  removeService: (state, payload) => {
    state.selectedServices = state.selectedServices.filter(x => x.id !== payload.id);
  }
}
