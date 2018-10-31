@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @auth
                        You are logged in
                    @else
                        You are not logged in
                    @endauth

                </div>
            </div>
        </div>
    </div>
</div>
@endsection