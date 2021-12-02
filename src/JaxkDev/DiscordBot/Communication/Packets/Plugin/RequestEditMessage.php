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

use JaxkDev\DiscordBot\Models\Messages\Message;
use Discord\Builders\MessageBuilder;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestEditMessage extends Packet
{

    /** @var Message */
    private $message;

    /** @var MessageBuilder|null */
    private $builder;

    public function __construct(Message $message, ?MessageBuilder $builder)
    {
        parent::__construct();
        $this->message = $message;
        $this->builder = $builder;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
    public function getMessageBuilder(): ?MessageBuilder{
        return $this->builder;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->message,
            $this->builder
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->message,
            $this->builder
        ] = unserialize($data);
    }
}
