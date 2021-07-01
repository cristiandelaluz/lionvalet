@extends('layouts.app')

@section('content')
@php
    $check = Auth::check() ? 1 : 0;
@endphp
<div class="container section__content">
    <div class="row justify-content-center">
        <reservation-manager :services="{{ $services }}" :is-auth="{{ $check }}" />
    </div>
</div>
@include('layouts.footer')
@endsection
