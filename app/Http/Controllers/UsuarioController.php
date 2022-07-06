<?php

namespace App\Http\Controllers;


use App\Models\Team;
use App\Models\User;
use App\Models\TeamUser;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::paginate(5);
        return view('usuarios.index', compact('usuarios'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $teams = Team::select('id', 'name')->get();
        return view('usuarios.crear', compact('roles','teams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'name' => 'required', 
                'email'=>'required|email|unique:users,email',
                'password'=>'required|same:confirm-password',
                'roles'=>'required',
                'teams'=>'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        
        $user = User::create($input);
        $user->assignRole($request->input('roles'));


        foreach($request->teams as $k => $v){
            $TeamUser = TeamUser::updateOrCreate(
                ['team_id' =>  $v],
                ['user_id' => $user->id]
            );
        }


        // $user_team = 

        return redirect()->route('usuarios.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $teams = Team::select('id', 'name')->get();
        $teamsUser = TeamUser::where('user_id',$id)->get();

        foreach($teams as $k => $v){        
            foreach($teamsUser as $k2 => $v2){
                if($v->id == $v2->team_id){
                    $teams[$k]['s'] =  'selected';                 
                }
            }
        }

        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('usuarios.editar', compact('user','roles','userRole','teams'));


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
        $this->validate($request, [
                'name' => 'required', 
                'email'=>'required|email|unique:users,email,'.$id,
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
            
            $TeamUser = TeamUser::updateOrCreate(
                ['team_id' =>  $v],
                ['user_id' => $user->id]
            );
        }

        return redirect()->route('usuarios.index');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('usuarios.index');
    }
}
