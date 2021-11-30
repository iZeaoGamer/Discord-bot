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

class RequestModifyInteraction extends Packet
{

    /** @var MessageBuilder */
    private $response;

    /** @var string */
    private $channelId;

    /** @var string */
    private $messageId;

    public function __construct(MessageBuilder $response, string $channelId, string $messageId)
    {
        parent::__construct();
        $this->response = $response;
        $this->channelId = $channelId;
        $this->messageId = $messageId;
    }
    public function getMessage(): MessageBuilder
    {
        return $this->response;
    }
    public function getChannelId(): string{
        return $this->channelId;
    }
    public function getMessageId(): string{
        return $this->messageId;
    }


    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->response,
            $this->channelId,
            $this->messageId
         
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->response,
            $this->channelId,
            $this->messageId
        ] = unserialize($data);
    }
}
