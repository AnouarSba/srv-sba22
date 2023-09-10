<?php

namespace App\Http\Controllers;

use App\Models\Vendeur;
use Illuminate\Http\Request;

class VendeurController extends Controller
{
    public function vendtrans ($id)
    {
        return view('ui.vendtrans',['cart'=>$id]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.vendeurs');
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
     * @param  \App\Models\Vendeur  $vendeur
     * @return \Illuminate\Http\Response
     */
    public function show(Vendeur $vendeur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendeur  $vendeur
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendeur $vendeur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendeur  $vendeur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendeur $vendeur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendeur  $vendeur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendeur $vendeur)
    {
        //
    }
}
