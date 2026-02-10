<?php

namespace App\Console\Commands;

use App\Mail\UserUnblocked;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UnblockExpiredUsers extends Command
{
    protected $signature = 'app:unblock-expired-users';

    protected $description = 'Command description';

    // Komandas izpilde
    public function handle()
    {
        // Iegūst pašreizējo laiku, ņemot vērā lietotnes laika joslu
        $now = now()->setTimezone(config('app.timezone'));

        // Atlasa visus lietotājus, kuri ir bloķēti un kuru bloķēšanas termiņš ir beidzies
        $users = User::where('is_blocked', true)
            ->whereNotNull('blocked_until')
            ->where('blocked_until', '<=', $now)
            ->get();


        // Cikls cauri katram atrastajam lietotājam
        foreach ($users as $user) {

            // Atjauno lietotāja statusu — atbloķē un notīra bloķēšanas datumu
            $user->update([
                'is_blocked' => false,
                'blocked_until' => null,
            ]);

            // Nosūta e-pastu lietotājam
            Mail::to($user->email)->send(new UserUnblocked($user->nickname));
        }

        return Command::SUCCESS;
    }
}
