<?php

namespace App\Http\Controllers;

use App\Models\Valideur;
use Illuminate\Http\Request;

class ValideurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.valideurs');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Valideur  $valideur
     * @return \Illuminate\Http\Response
     */
    public function show(Valideur $valideur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Valideur  $valideur
     * @return \Illuminate\Http\Response
     */
    public function edit(Valideur $valideur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Valideur  $valideur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Valideur $valideur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Valideur  $valideur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Valideur $valideur)
    {
        //
    }
}
