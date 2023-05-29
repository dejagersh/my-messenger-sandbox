<?php

namespace App\MessageHandler;

use App\Message\OptimizePodcast;
use App\Message\ProcessPodcast;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class OptimizePodcastHandler
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(OptimizePodcast $message)
    {
        $this->logger->info('Optimizing podcast!');
    }
}
