<template>
    <fragment>
        <div class="col-md-12 col-lg-9">
            <reservation-form
                ref="reservationForm"
                @isForwarding="(flag) => isForwarding = flag" />
            <reservation-services
                :services="extraServices" />
        </div>
        <div class="col-md-12 col-lg-3">
            <div class="sticky-element" style="top: 5rem;">
                <div class="card border-0 shadow">
                    <div class="card-header text-center bg-secondary text-white border-bottom-0">
                        <h5 class="m-0 font-weight-bold">Récapitulatif</h5>
                        <small v-if="daysNumber">pour {{ daysNumber }} {{ daysNumber > 1 ? 'jours' : 'jour' }}</small>
                    </div>
                    <div class="card-body p-3 text-center">
                        <div class="card border-info mb-3" style="border-radius: 1rem !important;">
                            <div class="card-body px-3 py-2">
                                <p class="m-0 font-weight-bold text-primary">Votre trajet</p>
                                <div v-if="departure && arrival">
                                    <p class="small font-weight-bold text-secondary mb-1">PARKING + VOITURIER</p>
                                    <p class="mb-0 mt-2"><i v-if="departureOnNigth" class="fas fa-moon"></i></p>
                                    <p class="mb-1">{{ departure.place }}</p>
                                    <span class="my-3 h5 text-secondary"><i class="fas fa-arrow-down"></i></span>
                                    <p class="mb-0 mt-2"><i v-if="arrivalOnNigth" class="fas fa-moon"></i></p>
                                    <p class="mb-0">{{ arrival.place }}</p>
                                    <hr class="border-info my-2" />
                                    <p class="m-0 font-weight-bold text-secondary">Total: {{ totalParking }} €</p>
                                </div>
                            </div>
                        </div>

                        <div v-if="selectedServices.length || isForwarding" class="card border-info" style="border-radius: 1rem !important;">
                            <div class="card-body p-2">
                                <p class="m-0 font-weight-bold text-primary">Vos options</p>
                                <div v-if="isForwarding">
                                    <p class="m-0 text-warning">Acheminement <strong>+{{ forwardingPrice }}€</strong></p>
                                </div>
                                <div v-if="selectedServices.length || isForwarding">
                                    <p class="m-0" v-for="service of selectedServices" :key="service.id">{{ service.name }}</p>
                                    <hr class="border-info my-2" />
                                    <p class="m-0 font-weight-bold text-secondary">Total: {{ isForwarding ? totalServices + forwardingPrice : totalServices }} €</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 mt-3 shadow">
                    <div class="card-body">
                        <h6>Montant Total</h6>
                        <h3 id="bill-total" class="text-secondary text-center m-0">{{ isForwarding ? (totalServices + totalParking + forwardingPrice) :  totalServices + totalParking}} €</h3>
                    </div>
                </div>
                <div class="text-center">
                    <b-button variant="primary" size="lg" class="mt-4 shadow" @click="submit">
                        Je valide ma réservation <i class="fas fa-lg fa-check-circle"></i>
                    </b-button>
                </div>
            </div>
        </div>
    </fragment>
</template>

<script>
    import { mapGetters } from 'vuex';
    import { Fragment } from 'vue-fragment';
    import ReservationForm from './reservation/ReservartionForm';
    import ReservationServices from './reservation/ReservationServices';
    import { parkingPrice, regularPrice, nigthPrice } from '../constants';
    import moment from 'moment';

    export default {
        components: {
            Fragment,
            ReservationForm,
            ReservationServices,
        },
        props: {
            services: {
                type: Array,
                default: () => [],
            },
            isAuth: {
                type: Number,
                default: 0,
            },
        },
        created() {
            for (const service of this.services) {
                const handled = {...service};
                handled.price = parseFloat(handled.price);
                this.extraServices.push(handled);
            }
        },
        data: () => ({
            extraServices: [],
            daysNumber: 0,
            totalParking: 0,
            departureOnNigth: false,
            arrivalOnNigth: false,
            isForwarding: false,
            forwardingPrice: 45,
        }),
        computed: {
            ...mapGetters('reservation', ['selectedServices', 'departure', 'arrival', 'dateAreReady']),
            totalServices() {
                return this.selectedServices.reduce((counter, current) => {
                    return counter + current.price;
                }, 0);
            }
        },
        methods: {
            async submit() {
                const validated = await this.$refs.reservationForm.submitNow();
                if (!validated) {
                    return;
                }

                const quoteFormData = JSON.parse(localStorage.getItem('quoteFormData'));
                quoteFormData.services = this.selectedServices.map(s => s.id);

                if (!this.isAuth) {
                    localStorage.setItem('pendingQuote', true);
                    localStorage.setItem('quoteFormData', JSON.stringify(quoteFormData));
                    window.location.href = '/login';
                    return;
                }

                window.axios.post('/reservations', quoteFormData).then((response) => {
                    const {reservation} = response.data;
                    localStorage.setItem('pendingQuote', false);
                    localStorage.removeItem('quote');
                    localStorage.removeItem('quoteFormData');
                    window.location.href = `/reservations/payment/${reservation.id}`;
                });
            },
            getTotal() {
                if (this.departure && this.arrival) {
                    const departure = moment(this.departure.date);
                    const arrival = moment(this.arrival.date);
                    this.departureOnNigth = false;
                    this.arrivalOnNigth = false;

                    this.daysNumber = arrival.diff(departure, 'days');

                    if (this.daysNumber <= 0) {
                        this.daysNumber = 1;
                    }

                    this.totalParking = parkingPrice * this.daysNumber + regularPrice;

                    // time
                    const departureHourSplitted = this.departure.hour.split(':');
                    const arrivalHourSplitted = this.arrival.hour.split(':');

                    if (departureHourSplitted[0] >= 0 && departureHourSplitted[0] <= 6) {
                        this.totalParking += nigthPrice - regularPrice;
                        this.departureOnNigth = true;

                        if (departureHourSplitted[0] == 6 && departureHourSplitted[1] > 0) {
                            this.totalParking -= nigthPrice;
                            this.totalParking += regularPrice;
                            this.departureOnNigth = false;
                        }
                    }

                    if (arrivalHourSplitted[0] >= 0 && arrivalHourSplitted[0] <= 6 && arrivalHourSplitted[1] < 1) {
                        this.totalParking += nigthPrice - regularPrice;
                        this.arrivalOnNigth = true;

                        if (arrivalHourSplitted[0] == 6 && arrivalHourSplitted[1] > 0) {
                            this.totalParking -= nigthPrice;
                            this.totalParking += regularPrice;
                            this.arrivalOnNigth = false;
                        }
                    }
                }
            }
        },
        watch: {
            arrival() {
                if (this.dateAreReady) {
                    this.getTotal();
                }
            },
            departure() {
                if (this.dateAreReady) {
                    this.getTotal();
                }
            }
        }
    }

</script>
