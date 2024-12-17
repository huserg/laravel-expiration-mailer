<?php

namespace HuserG\LaravelExpirationMailer\Controllers;

use HuserG\LaravelExpirationMailer\Models\Expiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class ExpirationController
{
    public function index()
    {
        $expirations = Expiration::all();

        return view('expiration-mailer::expirations.index', compact('expirations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'emails' => 'required|string',
            'message' => 'nullable|string',
        ]);

        // Retourner les erreurs si validation échoue
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Si l'input "emails" contient une seule adresse, la convertir en tableau
            $emails = is_string($request->input('emails'))
                ? array_map('trim', explode(',', $request->input('emails')))
                : [$request->input('emails')];

            // Nettoyer les emails : enlever les entrées vides et doublons
            $emails = array_unique(array_filter($emails, fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL)));

            if (empty($emails)) {
                return redirect()->back()
                    ->withErrors(['emails' => __('Please provide valid email addresses.')])
                    ->withInput();
            }

            Expiration::create([
                'name' => $request->input('name'),
                'expiration_date' => $request->input('expiration_date'),
                'emails' => json_encode($emails),
                'message' => $request->input('message'),
            ]);

            return redirect()->back()->with('success', __('Expiration added successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => __('Something went wrong. Please try again.')])
                ->withInput();
        }
    }

    public function forceSend($id)
    {
        try {
            // Passer l'ID spécifique à la commande artisan
            Artisan::call('expirations:send-mail', [
                'id' => $id // En supposant que ta commande accepte un paramètre "id"
            ]);

            return redirect()->back()->with('success', __('Email sent successfully via command.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to send email: ' . $e->getMessage()));
        }
    }
}
