<?php

namespace HuserG\LaravelExpirationMailer\Controllers;

use HuserG\LaravelExpirationMailer\Models\Expiration;
use Illuminate\Http\Request;

class ExpirationController
{
    public function index()
    {
        $expirations = Expiration::all();

        return view('laravel-expiration-manager::index', compact('expirations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'emails' => 'required|array',
            'message' => 'nullable|string',
        ]);

        Expiration::create($request->all());

        return redirect()->back()->with('success', __('Expiration added successfully'));
    }
}
