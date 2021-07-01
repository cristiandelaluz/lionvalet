export const getters = {
  departure: state => state.departure,
  arrival: state => state.arrival,
  selectedServices: state => state.selectedServices,
  dateAreReady: state => {
    if (state.departure && state.arrival) {
      return true;
    }

    return false;
  },
}
