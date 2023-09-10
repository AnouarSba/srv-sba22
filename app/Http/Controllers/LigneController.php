<?php

namespace App\Http\Controllers;

use App\Models\Arret;
use App\Models\Ligne;
use Illuminate\Http\Request;

class LigneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.lines');
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
     * @param  \App\Models\Ligne  $ligne
     * @return \Illuminate\Http\Response
     */
    public function show(Ligne $ligne)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ligne  $ligne
     * @return \Illuminate\Http\Response
     */
    public function edit(Ligne $ligne)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ligne  $ligne
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ligne $ligne)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ligne  $ligne
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ligne $ligne)
    {
        //
    }

    public function getarret(){

        $arr =   Arret::get(['name','lat','long']);
        return response($arr , 200);
    }
    public function getline(){
        $arr =   Ligne::get(['name','maps','color']);
        return response($arr , 200);
    }
}
