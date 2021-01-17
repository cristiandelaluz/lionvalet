<template>
  <div class="card border-0 shadow">
    <div class="card-header text-center bg-white border-bottom-0">
        <h4>
            <i class="fas fa-calendar-day text-primary mr-3"></i>
            Votre Réservation
        </h4>
    </div>

    <div class="card-body">
        <validation-observer ref="formObserver">
            <form action="/clients/reserve" method="post" ref="form" id="formObserver">
                <input type="hidden" name="_token" id="csrf-token" :value="csrfToken" />
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
export default {
    data: () => ({
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
    }),
    updated() {
        if (this.departure_place && this.departure_date) {
            this.$emit('departure-ok', this.departure_date);
        }

        if (this.arrival_place && this.arrival_date) {
            this.$emit('arrival-ok', this.arrival_date);
        }
    },
    computed: {
        csrfToken() {
            return document.getElementById('csrf-token').getAttribute('content');
        }
    },
    methods: {
        async submitNow() {
            const formObserver = await this.$refs.formObserver.validate();

            if (!formObserver) {
                return;
            }

            const servicesInput = document.createElement('input');
            servicesInput.type = 'text';
            servicesInput.name = 'services[]';
            servicesInput.value = ['dog', 'cat'];
            document.getElementById('formObserver').appendChild(servicesInput);

            this.$refs.form.submit();

            console.log('its good !');
        }
    },
    watch: {
        departure_place(val) {
            this.departure_meeting_point = this.meetingPoints[this.stations.indexOf(val)];
        },
        arrival_place(val) {
            this.arrival_meeting_point = this.meetingPoints[this.stations.indexOf(val)];
        }
    }
}
</script>