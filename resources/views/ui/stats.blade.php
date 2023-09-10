@extends('layouts.admin')
@section('content')
    <div class="col-md-12">
        <div class="row justify-content-center">
            <div class="col-md-12 py-2">

                <div class="card" style="background-color: rgb(120, 144, 230)">
                    <div class="card-header"><h3>{{ __('Statistiques pour les ouvriers') }}</h3></div>
    
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('stat1') }}">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <label for="exampleFormControlInput1">type d'ouvrier</label>
                                    <select name="type" class="form-control" aria-label="Default select example">
                                        <option value="">--Selectioner le Type--</option>
                                        <option value="App\Models\Kabid">Receveur</option>
                                        <option value="App\Models\Control">Controller</option>
                                        <option value="App\Models\Vendeur">Vendeur</option>
                                      </select>
                                      @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-auto">
                                    <label for="exampleFormControlInput1">id d'ouvrier </label>
                                    <input type="numeric" class="form-control" id="exampleFormControlInput1"  name="type_id">
                                    @error('type_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-auto">
                                    <label for="exampleFormControlInput1">Date De Debut</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="start_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('start_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-auto">
                                    <label for="exampleFormControlInput1">Date de Fin</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="end_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('end_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                                
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2"> Envoyer</button>
                              </div>
                            </div>
                          </form>
                    
                    </div>
                </div>

                <div class="card" style="background-color: aqua">
                    <div class="card-header"> <h3>{{ __(' Les Revenus') }}</h3></div>
    
                    <div class="card-body">
                        <form method="POST" action="{{ route('stat2') }}">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date De Debut</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="start_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('start_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date de Fin</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="end_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('end_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                                
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2">Envoyer</button>
                              </div>
                            </div>
                          </form>
                    
                    </div>
                </div>

                <div class="card" style="background-color: rgb(0, 255, 64)">
                    <div class="card-header"> <h3>{{ __(' Rapport de paiement électronique') }}</h3></div>
    
                    <div class="card-body">
                        <form method="POST" action="{{ route('stat3') }}">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date De Debut</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="start_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('start_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date de Fin</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="end_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('end_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                                
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2">Envoyer</button>
                              </div>
                            </div>
                          </form>
                    
                    </div>
                </div>


                
                <div class="card" style="background-color: rgb(121, 190, 250)">
                    <div class="card-header"> <h3>{{ __(' Rapport des tickets classiques') }}</h3></div>
    
                    <div class="card-body">
                        <form method="POST" action="{{ route('stat4') }}">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date De Debut</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="start_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('start_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date de Fin</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="end_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('end_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                                
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2">Envoyer</button>
                              </div>
                            </div>
                          </form>
                    
                    </div>
                </div>
                <div class="card" style="background-color: rgb(0, 255, 70)">
                    <div class="card-header"> <h3>{{ __(' Rapport des Cartes spéciales ') }}</h3></div>
    
                    <div class="card-body">
                        <form method="POST" action="{{ route('stat5') }}">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date De Debut</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="start_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('start_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date de Fin</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="end_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('end_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                                
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2">Envoyer</button>
                              </div>
                            </div>
                          </form>
                    
                    </div>
                </div>
                <div class="card" style="background-color: rgb(0, 255, 70)">
                    <div class="card-header"> <h3>{{ __(' Liste Des éleves ') }}</h3></div>
    
                    <div class="card-body">
                        <form method="POST" action="{{ route('stat6') }}">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date De Debut</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="start_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('start_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date de Fin</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="end_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('end_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                                
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2">Envoyer</button>
                              </div>
                            </div>
                          </form>
                    
                    </div>
                </div>
                <div class="card"  style="background-color: rgb(0, 255, 70)">
                    <div class="card-header"> <h3>{{ __(' Rapport top Flexy ') }}</h3></div>
    
                    <div class="card-body">
                        <form method="GET" action="{{ route('Top') }}">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date De Debut</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="start_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('start_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-auto">
                                    <label for="exampleFormControlInput1"> Date de Fin</label> 
                                    <input type="datetime-local" class="form-control" id="game-date-time-text" name="end_date" value="{{ now()->setTimezone('T')->format('Y-m-d H:m') }}" >
                                    @error('end_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                                
                              <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2">Envoyer</button>
                              </div>
                            </div>
                          </form>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection