@extends('layouts.admin')

@section('content')
    <div class="col-md-12">
        <div class="row justify-content-center">
            <div class="col-md-12 py-2">
                <div class="card">
                     <div class="card-header">{{ __('Statone') }}</div>
                     
         
     
                    <div class="card-body">

                        @can('show_statistics')
                        <livewire:statone />
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
    
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
    
                        <livewire:show-posts />
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
