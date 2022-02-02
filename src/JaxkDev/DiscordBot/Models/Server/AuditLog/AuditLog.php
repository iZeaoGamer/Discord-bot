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

namespace JaxkDev\DiscordBot\Models\Server\AuditLog;

use JaxkDev\DiscordBot\Models\Webhook;
use JaxkDev\DiscordBot\Models\User;
use JaxkDev\DiscordBot\Models\Server\ServerScheduledEvent;
use JaxkDev\DiscordBot\Models\Thread\ThreadChannel;
use JaxkDev\DiscordBot\Plugin\Utils;

class AuditLog implements \Serializable
{

    /** @var string */
    private $serverId = [];

    /** @var WebHook[] */
    private $webhooks = [];

    /** @var User[] */
    private $users = [];

    /** @var Entry[] */
    private $audit_log_entries = [];

    /** @var ServerScheduledEvent[] */
    private $events = [];

    /** @var ThreadChannel[] */
    private $threads = [];


    /** AuditLog Constructor.
     * 
     * @param string                    $serverId
     * @param WebHook[]                 $webhooks
     * @param User[]                    $users
     * @param Entry[]                   $audit_log_entries
     * @param ServerScheduledEvent[]    $events
     * @param ThreadChannel[]           $threads
     * 
     */
    public function __construct(
        string $serverId,
        array $webhooks = [],
        array $users = [],
        array $audit_log_entries = [],
        array $events = [],
        array $threads = []
    ) {
        $this->setServerId($serverId);
        $this->setWebHooks($webhooks);
        $this->setUsers($users);
        $this->setAuditLogEntries($audit_log_entries);
        $this->setScheduledEvents($events);
        $this->setThreads($threads);
    }
    public function getServerId(): string
    {
        return $this->serverId;
    }
    public function setServerId(string $serverId): void
    {
        if (!Utils::validDiscordSnowflake($serverId)) {
            throw new \AssertionError("Server ID: {$serverId} is invalid!");
        }
        $this->serverId = $serverId;
    }
    /** @return WebHook[] */
    public function getWebHooks(): array
    {
        return $this->webhooks;
    }
    /** @param WebHook[] $webhooks
     * @return void
     */
    public function setWebHooks(array $webhooks): void
    {
        foreach ($webhooks as $webhook) {
            if (!Utils::validDiscordSnowflake($webhook->getId())) {
                throw new \AssertionError("Webhook ID: {$webhook->getId()} is invalid.");
            }
        }
        $this->webHooks = $webhooks;
    }

    /** @return User[] */
    public function getUsers(): array
    {
        return $this->users;
    }

    /** @param User[] $users
     * @return void
     */
    public function setUsers(array $users): void
    {
        foreach ($users as $user) {
            if (!Utils::validDiscordSnowflake($user->getId())) {
                throw new \AssertionError("User ID: {$user->getId()} is invalid.");
            }
        }
        $this->users = $users;
    }

    /** @return Entry[] */
    public function getAuditLogEntries(): array
    {
        return $this->audit_log_entries;
    }

    /** @param Entry[] $audit_log_entries
     * @return void
     */
    public function setAuditLogEntries(array $audit_log_entries): void
    {
        foreach ($audit_log_entries as $entry) {
            if (!Utils::validDiscordSnowflake($entry->getId())) {
                throw new \AssertionError("AuditLog Entry ID: {$entry->getId()} is invalid.");
            }
        }
        $this->audit_log_entries = $audit_log_entries;
    }

    /** @return ServerScheduledEvent[] */
    public function getScheduledEvents(): array
    {
        return $this->events;
    }

    /** @param ServerScheduledEvent[] $schedules
     * @return void
     */
    public function setScheduledEvents(array $schedules): void
    {
        foreach ($schedules as $event) {
            if (!Utils::validDiscordSnowflake($event->getId())) {
                throw new \AssertionError("Scheduled Event ID {$event->getId()} is invalid.");
            }
        }
        $this->events = $schedules;
    }

    /** @return ThreadChannel[] */
    public function getThreads(): array
    {
        return $this->threads;
    }

    /** @param ThreadChannel[] $threads
     * @return void
     */
    public function setThreads(array $threads): void
    {
        foreach ($threads as $thread) {
            if (!Utils::validDiscordSnowflake($thread->getId())) {
                throw new \AssertionError("Thread Channel ID: {$thread->getId()} is invalid.");
            }
        }
        $this->threads = $threads;
    }




    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->serverId,
            $this->webhooks,
            $this->users,
            $this->audit_log_entries,
            $this->events,
            $this->threads
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->serverId,
            $this->webhooks,
            $this->users,
            $this->audit_log_entries,
            $this->events,
            $this->threads
        ] = unserialize($data);
    }
}
