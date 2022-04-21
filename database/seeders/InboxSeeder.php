<?php

namespace Database\Seeders;

use App\Models\Inbox;
use Illuminate\Database\Seeder;

class InboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inbox = Inbox::create([
            'name' => 'admin',
            'user_id' => '1',
            'email' => 'admin@admin.com',
            'subject' => 'subject subject',
            'message' => 'message message message message',
        ]);
    }
}
