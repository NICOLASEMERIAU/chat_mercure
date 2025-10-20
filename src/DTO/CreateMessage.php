<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class CreateMessage
{

    public function __construct(
        public string $content,
        public int $conversationId
    )    {

    }

}
