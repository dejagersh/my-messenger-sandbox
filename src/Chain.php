<?php

namespace App;

use InvalidArgumentException;
use Symfony\Component\Messenger\Envelope;

class Chain
{
    public static function make(array $messages): Envelope
    {
        if (count($messages) === 0) {
            throw new InvalidArgumentException('Chain must contain at least one message');
        }

        $firstMessage = Envelope::wrap($messages[0]);

        if (count($messages) === 1) {
            return $firstMessage;
        }

        return $firstMessage->with(
            new ChainStamp(array_slice($messages, 1))
        );
    }
}
