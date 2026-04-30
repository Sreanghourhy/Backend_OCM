<?php
namespace App\Models\BlockChain;

use App\Models\BlockChain\BlockChain;
use App\Models\BlockChain\Block;

class Simple {
    public function __construct() {
        /*
        Set up a simple chain and mine two blocks.
        */

        $testCoin = new BlockChain();

        echo "mining block 1...\n";
        $testCoin->addBlock(new Block(1, [ 'amount' => 4 ] ));

        echo "mining block 2...\n";
        $testCoin->addBlock(new Block(2, [ 'amount' => 10 ] ));

        echo json_encode($testCoin, JSON_PRETTY_PRINT);
    }
}