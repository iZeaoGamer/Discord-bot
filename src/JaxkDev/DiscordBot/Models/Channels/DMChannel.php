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

namespace JaxkDev\DiscordBot\Models\Channels;

use JaxkDev\DiscordBot\Plugin\Utils;

use JaxkDev\DiscordBot\Models\User;

class DMChannel implements \Serializable
{


    /** @var string */
    protected $recipient_id;

    /** @var string */
    protected $owner_id;

    /** @var string|null */
    protected $id; //null when creating channel.

    /** @var User[] */
    protected $users = [];

    /** @var string|null */
    protected $application_id; //null when the application id isn't a bot.


    /** DM Channel Constructor.
     * 
     * @param string                $recipient_id
     * @param string                $owner_id
     * @param string|null           $id
     * @param array                 $users
     * @param string|null           $application_id
     * 
     */
    public function __construct(
        string $recipient_id,
        string $owner_id,
        ?string $id = null,
        array $users = [],
        ?string $application_id = null
    ) {
        $this->setRecipientId($recipient_id);
        $this->setOwnerId($owner_id);
        $this->setId($id);
        $this->setUsers($users);
        $this->setApplicationId($application_id);
    }
    public function getRecipientId(): string
    {
        return $this->recipient_id;
    }
    public function setRecipientId(string $recipient_id): void
    {
        if (!Utils::validDiscordSnowflake($recipient_id)) {
            throw new \AssertionError("Recipient ID '$recipient_id' is invalid.");
        }
        $this->recipient_id = $recipient_id;
    }
    public function getOwnerId(): string
    {
        return $this->owner_id;
    }
    public function setOwnerId(string $owner_id): void
    {
        if (!Utils::validDiscordSnowflake($owner_id)) {
            throw new \AssertionError("Owner ID '$owner_id' is invalid.");
        }
        $this->owner_id = $owner_id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        if ($id !== null and !Utils::validDiscordSnowflake($id)) {
            throw new \AssertionError("Channel ID '$id' is invalid.");
        }
        $this->id = $id;
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
                throw new \AssertionError("User ID '{$user->getId()}' is invalid.");
            }
        }
        $this->users = $users;
    }
    public function getApplicationId(): ?string
    {
        return $this->application_id;
    }

    public function setApplicationId(?string $application_id): void
    {
        if ($application_id !== null and !Utils::validDiscordSnowflake($application_id)) {
            throw new \AssertionError("Application ID '$application_id' is invalid.");
        }
        $this->application_id = $application_id;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->recipient_id,
            $this->owner_id,
            $this->id,
            $this->users,
            $this->application_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->recipient_id,
            $this->owner_id,
            $this->id,
            $this->users,
            $this->application_id
        ] = unserialize($data);
    }
}
