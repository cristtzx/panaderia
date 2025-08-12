<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UsuariosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ActualizarDatos(Request $request)
    {
        $user = auth()->user();
        $datos = $request->all();
        $cambios = false;

        // Actualizar email si es diferente
        if($user->email != $datos['email']) {
            $request->validate([
                'email' => 'required|email|unique:users,email,'.$user->id
            ]);
            
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'email' => $datos['email'],
                    'email_verified_at' => null
                ]);
            $cambios = true;
        }

        // Manejar foto de perfil
        if($request->hasFile('fotoPerfil')) {
            $request->validate([
                'fotoPerfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            // Eliminar imagen anterior si existe
            if($user->foto && Storage::exists('public/'.$user->foto)) {
                Storage::delete('public/'.$user->foto);
            }
            
            // Guardar nueva imagen
            $rutaImg = $request->file('fotoPerfil')->store('public/profile-photos');
            $rutaImg = str_replace('public/', '', $rutaImg);
            
            DB::table('users')
                ->where('id', $user->id)
                ->update(['foto' => $rutaImg]);
            $cambios = true;
        } else {
            $rutaImg = $user->foto;
        }

        // Actualizar contraseña si se proporciona
        if(isset($datos['password']) && !empty($datos['password'])) {
            $request->validate([
                'password' => 'min:8|confirmed'
            ]);
            
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($datos['password'])
                ]);
            $cambios = true;
        }

        // Actualizar nombre si existe en el request
        if(isset($datos['name']) && $user->name != $datos['name']) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => $datos['name']]);
            $cambios = true;
        }

        return back()->with(
            $cambios ? 'success' : 'info', 
            $cambios ? 'Datos actualizados correctamente' : 'No se realizaron cambios'
        );
    }
        public function index()
        {
            if (auth()->user()->rol != 'Administrador') {
                return redirect('Inicio');
            }
            
            // Usuarios paginados (correcto)
            $usuarios = User::with('sucursal')->paginate(10); // 10 usuarios por página 
            
            // Sucursales para los filtros
            $sucursales = Sucursal::all();
            
            return view('modulos.users.Usuarios', compact('usuarios', 'sucursales'));
        }

    public function update()
    {
        // Validar si se envió una nueva contraseña
        if (request('password')) {
            $validarPass = request()->validate([
                'password' => ['string', 'min:3']
            ]);
            $pass = true;
        } else {
            $pass = false;
        }

        $datos = request();

        // Buscar y actualizar usuario
        $User = User::find($datos['id']);
        $User->name = $datos['name'];
        $User->email = $datos['email'];
        $User->id_sucursal = $datos['rol'] == 'Administrador' ? null : $datos['id_sucursal']; // Cambio clave
        $User->rol = $datos['rol'];

        // Solo actualizar contraseña si fue proporcionada
        if ($pass) {
            $User->password = Hash::make($datos['password']);
        }

        $User->save();

        return redirect('Usuarios')->with('success', 'El Usuario ha sido Actualizado Correctamente');
    }

    public function create()
    {
        // Método se mantiene igual
    }

    public function store(Request $request)
    {
        $validarEmail = request()->validate([
            'email'=>['unique:users']
        ]);

        $datos = request();
        
        User::create([
            'name' => $datos["name"],
            'email' => $validarEmail["email"],
            'id_sucursal' => $datos["rol"] == 'Administrador' ? null : $datos["id_sucursal"], // Cambio clave
            'foto' => '',
            'password' => Hash::make($datos["password"]),
            'estado' => 1,
            'ultimo_login' => null,
            'rol' => $datos["rol"]
        ]);

        return redirect('Usuarios')->with('success', 'Usuario creado correctamente');
    }

    public function CambiarEstado($id_usuario, $estado)
    {
        User::find($id_usuario)->update([ 'estado'=>$estado ]);
    }

    public function edit($id_usuario)
    {
        $usuario = User::find($id_usuario);
        return response()->json($usuario);
    }

    public function destroy($id_usuario)
    {
        $usuario = User::find($id_usuario);

        if ($usuario->foto != '') {
            $path = storage_path('app/public/' . $usuario->foto);
            unlink($path);
        }

        User::destroy($id_usuario);

        return redirect('Usuarios');
    }
}