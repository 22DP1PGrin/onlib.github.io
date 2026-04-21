<?php

namespace App\Http\Controllers;

use App\Models\TechnicalSupportForm;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Kontrolieris, kas apstrādā ar lietotāja pieteikumiem saistītās darbības
class SupportController extends Controller
{
    // Metode, kas apstrādā atbalsta pieteikuma saglabāšanu
    public function store(Request $request)
    {
        // Valide saņemtos datus
        $validated = $request->validate([
            'nickname' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'subject' => 'required|string|max:50',
            'problem' => 'required|string',
        ]);

        // Izveido jaunu atbalsta pieteikumu datu bāzē
        TechnicalSupportForm::create([
            'nickname' => $validated['nickname'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'problem' => $validated['problem'],
        ]);

        // Atgriež atbildi, ka pieteikums ir veiksmīgi nosūtīts
        return back()->with('success', 'Pieteikums veiksmīgi nosūtīts!');

    }

    // Atgriež tehniskā atbalsta pieteikumu sarakstu (ar meklēšanu)
    public function showProblems(Request $request)
    {
        $search = $request->input('search'); // Meklēšanas pieprasījums

        $technical_support_form = TechnicalSupportForm::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nickname', 'like', "%{$search}%"); // Filtrē pēc lietotāja lieotājvārda
            })
            ->latest() // Jaunākie pieteikumi vispirms
            ->get();

        return Inertia::render('Control/TechnicalSupport/Problems', [
            'technical_support_form' => $technical_support_form,
            'filters' => [
                'search' => $search // Aktīvais meklēšanas filtrs
            ]
        ]);
    }

    // Dzēš tehniskā atbalsta pieteikumu
    public function destroy($id)
    {
        $form = TechnicalSupportForm::findOrFail($id); // Atrod pieteikumu
        $form->delete(); // Dzēš pieteikumu

        return redirect()->route('problems')
            ->with('success', 'Pieteikums dzēsts.'); // Paziņojums par veiksmīgu dzēšanu
    }
}
