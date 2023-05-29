<?php

namespace App\Middleware;

use App\Chain;
use App\ChainStamp;
use Exception;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class ChainMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $chainStamp = $envelope->last(ChainStamp::class);
        $handledStamp = $envelope->last(HandledStamp::class);

        if (!$chainStamp || !$handledStamp || count($chainStamp->getChain()) === 0) {
            return $envelope;
        }

        $this->messageBus->dispatch(
            Chain::make($chainStamp->getChain()),
        );

        return $envelope;
    }
}
