<?php
namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\DiscountCode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredDiscountCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discounts:deactivate-expired';
    protected $description = 'Deactivate discount codes whose end date and time have passed';

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
        // Get the current UTC date and time
        $now = Carbon::now('UTC');

        // Log the current time for debugging purposes
        Log::info('Running DeactivateExpiredDiscountCodes at: ' . $now->toIso8601String());

        // Update expired discount codes whose end_date is less than or equal to the current UTC time
        $affectedRows = DiscountCode::where('end_date', '<=', $now)
            ->where('expiration_status', 'active')
            ->update([
                'expiration_status' => 'inactive',
                'status' => '0'
            ]);

        // Log how many rows were updated
        Log::info("Number of discount codes deactivated: $affectedRows");

        if ($affectedRows > 0) {
            $this->info('Expired discount codes have been deactivated.');
        } else {
            $this->info('No expired discount codes found or updated.');
        }

        return 0;
    }
}
