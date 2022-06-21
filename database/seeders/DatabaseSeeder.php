<?php

namespace Database\Seeders;

use App\Models\Mese;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(1)->create();
        $role = Role::create([
        	"nombre" =>"admin"
        ]);
        User::create([
        	"role_id" => $role->id,
        	"nombres" => "secrets",
        	"apellidos" => "secrets",
        	"email"=>"secret@secret.com",
        	"username"=>"secret",
        	'password' => Hash::make('secret')
        ]);

        $meses = [
            'enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'
        ];

        for ($i=0; $i <12; $i++) { 
            Mese::create([
                "no" => $i+1,
                "mes" => $meses[$i]
            ]);
        }
        
    }
}
