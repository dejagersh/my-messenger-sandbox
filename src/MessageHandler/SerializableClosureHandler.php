<?php

namespace App\MessageHandler;

use App\Kernel;
use Opis\Closure\SerializableClosure;
use ReflectionFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SerializableClosureHandler
{
    private ContainerInterface $container;

    public function __construct(Kernel $kernel)
    {
        $this->container = $kernel->getContainer();
    }

    public function __invoke(SerializableClosure $serializableClosure)
    {
        $closure = $serializableClosure->getClosure();

        $parameters = (new ReflectionFunction($closure))->getParameters();

        $args = [];

        // For each parameter, fetch the appropriate service from the container
        foreach ($parameters as $parameter) {
            $typeHint = $parameter->getType();

            $invadedContainer = invade($this->container);

            $args[] = $invadedContainer->get($typeHint->getName(), ContainerInterface::NULL_ON_INVALID_REFERENCE)
                ?? $invadedContainer->privates($typeHint->getName());
        }

        $closure(...$args);
    }
}
