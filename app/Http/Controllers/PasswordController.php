<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit()
    {
        $data  = [        
        'menu'          =>  'Ubah' ,
        'title'         =>  'Ubah Password',        
    ];
        return view('auth.change-password',$data);
    }

    public function update(Request $request)
    {
        // validasi input
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|min:8|confirmed',
        ]);

        $user = Session("user_data");

        // cek password lama
        if (md5($request->current_password)!=$user->password) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.'
            ]);
        }

        // update password
        $user->password = md5($request->new_password);
        $user->save();

        return redirect()->route('password.edit')->with('success', 'Password berhasil diubah!');
    }
}
