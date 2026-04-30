<?php
namespace App\Models\BlockChain;

class Block
{
    private int $index;
    private String $timestamp;
    private Array $data;
    private String $previousHash;
    private String $hash;
    private int $calculationCounter;

    public function __construct(int $index,Array $data, String $previousHash = '')
    {
        $this->calculationCounter = 0;
        $this->setIndex( $index );
        $this->setTimestamp( strtotime("now") );
        $this->setData( $data );
        $this->setPreviousHash( $previousHash );
        $this->setHash( $this->calculateHash() );
    }
    public function getHash(): String {
        return $this->hash;
    }
    public function getPreviousHash(): String {
        return $this->previousHash;
    }
    public function getData(): Array {
        return $this->data;
    }
    public function getIndex(): int {
        return $this->index;
    }
    public function getTimestamp(): String {
        return $this->timestamp;
    }
    public function setIndex(int $index){
        $this->index = $index ;
    }
    public function setTimestamp(String $timestamp){
        $this->timestamp = $timestamp ;
    }
    public function setData(Array $data){
        $this->data = $data ;
    }
    public function setHash(String $hash){
        $this->hash = $hash ;
    }
    public function setPreviousHash(String $hash){
        $this->previousHash = $hash ;
    }
    public function calculateHash(): String
    {
        $this->calculationCounter++;
        return hash(
            'sha256', 
            sprintf(
               '%d%s%s%s',
               $this->getIndex() ,
               $this->getTimestamp() ,
               $this->getPreviousHash() ,
               json_encode($this->getData()),
           )
        );
    }
    public function toString(): String{
        return 
            '===============' . PHP_EOL .
            'COUNTER       : ' . $this->calculationCounter . PHP_EOL .
            'INDEX         : ' . $this->getIndex() . PHP_EOL .
            'TIMESTAMP     : ' . $this->getTimestamp() . PHP_EOL .
            'DATA          : ' . json_encode($this->getData()) . PHP_EOL .
            '---------------' . PHP_EOL .
            'HASH          : ' . $this->getHash() . PHP_EOL .
            'PREVIOUS HASH : ' . $this->getPreviousHash() . PHP_EOL .
            '==============='
        ;
    }
}