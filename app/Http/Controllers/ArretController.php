<?php

namespace App\Http\Controllers;

use App\Models\Arret;
use Illuminate\Http\Request;

class ArretController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.arrets');
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
     * @param  \App\Models\Arret  $arret
     * @return \Illuminate\Http\Response
     */
    public function show(Arret $arret)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Arret  $arret
     * @return \Illuminate\Http\Response
     */
    public function edit(Arret $arret)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Arret  $arret
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arret $arret)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Arret  $arret
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arret $arret)
    {
        //
    }
}
