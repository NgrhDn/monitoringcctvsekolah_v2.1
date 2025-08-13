<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;

class InfoUserController extends Controller
{

    public function create()
    {
        return view('laravel-examples/user-profile');
    }

    public function store(Request $request)
    {

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            'phone'     => ['max:50'],
            'location' => ['max:70'],
            'about_me'    => ['max:150'],
        ]);
        if($request->get('email') != Auth::user()->email)
        {
            if(env('IS_DEMO') && Auth::user()->id == 1)
            {
                return redirect()->back()->withErrors(['msg2' => 'You are in a demo version, you can\'t change the email address.']);
                
            }
            
        }
        else{
            $attribute = request()->validate([
                'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            ]);
        }
        
        
        User::where('id',Auth::user()->id)
        ->update([
            'name'    => $attributes['name'],
            'email' => $attribute['email'],
            'phone'     => $attributes['phone'],
            'location' => $attributes['location'],
            'about_me'    => $attributes["about_me"],
        ]);


        return redirect('/user-profile')->with('success','Profile updated successfully');
    }

    public function daftarAdmin()
    {
        $users = \App\Models\User::select('name', 'email', 'phone')->get();
        return view('rekapan.users', compact('users'));
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('profile.profile-user', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $attributes = $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'max:20'],
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama maksimal 50 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
        ]);

        // Cek apakah ada perubahan data
        if ($attributes['name'] == $user->name && 
            $attributes['email'] == $user->email && 
            $attributes['phone'] == $user->phone) {
            return redirect()->route('profile-user')->with('error', 'Tidak ada perubahan data yang disimpan.');
        }

        $user->update($attributes);

        return redirect()->route('profile-user')->with('success', 'Profil berhasil diperbarui!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini harus diisi.',
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile-user')->with('error', 'Password saat ini tidak benar!');
        }

        // Cek apakah password baru sama dengan password lama
        if (Hash::check($request->new_password, $user->password)) {
            return redirect()->route('profile-user')->with('error', 'Password baru tidak boleh sama dengan password lama.');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile-user')->with('success', 'Password berhasil diubah!');
    }
}