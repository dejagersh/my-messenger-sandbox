<?php

namespace App\Command;

use App\Chain;
use App\Kernel;
use App\Message\OptimizePodcast;
use App\Message\ProcessPodcast;
use Opis\Closure\SerializableClosure;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'SendMessage',
    description: 'Add a short description for your command',
)]
class SendMessageCommand extends Command
{
    public function __construct(private MessageBusInterface $messageBus, private LoggerInterface $logger, private Kernel $kernel)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->messageBus->dispatch(
            Chain::make([
                new ProcessPodcast(),
                new OptimizePodcast()
            ])
        );

        return Command::SUCCESS;
    }
}
