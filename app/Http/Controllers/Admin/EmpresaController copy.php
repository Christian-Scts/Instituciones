<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmpresaStoreRequest;
use App\Http\Requests\EmpresaUpdateRequest;
use App\Models\Empresa;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::latest()->paginate(20);
        return view('admin.empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('admin.empresas.create');
    }

    public function store_ok(EmpresaStoreRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['razon_social']);
        $data['endpoint_password_hash'] = Hash::make($data['endpoint_password']);
        $data['jwt_secret_encrypted'] = Crypt::encryptString($data['jwt_secret']);

        if (!empty($data['pui_password'])) {
            $data['pui_password_encrypted'] = Crypt::encryptString($data['pui_password']);
        }

        unset($data['endpoint_password'], $data['jwt_secret'], $data['pui_password']);

        Empresa::create($data);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa registrada correctamente.');
    }

    public function store_ok_ok(EmpresaStoreRequest $request)
{
    $data = $request->validated();

    $plainPassword = $data['endpoint_password'];

    $whitelistText = $request->input('ip_whitelist_text', '');
    $ips = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $whitelistText)));
    $ips = array_values(array_filter($ips, fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP)));

    $empresaData = [
        'rfc' => strtoupper(trim($data['rfc'])),
        'razon_social' => trim($data['razon_social']),
        'nombre_comercial' => $data['nombre_comercial'] ?? null,
        'slug' => Str::slug($data['razon_social']),
        'giro' => $data['giro'] ?? null,
        'ambiente' => $data['ambiente'],
        'ip_registrada' => $data['ip_registrada'] ?? null,
        'url_base_api' => rtrim($data['url_base_api'], '/'),
        'ip_whitelist' => $ips,
        'rate_limit_per_minute' => (int) ($data['rate_limit_per_minute'] ?? 60),

        'endpoint_user' => $data['endpoint_user'] ?? 'PUI',
        'endpoint_password_hash' => Hash::make($plainPassword),

        'pui_user' => $data['pui_user'] ?? 'PUI',
        'pui_password_encrypted' => Crypt::encryptString($data['pui_password'] ?? $plainPassword),

        'jwt_secret_encrypted' => Crypt::encryptString($data['jwt_secret']),
        'jwt_algo' => 'HS256',

        'activo' => true,
        'aprobado_sandbox' => false,
        'aprobado_productivo' => false,
    ];

    Empresa::create($empresaData);

    return redirect()
        ->route('admin.empresas.index')
        ->with('success', 'Empresa registrada correctamente.');
}

public function store(EmpresaStoreRequest $request)
{
    $data = $request->validated();

    $plainPassword = $data['endpoint_password'];

    $whitelistText = $request->input('ip_whitelist_text', '');
    $ips = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $whitelistText)));
    $ips = array_values(array_filter($ips, fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP)));

    $empresaData = [
        'rfc' => strtoupper(trim($data['rfc'])),
        'razon_social' => trim($data['razon_social']),
        'nombre_comercial' => $data['nombre_comercial'] ?? null,
        'slug' => Str::slug($data['razon_social']),
        'giro' => $data['giro'] ?? null,
        'ambiente' => $data['ambiente'],
        'ip_registrada' => $data['ip_registrada'] ?? null,
        'url_base_api' => rtrim($data['url_base_api'], '/'),
        'ip_whitelist' => $ips,
        'rate_limit_per_minute' => (int) ($data['rate_limit_per_minute'] ?? 60),

        'endpoint_user' => $data['endpoint_user'] ?? 'PUI',
        'endpoint_password_hash' => Hash::make($plainPassword),

        'pui_user' => $data['pui_user'] ?? 'PUI',
        'pui_password_encrypted' => Crypt::encryptString($data['pui_password'] ?? $plainPassword),

        'jwt_secret_encrypted' => Crypt::encryptString($data['jwt_secret']),
        'jwt_algo' => 'HS256',

        'activo' => true,
        'aprobado_sandbox' => false,
        'aprobado_productivo' => false,
    ];

    Empresa::create($empresaData);

    return redirect()
        ->route('admin.empresas.index')
        ->with('success', 'Empresa registrada correctamente.');
}

    public function edit(Empresa $empresa)
    {
        return view('admin.empresas.edit', compact('empresa'));
    }

    public function update_okk(EmpresaUpdateRequest $request, Empresa $empresa)
{
    $data = $request->validated();

    $data['activo'] = $request->boolean('activo');
    $data['aprobado_sandbox'] = $request->boolean('aprobado_sandbox');
    $data['aprobado_productivo'] = $request->boolean('aprobado_productivo');

    $whitelistText = $request->input('ip_whitelist_text', '');
    $ips = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $whitelistText)));

    $data['ip_whitelist'] = array_values($ips);
    $data['rate_limit_per_minute'] = (int) $request->input('rate_limit_per_minute', 60);

    $empresa->update($data);

    return redirect()
        ->route('admin.empresas.index')
        ->with('success', 'Empresa actualizada correctamente.');
}

public function update(EmpresaUpdateRequest $request, Empresa $empresa)
    {
        $data = $request->validated();

        $whitelistText = $request->input('ip_whitelist_text', '');
        $ips = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $whitelistText)));
        $ips = array_values(array_filter($ips, fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP)));

        $update = [
            'rfc' => $data['rfc'],
            'razon_social' => trim($data['razon_social']),
            'nombre_comercial' => $data['nombre_comercial'] ?? null,
            'slug' => Str::slug($data['razon_social']) . '-' . Str::lower($data['rfc']),
            'giro' => $data['giro'] ?? null,
            'ambiente' => $data['ambiente'],
            'ip_registrada' => $data['ip_registrada'] ?? null,
            'url_base_api' => $data['url_base_api'],
            'ip_whitelist' => $ips,
            'rate_limit_per_minute' => (int) ($data['rate_limit_per_minute'] ?? 60),
            'endpoint_user' => $data['endpoint_user'] ?? $empresa->endpoint_user ?? 'PUI',
            'pui_user' => $data['pui_user'] ?? $empresa->pui_user ?? 'PUI',
            'jwt_algo' => $data['jwt_algo'] ?? $empresa->jwt_algo ?? 'HS256',
            'activo' => $request->boolean('activo'),
            'aprobado_sandbox' => $request->boolean('aprobado_sandbox'),
            'aprobado_productivo' => $request->boolean('aprobado_productivo'),
        ];

        if (!empty($data['endpoint_password'])) {
            $update['endpoint_password_hash'] = Hash::make($data['endpoint_password']);
        }

        if (!empty($data['pui_password'])) {
            $update['pui_password_encrypted'] = Crypt::encryptString($data['pui_password']);
        }

        if (!empty($data['jwt_secret'])) {
            $update['jwt_secret_encrypted'] = Crypt::encryptString($data['jwt_secret']);
        }

        $empresa->update($update);

        return redirect()
            ->route('admin.empresas.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function update_OTRO(EmpresaUpdateRequest $request, Empresa $empresa)
    {
        $data = $request->validated();

        if (!empty($data['endpoint_password'])) {
            $data['endpoint_password_hash'] = Hash::make($data['endpoint_password']);
        }

        if (!empty($data['jwt_secret'])) {
            $data['jwt_secret_encrypted'] = Crypt::encryptString($data['jwt_secret']);
        }

        if (!empty($data['pui_password'])) {
            $data['pui_password_encrypted'] = Crypt::encryptString($data['pui_password']);
        }

        unset($data['endpoint_password'], $data['jwt_secret'], $data['pui_password']);

        $empresa->update($data);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }
}