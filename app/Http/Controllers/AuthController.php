<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\RegistrationCode;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use App\Mail\RegistrationCodeMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetCodeMail;
use Laravel\Socialite\Facades\Socialite;
use App\Models\SocialAccount;


class AuthController extends Controller
{
    /* Muestra la vista de registro.
     */
    public function showRegister(): View
    {
        return view('register');
    }

    /**Procesa el registro de usuario.
     */
public function register(Request $request): RedirectResponse
{
    $request->validate([
        'name' => 'required|string|max:100',
        'surname' => 'required|string|max:150',
        'email' => 'required|email|max:150',
        'password' => 'required|min:6|same:password_confirmation',
        'password_confirmation' => 'required',
        'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $email = htmlspecialchars(trim($request->email), ENT_QUOTES, 'UTF-8');

    $existingUser = User::where('email', $email)->first();

    if ($existingUser) {
        return back()
            ->withInput()
            ->with('error', 'Ya existe una cuenta con este correo. Inicia sesión en lugar de registrarte.');
    }

    $name = htmlspecialchars(trim($request->name), ENT_QUOTES, 'UTF-8');
    $surname = htmlspecialchars(trim($request->surname), ENT_QUOTES, 'UTF-8');

    $profilePhotoPath = null;

    if ($request->hasFile('profile_photo')) {
        $profilePhotoPath = $request->file('profile_photo')->store('profiles', 'public');
    }

    $userRole = Role::where('name', 'usuario')->first();

    if (!$userRole) {
        return back()->withInput()->with('error', 'Rol de usuario no encontrado.');
    }

    $user = User::create([
        'role_id' => $userRole->id,
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'profile_photo' => $profilePhotoPath,
        'password' => hash('sha256', $request->password),
    ]);

    $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    RegistrationCode::create([
        'user_id' => $user->id,
        'code' => $code,
        'expires_at' => now()->addMinutes(10),
        'used' => false,
    ]);

    Session::put('pending_register_user_id', $user->id);

    Mail::to($user->email)->send(
        new RegistrationCodeMail($code, $user->name)
    );

    return redirect()->route('register.code.form')
        ->with('success', 'Cuenta creada correctamente. Te hemos enviado un código de verificación a tu correo.');
}

    /**
     * Muestra la vista de login.
     */
    public function showLogin(): View
    {
        return view('login');
    }

    /**
     * Procesa el inicio de sesión.
     */
public function login(Request $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $email = htmlspecialchars(trim($request->email), ENT_QUOTES, 'UTF-8');

    $user = User::with('role')
        ->where('email', $email)
        ->first();

    if (!$user) {
        return back()->withInput()->with('error', 'Credenciales incorrectas.');
    }

    if (!$user->password && $user->google_id) {
        return back()
            ->withInput()
            ->with('error', 'Esta cuenta fue registrada con Google. Debes iniciar sesión con Google.');
    }

    $password = hash('sha256', $request->password);

    if ($user->password !== $password) {
        return back()->withInput()->with('error', 'Credenciales incorrectas.');
    }

    if (is_null($user->email_verified_at)) {
        return back()
            ->withInput()
            ->with('error', 'Debes verificar tu cuenta antes de iniciar sesión.');
    }

    if ($user->isAdmin()) {
        return back()
            ->withInput()
            ->with('error', 'Los administradores deben iniciar sesión desde el acceso de administrador.');
    }

    Session::put('user_id', $user->id);
    Session::put('user_name', $user->name);
    Session::put('role_id', $user->role_id);

    return redirect()->route('dashboard');
}
    /*
      Cierra la sesión del usuario.
     */
    public function logout(Request $request): RedirectResponse
    {
        Session::flush();

        return redirect()->route('login.form');
    }

    /*
      Muestra la vista de recuperación de contraseña.
     */
    public function showForgotPassword(): View
    {
        return view('forgot-password');
    }

    /*
      Crea y guarda un código de 6 dígitos.
     */
    public function sendResetCode(Request $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $email = htmlspecialchars(trim($request->email), ENT_QUOTES, 'UTF-8');

    $user = User::where('email', $email)->first();

    if (!$user) {
        return back()->with('error', 'No existe ninguna cuenta con ese correo.');
    }

    $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    PasswordResetCode::create([
        'user_id' => $user->id,
        'code' => $code,
        'expires_at' => now()->addMinutes(10),
        'used' => false,
    ]);

    Session::put('reset_user_id', $user->id);

    Mail::to($user->email)->send(
        new PasswordResetCodeMail($code, $user->name)
    );

    return redirect()->route('code.form')
        ->with('success', 'Te hemos enviado un código de recuperación a tu correo.');
}

    /*
      Muestra la vista de verificación del código.
     */
    public function showVerifyCode(): View
    {
        return view('verify-code');
    }

    /*
      Verifica el código de recuperación.
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $userId = Session::get('reset_user_id');

        if (!$userId) {
            return redirect()->route('forgot.form')->with('error', 'Debes solicitar primero un código.');
        }

        $code = htmlspecialchars(trim($request->code), ENT_QUOTES, 'UTF-8');

        $resetCode = PasswordResetCode::where('user_id', $userId)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (!$resetCode) {
            return back()->with('error', 'El código no es válido o ha expirado.');
        }

        Session::put('verified_reset_code_id', $resetCode->id);

        return redirect()->route('reset.form')->with('success', 'Código verificado correctamente.');
    }

    /*
      Muestra la vista para establecer nueva contraseña.
     */
    public function showResetPassword(): View
    {
        return view('reset-password');
    }

    /*
     Actualiza la contraseña del usuario.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required',
        ]);

        $userId = Session::get('reset_user_id');
        $resetCodeId = Session::get('verified_reset_code_id');

        if (!$userId || !$resetCodeId) {
            return redirect()->route('forgot.form')->with('error', 'Proceso de recuperación no válido.');
        }

        $user = User::find($userId);
        $resetCode = PasswordResetCode::find($resetCodeId);

        if (!$user || !$resetCode || $resetCode->used) {
            return redirect()->route('forgot.form')->with('error', 'No se pudo restablecer la contraseña.');
        }

        $user->password = hash('sha256', $request->password);
        $user->save();

        $resetCode->used = true;
        $resetCode->save();

        Session::forget('reset_user_id');
        Session::forget('verified_reset_code_id');

        return redirect()->route('login.form')->with('success', 'Contraseña actualizada correctamente.');
    }

    
    public function dashboard(): View|RedirectResponse
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión.');
        }

        return view('dashboard');
    }

    /*
      Redirección a Google.
     */
   public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}

    /*
     * Callback  de Google.
     */
public function handleGoogleCallback(): RedirectResponse
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    $provider = 'google';
    $providerUserId = $googleUser->getId();
    $providerEmail = $googleUser->getEmail();

    $socialAccount = SocialAccount::where('provider', $provider)
        ->where('provider_user_id', $providerUserId)
        ->first();

    if ($socialAccount) {
        $user = $socialAccount->user;

        if ($user->isAdmin()) {
            return redirect()->route('admin.login.form')
                ->with('error', 'El administrador debe iniciar sesión desde el acceso de administrador.');
        }

        Session::put('user_id', $user->id);
        Session::put('user_name', $user->name);
        Session::put('role_id', $user->role_id);

        return redirect()->route('dashboard');
    }

    $user = User::with('role')->where('email', $providerEmail)->first();

    if ($user) {
        if ($user->isAdmin()) {
            return redirect()->route('admin.login.form')
                ->with('error', 'El administrador debe iniciar sesión desde el acceso de administrador.');
        }

        $user->google_id = $providerUserId;

        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
        }

        $user->save();

        SocialAccount::firstOrCreate([
            'user_id' => $user->id,
            'provider' => $provider,
        ], [
            'provider_user_id' => $providerUserId,
            'provider_email' => $providerEmail,
        ]);

        Session::put('user_id', $user->id);
        Session::put('user_name', $user->name);
        Session::put('role_id', $user->role_id);

        return redirect()->route('dashboard')
            ->with('success', 'Has iniciado sesión con Google correctamente.');
    }

    $userRole = Role::where('name', 'usuario')->first();

    if (!$userRole) {
        return redirect()->route('login.form')
            ->with('error', 'Rol de usuario no encontrado.');
    }

    $user = User::create([
        'role_id' => $userRole->id,
        'google_id' => $providerUserId,
        'name' => $googleUser->getName() ?? 'Usuario',
        'surname' => '',
        'email' => $providerEmail,
        'profile_photo' => null,
        'password' => null,
        'email_verified_at' => now(),
    ]);

    SocialAccount::create([
        'user_id' => $user->id,
        'provider' => $provider,
        'provider_user_id' => $providerUserId,
        'provider_email' => $providerEmail,
    ]);

    Session::put('user_id', $user->id);
    Session::put('user_name', $user->name);
    Session::put('role_id', $user->role_id);

    return redirect()->route('dashboard')
        ->with('success', 'Cuenta creada con Google correctamente.');
}

    /*
  Muestra el formulario de verificación del código de registro.
 */
public function showRegisterCodeForm(): View
{
    return view('verify-register-code');
}


/*
  Verifica el código de activación de la cuenta.
 */
public function verifyRegisterCode(Request $request): RedirectResponse
{
    $request->validate([
        'code' => 'required|digits:6',
    ]);

    $userId = Session::get('pending_register_user_id');

    if (!$userId) {
        return redirect()->route('register.form')
            ->with('error', 'No hay ningún registro pendiente de verificación.');
    }

    $code = htmlspecialchars(trim($request->code), ENT_QUOTES, 'UTF-8');

    $registrationCode = RegistrationCode::where('user_id', $userId)
        ->where('code', $code)
        ->where('used', false)
        ->where('expires_at', '>=', now())
        ->latest()
        ->first();

    if (!$registrationCode) {
        return back()->with('error', 'El código no es válido o ha expirado.');
    }

    $user = User::find($userId);

    if (!$user) {
        return redirect()->route('register.form')
            ->with('error', 'Usuario no encontrado.');
    }

    $user->email_verified_at = now();
    $user->save();

    $registrationCode->used = true;
    $registrationCode->save();

    Session::forget('pending_register_user_id');

    return redirect()->route('login.form')
        ->with('success', 'Cuenta verificada correctamente. Ya puedes iniciar sesión.');
}

public function showAdminLogin()
{
    return view('loginadmin');
}

public function adminLogin(Request $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::with('role')->where('email', $request->email)->first();

    if (!$user) {
        return back()->with('error', 'Credenciales incorrectas.');
    }

    if (!$user->password) {
        return back()->with('error', 'El administrador debe tener contraseña propia.');
    }

    if (hash('sha256', $request->password) !== $user->password) {
        return back()->with('error', 'Credenciales incorrectas.');
    }

    if (!$user->email_verified_at) {
        return back()->with('error', 'Debes verificar tu cuenta antes de iniciar sesión.');
    }

    if (!$user->isAdmin()) {
        return back()->with('error', 'Este acceso es solo para administradores.');
    }

    Session::put('user_id', $user->id);
    Session::put('user_name', $user->name);
    Session::put('role_id', $user->role_id);

    return redirect()->route('dashboard');
}


}