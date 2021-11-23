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
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;

class RequestMessageBulkDelete extends Packet{

    /** @var int */
    private $amount;

    /** @var ServerChannel */
    private $channel;

    public function __construct(ServerChannel $channel, int $amount){
        parent::__construct();
        $this->channel = $channel;
        $this->amount = $amount;
       
    }

    public function getValue(): int{
        return $this->amount;
    }

    public function getChannel(): ServerChannel{
        return $this->channel;
    }

    public function serialize(): ?string{
        return serialize([
            $this->UID,
            $this->channel,
            $this->amount
        ]);
    }

    public function unserialize($data): void{
        [
            $this->UID,
            $this->channel,
            $this->amount
        ] = unserialize($data);
    }
}