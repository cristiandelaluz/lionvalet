@extends('layouts.app')

@section('content')
<div class="container section__content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-bottom-0">
                    <h4>
                        <i class="fas fa-user-friends text-primary mr-3"></i>
                        {{ __('All Clients') }}
                    </h4>
                </div>

                <div class="card-body">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Last Name') }}</th>
                                <th scope="col">{{ __('License P Number') }}</th>
                                <th scope="col">{{ __('Email') }}</th>
                                <th scope="col">{{ __('Phone') }}</th>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
