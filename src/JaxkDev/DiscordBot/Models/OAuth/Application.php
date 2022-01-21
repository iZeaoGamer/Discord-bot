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

    /** @var bool */
    private $bot_public;

    /** @var bool */
    private $bot_require_code_grant;

    /** @var string|null */
    private $terms_of_service_url; //null when empty or not found.

    /** @var string|null */
    private $privacy_policy_url; //null when empty or not found.

    /** @var string */
    private $verify_key;

    /** @var object|null */
    private $team;


    /** Application Constructor.
     * 
     * @param string                $name
     * @param string                $description
     * @param string                $invite_url
     * @param string|null           $id
     * @param string|null           $icon
     * @param string[]              $rpc_origins
     * @param User|null             $user
     * @param bool                  $bot_public   
     * @param bool                  $bot_require_code_grant
     * @param string|null           $terms_of_service_url
     * @param string|null           $privacy_policy_url
     * @param string                $verify_key
     * @param object|null           $team
     * 
     */
    public function __construct(
        string $name,
        string $description,
        string $invite_url,
        ?string $id = null,
        ?string $icon = null,
        array $rpc_origins = [],
        ?User $user = null,
        bool $bot_public = false,
        bool $bot_require_code_grant = false,
        ?string $terms_of_service_url = null,
        ?string $privacy_policy_url = null,
        string $verify_key = "",
        ?object $team = null
    ) {
        $this->setName($name);
        $this->setDescription($description);
        $this->setInviteUrl($invite_url);
        $this->setId($id);
        $this->setIcon($icon);
        $this->setOrigins($rpc_origins);
        $this->setUser($user);
        $this->setBotPublic($bot_public);
        $this->setBotRequireCodeGrant($bot_require_code_grant);
        $this->setTermsOfServiceURL($terms_of_service_url);
        $this->setPrivacyPolicyURL($privacy_policy_url);
        $this->setVerifyKey($verify_key);
        $this->setTeam($team);
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
    public function isBotPublic(): bool
    {
        return $this->bot_public;
    }
    public function setBotPublic(bool $public)
    {
        $this->bot_public = $public;
    }
    public function isBotRequireCodeGrant(): bool
    {
        return $this->bot_require_code_grant;
    }
    public function setBotRequireCodeGrant(bool $require): void
    {
        $this->bot_require_code_grant = $require;
    }
    public function getTermsOfServiceURL(): ?string
    {
        return $this->terms_of_service_url;
    }
    public function setTermsOfServiceURL(?string $url)
    {
        if ($url !== null and !str_starts_with($url, "https")) {
            throw new \AssertionError("URL '$url' must start with https.");
        }
        $this->terms_of_service_url = $url;
    }
    public function getPrivacyPolicyURL(): ?string
    {
        return $this->privacy_policy_url;
    }
    public function setPrivacyPolicyURL(?string $url)
    {
        if ($url !== null and !str_starts_with($url, "https")) {
            throw new \AssertionError("URL '$url' must start with https.");
        }
        $this->privacy_policy_url = $url;
    }
    public function getVerifyKey(): string
    {
        return $this->verify_key;
    }
    public function setVerifyKey(string $verify_key): void
    {
        $this->verify_key = $verify_key;
    }
    public function getTeam(): ?object
    {
        return $this->team;
    }
    public function setTeam(?object $team): void
    {
        $this->team = $team;
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
            $this->user,
            $this->bot_public,
            $this->bot_require_code_grant,
            $this->terms_of_service_url,
            $this->privacy_policy_url,
            $this->verify_key,
            $this->team
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
            $this->user,
            $this->bot_public,
            $this->bot_require_code_grant,
            $this->terms_of_service_url,
            $this->privacy_policy_url,
            $this->verify_key,
            $this->team
        ] = unserialize($data);
    }
}
