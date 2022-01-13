<template>
  <b-overlay :show="loading" rounded="sm">
    <b-tabs content-class="mt-3" justified pills>
      <b-tab title="Réservations à venir" active>
        <div :class="`d-flex card py-3 px-4 ${!index ? '' : 'mt-3'}`" style="border-radius: 1rem !important;" v-for="(reservation, index) of upcoming" :key="reservation.id">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex">
              <h5 class="m-0">{{ reservation.departure_place }}</h5> <h5 class="m-0"><i class="fas fa-arrow-right text-primary mx-4"></i></h5> <h5 class="m-0">{{ reservation.arrival_place }}</h5>
            </div>
            <div class="d-flex align-items-center">
              <b-button v-if="isAdmin" variant="secondary" size="sm" class="mr-3" @click="showInfoClient(reservation.client)">
                Info du client
              </b-button>
              <h3 class="text-secondary m-0">{{ reservation.total + reservation.services_total }} €</h3>
              <button v-if="reservation.status === RESERVATION_PENDING && !isAdmin" class="btn btn-sm btn-warning ml-3" @click="goToPay(reservation.id)">Payer</button>
              <button v-if="reservation.status === RESERVATION_PAID && checkAnnulation(reservation) && !isAdmin" @click="refund(reservation)" class="btn btn-sm btn-secondary ml-3">Annuler</button>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12" v-if="reservation.status === RESERVATION_PENDING">
              <b-alert show variant="warning">
                Cette réservation n'a pas été payée.
              </b-alert>
            </div>
            <div class="col-12" v-if="reservation.status === RESERVATION_REFUNDED">
              <b-alert show variant="secondary">
                Cette réservation a été annulée et elle será remboursée.
              </b-alert>
            </div>
            <div class="col-sm-12 col-md-6">
              <p class="text-primary mb-0"><strong>Départ</strong></p>
              <p class="mb-0">Rendez vous: <strong>{{ reservation.departure_meeting_point }}</strong> le <strong>{{ formatDate(reservation.departure_date) }}</strong> à <strong>{{ reservation.departure_hour }}</strong></p>
              <p class="mb-0">Numéro de vol: 
                <strong v-if="reservation.departure_ticket_number">{{ reservation.departure_ticket_number }}</strong>
                <span v-else>Non rempli</span>
              </p>
            </div>

            <div class="col-sm-12 col-md-6">
              <p class="text-secondary mb-0"><strong>Arrivée</strong></p>
              <p class="mb-0">Rendez vous: <strong>{{ reservation.arrival_meeting_point }}</strong> le <strong>{{ formatDate(reservation.arrival_date) }}</strong> à <strong>{{ reservation.arrival_hour }}</strong></p>
              <p class="mb-0">Numéro de vol: 
                <strong v-if="reservation.arrival_ticket_number">{{ reservation.arrival_ticket_number }}</strong>
                <span v-else>Non rempli</span>
              </p>
            </div>

            <div class="col-12 mt-4" v-if="reservation.extra_services.length">
              <div class="row">
                <div class="col-6 col-sm-4 col-md-2 text-center" v-for="service of reservation.extra_services" :key="service.id">
                  <img :src="`assets/${service.img_name}`" width="60px">
                  <small class="d-block">{{ service.name }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <h3 class="text-center mt-5" v-if="!upcoming.length">Il n'y a aucune réservation</h3>
      </b-tab>
      <b-tab title="Réservations passées">
        <div :class="`d-flex card py-3 px-4 ${!index ? '' : 'mt-3'}`" style="border-radius: 1rem !important;" v-for="(reservation, index) of done" :key="reservation.id">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex">
              <h5 class="m-0">{{ reservation.departure_place }}</h5> <h5 class="m-0"><i class="fas fa-arrow-right text-primary mx-4"></i></h5> <h5 class="m-0">{{ reservation.arrival_place }}</h5>
            </div>
            <div class="d-flex align-items-center">
              <b-button v-if="isAdmin" variant="secondary" size="sm" class="mr-3" @click="showInfoClient(reservation.client)">
                Info du client
              </b-button>
              <h3 class="text-secondary m-0">{{ reservation.total + reservation.services_total }} €</h3>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12" v-if="reservation.status === RESERVATION_PENDING">
              <b-alert show variant="danger">
                Cette réservation n'a pas été effectuée
              </b-alert>
            </div>
            <div class="col-sm-12 col-md-6">
              <p class="text-primary mb-0"><strong>Départ</strong></p>
              <p class="mb-0">Rendez vous: <strong>{{ reservation.departure_meeting_point }}</strong> le <strong>{{ formatDate(reservation.departure_date) }}</strong> à <strong>{{ reservation.departure_hour }}</strong></p>
              <p class="mb-0">Numéro de vol: 
                <strong v-if="reservation.departure_ticket_number">{{ reservation.departure_ticket_number }}</strong>
                <span v-else>Non rempli</span>
              </p>
            </div>

            <div class="col-sm-12 col-md-6">
              <p class="text-secondary mb-0"><strong>Arrivée</strong></p>
              <p class="mb-0">Rendez vous: <strong>{{ reservation.arrival_meeting_point }}</strong> le <strong>{{ formatDate(reservation.arrival_date)  }}</strong> à <strong>{{ reservation.arrival_hour }}</strong></p>
              <p class="mb-0">Numéro de vol: 
                <strong v-if="reservation.arrival_ticket_number">{{ reservation.arrival_ticket_number }}</strong>
                <span v-else>Non rempli</span>
              </p>
            </div>

            <div class="col-12 mt-4" v-if="reservation.extra_services.length">
              <div class="row">
                <div class="col-6 col-sm-4 col-md-2 text-center" v-for="service of reservation.extra_services" :key="service.id">
                  <img :src="`assets/${service.img_name}`" width="60px">
                  <small class="d-block">{{ service.name }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <h3 class="text-center mt-5" v-if="!done.length">Il n'y a aucune réservation</h3>
      </b-tab>
    </b-tabs>

    <b-modal v-model="modalShow" title="Information du client" size="sm" ok-only centered hide-header-close>
      <div v-if="clientInfo">
        <p>Nom : <strong>{{ clientInfo.name }} {{ clientInfo.lastname }}</strong></p>
        <p>Nº de matricule : <strong class="text-secondary">{{ clientInfo.license_plate_number }}</strong></p>
        <p>Téléphone : <strong>{{ clientInfo.phone }}</strong></p>
        <p>E-mail : <strong>{{ clientInfo.user.email }}</strong></p>
      </div>
    </b-modal>
  </b-overlay>
</template>

<script>
import moment from 'moment';
import { RESERVATION_PENDING, RESERVATION_PAID, RESERVATION_REFUNDED } from './../constants';

export default {
  props: {
    reservations: {
      type: Array,
      default: () => [],
    },
    isAdmin: {
      type: Number,
      default: 0,
    }
  },
  data: () => ({
    upcoming: [],
    done: [],
    clientInfo: null,
    modalShow: false,
    RESERVATION_PENDING,
    RESERVATION_PAID,
    RESERVATION_REFUNDED,
    loading: false,
  }),
  created() {
    this.checkReservations();
  },
  methods: {
    checkReservations() {
      const now = moment();

      for (const r of this.reservations) {
        const departure = moment(r.departure_date);

        if (now.diff(departure, 'days') <= 0) {
          this.upcoming.push(r);
        } else {
          this.done.push(r);
        }
      }
    },
    formatDate(date) {
      const unformated = moment(date);
      return unformated.format("dddd, Do MMMM YYYY");
    },
    showInfoClient(client) {
      this.clientInfo = client;
      this.modalShow = true;
    },
    goToPay(id) {
      window.location.href = `/reservations/payment/${id}`;
    },
    checkAnnulation(reservation) {
      if (!reservation) {
        return false;
      }

      if (!reservation.extra_services) {
        return false;
      }

      return reservation.extra_services.find(x => x.name === 'Annulation')
    },
    refund(reservation) {
      this.loading = true;
      window.axios.post('/refund', {
        id: reservation.id,
        payment_method_id: reservation.payment_method_id
      })
      .then(() => {
        const toUpdate = this.reservations.find(x => x.id === reservation.id);
        toUpdate.status = RESERVATION_REFUNDED;
        this.$bvToast.toast('La reservation à été annulée', {
          title: 'Annulation',
          variant: 'success',
          solid: true
        });
      })
      .catch(() => {
        this.$bvToast.toast("Une erreur s'est produite, réessayez plus tard", {
          title: 'Annulation',
          variant: 'danger',
          solid: true
        });
      })
      .finally(() => {
        this.loading = false;
      });
    }
  }
}
</script>
