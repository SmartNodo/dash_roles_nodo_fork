<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = User::create([
            'name' => 'Isaias Soto',
            'email' => 'isoto@gmail.com',
            'password' => bcrypt('123456789')
        ]);

        //Para cuando no se ha creador el rol Administrador
        $rol = Role::create(['name'=>'Administrador']);
        $permisos = Permission::pluck('id','id')->all();
        $rol->syncPermissions($permisos);
        $usuario->assignRole([$rol->id]);

        // Cuando ya tenemos el rol Administrador
        // $rol = Role::create(['name'=>'Administrador']);
        // $permisos = Permission::pluck('id','id')->all();
        // $rol->syncPermissions($permisos);
        $usuario->assignRole('Administrador');

    }
}
