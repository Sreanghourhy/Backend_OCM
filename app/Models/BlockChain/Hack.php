<?php
namespace App\Models\BlockChain;

use App\Models\BlockChain\BlockChain;
use App\Models\BlockChain\Block;

class Hack {
    public function __construct() {
        /*
        Hack the chain, changing values in the first block.
        */

        $testCoin = new \App\Models\Blockchain\BlockChain();

        echo "mining block 1...\n";
        $testCoin->addBlock(new Block(1, [ 'amount' => 4 ] ));

        echo "mining block 2...\n";
        $testCoin->addBlock(new Block(2, [ 'amount' => 10 ] ));

        echo "Chain valid: ".($testCoin->isPure(true) ? "true" : "false")."\n";
    }
}