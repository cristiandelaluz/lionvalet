@extends('layouts.app')

@section('content')
<div class="index pt-5">
    <div class="container-fluid px-5">
        <img class="driver d-none d-md-block" src="{{ asset('assets/driver.svg') }}" alt="">
        <div class="row">
            <div class="col-12 mt-5 pt-5 d-none d-md-block">
                <h1 class="text-center lionvalet-main-text" style="font-size: 3rem;">
                    {{ __('VOTRE PARKING AVEC VOITURIER DANS LES AÉROPORTS ET LES GARES') }}
                </h1>
            </div>
            <welcome-form />
        </div>
    </div>
</div>
@include('website.services')
@include('website.pricing')
@include('website.contact')
@include('layouts.footer')
@endsection