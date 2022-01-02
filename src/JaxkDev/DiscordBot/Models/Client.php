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

namespace JaxkDev\DiscordBot\Models;

use JaxkDev\DiscordBot\Plugin\Utils;

use JaxkDev\DiscordBot\Models\OAuth\Application;
use JaxkDev\DiscordBot\Models\Server;
use JaxkDev\DiscordBot\Models\Channels\DMChannel;

class Client implements \Serializable
{


    /** @var string */
    private $id;

    /** @var string */
    private $username;

    /** @var string 0000 when user is webhook/system etc. */
    private $discriminator;

    /** @var string */
    private $avatar_url;

    /** @var bool */
    private $bot = false;

    /** @var Application */
    private $application;

    /** @var Server[] */
    private $servers = [];

    /** @var User[] */
    private $users = [];

    /** @var DMChannel[] */
    private $channels = [];


    /** @var Array<string, bool> */
    private $flags = [];

    public function __construct(
        string $id,
        string $username,
        string $discriminator,
        string $avatar_url,
        bool $bot,
        Application $application,
        array $servers = [],
        array $users = [],
        array $channels = []

    ) {
        $this->setId($id);
        $this->setUsername($username);
        $this->setDiscriminator($discriminator);
        $this->setAvatarUrl($avatar_url);
        $this->setBot($bot);
        $this->setApplication($application);
        $this->setServers($servers);
        $this->setUsers($users);
        $this->setChannels($channels);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        if (!Utils::validDiscordSnowflake($id)) {
            throw new \AssertionError("User ID '$id' is invalid.");
        }
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getDiscriminator(): string
    {
        return $this->discriminator;
    }

    public function setDiscriminator(string $discriminator): void
    {
        if (strlen($discriminator) !== 4) {
            throw new \AssertionError("Discriminator '$discriminator' is invalid.");
        }
        $this->discriminator = $discriminator;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatar_url;
    }

    public function setAvatarUrl(string $avatar_url): void
    {
        $this->avatar_url = $avatar_url;
    }

    public function getCreationTimestamp(): int
    {
        return Utils::getDiscordSnowflakeTimestamp($this->id);
    }

    public function isBot(): bool
    {
        return $this->bot;
    }

    public function setBot(bool $bot): void
    {
        $this->bot = $bot;
    }

    public function getApplication(): Application
    {
        return $this->application;
    }
    public function setApplication(Application $application): void
    {
        $this->application = $application;
    }

    /** @return Server[] */
    public function getServers(): array
    {
        return $this->servers;
    }
    public function setServers(array $servers): void
    {
        /** @var Server $server */
        foreach ($servers as $server) {
            if (!Utils::validDiscordSnowflake($server->getId())) {
                throw new \AssertionError("Server ID: '{$server->getId()}' is invalid.");
            }
            //todo
        }
        $this->servers = $servers;
    }

    /** @return User[] */
    public function getUsers(): array
    {
        return $this->users;
    }
    public function setUsers(array $users): void
    {
        /** @var User $user */
        foreach ($users as $user) {
            if (!Utils::validDiscordSnowflake($user->getId())) {
                throw new \AssertionError("User ID: {$user->getId()} is invalid.");
            }
            //todo
        }
        $this->users = $users;
    }
    /** @return DMChannel[] */
    public function getChannels(): array
    {
        return $this->channels;
    }
    public function setChannels(array $channels): void
    {
        /** @var DMChannel $channel */
        foreach ($channels as $channel) {
            if (!Utils::validDiscordSnowflake($channel->getId())) {
                throw new \AssertionError("User ID: {$channel->getId()} is invalid.");
            }
            //todo
        }
        $this->channels = $channels;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->discriminator,
            $this->avatar_url,
            $this->bot,
            $this->application,
            $this->servers,
            $this->users
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->id,
            $this->username,
            $this->discriminator,
            $this->avatar_url,
            $this->bot,
            $this->application,
            $this->servers,
            $this->users
        ] = unserialize($data);
    }
}
