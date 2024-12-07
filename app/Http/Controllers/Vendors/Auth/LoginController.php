<?php

namespace App\Http\Controllers\Vendors\Auth;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showVendorRegisterForm()
    {
        return view('vendors.auth.register');
    }

    public function vendorRegister(Request $request)
    {
        // dd($request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|min:6',  // password confirmation
        ]);

        if ($validator->fails()) {
            return redirect()->route('vendor.register.form')
                ->withErrors($validator)
                ->withInput();
        }

        // Create the vendor and hash the password
        $vendor = Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Optionally, you can log the vendor in right after registration
        Auth::guard('vendor')->login($vendor);

        // Redirect to the vendor dashboard or wherever you want
        return redirect()->route('vendor.dashboard');
    }

    public function showVendorLoginForm()
    {
        return view('vendors.auth.login');
    }

    // public function vendorLogin(Request $request)
    // {
    //     // dd($request->all());
    //     $this->validate($request, [
    //         'email' => 'required|email',
    //         'password' => 'required|min:6'
    //     ]);

    //     if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
    //         return redirect()->intended('/vendor/dashboard');
    //     }
    //     return back()->withInput($request->only('email', 'remember'));
    // }

    public function vendorLogin(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->route('vendor.login.form')
                ->withErrors($validator)
                ->withInput();
        }

        // Attempt to log the vendor in
        if (Auth::guard('vendor')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->remember)) {
            // If successful, redirect to the vendor dashboard
            return redirect()->intended('/vendor/dashboard');
        }

        // If authentication fails, redirect back with input values and an error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function vendorLogout(Request $request)
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/vendor/login');
    }
}
