<?php

namespace App\Http\Controllers;

use App\Client;
use App\Business;
use Illuminate\Http\Request;

class Client2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Client $client)
    {  
//        return view('client.index', compact('client'));

        return view();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return view('client.show',compact('client'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'client.fname' => 'required',
            'client.mname' => 'required',
            'client.lname' => 'required',
            'client.email' => 'required',
            'business'     => 'required'
        ]);

        $id = Client::create($request['client']);

        if(count($request['business'])){
            $this->saveBusiness($id, $request['business']);
        }
    }

    public function update(Client $client, Request $request)
    {   
        $client->update($request['client']);
        return 'success';
    }

    public function destroy(Request $request)
    {
        return Client::destroy($request['client_id']);
    }

    public function create(Client $client)
    {
        return view('client.show', compact('client'));
    }

    protected function saveBusiness($business)
    {
        
    }
}