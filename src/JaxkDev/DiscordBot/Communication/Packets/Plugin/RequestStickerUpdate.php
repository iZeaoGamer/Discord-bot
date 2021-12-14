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

use JaxkDev\DiscordBot\Models\Messages\Stickers;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestStickerUpdate extends Packet
{

    /** @var Stickers */
    private $sticker;

    public function __construct(Stickers $sticker)
    {
        parent::__construct();
        $this->sticker = $sticker;
    }

    public function getSticker(): Stickers
    {
        return $this->sticker;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->sticker
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->sticker
        ] = unserialize($data);
    }
}
