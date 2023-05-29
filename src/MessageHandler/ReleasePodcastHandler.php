<?php

namespace App\MessageHandler;

use App\Message\ReleasePodcast;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ReleasePodcastHandler
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(ReleasePodcast $message)
    {
        $this->logger->info('Releasing podcast!');
    }
}
