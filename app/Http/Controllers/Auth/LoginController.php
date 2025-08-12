<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\User;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/Inicio';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
        
    }

    protected function authenticated(Request $request, $user)
    {
        if($user->estado == 0){

            Auth::logout();

            return redirect()->route('Ingresar')->withErrors(['estado' => 'Su Usuario se encuentra Desactivado']);

        }else{

            $user->ultimo_login = now();
            $user->save();

        }

     
     
     
     
        $user ->ultimo_login = now();
        $user ->save();

    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }





    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(16)), // Puedes guardar algo aleatorio
            ]
        );

        Auth::login($user);

        return redirect('/Inicio'); // o tu ruta preferida

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Ocurrió un error al autenticar con Google.');
        }

    }







public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    // Busca al usuario
    $user = User::where('email', $credentials['email'])->first();

    if ($user) {
        // Verifica si el password NO es un hash Bcrypt (opcional)
        if (!preg_match('/^\$2[ayb]\$.{56}$/', $user->password)) {
            // Si la contraseña coincide (en texto plano o otro hash), la rehashea con Bcrypt
            if (Hash::check($credentials['password'], $user->password)) {
                $user->password = bcrypt($credentials['password']);
                $user->save();
            }
        }

        // Intenta el login normalmente
        if (Auth::attempt($credentials)) {
            return redirect('/Inicio');
        }
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas']);
}
}
