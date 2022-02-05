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

use JaxkDev\DiscordBot\Models\Channels\Stage;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a stage has been created.
 * 
 * @see StageCreated Emitted when a stage has been created.
 * @see StageUpdated Emitted when a stage has been updated.
 */
class StageDeleted extends DiscordBotEvent
{

    /** @var Stage|null */
    private $stage;

    public function __construct(Plugin $plugin, ?Stage $stage)
    {
        parent::__construct($plugin);
        $this->stage = $stage;
    }

    public function getStage(): ?Stage
    {
        return $this->stage;
    }
}
