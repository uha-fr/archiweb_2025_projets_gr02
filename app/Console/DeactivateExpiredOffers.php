<?php

namespace App\Console\Commands;

use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeactivateExpiredOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Désactive les offres dont la date de fin est passée';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Trouver toutes les offres actives dont la date de fin est passée
        $expiredOffers = Offer::where('status', 'active')
                            ->where('end_time', '<', $now)
                            ->get();
        
        $count = 0;
        foreach ($expiredOffers as $offer) {
            $offer->status = 'cancelled';
            $offer->save();
            $count++;
        }
        
        $this->info("$count offres expirées ont été désactivées.");
        
        return 0;
    }
}
