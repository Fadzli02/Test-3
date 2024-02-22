<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenController extends Controller
{
    public function login_view()
    {
        return  view('login');
    }

    public function regis_view()
    {
        return view('regis');
    }

    public function proses_login()
    {


        $valid = request()->validate([
            'username' => 'required',
            'password' => 'required|min:8'
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter'
        ]);

        // dd($valid["username"]);

        $data = User::where('name', $valid['username'])->first();

        if (!$data) {
            return back()->with('error', 'Username atau password salah!');
        }



        $credencial = [
            "name" => $data->name,
            "password" => $valid['password'],
        ];

        if (Auth::attempt($credencial)) {
            request()->session()->regenerate();

            if ($data->name == 'admin') {
                return redirect("admin")->with('status', 'Login Berhasil !');
            } else {
                return redirect('home')->with('status', 'Login berhasil!');
            }

        } else {
            return back()->with('error', 'Username atau password salah!');
        }
    }



    public function proses_regis()
    {

        $validregis = request()->validate([
            'username' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8'
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak Valid',
            'email.unique' => 'Email Sudah ada',
            'password.min' => 'Password minimal 8 karakter'
        ]);

        $pw = Hash::make($validregis['password']);

        $newUser = User::create([
            'name' => $validregis['username'],
            'email' => $validregis['email'],
            'password' => $pw,
        ]);

        if ($newUser) {
            return redirect('login')->with('status', 'Daftar Akun Berhasil!');
        } else {
            return back()->with('error', 'Ada yang salah Bro!');
        }
    }
    // Logout

    public function proses_logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect("/login")->with("status", "Anda telah log out!");
    }
}
