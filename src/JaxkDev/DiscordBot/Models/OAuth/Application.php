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

namespace JaxkDev\DiscordBot\Models\OAuth;

use JaxkDev\DiscordBot\Plugin\Utils;

use JaxkDev\DiscordBot\Models\User;

class Application implements \Serializable
{


    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var string */
    private $invite_url;

    /** @var string|null */
    private $id; //null when creating application.

    /** @var string|null */
    private $icon; //null when using discord's default pfp.

    /** @var string[] */
    private $rpc_origins;

    /** @var User|null */
    private $user; //null when creating application.

    public function __construct(
        string $name,
        string $description,
        string $invite_url,
        ?string $id = null,
        ?string $icon = null,
        array $rpc_origins = [],
        ?User $user = null
    ) {
        $this->setName($name);
        $this->setDescription($description);
        $this->setInviteUrl($invite_url);
        $this->setId($id);
        $this->setIcon($icon);
        $this->setOrigins($rpc_origins);
        $this->setUser($user);
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description)
    {
        if (strlen($description) > 400) {
            throw new \AssertionError("Description is invalid. It has to be below 400 characters.");
        }
        $this->description = $description;
    }
    public function getInviteUrl(): string
    {
        return $this->invite_url;
    }
    public function setInviteUrl(string $url): void
    {
        $this->invite_url = $url;
    }
    public function getId(): ?string
    {
        return $this->description;
    }
    public function setId(?string $id): void
    {
        if ($id !== null) {
            if (!Utils::validDiscordSnowflake($id)) {
                throw new \AssertionError("Client ID: '{$id}' is invalid.");
            }
        }
        $this->id = $id;
    }
    public function getIcon(): ?string
    {
        return $this->icon;
    }
    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    /** @return string[] */
    public function getOrigins(): array
    {
        return $this->rpc_origins;
    }
    public function setOrigins(array $origins)
    {
        $this->rpc_origins = $origins;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): void
    {
        if ($user !== null) {
            if (!Utils::validDiscordSnowflake($user->getId())) {
                throw new \AssertionError("Application Owner Id: '{$user->getId()}' is invalid.");
            }
            if (!Utils::validUserDiscriminator($user->getDiscriminator())) {
                throw new \AssertionError("Application Owner Discriminator: '{$user->getDiscriminator()}' is invalid.");
            }
        }
        $this->user = $user;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->description,
            $this->invite_url,
            $this->id,
            $this->icon,
            $this->rpc_origins,
            $this->user
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->description,
            $this->invite_url,
            $this->id,
            $this->icon,
            $this->rpc_origins,
            $this->user
        ] = unserialize($data);
    }
}
