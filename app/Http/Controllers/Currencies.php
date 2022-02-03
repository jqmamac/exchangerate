<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class Currencies extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Currency::get();
        //print_r($data);
        //return view("exchangerate");
        //return response()->json([CurrenciesResource::collection($data), 'Programs fetched.']);
        $data = $this->make_ui($data);
        return $data;
    }

    private function make_ui($result)
    {
        $data='<div class="table-responsive">
            <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Rate</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>';

            foreach($result as $c){
            $data.='
                <tr class="gradeX">
                    <td>'. $c->numcode .'</td>
                    <td>'. $c->name .'</td>
                    <td>'. $c->rate .'</td>
                    <td>'. $c->create_at .'</td>
                </tr>
            ';
            }
            $data .= '</tbody>
           
        </table>
    </div>';
    return $data;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
