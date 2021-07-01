@extends('layouts.app')

@section('content')
<div class="container section__content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-bottom-0">
                    <h4 class="m-0">
                        <i class="fas fa-user-friends text-primary mr-3"></i>
                        Tous les clients
                    </h4>
                </div>

                <div class="card-body">
                    @if (count($clients) < 1)
                    <h3 class="text-center">Aucun client encore</h3>
                    @else
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">Prénom</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Numéro de matriculation</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Téléphone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <th scope="row">{{ $client->name }}</th>
                                    <td>{{ $client->lastname }}</td>
                                    <td>{{ $client->license_plate_number }}</td>
                                    <td>{{ $client->user->email }}</td>
                                    <td>{{ $client->phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
