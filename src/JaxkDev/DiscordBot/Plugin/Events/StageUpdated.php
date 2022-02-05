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
 * @see StageDeleted Emitted when a stage gets deleted.
 */
class StageUpdated extends DiscordBotEvent
{

    /** @var Stage */
    private $stage;

    /** @var Stage|null */
    private $old;

    public function __construct(Plugin $plugin, Stage $stage, ?Stage $old)
    {
        parent::__construct($plugin);
        $this->stage = $stage;
        $this->old = $old;
    }

    public function getStage(): Stage
    {
        return $this->stage;
    }
    public function getOldStage(): ?Stage
    {
        return $this->old;
    }
}
