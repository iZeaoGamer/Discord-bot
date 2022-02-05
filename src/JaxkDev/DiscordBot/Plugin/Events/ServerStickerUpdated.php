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

namespace JaxkDev\DiscordBot\Plugin\Events;

use JaxkDev\DiscordBot\Models\Server\Sticker;
use pocketmine\plugin\Plugin;

/**
 * Emitted when an guild Sticker gets updated.
 * 
 */
class ServerStickerUpdated extends DiscordBotEvent
{

    /** @var Sticker[] */
    private $newStickers = [];

    /** @var Sticker[] */
    private $oldStickers = [];

    public function __construct(Plugin $plugin, array $newStickers, array $oldStickers)
    {
        parent::__construct($plugin);
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
}
