<?php

namespace App\Http\Controllers;

use App\Models\ClassicBook;
use App\Models\ObjectReport;
use App\Models\TechnicalSupportForm;
use App\Models\UserBook;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Carbon\Carbon;

// Kontrolieris, kas veido pdf izrakstas
class PdfExport
{
    // Eksportē lietotāju sarakstu PDF formātā
    public function exportUsersPdf()
    {
        // Saņem lietotājus ar prioritāti pēc lomas
        $users = User::orderByRaw("
        CASE role
            WHEN 'superadmin' THEN 1
            WHEN 'admin' THEN 2
            ELSE 3
        END
    ")->get();

        // Pievieno tulkotos laukus un bloķēšanas statusu
        $users->transform(function ($user) {
            $user->role_translated = match ($user->role) {
                'superadmin' => 'Super Administrators',
                'admin' => 'Administrators',
                default => 'Lietotājs'
            };

            $user->block_status = $user->is_blocked
                ? 'Bloķēts'
                : 'Nav bloķēts';

            return $user;
        });

        // PDF informācija
        $lastUpdated = Carbon::now()->format('Y-m-d H:i:s'); // pēdējās atjaunināšanas laiks
        $totalUsers = $users->count(); // kopējais lietotāju skaits

        // Izveido PDF no skata
        $pdf = Pdf::loadView('pdf.users', compact(
            'users',
            'lastUpdated',
            'totalUsers'
        ));

        return $pdf->download('users_report.pdf'); // Lejupielādē PDF
    }

    // Eksportē grāmatu un stāstu sarakstu PDF
    public function exportBooksPdf()
    {
        // Saņem lietotāju stāstus un klasiskās grāmatas
        $userBooks = UserBook::with('user')->get();
        $classicBooks = ClassicBook::get();

        // Skaits katram tipam
        $userBooksCount = $userBooks->count();
        $classicBooksCount = $classicBooks->count();
        $totalCount = $userBooksCount + $classicBooksCount;

        // Pārveido lietotāju stāstus un grāmatas PDF formātam un apvieno viena kolekcijā
        $books = $userBooks->map(function ($book) {
            $book->type = 'Stāsts';
            $book->author_name = $book->user->nickname ?? '—';
            return $book;
        })->merge(
            $classicBooks->map(function ($book) {
                $book->type = 'Grāmata';
                $book->author_name = $book->Author_name . ' ' . $book->Author_surname;
                return $book;
            })
        );

        // Kārto: vispirms Grāmatas, pēc tam Stāsti
        $books = $books->sortBy(fn($b) => $b->type === 'Grāmata' ? 1 : 2);

        // Pievieno bloķēšanas statusu
        $books = $books->map(function ($book) {
            $book->block_status = $book->is_blocked
                ? 'Bloķēts'
                : 'Nav bloķēts';
            return $book;
        });

        $lastUpdated = Carbon::now()->format('Y-m-d H:i:s'); // Pēdējās atjaunināšanas laiks

        // Izveido PDF
        $pdf = Pdf::loadView('pdf.books', compact(
            'books',
            'lastUpdated',
            'totalCount',
            'userBooksCount',
            'classicBooksCount'
        ));

        return $pdf->download('books_report.pdf'); // Lejupielādē PDF
    }

    // Eksportē ziņojumu sarakstu PDF
    public function exportReportsPdf()
    {
        // Saņem visus ziņojumus ar saistītajiem objektiem
        $reportsRaw = ObjectReport::with([
            'reportedUser',
            'userBook',
            'classicBook',
            'comment',
            'reporter'
        ])->get();

        // Pārveido ziņojumus PDF formātam
        $reports = $reportsRaw->map(function ($report) {

            // Nosaka ziņojuma tipu
            if ($report->reported_user_id) {
                $type = 'Lietotājs';
                $targetName = $report->reportedUser->nickname ?? '—';
                $sortPriority = 1;
            }
            elseif ($report->user_book_id) {
                $type = 'Stāsts';
                $targetName = $report->userBook->name ?? '—';
                $sortPriority = 2;
            }
            elseif ($report->classic_book_id) {
                $type = 'Grāmata';
                $targetName = $report->classicBook->name ?? '—';
                $sortPriority = 3;
            }
            else{
                $type = 'Komentārs';
                $targetName = $report->comment->content ?? '—';
                $sortPriority = 4;
            }

            return [
                'type' => $type,
                'target' => $targetName,
                'reporter' => $report->reporter->nickname ?? '—',
                'reason' => $report->subject,
                'created_at' => $report->created_at,
                'sort_priority' => $sortPriority
            ];
        });

        // Kārto pēc prioritātes
        $reports = $reports->sortBy('sort_priority');

        $totalReports = $reports->count(); // kopējais ziņojumu skaits
        $lastUpdated = now()->format('Y-m-d H:i:s');

        // Izveido PDF
        $pdf = Pdf::loadView('pdf.reports', compact(
            'reports',
            'totalReports',
            'lastUpdated'
        ));

        return $pdf->download('reports_list.pdf'); // Lejupielādē PDF
    }

    // Eksportē tehniskā atbalsta jautājumus PDF
    public function exportProblemsPdf()
    {
        // Saņem visas tehniskā atbalsta formas
        $forms = TechnicalSupportForm::all();

        // Nosaka pēdējās atjaunināšanas laiku
        $lastUpdated = $forms->max('updated_at');
        $totalForms = $forms->count();
        $lastUpdatedFormatted = $lastUpdated ? Carbon::parse($lastUpdated)->format('d.m.Y H:i') : now()->format('d.m.Y H:i');

        // Izveido PDF
        $pdf = Pdf::loadView('pdf.problems', [
            'forms' => $forms,
            'totalForms' => $totalForms,
            'lastUpdated' => $lastUpdatedFormatted
        ]);

        return $pdf->download('questions_reports.pdf'); // Lejupielādē PDF
    }
}
