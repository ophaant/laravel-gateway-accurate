<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Tanjidor',
            'email' => 'tanjidor@accurate.gatotkaca.id',
            'password' => bcrypt('Delta_234@')]);
        $user->givePermissions(['sales_invoices-create']);
    }
}
