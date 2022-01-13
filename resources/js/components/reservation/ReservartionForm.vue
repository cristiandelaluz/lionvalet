<template>
  <div class="card border-0 shadow">
    <div class="card-header text-center bg-white border-bottom-0">
        <h4 class="m-0">
            <i class="fas fa-calendar-day text-primary mr-3"></i>
            Votre Réservation
        </h4>
    </div>

    <div class="card-body">
        <validation-observer ref="formObserver">
            <form>
                <div class="row">
                    <div class="col-md-3 text-center">
                        <h3 class="text-primary"><i class="fas fa-plane-departure"></i></h3>
                        <h4>Départ</h4>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <b-form-group label="Lieu de départ">
                                    <validation-provider tag="div" rules="required" name="lieu de départ" v-slot="{ errors }">
                                        <b-form-select v-model="departure_place" name="departure_place" :options="stations">
                                            <template v-slot:first>
                                                <b-form-select-option :value="null" disabled>-- Choisisez une option --</b-form-select-option>
                                            </template>
                                        </b-form-select>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-6">
                                <b-form-group label="Point de rendez vous">
                                    <b-form-select v-model="departure_meeting_point" name="departure_meeting_point" :options="meetingPoints" disabled>
                                        <template v-slot:first>
                                            <b-form-select-option :value="null" disabled>-- Choisisez une option --</b-form-select-option>
                                        </template>
                                    </b-form-select>
                                </b-form-group>
                            </div>
                            <div class="col-md-6">
                                <b-form-group label="Date de départ">
                                    <validation-provider tag="div" rules="required" name="date de départ" v-slot="{ errors }">
                                        <b-form-datepicker
                                            v-model="departure_date"
                                            placeholder="Aucune date sélectionnée"
                                            today-button
                                            :min="today"
                                            :max="maxDepartDate"
                                            locale="fr"
                                            name="departure_date">
                                        </b-form-datepicker>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-6">
                                <b-form-group label="Heure de départ">
                                    <validation-provider tag="div" rules="required" name="heure de départ" v-slot="{ errors }">
                                        <b-form-timepicker
                                            v-model="departure_hour"
                                            placeholder="Aucune heure sélectionnée"
                                            now-button
                                            locale="fr"
                                            name="departure_date">
                                        </b-form-timepicker>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-6">
                                <b-form-group label="Numero de vol / train">
                                    <b-form-input v-model="departure_ticket_number" name="departure_ticket_number"></b-form-input>
                                </b-form-group>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3 text-center">
                        <h3 class="text-primary"><i class="fas fa-plane-arrival"></i></h3>
                        <h4>Arrivée</h4>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <b-form-group label="Lieu d'arrivée">
                                    <validation-provider tag="div" rules="required" name="lieu d'arrivée" v-slot="{ errors }">
                                        <b-form-select v-model="arrival_place" name="arrival_place" :options="stations">
                                            <template v-slot:first>
                                                <b-form-select-option :value="null" disabled>-- Choisisez une option --</b-form-select-option>
                                            </template>
                                        </b-form-select>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-6">
                                <b-form-group label="Point de rendez vous">
                                    <b-form-select v-model="arrival_meeting_point" name="arrival_meeting_point" :options="meetingPoints" disabled>
                                        <template v-slot:first>
                                            <b-form-select-option :value="null" disabled>-- Choisisez une option --</b-form-select-option>
                                        </template>
                                    </b-form-select>
                                </b-form-group>
                            </div>
                            <div class="col-md-6">
                                <b-form-group label="Date d'arrivée">
                                    <validation-provider tag="div" rules="required" name="date d'arrivée" v-slot="{ errors }">
                                        <b-form-datepicker
                                            v-model="arrival_date"
                                            placeholder="Aucune date sélectionnée"
                                            today-button
                                            :min="minArrrivalDate"
                                            locale="fr"
                                            name="arrival_date">
                                        </b-form-datepicker>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-6">
                                <b-form-group label="Heure d'arrivée">
                                    <validation-provider tag="div" rules="required" name="heure d'arrivée" v-slot="{ errors }">
                                        <b-form-timepicker
                                            v-model="arrival_hour"
                                            placeholder="Aucune heure sélectionnée"
                                            now-button
                                            locale="fr"
                                            name="departure_date">
                                        </b-form-timepicker>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-6">
                                <b-form-group label="Numero de vol / train">
                                    <b-form-input v-model="arrival_ticket_number" name="arrival_ticket_number"></b-form-input>
                                </b-form-group>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </validation-observer>
    </div>
</div>
</template>

<script>
import { mapActions } from 'vuex';
import { formData } from '../../constants';

export default {
    data: () => ({
        ...formData
    }),
    created() {
        const form = localStorage.getItem('quote');

        if (form) {
            const content = JSON.parse(form);
            this.departure_place = content.departure_place;
            this.departure_meeting_point = content.departure_meeting_point;
            this.departure_date = content.departure_date;
            this.departure_hour = content.departure_hour;
            this.departure_ticket_number = content.departure_ticket_number,
            this.arrival_place = content.arrival_place;
            this.arrival_meeting_point = content.arrival_meeting_point;
            this.arrival_date = content.arrival_date;
            this.arrival_hour = content.arrival_hour;
            this.arrival_ticket_number = content.arrival_ticket_number;
            this.is_forwarding = content.is_forwarding;
        }

        this.isThereForwarding(this.departure_place, this.arrival_place);
    },
    computed: {
        today() {
            const now = new Date();
            return new Date(now.getFullYear(), now.getMonth(), now.getDate());
        },
        minArrrivalDate() {
            if (!this.departure_date) {
                return this.today;
            }

            const splittedDate = this.departure_date.split('-');

            return new Date(parseInt(splittedDate[0]), parseInt(splittedDate[1]) - 1, parseInt(splittedDate[2]));
        },
        maxDepartDate() {
            if (!this.arrival_date) {
                return null;
            }

            const splittedDate = this.arrival_date.split('-');

            return new Date(parseInt(splittedDate[0]), parseInt(splittedDate[1]) - 1, parseInt(splittedDate[2]));
        }
    },
    methods: {
        ...mapActions('reservation', ['setDeparture', 'setArrival']),
        async submitNow() {
            const formObserver = await this.$refs.formObserver.validate();

            if (!formObserver) {
                return false;
            }

            const quoteFormData = {
                departure_place: this.departure_place,
                departure_meeting_point: this.departure_meeting_point,
                departure_date: this.departure_date,
                departure_hour: this.departure_hour,
                departure_ticket_number: this.departure_ticket_number,
                arrival_place: this.arrival_place,
                arrival_meeting_point: this.arrival_meeting_point,
                arrival_date: this.arrival_date,
                arrival_hour: this.arrival_hour,
                arrival_ticket_number: this.arrival_ticket_number,
                is_forwarding: this.is_forwarding,
                services: [],
            };

            localStorage.setItem('quoteFormData', JSON.stringify(quoteFormData));

            return true;
        },
        emitDeparture() {
            if (this.departure_place && this.departure_date && this.departure_hour) {
                this.setDeparture({ place: this.departure_place, date: this.departure_date, hour: this.departure_hour });
            }
        },
        emitArrival() {
            if (this.arrival_place && this.arrival_date && this.arrival_hour) {
                this.setArrival({ place: this.arrival_place, date: this.arrival_date, hour: this.arrival_hour });
            }
        },
        isThereForwarding(departure, arrival) {
            if (!departure) {
                return false;
            }

            if (!arrival) {
                return false;
            }

            this.$emit('isForwarding', departure !== arrival);
        }
    },
    watch: {
        departure_place(val) {
            this.departure_meeting_point = this.meetingPoints[this.stations.indexOf(val)];
            this.emitDeparture();
            this.isThereForwarding(val, this.arrival_place);
        },
        arrival_place(val) {
            this.arrival_meeting_point = this.meetingPoints[this.stations.indexOf(val)];
            this.emitArrival();
            this.isThereForwarding(this.departure_place, val);
        },
        departure_date() {
            this.emitDeparture();
        },
        arrival_date() {
            this.emitArrival();
        },
        departure_hour() {
            this.emitDeparture();
        },
        arrival_hour() {
            this.emitArrival();
        }
    }
}
</script>