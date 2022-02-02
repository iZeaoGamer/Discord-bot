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

use JaxkDev\DiscordBot\Models\Channels\Channel;
use JaxkDev\DiscordBot\Models\User\Member;
use JaxkDev\DiscordBot\Models\Server\Role;
use JaxkDev\DiscordBot\Models\Server\Server;
use JaxkDev\DiscordBot\Models\Channels\Messages\Message;
use JaxkDev\DiscordBot\Models\Thread\Thread;
use pocketmine\plugin\Plugin;

/**
 * Emitted when the bot joins a discord server.
 * 
 * @see ServerDeleted Emitted when the bot leaves a server
 * @see ServerUpdated Emitted when a server the bot is in has been updated.
 */
class ServerJoined extends DiscordBotEvent
{

    /** @var Server */
    private $server;

    /** @var Role[] */
    private $roles;

    /** @var Thread[] */
    private $threads;

    /** @var Channel[] */
    private $channels;

    /** @var Member[] */
    private $members;

    /** @var Message[] */
    private $messages;

    /**
     * @param Plugin    $plugin
     * @param Server    $server
     * @param Thread[] $threads
     * @param Role[]    $roles
     * @param Channel[] $channels
     * @param Member[]  $members
     * @param Message[]
     */
    public function __construct(Plugin $plugin, Server $server, array $threads, array $roles, array $channels, array $members, array $messages)
    {
        parent::__construct($plugin);
        $this->server = $server;
        $this->threads = $threads;
        $this->roles = $roles;
        $this->channels = $channels;
        $this->members = $members;
        $this->messages = $messages;
    }

    public function getServer(): Server
    {
        return $this->server;
    }

    /** @return Thread[] */
    public function getThreads(): array{
        return $this->threads;
    }

    /**
     * @return Role[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return Channel[]
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /**
     * @return Member[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /** @return Message[] */
    public function getMessages(): array{
        return $this->messages;
    }
}
