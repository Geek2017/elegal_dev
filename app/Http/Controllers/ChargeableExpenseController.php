<?php

namespace App\Http\Controllers;

use App\ChargeableExpense;
use Illuminate\Http\Request;

class ChargeableExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.chargeable-expense.create');
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
     * @param  \App\ChargeableExpense  $chargeableExpense
     * @return \Illuminate\Http\Response
     */
    public function show(ChargeableExpense $chargeableExpense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ChargeableExpense  $chargeableExpense
     * @return \Illuminate\Http\Response
     */
    public function edit(ChargeableExpense $chargeableExpense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ChargeableExpense  $chargeableExpense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChargeableExpense $chargeableExpense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ChargeableExpense  $chargeableExpense
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChargeableExpense $chargeableExpense)
    {
        //
    }
}
