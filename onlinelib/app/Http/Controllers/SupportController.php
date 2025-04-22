<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TechnicalSupportForm;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    // Metode, kas apstrādā atbalsta pieteikuma saglabāšanu
    public function store(Request $request)
    {
        // Validējam saņemtos datus
        $validated = $request->validate([
            'nickname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'problem' => 'required|string|max:1000',
        ]);

        try {
            // Izveidojam jaunu atbalsta pieteikumu datu bāzē
            $form = TechnicalSupportForm::create([
                'nickname' => $validated['nickname'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'problem' => $validated['problem'],
            ]);

            // Atgriežam atbildi, ka pieteikums ir veiksmīgi nosūtīts
            return response()->json(['message' => 'Pieteikums veiksmīgi nosūtīts!'], 200);
        } catch (\Exception $e) {
            // Ja notika kļūda, atgriežam kļūdas ziņojumu
            return response()->json(['error' => 'Radās kļūda: ' . $e->getMessage()], 500);
        }
    }
}
