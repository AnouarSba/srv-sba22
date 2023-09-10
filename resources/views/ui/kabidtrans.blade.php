@extends('layouts.admin')

@section('content')
    <div class="col-md-12">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Receveur Transactions') }} : {{App\Models\Kabid::find($cart)->name;}}</div>
    
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @livewire('kabidtrans', ['cart' => $cart])
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection