<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReorderOrganizationChart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reorder-organization-chart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the order represent the organication chart.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "READING ROOT NOTE." . PHP_EOL;
        \App\Models\Organization\OrganizationStructure::whereNull('pid')->orWhere('pid',0)->get()->map(function($root){
            echo "ROOT NODE NAME : " . $root->organization->name . PHP_EOL;
            $root->update(['tpid'=> $root->pid . ':' ]);
            if( $root->children != null && $root->children->count() > 0 ){
                $this->reorderTpid( $root );
            }    
        });
    }

    private function reorderTpid($root){
        $root->children->map(function($child) use($root){
            echo "CHILD NODE NAME : " . $child->organization->name . PHP_EOL;
            $child->update(['tpid'=> $root->tpid . $root->id . ':' ]);
            if( $child->children != null && $child->children->count() > 0 ){
                $this->reorderTpid( $child );
            }
        });
    }
}
