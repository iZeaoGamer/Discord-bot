<?php
/*
 * DiscordBot, PocketMine-MP Plugin.
 *
 * Licensed under the Open Software License version 3.0 (OSL-3.0)
 * Copyright (C) 2020-2021 JaxkDev
 *
 * Twitter :: @JaxkDev
 * Discord :: JaxkDev#2698
 * Email   :: JaxkDev@gmail.com
 */

namespace JaxkDev\DiscordBot\Communication\Packets\Plugin;

use JaxkDev\DiscordBot\Communication\Packets\Packet;

use Discord\Builders\MessageBuilder;
use JaxkDev\DiscordBot\Models\Messages\Message;

class RequestCreateInteraction extends Packet
{

    /** @var MessageBuilder */
    private $response;

    /** @var Message */
    private $message;

    /** @var bool */
    private $ephemeral;

    public function __construct(MessageBuilder $response, Message $message, bool $ephemeral)
    {
        parent::__construct();
        $this->response = $response;
        $this->message = $message;
        $this->ephemeral = $ephemeral;
    }
    public function getMessageBuilder(): MessageBuilder
    {
        return $this->response;
    }
    public function getMessage(): Message
    {
        return $this->message;
    }
    public function isEphemeral(): bool
    {
        return $this->ephemeral;
    }


    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->response,
            $this->message,
            $this->ephemeral

        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->response,
            $this->message,
            $this->ephemeral
        ] = unserialize($data);
    }
}
