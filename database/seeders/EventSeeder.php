<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::withoutEvents(function () {
            
            $this->command->info('Membuat peserta...');
            User::factory(80)->create(['institution' => 'PSDMBP']);
            User::factory(25)->create(fn () => [
                'institution' => 'Satker Badan Geologi Lainnya',
                'phone_number' => rand(0, 1) ? null : fake('id_ID')->phoneNumber(),
            ]);

            $this->command->info('Membuat event...');
            $events = Event::factory(40)->create();
            $participants = User::where('role', 'participant')->get();
            $categories = Category::all();

            $this->command->info('Mengundang peserta dan membuat data kehadiran...');
            $attendances = [];

            foreach ($events as $event) {
                $eventCategoryName = $categories->find($event->category_id)->name;

                // Peserta yang relevan berdasarkan 'specialty'
                $relevantParticipants = $participants->where('specialty', $eventCategoryName);
                
                // Peserta dari divisi 'Umum'
                $generalParticipants = $participants->where('specialty', 'Umum');

                // Gabungkan peserta (pastikan generalParticipants tidak kosong)
                $invitedParticipants = $relevantParticipants;
                if ($generalParticipants->isNotEmpty()) {
                    $invitedParticipants = $invitedParticipants->merge($generalParticipants->random(min($generalParticipants->count(), rand(3, 5))));
                }
                $invitedParticipants = $invitedParticipants->unique('id');

                if ($invitedParticipants->isEmpty()) continue;

                if ($invitedParticipants->count() > 30) {
                    $invitedParticipants = $invitedParticipants->random(30);
                }

                $event->participants()->attach($invitedParticipants->pluck('id'));

                if ($event->status == 'Selesai') {
                    foreach ($invitedParticipants as $participant) {
                        if (rand(1, 100) <= 90) { // Peluang 90% hadir
                            $checkInTime = (clone $event->start_time)->modify('+' . rand(5, 30) . ' minutes');
                            $attendances[] = [
                                'event_id' => $event->id,
                                'user_id' => $participant->id,
                                'check_in_time' => $checkInTime,
                                'created_at' => $checkInTime,
                                'updated_at' => $checkInTime,
                            ];
                        }
                    }
                }
            }
            
            if (!empty($attendances)) {
                DB::table('attendances')->insert($attendances);
            }
        });
        
        $this->command->info('Seeding event dan peserta selesai!');
    }
}