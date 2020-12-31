<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
// use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->is_active) {
                return $next($request);
            }

            if ($request->ajax()) {
                $request->session()->flash('alert', 'User Tidak Aktif Atau Di Block, Redirected To Home');
                Auth::logout();

                return response('User Tidak Aktif Atau Di Block', 401);
            }

            Auth::logout();

            return redirect('/oauth2/sign_out');
        }

        $email = @$_SERVER['HTTP_X_EMAIL'] != null ? $_SERVER['HTTP_X_EMAIL'] : env('TEST_EMAIL');

        $mail_data = explode('@', $email);
        if (count($mail_data) < 2) {
            return redirect('/')->with('alert', 'Kamu Harus Pakai Account cilientname');
        }

        if ($mail_data[1] != 'cilientname.id') {
            return redirect('/')->with('alert', 'Kamu Harus Pakai Account cilientname');
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            if ($user->is_active) {
                Auth::login($user);

                return $next($request);
            }

            if ($request->ajax()) {
                $request->session()->flash('alert', 'User Tidak Aktif Atau Di Block, Redirected To Home');
                Auth::logout();

                return response('User Tidak Aktif Atau Di Block', 401);
            }
            Auth::logout();

            return redirect('/oauth2/sign_out');
        }

        try {
            $data = ['email' => $email, 'name' => $mail_data[0], 'password' => Hash::make('mypassword')];
            $user = User::create($data);
            Auth::login($user);

            return $next($request);
        } catch (\Exception $e) {
            return redirect('/')->with('alert', 'Server Error');
        }
    }
}
