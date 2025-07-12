<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;

class CarController extends Controller
{
    
    public function login(Request $request){
   $jwtAuth = new JwtAuth();
   //Recibir POST
   $json = $request->input('json', null);
   $params = json_decode($json);
   $email =(!is_null($json) && isset($params->email)) ? $params->email : null;
   $password=(!is_null($json) && isset($params->password)) ? $params->password : null;
   $getToken=(!is_null($json) && isset($params->getToken)) ? $params->getToken : null;


   if(!is_null($email) && !is_null($password) && ($getToken == null || $getToken == false)){
       $signup = $jwtAuth->signup($email, $password);
   }elseif($getToken != null){
       $signup = $jwtAuth->signup($email, $password, $getToken);
   }else{
       $signup=array(
           'status'=>'error',
           'message' => 'Envía tus datos por post',
       );
   }
   return response()->json($signup, 200);
}
public function index(Request $request){
   $hash =  $request->header('Authorization', null);
   $jwtAuth = new JwtAuth();
   $checkToken = $jwtAuth->checkToken($hash);
   if($checkToken){
       echo "Index de CarController Autenticado"; die();
   }else{
       echo "Index de CarController No Autenticado"; die();
   }
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
