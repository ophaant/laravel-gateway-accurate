<?php

namespace App\Console\Commands;

use App\Services\Accurate\AccurateAuthServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Token Accurate description';

    /**
     * Execute the console command.
     */
    protected $accurateAuthServices;

    public function __construct(AccurateAuthServices $accurateAuthServices)
    {
        parent::__construct();
        $this->accurateAuthServices = $accurateAuthServices;
    }

    public function handle(): void
    {
        Log::info("Refresh Token Cron running at " . now());

        $this->accurateAuthServices->refreshToken();

        Log::info("Refresh Token Cron stopping at " . now());
    }
}
