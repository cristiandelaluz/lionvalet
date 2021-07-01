<template>
<div class="card border-0 shadow mt-4">
    <div class="card-header text-center bg-white border-bottom-0">
        <h4 class="m-0">
            <i class="fas fa-cart-plus text-primary mr-3"></i>
            Vos services
        </h4>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 mb-3" v-for="service in servicesList" :key="service.id">
                <div class="card border-secondary">
                    <div class="card-body text-center p-3">
                        <img width="50" :src="`../assets/${service.img_name}`" alt="Service">
                        <p class="py-2 m-0 small">{{ service.name }}</p>
                        <h5 class="pb-2 m-0 font-weight-bold text-secondary">{{ service.price }} €</h5>
                        <b-button v-if="!service.selected" variant="primary" size="sm" @click="addToCart(service.id)">Ajouter</b-button>
                        <b-button v-else variant="danger" size="sm" @click="removeToCart(service.id)">Annuler</b-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    props: {
        services: {
            type: Array,
            default: () => [],
        },
    },
    data: () => ({
        servicesList: [],
    }),
    mounted() {
        for (const service of this.services) {
            this.servicesList.push({
                ...service,
                selected: false
            });
        }
    },
    methods: {
        ...mapActions('reservation', ['addService', 'removeService']),
        addToCart(id) {
            const found = this.servicesList.find(x => x.id === id);
            found.selected = true;
            this.addService(found);
        },
        removeToCart(id) {
            const found = this.servicesList.find(x => x.id === id);
            found.selected = false;
            this.removeService(found);
        }
    }
}
</script>