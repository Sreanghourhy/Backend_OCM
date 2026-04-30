<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as Faker;
use App\Models\People\People;
use App\Models\People\Officer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HolidayGenerate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:holiday {from} {to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate mock data for holiday';

    /**
     * Faker instance
     */
    protected $faker;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo $this->argument('from') . ' => ' . $this->argument('to') . PHP_EOL;
        $from = intval( $this->argument('from') ) >= 1970 ? \Carbon\Carbon::parse( $this->argument('from') . '-01-01' ) : false ;
        $to = intval( $this->argument('to') ) >= 1970 ? \Carbon\Carbon::parse( $this->argument('to') . '-01-01' ) : false ;
           
        $nationalHolidays = [
            [
                'title' => 'ចូលឆ្នាំសកល',
                'date' => '-01-01'
            ],
            [
                'title' => 'ជ័យជំនះ៧មករា',
                'date' => '-01-07',
            ],
            [
                'title' => 'ថ្ងៃសិទ្ធិនារិ',
                'date' => '-03-08',
            ],
            [
                'title' => 'ថ្ងៃចូលឆ្នាំប្រពៃណីជាតិខ្មែរ ១',
                'date' => '-04-14',
            ],
            [
                'title' => 'ថ្ងៃចូលឆ្នាំប្រពៃណីជាតិខ្មែរ ២',
                'date' => '-04-15',
            ],
            [
                'title' => 'ថ្ងៃចូលឆ្នាំប្រពៃណីជាតិខ្មែរ ៣',
                'date' => '-04-16',
            ],
            [
                'title' => 'ទិវាពលកម្មអន្តរជាតិ',
                'date' => '-05-01',
            ],
            [
                'title' => 'វិសាខបូជា',
                'date' => '-05-11',
            ],
            [
                'title' => 'ថ្ងៃចំម្រើនព្រះជន្មព្រះមហាក្សត្រ',
                'date' => '-05-14',
            ],
            [
                'title' => 'ព្រះរាជពិធីច្រតព្រះនង្គ័ល',
                'date' => '-05-15',
            ],
            [
                'title' => 'ចម្រើនព្រះជន្មសម្ដេចព្រះម្ចាសក្សត្រី',
                'date' => '-06-18',
            ],
            [
                'title' => 'ពិធីបុណ្យភ្ជំបិណ្យ ៣',
                'date' => '-09-23',
            ],
            [
                'title' => 'ពិធីបុណ្យភ្ជំបិណ្យ ១',
                'date' => '-09-21',
            ],
            [
                'title' => 'ពិធីបុណ្យភ្ជំបិណ្យ ២',
                'date' => '-09-22',
            ],
            [
                'title' => 'ថ្ងៃប្រកាសរដ្ឋធម្មនុញ្ញ',
                'date' => '-09-24',
            ],
            [
                'title' => 'ព្រះរាជពិធីកាន់ទុក្ខព្រះបរមរតនកោដ',
                'date' => '-10-15',
            ],
            [
                'title' => 'ថ្ងៃឡើងគ្រោងរាជសម្បត្តិ',
                'date' => '-10-29',
            ],
            [
                'title' => 'ព្រះរាជពិធីបុណ្យអំទូក អកអំបុក សំពះព្រះខែ ទី១',
                'date' => '-11-04',
            ],
            [
                'title' => 'ព្រះរាជពិធីបុណ្យអំទូក អកអំបុក សំពះព្រះខែ ទី២',
                'date' => '-11-05',
            ],
            [
                'title' => 'ព្រះរាជពិធីបុណ្យអំទូក អកអំបុក សំពះព្រះខែ ទី៣',
                'date' => '-11-06',
            ],
            [
                'title' => 'ថ្ងៃបុណ្យឯករាជជាតិ',
                'date' => '-11-09',
            ],
            [
                'title' => 'ថ្ងៃបុណ្យសន្តិភាពនៃព្រះរាជាណាចក្រកម្ពុជា',
                'date' => '-12-29',
            ]
        ];

        $this->truncateTables();

        echo "START GENERATE HOLIDAY FROM YEAR " . $from->format('Y') . ' TO YEAR ' . $to->format('Y') . PHP_EOL;
        for( $from ; $from->lte( $to) ; $from->addYear() ){
            echo "CREATE HOLIDAY OF YEAR " . $from->format('Y') . PHP_EOL;
            foreach( $nationalHolidays as $holiday ){
                echo "CREATE HOLIDAY : " . $from->format('Y') . $holiday['date'] . " => " . $holiday['title'] . PHP_EOL;
                \DB::table('holidays')->insert([
                    'title' => $holiday['title'],
                    'date' => $from->format('Y') . $holiday['date'],
                    'is_public' => 1 ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        }
        echo 'All Done !' . PHP_EOL;
    }

    /**
     * Truncate tables (PostgreSQL compatible)
     */
    protected function truncateTables()
    {
        $this->info('Truncating tables...');

        // Truncate in correct order due to foreign keys
        DB::statement('TRUNCATE TABLE holidays RESTART IDENTITY CASCADE;');

        $this->info('Tables truncated successfully.');
    }

}
