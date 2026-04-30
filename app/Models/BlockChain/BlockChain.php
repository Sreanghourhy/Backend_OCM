<?php
namespace App\Models\BlockChain;
use App\Models\BlockChain\Block;

class BlockChain
{
    private array $chain;

    public function __construct()
    {
        $this->chain = [$this->createGenesisBlock()];
    }

    private function createGenesisBlock(): Block
    {
        return new Block(
            0, 
            ['Genesis Block'],
            '0'
        );
    }

    public function getLatestBlock(): Block
    {
        return $this->chain[count($this->chain) - 1];
    }

    public function getLength(): int {
        return count( $this->chain ) ;
    }

    public function addBlock(Block $newBlock): void
    {
        $newBlock->setPreviousHash( $this->getLatestBlock()->getHash() );
        $newBlock->setHash( $newBlock->calculateHash() );
        $this->chain[] = $newBlock;
    }

    public function isPure(): bool
    {
        if( count( $this->chain ) == 1 ) return true ;
        $start = 1 ;
        do{
            if( 
                ( $this->chain[$start]->getHash() !== $this->chain[$start]->calculateHash() )
                || ( $this->chain[$start]->getPreviousHash() !== $this->chain[$start-1]->getHash() )
            ) return false;
            $start++;
        }while($start < count( $this->chain ) );
        return true ;
    }
    
}