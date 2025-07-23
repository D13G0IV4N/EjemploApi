<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use App\Models\Car;


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
   	$cars = Car::all();
   	return response()->json(array(
      	 'cars'=>$cars,
      	 'status'=>'success'
   	), 200);
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
   
        public function store(Request $request){
   $hash =  $request->header('Authorization', null);
   $jwtAuth = new JwtAuth();
   $checkToken = $jwtAuth->checkToken($hash);

   if($checkToken){
       //Recoger los datos por post
       $json = $request->input('json', null);
       $params = json_decode($json);
       $params_array = json_decode($json, true);

       //Conseguir el usuario identificado
       $user = $jwtAuth->checkToken($hash, true);

       //Validación de laravel
       $request->merge($params_array);
       try{
           $validate = $this->validate($request,[
               'title' => 'required',
               'description' => 'required',
               'price' => 'required',
               'status' => 'required'
           ]);

       }catch (\Illuminate\Validation\ValidationException $e){
           return $e->getResponse();
       }


       //Guardar el coche
           $car = new Car();
           $car->user_id = $user->sub;
           $car->title = $params->title;
           $car->description = $params->description;
           $car->price = $params->price;
           $car->status = $params->status;
           $car->save();

           $data = array(
               'car'=>$car,
               'status' => 'success',
               'code' => 200
           );

   }else{
       //Devolver un error
       $data = array(
           'message' => 'Login incorrecto',
           'status' => 'success',
           'code' => 200
       );
   }
   return response()->json($data, 300);
}

    

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
{
   $hash =  $request->header('Authorization', null);
   $jwtAuth = new JwtAuth();
   $checkToken = $jwtAuth->checkToken($hash);
   if($checkToken){
       $car = Car::find($id);
       return response()->json(array('car' => $car, 'status' => 'success'), 200);
   }else{
       echo "Index de CarController No Autenticado"; die();
   }
  
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
    public function update($id, Request $request){
   $hash =  $request->header('Authorization', null);
   $jwtAuth = new JwtAuth();
   $checkToken = $jwtAuth->checkToken($hash);

   if($checkToken){
       //Recoger parámetros en post
       $json = $request->input('json', null);
       $params = json_decode($json);
       $params_array = json_decode($json, true);

       //Validar los datos
       $validate = \Validator::make($params_array,[
           'title' => 'required',
           'description' => 'required',
           'price' => 'required',
           'status' => 'required'
       ]);
       if($validate->fails()){
           return response()->json($validate->errors(), 400);
       }

       //Actualizar el carro
       $car = Car::where('id', $id)->update($params_array);
       $data = array(
         'car' => $params,
         'status' => 'success',
         'codigo' => 200
       );
   }else{
       //Devolver un error
       $data = array(
           'message' => 'Login incorrecto',
           'status' => 'success',
           'code' => 200
       );
   }
   return response()->json($data, 300);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request){
   $hash = $request->header('Authorization', null);
   $jwtAuth = new JwtAuth();
   $checkToken = $jwtAuth->checkToken($hash);
   if($checkToken){
       //comprobar si existe el registro
       $car = Car::find($id);

       //Borrarlo
       $car->delete();
       //Devolverlo
       $data = array(
           'car' => $car,
           'status' => 'success',
           'code' => 200
       );
   }else{
       $data = array(
       'status' => 'error',
       'code' => '400',
       'message' => 'Login Incorrecto !!'
       );
   }
   return response()->json($data, 200);
}

}
