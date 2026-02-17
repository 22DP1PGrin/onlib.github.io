<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TechnicalSupportForm;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

    // Atgriež visu tehnisko atbalsta pieteikumu sarakstu
    public function showProblems(Request $request)
    {
        $search = $request->input('search');

        $technical_support_form = TechnicalSupportForm::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nickname', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return Inertia::render('Control/TechnicalSupport/Problems', [
            'technical_support_form' => $technical_support_form,
            'filters' => [
                'search' => $search
            ]
        ]);
    }

    // Atgriež konkrēta pieteikuma detalizētu informāciju
    public function showForm($id)
    {
        $form = TechnicalSupportForm::findOrFail($id);

        return Inertia::render('Control/TechnicalSupport/ProblemInfo', [
            'form' => $form,
        ]);
    }

    // Dzēš konkrētu tehnisko atbalsta pieteikumu
    public function destroy($id)
    {
        $form = TechnicalSupportForm::findOrFail($id);
        $form->delete();

        return redirect()->route('problems')->with('success', 'Pieteikums dzēsts.');
    }
}
