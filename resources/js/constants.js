export const parkingPrice = 4;
export const regularPrice = 37;
export const nigthPrice = 58;

export const formData = {
  departure_place: null,
  departure_meeting_point: null,
  departure_date: null,
  departure_hour: null,
  departure_ticket_number: null,
  arrival_place: null,
  arrival_meeting_point: null,
  arrival_date: null,
  arrival_hour: null,
  arrival_ticket_number: null,
  stations: [
      'Bordeaux Gare Saint Jean',
      'Bordeaux Merignac Aeroport'
  ],
  meetingPoints: [
      'Dépose minutes P1',
      'Parking Express'
  ],
  is_forwarding: false,
}

// Reservations status
export const RESERVATION_PENDING = 'PENDING';
export const RESERVATION_PAID = 'PAID';
export const RESERVATION_REFUNDED = 'REFUNDED';