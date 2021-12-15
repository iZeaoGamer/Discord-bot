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

use JaxkDev\DiscordBot\Models\User;


class Emoji implements \Serializable
{

    /** @var string */
    private $name;

    /** @var string */
    private $serverId;

    /** @var bool */
    private $managed;

    /** @var string|null */
    private $id; //null when creating.

    /** @var bool */
    private $requireColons;

    /** @var string[] */
    private $roles;

    /** @var User|null */
    private $user;

    /** @var bool */
    private $animated;

    /** @var bool */
    private $available;

    public function __construct(
        string $name,
        string $server_id,
        bool $managed = false,
        ?string $id = null,
        bool $require_colons = false,
        array $roles = [],
        ?User $user = null,
        bool $animated = false,
        bool $available = false
    ) {
        $this->setName($name);
        $this->setServerId($server_id);
        $this->setManaged($managed);
        $this->setId($id);
        $this->setRequireColons($require_colons);
        $this->setRoles($roles);
        $this->setUser($user);
        $this->setAnimated($animated);
        $this->setAvailable($available);
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getServerId(): string
    {
        return $this->serverId;
    }
    public function setServerId(string $serverId)
    {
        $this->serverId = $serverId;
    }
    public function isManaged(): bool
    {
        return $this->managed;
    }
    public function setManaged(bool $managed)
    {
        $this->managed = $managed;
    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id)
    {
        $this->id = $id;
    }
    public function isColonsRequired(): bool
    {
        return $this->requireColons;
    }
    public function setRequireColons(bool $require_colons)
    {
        $this->requireColons = $require_colons;
    }
    public function getRoles(): array
    {
        return $this->roles;
    }
    public function setRoles(array $roles)
    {
        foreach ($roles as $id) {
            if (!Utils::validDiscordSnowflake($id)) {
                throw new \AssertionError("Invalid Role ID: {$id}!");
            }
        }
        $this->roles = $roles;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user)
    {
        $this->user = $user;
    }
    public function isAnimated(): bool
    {
        return $this->animated;
    }
    public function setAnimated(bool $animated)
    {
        $this->animated = $animated;
    }
    public function isAvailable(): bool
    {
        return $this->available;
    }
    public function setAvailable(bool $available)
    {
        $this->available = $available;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->serverId,
            $this->managed,
            $this->id,
            $this->requireColons,
            $this->roles,
            $this->user,
            $this->animated,
            $this->available
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->serverId,
            $this->managed,
            $this->id,
            $this->requireColons,
            $this->roles,
            $this->user,
            $this->animated,
            $this->available
        ] = unserialize($data);
    }
}
