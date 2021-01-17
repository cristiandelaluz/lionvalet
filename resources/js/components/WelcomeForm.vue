<template>
  <div>
    <div class="col-lg-9 col-xl-8 mt-5">
        <div class="card border-0 shadow-lg">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-12 pt-2 mb-3">
                            <h4>
                                <i class="fas fa-plane-departure text-primary mr-4"></i>
                                Aller <small>( Prise en charge )</small>
                            </h4>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="from">Lieu de départ</label>
                                <input type="text" class="form-control" v-model="takeCar.location">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="from">Rendez vouz</label>
                                <input type="text" class="form-control" v-model="takeCar.point">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="from">Jour de départ</label>
                                <input type="text" class="form-control" v-model="takeCar.date">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="from">Heure de départ</label>
                                <input type="text" class="form-control" v-model="takeCar.time">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-xl-8 mt-4">
        <div class="card border-0 shadow-lg">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-12 pt-2 mb-3">
                            <h4>
                                <i class="fas fa-plane-arrival text-primary mr-4"></i>
                                Retour <small>( Restitution )</small>
                            </h4>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="from">Lieu de retour</label>
                                <input type="text" class="form-control" v-model="returnCar.location">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="from">Rendez vouz</label>
                                <input type="text" class="form-control" v-model="returnCar.point">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="from">Jour de retour</label>
                                <input type="text" class="form-control" v-model="returnCar.date">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="from">Heure de retour</label>
                                <input type="text" class="form-control" v-model="returnCar.time">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 pb-5">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-xl-3 mt-4">
                <button type="button" class="btn btn-secondary btn-lg">Obtenir mon tarif <i class="fas fa-arrow-right ml-3"></i></button>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
    data: () => ({
        takeCar: {
            location: null,
            point: null,
            date: null,
            time: null,
        },
        returnCar: {
            location: null,
            point: null,
            date: null,
            time: null,
        }
    })
}
</script>
