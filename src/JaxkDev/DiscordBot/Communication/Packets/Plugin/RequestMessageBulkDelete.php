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

class RequestMessageBulkDelete extends Packet
{

    /** @var int */
    private $amount;

    /** @var string */
    private $channelID;

    /** @var string|null */
    private $reason;

    public function __construct(string $channelID, int $amount, ?string $reason = null)
    {
        parent::__construct();
        $this->channelID = $channelID;
        $this->amount = $amount;
        $this->reason = $reason;
    }

    public function getValue(): int
    {
        return $this->amount;
    }

    public function getChannelID(): string
    {
        return $this->channelID;
    }
    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->channelID,
            $this->amount,
            $this->reason
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->channelID,
            $this->amount,
            $this->reason
        ] = unserialize($data);
    }
}
