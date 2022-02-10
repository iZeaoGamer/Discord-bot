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

namespace JaxkDev\DiscordBot\Models\Interactions\Request;

use JaxkDev\DiscordBot\Bot\ModelConverter;
use JaxkDev\DiscordBot\Plugin\Utils;
use JaxkDev\DiscordBot\Models\User;
use JaxkDev\DiscordBot\Models\Member;
use JaxkDev\DiscordBot\Models\Role;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Thread\Thread;
use JaxkDev\DiscordBot\Models\Channels\Messages\Message;
use JaxkDev\DiscordBot\Models\Channels\Messages\Attachment;

class Resolved implements \Serializable
{

    /** @var Array<string, User>|null */
    private $users;

    /** @var Array<string, Member>|null */
    private $members;

    /** @var Array<string, Role>|null */
    private $roles;

    /** @var Array<string, ServerChannel|Thread>|null */
    private $channels;

    /** @var Array<string, Message>|null */
    private $messages;

    /** @var Array<string, Attachment>|null */
    private $attachments;

    /** @var string|null */
    private $server_id;


    /** 
     * Resolved Constructor
     *
     * @param Array<string, User>|null                                       $users          The ids and User objects.
     * @param Array<string, Member>|null                                     $members        The ids and partial Member objects.
     * @param Array<string, Role>|null                                       $roles          The ids and Role objects.
     * @param Array<string, ServerChannel|Thread>|null                       $channels       The ids and partial Channel objects.
     * @param Array<string, Message>|null                                    $messages       The ids and partial Message objects.
     * @param Array<string, Attachment>|null                                 $attachments    The ids and partial Attachment objects.
     * @param string|null   $server_id                                       ID of the server passed from Interaction.
     * 
     */
    public function __construct(
        ?array $users = null,
        ?array $members = null,
        ?array $roles = null,
        ?array $channels = null,
        ?array $messages = null,
        ?array $attachments = null,
        ?string $server_id = null
    ) {
        $this->setUsers($users);
        $this->setMembers($members);
        $this->setRoles($roles);
        $this->setChannels($channels);
        $this->setMessages($messages);
        $this->setAttachments($attachments);
        $this->setServerId($server_id);
    }

    /** @return Array<string, User>|null */
    public function getUsers(): ?array
    {
        return $this->users;
    }

    /** @param Array<string, User>|null $users */
    public function setUsers(?array $users)
    {
        if ($users) {
            foreach ($users as $snowflake => $user) {
                if (!Utils::validDiscordSnowflake($snowflake)) {
                    throw new \AssertionError("User ID: {$snowflake} is invalid.");
                }
            }
        }
        $this->users = $users;
    }

    /** @return Array<string, Member>|null */
    public function getMembers(): ?array
    {
        return $this->members;
    }

    /** @param Array<string, Member>|null $members */
    public function setMembers(?array $members)
    {
        if ($members) {
            foreach ($members as $snowflake => $member) {
                if (!Utils::validDiscordSnowflake($snowflake)) {
                    throw new \AssertionError("Member ID: {$snowflake} is invalid.");
                }
            }
        }
        $this->members = $members;
    }

    /** @return Array<string, Role>|null */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /** @param Array<string, Role> $roles */
    public function setRoles(?array $roles)
    {
        if ($roles) {
            foreach ($roles as $snowflake => $role) {
                if (!Utils::validDiscordSnowflake($snowflake)) {
                    throw new \AssertionError("Role ID: {$snowflake} is invalid.");
                }
            }
        }
        $this->roles = $roles;
    }

    /** @return Array<string, ServerChannel|Thread>|null */
    public function getChannels(): ?array
    {
        return $this->channels;
    }

    /** @param Array<string, ServerChannel|Thread>|null $channels */
    public function setChannels(?array $channels)
    {
        if ($channels) {
            foreach ($channels as $snowflake => $channel) {
                if (!Utils::validDiscordSnowflake($snowflake)) {
                    throw new \AssertionError("User ID: {$snowflake} is invalid.");
                }
            }
        }
        $this->channels = $channels;
    }

    /** @return Array<string, Message>|null */
    public function getMessages(): ?array
    {
        return $this->messages;
    }

    /** @param Array<string, Message>|null $messages  */
    public function setMessages(?array $messages)
    {
        if ($messages) {
            foreach ($messages as $snowflake => $message) {
                if (!Utils::validDiscordSnowflake($snowflake)) {
                    throw new \AssertionError("User ID: {$snowflake} is invalid.");
                }
            }
        }
        $this->messages = $messages;
    }

    /** @return Array<string, Attachment>|null */
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /** @param Array<string, Attachment>|null $attachments */
    public function setAttachments(?array $attachments): void
    {
        if ($attachments) {
            foreach ($attachments as $snowflake => $attachment) {
                if (!Utils::validDiscordSnowflake($snowflake)) {
                    throw new \AssertionError("Attachment ID: {$snowflake} is invalid.");
                }
            }
        }
        $this->attachments = $attachments;
    }
    public function getServerId(): ?string
    {
        return $this->server_id;
    }
    public function setServerId(?string $server_id): void
    {
        if ($server_id) {
            if (!Utils::validDiscordSnowflake($server_id)) {
                throw new \AssertionError("Server ID: {$server_id} is invalid.");
            }
        }
        $this->server_id = $server_id;
    }

    //----- Serialization -----//
    public function serialize(): ?string
    {
        return serialize([
            $this->users,
            $this->members,
            $this->roles,
            $this->channels,
            $this->messages,
            $this->attachments,
            $this->server_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->users,
            $this->members,
            $this->roles,
            $this->channels,
            $this->messages,
            $this->attachments,
            $this->server_id
        ] = unserialize($data);
    }
}
