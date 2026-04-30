<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DocumentTransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:document-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * ដំណាក់កាលចុះលេខឯកសារ
         */
        $documentTransaciton = \App\Models\Document\Transaction::create([
            
        ]);

        \App\Models\Officer\Officer::inRandomOrder()->limit(1)->first();

        $documentTransaciton->save();
        $documentTransaciton->send();

    }


}
