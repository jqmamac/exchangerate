<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Currency;
use App\Http\Resources\CurrenciesResource;
use Laravie\Parser\Xml\Reader;
use Laravie\Parser\Xml\Document;

class CurrenciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Currency::latest()->get();
        return response()->json([CurrenciesResource::collection($data), 'Programs fetched.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $xml = (new Reader(new Document()))->load('https://www.cbr.ru/scripts/XML_daily.asp');
        $rates = $xml->parse([
            'numcode' => ['uses' => 'Valute[NumCode]'],
            'name' => ['uses' => 'Valute[Name]'],
            'rate' => ['uses' => 'Valute[Value]'],
          ]);

        $data = [];
        for($i= 0; $i < count($rates['numcode']); $i++){

            $currrency = Currency::create([
                'name' => $rates['name'][$i]['Name'],
                'rate' => $rates['rate'][$i]['Value'],
                'numcode' => $rates['numcode'][$i]['NumCode'],
                
            ]);   
        }
        return response()->json(['Currency saved successfully.']);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $numcode
     * @return \Illuminate\Http\Response
     */
    public function show($numcode)
    {
       //print_r($id);
      //  $currrency = Currency::find($numcode);
        $currrency = Currency::where("numcode", '=', $numcode)->first();
        if (is_null($currrency)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new CurrenciesResource($currrency)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $numcode
     * @return \Illuminate\Http\Response
     */
    public function history($numcode)
    {
        $currrency = Currency::where("numcode", '=', $numcode)->get();
        if (is_null($currrency)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([CurrenciesResource::collection($currrency), 'History fetched.']);
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currrency)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'rate' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $currrency->name = $request->name;
        $currrency->rate = $request->rate;
        $currrency->save();
        
        return response()->json(['Currency updated successfully.', new CurrenciesResource($currrency)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currrency)
    {
        $currrency->delete();

        return response()->json('Currency deleted successfully');
    }
}