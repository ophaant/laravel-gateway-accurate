<?php

namespace Database\Seeders;

use App\Models\BlockIp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlockIpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = collect([
            [
                'ip'=>'103.180.166.103',
                'description' => 'Tanjidor POS',
                'type' => 'Production',
                'status' =>'Enable',
                'user_id' => 3
            ],
            [
                'ip'=>'127.0.0.1',
                'description'=>'IP Local Address',
                'type' => 'Development',
                'status' =>'Enable',
                'user_id' => 1
            ]

        ]);
        $data->each(function ($item) {
            BlockIp::create($item);
        });
    }
}
