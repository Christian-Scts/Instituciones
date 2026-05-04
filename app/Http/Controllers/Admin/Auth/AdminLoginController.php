<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function showLogin()
    {
        if (session()->has('admin_user')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $usuario = Usuario::with('roles.permissions')
        ->where('email', $request->email)
        ->where('activo', true)
        ->first();

    if (!$usuario || !Hash::check($request->password, $usuario->password)) {
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Las credenciales no son válidas.',
            ]);
    }

    $menu = app(MenuService::class)->buildForUsuario($usuario);

    session([
        'admin_user' => [
            'id' => $usuario->id,
            'name' => $usuario->name,
            'email' => $usuario->email,
            'empresa_id' => $usuario->empresa_id,
        ],
        'admin_roles' => $usuario->roles->pluck('slug')->toArray(),
        'admin_permissions' => $usuario->permissionsArray(),
        'menu_por_rol' => $menu,
    ]);

    $request->session()->regenerate();

    return redirect()->route('admin.dashboard');
}

    public function login_ok(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $usuario = Usuario::with('roles.permissions')
            ->where('email', $request->email)
            ->where('activo', true)
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Las credenciales no son válidas.',
                ]);
        }

        session([
            'admin_user' => [
                'id' => $usuario->id,
                'name' => $usuario->name,
                'email' => $usuario->email,
                'empresa_id' => $usuario->empresa_id,
            ],
            'admin_roles' => $usuario->roles->pluck('slug')->toArray(),
            'admin_permissions' => $usuario->permissionsArray(),
        ]);

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'admin_user',
            'admin_roles',
            'admin_permissions',
            'menu_por_rol',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}