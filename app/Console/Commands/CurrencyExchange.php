<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CurrencyExchange extends Command
{
    use \App\Traits\CurrencyExchange;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:exchange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $currency_exchange_from = config('currency_exchange_from') ?? 'USD';
        $currency_exchange_to = config('currency_exchange_to') ?? 'USD';

        $rate = $this->getExchangeRate($currency_exchange_from, $currency_exchange_to);

        //update setting
        $setting = \App\Models\SystemSettings::where('settings_key', 'general')->first();

        if($setting){
            $setting->settings_value = array_merge($setting->settings_value, ['currency_exchange_rate' => $rate]);
            $setting->save();
        }

    }
}
