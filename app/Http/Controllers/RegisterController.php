<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        Log::info('Form Data:', $request->all());

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            Log::error('Validation Errors:', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = $this->create($request->all());
            Log::info('User Created:', ['user' => $user]);
        } catch (\Exception $e) {
            Log::error('User Creation Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }

        Auth::login($user);

        return redirect()->intended('dashboard')->with('success', 'Registration successful!');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}