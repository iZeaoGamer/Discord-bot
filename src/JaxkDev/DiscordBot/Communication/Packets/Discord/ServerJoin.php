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

use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Member;
use JaxkDev\DiscordBot\Models\Role;
use JaxkDev\DiscordBot\Models\Server;
use JaxkDev\DiscordBot\Models\Messages\Message;
use JaxkDev\DiscordBot\Models\Channels\ThreadChannel;
use JaxkDev\DiscordBot\Models\ServerTemplate;
use JaxkDev\DiscordBot\Models\ServerScheduledEvent;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class ServerJoin extends Packet
{

    /** @var Server */
    private $server;

    /** @var ThreadChannel[] */
    private $threads;

    /** @var ServerChannel[] */
    private $channels;

    /** @var Member[] */
    private $members;

    /** @var Role[] */
    private $roles;

    /** @var ServerTemplate[] */
    private $templates;

    /** @var ServerScheduledEvent[] */
    private $events;

    /** @var Message[] */
    private $messages;

    /** @var Command[] */
    private $commands;

    /**
     * ServerJoin constructor.
     *
     * @param Server          $server
     * @param ThreadChannel[] $threads
     * @param ServerChannel[] $channels
     * @param Member[]        $members
     * @param Role[]          $roles
     * @param ServerTemplate[] $templates
     * @param ServerScheduledEvent[] $events
     * @param Message[] $messages
     * @param Command[] $commands
     */
    public function __construct(Server $server, array $threads, array $channels, array $members, array $roles, array $templates, array $events, array $messages, array $commands)
    {
        parent::__construct();
        $this->server = $server;
        $this->threads = $threads;
        $this->channels = $channels;
        $this->members = $members;
        $this->roles = $roles;
        $this->templates = $templates;
        $this->events = $events;
        $this->messages = $messages;
        $this->commands = $commands;
    }

    public function getServer(): Server
    {
        return $this->server;
    }

    /** @return ThreadChannel[] */
    public function getThreads(): array
    {
        return $this->threads;
    }

    /** @return ServerChannel[] */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /** @return Member[] */
    public function getMembers(): array
    {
        return $this->members;
    }

    /** @return Role[] */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /** @return ServerTemplate[] */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /** @return ServerScheduledEvent[] */
    public function getScheduledEvents(): array
    {
        return $this->events;
    }

    /** @return Message[] */
    public function getMessages(): array
    {
        return $this->messages;
    }
      /** @return Command[] */
      public function getCommands(): array{
        return $this->commands;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->server,
            $this->threads,
            $this->channels,
            $this->members,
            $this->roles,
            $this->templates,
            $this->events,
            $this->messages,
            $this->commands
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->server,
            $this->threads,
            $this->channels,
            $this->members,
            $this->roles,
            $this->templates,
            $this->events,
            $this->messages,
            $this->commands
        ] = unserialize($data);
    }
}
