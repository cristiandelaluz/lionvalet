<template>
    <fragment>
        <div class="col-md-12 col-lg-9 mb-3">
            <reservation-form
                ref="reservationForm"
                @departure-ok="departureOk"
                @arrival-ok="arrivalOk" />
            <reservation-services
                :services="services"
                @addToCart="addToCart" 
                @removeToCart="removeToCart" />
        </div>
        <div class="col-md-12 col-lg-3">
            <div class="sticky-element" style="top: 5rem;">
                <div class="card border-0 shadow">
                    <div class="card-header text-center bg-secondary text-white border-bottom-0">
                        <h5 class="m-0 font-weight-bold">Récapitulatif</h5>
                    </div>
                    <div class="card-body p-3 text-center">
                        <div class="card border-info mb-3" style="border-radius: 1rem !important;">
                            <div class="card-body px-3 py-1">
                                <p class="small font-weight-bold text-primary mb-1">PARKING + VOITURIER</p>
                                <p class="mb-1">Bordo</p>
                                <p class="mb-0">Bordo</p>
                                <hr class="border-info my-2" />
                                <p class="m-0 font-weight-bold text-primary">{{ priceForStay }} €</p>
                            </div>
                        </div>

                        <div class="card border-info" style="border-radius: 1rem !important;">
                            <div class="card-body p-3">
                                <h5>Vos options</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 mt-3 shadow">
                    <div class="card-body">
                        <h6>Montant Total</h6>
                        <h3 id="bill-total" class="text-secondary text-center m-0">{{ total }} €</h3>
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
    import {
        Fragment
    } from 'vue-fragment';
    import ReservationForm from './reservation/ReservartionForm';
    import ReservationServices from './reservation/ReservationServices';
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
        },
        data: () => ({
            total: 0,
            selectedServices: [],
            departureDate: null,
            arrivalDate: null,
            daysNumber: 0,
            priceForStay: 0,
        }),
        methods: {
            addToCart(id) {
                this.total += this.services.find(x => x.id === id).price;
            },
            removeToCart(id) {
                this.total -= this.services.find(x => x.id === id).price;
            },
            departureOk(date) {
                this.departureDate = date;
            },
            arrivalOk(date) {
                this.arrivalDate = date;
            },
            submit() {
                this.$refs.reservationForm.submitNow();
            }
        },
        watch: {
            arrivalDate() {
                if (this.departureDate) {
                    const departure = moment(this.departureDate);
                    const arrival = moment(this.arrivalDate);

                    this.daysNumber = arrival.diff(departure, 'days');

                    if (this.priceForStay > 0) {
                        this.total -= this.priceForStay;
                    }

                    this.priceForStay = this.daysNumber * 59;
                    this.total += this.priceForStay;
                }
            }
        }
    }

</script>
