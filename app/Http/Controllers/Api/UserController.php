<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'            
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            "status" => 1,
            "msg" => "¡Registro de usuario exitoso!",
        ]);    
    }


    public function login(Request $request) {

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where("email", "=", $request->email)->first();

        if( isset($user->id) ){
            if(Hash::check($request->password, $user->password)){
                //creamos el token
                $token = $user->createToken("auth_token")->plainTextToken;
                //si está todo ok
                return response()->json([
                    "status" => 1,
                    "msg" => "¡Usuario logueado exitosamente!",
                    "access_token" => $token
                ]);        
            }else{
                return response()->json([
                    "status" => 0,
                    "msg" => "La password es incorrecta",
                ], 404);    
            }

        }else{
            return response()->json([
                "status" => 0,
                "msg" => "Usuario no registrado",
            ], 404);  
        }
    }

    public function userProfile() {
        return response()->json([
            "status" => 0,
            "msg" => "Acerca del perfil de usuario",
            "data" => auth()->user()
        ]); 
    }

    public function userUpdate(Request $request)
    {
        
        $user_id_login = auth()->user()->id;

        $this->validate($request, [
            'name' => 'required', 
            'email'=>'required|email|unique:users,email,'.$request->idUser,
            'password'=>'same:confirm-password',
            'roles'=>'required',
            'teams'=>'required'
        ]);
        
        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));
        
        TeamUser::where('user_id', $user->id)->delete();

        foreach($request->teams as $k => $v){
            $TeamUser = new TeamUser;
            $TeamUser->team_id = $v;
            $TeamUser->user_id = $user->id;
            $TeamUser->save();
        }

        return response()->json([
            "status" => 1,
            "msg" => "Usuario actializado de Sesión",            
        ]);
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        
        return response()->json([
            "status" => 1,
            "msg" => "Cierre de Sesión",            
        ]); 
    }
}