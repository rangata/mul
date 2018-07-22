<?php

namespace App\Http\Controllers;

use App\Resource;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;



class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//        $patients = Resource::with('tags')->where('resourceType', '=', 0)->paginate(10);
        $patients = Resource::with('tags')->where('resourceType', '=', 0)->get();
//        dd($patients);
        return view('patients.index', compact('patients'));
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
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = new Client();

        $resource = Resource::where('publicId','=',$id)->get();

        $studiesRaw = $client->get('http://localhost:8042/patients/' . $id . '/studies');

        $studies = collect(json_decode($studiesRaw->getBody()->getContents(), true));

        $instancesRaw = $client->get('http://localhost:8042/patients/' . $id . '/instances');


        $instances = $instancesRaw->getBody()->getContents();
        $instances = json_decode($instances);

//        $respos = $client->get('http://localhost:8042/instances/' . $instances[0]['ID'] . '/preview');
//        $im = $respos->getBody()->getContents();


        return view('patients.show', compact('resource', 'studies','instances'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        //
    }
}
