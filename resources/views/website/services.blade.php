<section class="pt-5 pb-5">
    <div class="container pb-5">
        <h1 class="text-center text-uppercase font-weight-bold pt-4 pb-2">Extra Services</h1>
        <div class="row">
            @foreach ($services as $service)
                <div class="col-md-6 col-lg-4 col-xl-3 mt-4">
                    <div class="card border-0 shadow-sm">    
                        <div class="card-body text-center">
                            <img width="100" class="py-3" src="{{ asset('assets/' . $service->img_name) }}" alt="Service">
                            <h6 class="mt-2">{{ $service->name }}</h6>
                            <h2 class="mt-3 font-weight-bold">{{ $service->price }} €</h2>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
