<?php

namespace App\Http\Controllers;

use App\Models\levelmodel;
use App\Models\usermodel;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(){
        $level = levelmodel::all();
        $user = usermodel::all();
        return view('auth.register',['level'=>$level,'user'=>$user]);
    }

    public function store(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Hash the password before saving the user
            $data = $request->all();
            $data['password'] = Hash::make($request->password); // Hash the password

            usermodel::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan',
                'redirect'=>url('login')
            ]);
        }
        return redirect('/');
    }
}