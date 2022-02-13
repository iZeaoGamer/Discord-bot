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

use JaxkDev\DiscordBot\Models\Server\Ban;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Channels\Invite;
use JaxkDev\DiscordBot\Models\User\Member;
use JaxkDev\DiscordBot\Models\Server\Role;
use JaxkDev\DiscordBot\Models\Server\Server;
use JaxkDev\DiscordBot\Models\User\User;
use JaxkDev\DiscordBot\Models\Thread\Thread;
use JaxkDev\DiscordBot\Models\Server\ServerTemplate;
use JaxkDev\DiscordBot\Communication\Packets\Packet;
use JaxkDev\DiscordBot\Models\Server\ServerScheduledEvent;
use JaxkDev\DiscordBot\Models\Channels\Messages\Message;
use JaxkDev\DiscordBot\Models\Interactions\Command\Command;

class DiscordDataDump extends Packet
{

    /** @var Server[] */
    private $servers = [];

    /** @var Thread[] */
    private $threads = [];

    /** @var ServerChannel[] */
    private $channels = [];

    /** @var Role[] */
    private $roles = [];

    /** @var Invite[] */
    private $invites = [];

    /** @var Ban[] */
    private $bans = [];

    /** @var Member[] */
    private $members = [];

    /** @var User[] */
    private $users = [];

    /** @var ServerTemplate[] */
    private $templates = [];

    /** @var ServerScheduledEvent[] */
    private $scheduledEvents = [];

    /** @var Message[] */
    private $messages = [];

    /** @var Command[] */
    private $commands = [];

    /** @var null|User */
    private $bot_user = null;

    /** @var int */
    private $timestamp;

    /**
     * @return Server[]
     */
    public function getServers(): array
    {
        return $this->servers;
    }

    public function addServer(Server $server): void
    {
        $this->servers[] = $server;
    }

    /** @return Thread[] */
    public function getThreads(): array
    {
        return $this->threads;
    }
    public function addThread(Thread $channel): void
    {
        $this->threads[] = $channel;
    }

    /**
     * @return ServerChannel[]
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    public function addChannel(ServerChannel $channel): void
    {
        $this->channels[] = $channel;
    }

    /**
     * @return Role[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(Role $role): void
    {
        $this->roles[] = $role;
    }

    /**
     * @return Invite[]
     */
    public function getInvites(): array
    {
        return $this->invites;
    }

    public function addInvite(Invite $invite): void
    {
        $this->invites[] = $invite;
    }

    /**
     * @return Ban[]
     */
    public function getBans(): array
    {
        return $this->bans;
    }

    public function addBan(Ban $ban): void
    {
        $this->bans[] = $ban;
    }

    /**
     * @return Member[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    public function addMember(Member $member): void
    {
        $this->members[] = $member;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        $this->users[] = $user;
    }
    public function getTemplates(): array
    {
        return $this->templates;
    }
    public function addTemplate(ServerTemplate $template): void
    {
        $this->templates[] = $template;
    }
    public function getScheduledEvents(): array
    {
        return $this->scheduledEvents;
    }
    public function addSchedule(ServerScheduledEvent $scheduled)
    {
        $this->scheduledEvents[] = $scheduled;
    }
    /** @return Message[] */
    public function getMessages(): array
    {
        return $this->messages;
    }
    public function addMessage(Message $message): void
    {
        $this->messages[] = $message;
    }

    /** @return Command[] */
    public function getCommands(): array{
        return $this->commands;
    }
    public function addCommand(Command $command): void{
        $this->commands[] = $command;
    }

    public function getBotUser(): ?User
    {
        return $this->bot_user;
    }

    public function setBotUser(User $bot): void
    {
        $this->bot_user = $bot;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getSize(): int
    {
        return sizeof($this->servers) + sizeof($this->threads) + sizeof($this->channels) + sizeof($this->roles) + sizeof($this->members)
            + sizeof($this->users) + sizeof($this->bans) + sizeof($this->invites) + sizeof($this->templates) + sizeof($this->scheduledEvents) + sizeof($this->messages) + sizeof($this->commands);
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->servers,
            $this->threads,
            $this->channels,
            $this->roles,
            $this->invites,
            $this->bans,
            $this->members,
            $this->users,
            $this->templates,
            $this->scheduledEvents,
            $this->messages,
            $this->commands,
            $this->bot_user,
            $this->timestamp
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->servers,
            $this->threads,
            $this->channels,
            $this->roles,
            $this->invites,
            $this->bans,
            $this->members,
            $this->users,
            $this->templates,
            $this->scheduledEvents,
            $this->messages,
            $this->commands,
            $this->bot_user,
            $this->timestamp
        ] = unserialize($data);
    }
}
