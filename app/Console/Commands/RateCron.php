<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravie\Parser\Xml\Reader;
use Laravie\Parser\Xml\Document;
use App\Models\Currency;

class RateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rate:cron';

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
        \Log::info("Cron is working fine!");

        $xml = (new Reader(new Document()))->load('https://www.cbr.ru/scripts/XML_daily.asp');
        $rates = $xml->parse([
            'numcode' => ['uses' => 'Valute[NumCode]'],
            'name' => ['uses' => 'Valute[Name]'],
            'rate' => ['uses' => 'Valute[Value]'],
          ]);

        $data = [];
        for($i= 0; $i < count($rates['numcode']); $i++){

            $currrency = Currency::create([
                'name' => $rates['name'][$i]['Name'],
                'rate' => $rates['rate'][$i]['Value'],
                'numcode' => $rates['numcode'][$i]['NumCode'],
                
            ]);   
        }
    }
}
