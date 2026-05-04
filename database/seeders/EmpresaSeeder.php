<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::updateOrCreate(
            ['rfc' => 'ABC123456XYZ'],
            [
                'razon_social' => 'Empresa Demo PUI',
                'nombre_comercial' => 'Demo PUI',
                'slug' => 'empresa-demo-pui',
                'giro' => 'Financiero',
                'ambiente' => 'sandbox',
                'ip_registrada' => '127.0.0.1',
                'url_base_api' => 'http://127.0.0.1:8000/api/pui',
                'endpoint_user' => 'PUI',
                'endpoint_password_hash' => Hash::make('ClaveSegura2026*'),
                'pui_user' => 'PUI',
                'pui_password_encrypted' => Crypt::encryptString('ClavePui2026*'),
                'jwt_secret_encrypted' => Crypt::encryptString(bin2hex(random_bytes(32))),
                'jwt_algo' => 'HS256',
                'activo' => true,
                'aprobado_sandbox' => true,
                'aprobado_productivo' => false,
            ]
        );
    }
}