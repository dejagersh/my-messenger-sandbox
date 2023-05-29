<?php

namespace App\MessageHandler;

use App\Message\ProcessPodcast;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler]
class ProcessPodcastHandler
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(ProcessPodcast $message)
    {
        throw new Exception('Hello, world');


        $this->logger->info('Processing podcast!');
    }
}
