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

namespace JaxkDev\DiscordBot\Communication\Packets\Discord;

use JaxkDev\DiscordBot\Communication\Packets\Packet;
use JaxkDev\DiscordBot\Models\Server\Sticker;

class ServerStickerUpdate extends Packet
{

    /** @var Sticker[] */
    private $newStickers = [];

    /** @var Sticker[] */
    private $oldStickers = [];


    /** 
     * @param Sticker[] $newStickers
     * @param Sticker[] $oldStickers
     */
    public function __construct(array $newStickers, array $oldStickers)
    {
        parent::__construct();
        $this->newStickers = $newStickers;
        $this->oldStickers = $oldStickers;
    }

    /** @return Sticker[] */
    public function getNewStickers(): array
    {
        return $this->newStickers;
    }

    /** @return Sticker[] */
    public function getOldStickers(): array
    {
        return $this->oldStickers;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->newStickers,
            $this->oldStickers
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->newStickers,
            $this->oldStickers
        ] = unserialize($data);
    }
}
