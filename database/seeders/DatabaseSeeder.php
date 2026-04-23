<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AiSetting;
use App\Models\Contact;
use App\Models\MailingList;
use App\Models\SmtpSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Paolo Bellini',
            'email' => 'paolo@bellini.one',
            'onboarded_at' => now(),
        ]);

        SmtpSetting::factory()->create([
            'user_id' => $user->id,
        ]);
        AiSetting::factory()->create([
            'user_id' => $user->id,
        ]);

        $contacts = Contact::factory(50)->create();
        $unsubscribed = Contact::factory(10)->unsubscribed()->create();

        $newsletter = MailingList::factory()->create([
            'name' => 'Newsletter',
            'description' => 'Iscritti alla newsletter settimanale',
            'icon' => 'envelope',
            'color' => 'wine',
        ]);

        $productUpdates = MailingList::factory()->create([
            'name' => 'Product Updates',
            'description' => 'Aggiornamenti su nuovi prodotti e funzionalità',
            'icon' => 'megaphone',
            'color' => 'blue',
        ]);

        $vip = MailingList::factory()->create([
            'name' => 'VIP Customers',
            'description' => 'Clienti premium con alto tasso di engagement',
            'icon' => 'star',
            'color' => 'amber',
        ]);

        MailingList::factory()->create([
            'name' => 'Re-engagement',
            'description' => 'Contatti inattivi da più di 60 giorni',
            'icon' => 'arrow-path',
            'color' => 'orange',
            'is_ai_generated' => true,
        ]);

        MailingList::factory()->create([
            'name' => 'New Subscribers',
            'description' => 'Iscritti negli ultimi 30 giorni',
            'icon' => 'sparkles',
            'color' => 'green',
            'is_ai_generated' => true,
        ]);

        MailingList::factory()->create([
            'name' => 'Events',
            'description' => 'Partecipanti a eventi e degustazioni',
            'icon' => 'calendar-days',
            'color' => 'purple',
        ]);

        $allContacts = $contacts->merge($unsubscribed);

        $newsletter->contacts()->attach($allContacts->random(40)->pluck('id'));
        $productUpdates->contacts()->attach($allContacts->random(25)->pluck('id'));
        $vip->contacts()->attach($contacts->random(10)->pluck('id'));
    }
}
