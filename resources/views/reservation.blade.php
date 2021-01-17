@extends('layouts.app')

@section('content')
<div class="container section__content">
    <div class="row justify-content-center">
        <reservation-manager :services="{{ $services }}" />
    </div>
</div>
@include('layouts.footer')
@endsection
