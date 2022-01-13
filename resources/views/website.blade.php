@extends('layouts.app')

@section('content')
<div class="index pt-5">
    <div class="container-fluid px-5">
        <img class="driver d-none d-md-block" src="{{ asset('assets/driver.svg') }}" alt="">
        <div class="row">
            <div class="col-12 mt-5 pt-5 d-none d-md-block">
                <h1 class="text-center lionvalet-main-text text-uppercase" style="font-size: 3rem;">
                    Votre voiturier, la proximité et le professionnalisme
                </h1>
            </div>
            <welcome-form />
        </div>
    </div>
</div>
@include('website.aboutus')
@include('website.timetable')
@include('website.services')
@include('layouts.footer')
@endsection