<?php

namespace App;

use Symfony\Component\Messenger\Stamp\StampInterface;

class ChainStamp implements StampInterface
{
    private array $chain;

    public function __construct(array $chain)
    {
        $this->chain = $chain;
    }

    public function getChain(): array
    {
        return $this->chain;
    }
}
