@extends('layouts.app')

@section('content')
@php
    $isAdmin = Auth::user()->hasRole('admin') ? 1 : 0;
@endphp
<div class="container section__content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-bottom-0">
                    <h4 class="m-0">
                        <i class="fas fa-clipboard-list text-primary mr-3"></i>
                        Réservations à venir
                    </h4>
                </div>

                <div class="card-body">
                    <reservations-list :reservations="{{ $reservations }}" :is-admin="{{ $isAdmin }}" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const pendingQuote = JSON.parse(localStorage.getItem('pendingQuote'));
const quoteFormData = JSON.parse(localStorage.getItem('quoteFormData'));

if (pendingQuote) {
    window.axios.post('/reservations', quoteFormData).then((response) => {
        console.log(response);
        const {data} = response;
        const {reservation} = data;
        localStorage.setItem('pendingQuote', false);
        localStorage.removeItem('quote');
        localStorage.removeItem('quoteFormData');
        // location.reload();
        window.location.href = `/reservations/payment/${reservation.id}`;
    });
}
</script>
@endpush