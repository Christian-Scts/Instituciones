<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Support\AdminEmpresaScope;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = Usuario::with(['empresa', 'roles'])
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->buscar;

                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $empresas = AdminEmpresaScope::filtrarEmpresas(
            Empresa::query()
        )->orderBy('razon_social')->get();
        $roles = Role::where('activo', true)->orderBy('nombre')->get();

        return view('admin.usuarios.create', compact('empresas', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id' => ['nullable', 'exists:empresas,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,id'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $usuario = Usuario::create([
            'empresa_id' => $data['empresa_id'] ?? null,
            'name' => trim($data['name']),
            'email' => strtolower(trim($data['email'])),
            'password' => Hash::make($data['password']),
            'activo' => $request->boolean('activo'),
        ]);

        $usuario->roles()->sync($data['roles']);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    public function edit(Usuario $usuario)
    {
        $usuario->load('roles');

        $empresas = AdminEmpresaScope::filtrarEmpresas(
            Empresa::query()
        )->orderBy('razon_social')->get();
        $roles = Role::where('activo', true)->orderBy('nombre')->get();

        $rolesAsignados = $usuario->roles->pluck('id')->toArray();

        return view('admin.usuarios.edit', compact(
            'usuario',
            'empresas',
            'roles',
            'rolesAsignados'
        ));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $data = $request->validate([
            'empresa_id' => ['nullable', 'exists:empresas,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,id'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $update = [
            'empresa_id' => $data['empresa_id'] ?? null,
            'name' => trim($data['name']),
            'email' => strtolower(trim($data['email'])),
            'activo' => $request->boolean('activo'),
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $usuario->update($update);
        $usuario->roles()->sync($data['roles']);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario)
    {
        if (session('admin_user.id') === $usuario->id) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->roles()->detach();
        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}