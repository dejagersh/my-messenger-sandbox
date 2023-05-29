<?php

namespace App\Tests;

use App\Chain;
use App\Message\OptimizePodcast;
use App\Message\ProcessPodcast;
use App\Middleware\ChainMiddleware;
use InvalidArgumentException;
use Opis\Closure\SerializableClosure;
use Psr\Log\LoggerInterface;
use ReflectionFunction;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Middleware\StackMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;


class ChainingTest extends KernelTestCase
{

    public function test_next_message_in_chain_is_dispatched_after_handling_the_first_message()
    {
        $envelope = Chain::make([
            new ProcessPodcast(),
            new OptimizePodcast()
        ])->with(new HandledStamp(null, 'handler'));

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(fn($d) => $d instanceof Envelope && $d->getMessage() instanceof OptimizePodcast))
            ->willReturn($envelope);

        $chainMiddleware = new ChainMiddleware($messageBus);

        $chainMiddleware->handle(
            $envelope,
            $this->getStackMock()
        );
    }

    public function test_middleware_does_not_dispatch_anything_when_single_element_in_chain()
    {
        $envelope = Chain::make([new ProcessPodcast()])->with(new HandledStamp(null, 'handler'));

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus
            ->expects($this->never())
            ->method('dispatch');

        $chainMiddleware = new ChainMiddleware($messageBus);

        $chainMiddleware->handle(
            $envelope,
            $this->getStackMock()
        );
    }

    public function test_chain_can_not_be_empty()
    {
        $this->expectException(InvalidArgumentException::class);

        Chain::make([]);
    }

    protected function getStackMock()
    {
        $nextMiddleware = $this->createMock(MiddlewareInterface::class);
        $nextMiddleware
            ->expects($this->once())
            ->method('handle')
            ->willReturnCallback(fn(Envelope $envelope, StackInterface $stack): Envelope => $envelope);

        return new StackMiddleware($nextMiddleware);
    }
}
