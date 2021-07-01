<template>
  <div>
    <validation-observer ref="formObserver">
        <form>
            <div class="col-md-12 col-lg-11 mt-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 pt-2 mb-3">
                                <h4>
                                    <i class="fas fa-plane-departure text-primary mr-4"></i>
                                    Aller <small>( Prise en charge )</small>
                                </h4>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <b-form-group label="Lieu de départ">
                                    <validation-provider tag="div" rules="required" name="lieu de départ" v-slot="{ errors }">
                                        <b-form-select v-model="departure_place" name="departure_place" :options="stations">
                                            <template v-slot:first>
                                                <b-form-select-option :value="null" disabled>--- Choisisez une option ---</b-form-select-option>
                                            </template>
                                        </b-form-select>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <b-form-group label="Point de rendez vous">
                                    <b-form-select v-model="departure_meeting_point" name="departure_meeting_point" :options="meetingPoints" disabled>
                                        <template v-slot:first>
                                            <b-form-select-option :value="null" disabled>-- Choisisez une option --</b-form-select-option>
                                        </template>
                                    </b-form-select>
                                </b-form-group>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <b-form-group label="Date de départ">
                                    <validation-provider tag="div" rules="required" name="date de départ" v-slot="{ errors }">
                                        <b-form-datepicker
                                            v-model="departure_date"
                                            placeholder="Aucune date"
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
                            <div class="col-sm-6 col-md-3">
                                <b-form-group label="Heure de départ">
                                    <validation-provider tag="div" rules="required" name="heure de départ" v-slot="{ errors }">
                                        <b-form-timepicker
                                            v-model="departure_hour"
                                            placeholder="Aucune heure"
                                            now-button
                                            locale="fr"
                                            name="departure_date">
                                        </b-form-timepicker>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-11 mt-4">
                <div class="card border-0 shadow-lg">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 pt-2 mb-3">
                                <h4>
                                    <i class="fas fa-plane-arrival text-primary mr-4"></i>
                                    Retour <small>( Restitution )</small>
                                </h4>
                            </div>
                            <div class="col-sm-6 col-md-3">
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
                            <div class="col-sm-6 col-md-3">
                                <b-form-group label="Point de rendez vous">
                                    <b-form-select v-model="arrival_meeting_point" name="arrival_meeting_point" :options="meetingPoints" disabled>
                                        <template v-slot:first>
                                            <b-form-select-option :value="null" disabled>-- Choisisez une option --</b-form-select-option>
                                        </template>
                                    </b-form-select>
                                </b-form-group>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <b-form-group label="Date d'arrivée">
                                    <validation-provider tag="div" rules="required" name="date d'arrivée" v-slot="{ errors }">
                                        <b-form-datepicker
                                            v-model="arrival_date"
                                            placeholder="Aucune date"
                                            today-button
                                            :min="minArrrivalDate"
                                            locale="fr"
                                            name="arrival_date">
                                        </b-form-datepicker>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <b-form-group label="Heure d'arrivée">
                                    <validation-provider tag="div" rules="required" name="heure d'arrivée" v-slot="{ errors }">
                                        <b-form-timepicker
                                            v-model="arrival_hour"
                                            placeholder="Aucune heure"
                                            now-button
                                            locale="fr"
                                            name="departure_date">
                                        </b-form-timepicker>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 pb-5">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-xl-3 mt-4">
                        <button type="button" class="btn btn-secondary btn-lg" @click="getPrice">Obtenir mon tarif <i class="fas fa-arrow-right ml-3"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </validation-observer>
  </div>
</template>

<script>
import { formData } from '../constants';

export default {
    data: () => ({
        ...formData
    }),
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
        async getPrice() {
            const formObserver = await this.$refs.formObserver.validate();

            if (!formObserver) {
                return;
            }

            const form = {
                departure_place: this.departure_place,
                departure_meeting_point: this.departure_meeting_point,
                departure_date: this.departure_date,
                departure_hour: this.departure_hour,
                arrival_place: this.arrival_place,
                arrival_meeting_point: this.arrival_meeting_point,
                arrival_date: this.arrival_date,
                arrival_hour: this.arrival_hour
            }

            localStorage.setItem('quote', JSON.stringify(form));
            window.location.href = '/votre-reservation';
        }
    },
    watch: {
        departure_place(val) {
            this.departure_meeting_point = this.meetingPoints[this.stations.indexOf(val)];
        },
        arrival_place(val) {
            this.arrival_meeting_point = this.meetingPoints[this.stations.indexOf(val)];
        },
    }
}
</script>
