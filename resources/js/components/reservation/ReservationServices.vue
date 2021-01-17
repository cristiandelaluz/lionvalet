<template>
<div class="card border-0 shadow mt-4">
    <div class="card-header text-center bg-white border-bottom-0">
        <h4>
            <i class="fas fa-cart-plus text-primary mr-3"></i>
            Vos services
        </h4>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3" v-for="service in servicesList" :key="service.id">
                <div class="card border-primary">
                    <div class="card-body text-center p-3">
                        <img width="50" :src="`../assets/${service.img_name}`" alt="Service">
                        <p class="py-2 m-0 small">{{ service.name }}</p>
                        <h5 class="pb-2 m-0 font-weight-bold text-primary">{{ service.price }} €</h5>
                        <b-button v-if="!service.selected" size="sm" @click="addToCart(service.id)">Ajouter</b-button>
                        <b-button v-else variant="danger" size="sm" @click="removeToCart(service.id)">Annuler</b-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
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
        addToCart(id) {
            this.$emit('addToCart', id);
            this.servicesList.find(x => x.id === id).selected = true;
        },
        removeToCart(id) {
            this.$emit('removeToCart', id);
            this.servicesList.find(x => x.id === id).selected = false;
        }
    }
}
</script>