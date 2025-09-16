<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Stock\ProductStocksController;
use Illuminate\Console\Command;

class SendAlertEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:stock-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send stock alert email on admin and owner email.';

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
        resolve(ProductStocksController::class)->sendAlertQuantityOnEmail();

        $this->info('Stock Alert Send Complete');
    }
}
