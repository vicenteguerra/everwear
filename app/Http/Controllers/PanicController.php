<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PanicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        echo "GET";
        error_log("GET " . $request , 3, "../storage/logs/myerror.log");
        var_dump($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo "POST";
        $name = $request->get('name');
        var_dump($name);
        var_dump($request);
        error_log("POST " . $request , 3, "../storage/logs/myerror.log");
    }


}
