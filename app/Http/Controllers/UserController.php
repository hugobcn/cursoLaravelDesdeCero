<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class UserController extends Controller {

    public function index() {
        /*
          request('field');

          if (request()->has('empty')) {
          $users = [];
          } else {
          $users = [
          'Joel', 'Ellie', 'Tess', 'Tommy', 'Bill',
          ];
          }
         */

        //$users = DB::table('users')->all();
        $users = User::all();

        $title = 'Listado de usuarios';

        return view('users.index')->with('users', User::all()->with('title', 'Listado de Usuarios'));

        //users/index folders
        return view('users.index', compact('title', 'users'));
        //return view('users', compact('title', 'users'));
    }

    /*
      public function show($id)
      {
      $user= User::findOrFail($id);

      //$user= User::find($id);

      //if($user == null){
      //return response()->view('errors.404',[],404);
      //}

      return view('users.show', compact('id'));
      } */

    public function show(User $user) {
        //$user= User::findOrFail($id);
        //$user= User::find($id);
        //if($user == null){
        //return response()->view('errors.404',[],404);
        //}

        return view('users.show', compact('id'));
    }

    /*
      public function index()
      {
      return 'Usuarios';
      }
     * 
      public function show($id)
      {
      return "Mostrando detalle del usuario: {$id}";
      }
     */

    public function create() {
        return view('users.create');
    }

    public function store() {
        /*
          //return redirect('usuarios/nuevo')->withInput();

          $data = request()->validate([
          'name' => 'required'
          ], [
          'name.required' => 'El campo nombre es obligatorio'
          ]);


          $data = request()->all();

          if(empty($data['name']))
          {
          return redirect('usuarios/nuevo')->withErrors([
          'name'=>'nombre campo obligatorio'
          ]);
          }
         */


        $data = request()->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required',
                ], [
            'name.required' => 'El campo nombre es obligatorio'
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        return redirect()->route('users.index');
    }

    public function edit(User $user) {
        return view('users.edit', ['user' => $user]);
    }

    public function update(User $user) {
        $this->withExceptionHandling();

        //$user->update(request()->all());

        $data = request()->validate([
            'name' => 'required',
            'email' => 'required' | 'email',
            'password' => ''
        ]);

        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route("users.show", ['user' => $user]);
        //return redirect()->route("users.show",['user'=>$user->id]);
    }

}
