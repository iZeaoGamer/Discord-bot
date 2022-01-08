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


use JaxkDev\DiscordBot\Plugin\Utils;
use JaxkDev\DiscordBot\Models\User;
use JaxkDev\DiscordBot\Models\Member;
use JaxkDev\DiscordBot\Models\Role;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Channels\ThreadChannel;
use JaxkDev\DiscordBot\Models\Messages\Message;

class Resolved implements \Serializable
{

    /** @var User[]|null */
private $users;

/** @var Member[]|null */
private $members;

/** @var Role[]|null */
private $roles;

/** @var ServerChannel[]|ThreadChannel[]|null */
private $channels;

/** @var Message[]|null */
private $messages;

/** @var string|null */
private $server_id;


    /** 
     * Resolved Constructor
     *
     * @param User[]|null             $users    The ids and User objects.
     * @param Member[]|null           $members  The ids and partial Member objects.
     * @param Role[]|null             $roles    The ids and Role objects.
     * @param ServerChannel[]|ThreadChannel[]|null $channels The ids and partial Channel objects.
     * @param Message[]|null          $messages The ids and partial Message objects.
     * @param string|null                        $server_id ID of the server passed from Interaction.
     */
    public function __construct(
        ?array $users = null,
        ?array $members = null,
        ?array $roles = null,
        ?array $channels = null,
        ?array $messages = null,
        ?string $server_id = null
    ) {
        $this->setUsers($users);
        $this->setMembers($members);
        $this->setRoles($roles);
        $this->setChannels($channels);
        $this->setMessages($messages);
        $this->setServerId($server_id);
    }

    public function getUsers(): ?array{
        return $this->users;
    }
    public function setUsers(?array $users){
        foreach($users ?? [] as $snowflake => $user){
            if(!Utils::validDiscordSnowflake($snowflake)){
                throw new \AssertionError("User ID: {$snowflake} is invalid.");
            }
        }
        $this->users = $users;
    }
    public function getMembers(): ?array{
        return $this->members;
    }
    public function setMembers(?array $members){
        foreach($members ?? [] as $snowflake => $member){
            if(!Utils::validDiscordSnowflake($snowflake)){
                throw new \AssertionError("Member ID: {$snowflake} is invalid.");
            }
        }
        $this->members = $members;
    }
    public function getRoles(): ?array{
        return $this->roles;
    }
    public function setRoles(?array $roles){
        foreach($roles ?? [] as $snowflake => $role){
            if(!Utils::validDiscordSnowflake($snowflake)){
                throw new \AssertionError("Role ID: {$snowflake} is invalid.");
            }
        }
        $this->roles = $roles;
    }
    public function getChannels(): ?array{
        return $this->channels;
    }
    public function setChannels(?array $channels){
        foreach($channel ?? [] as $snowflake => $channel){
            if(!Utils::validDiscordSnowflake($snowflake)){
                throw new \AssertionError("User ID: {$snowflake} is invalid.");
            }
        }
        $this->channels = $channels;
    }
    public function getMessages(): ?array{
        return $this->messages;
    }
    public function setMessages(?array $messages){
        foreach($messages ?? [] as $snowflake => $message){
            if(!Utils::validDiscordSnowflake($snowflake)){
                throw new \AssertionError("User ID: {$snowflake} is invalid.");
            }
        }
        $this->messages = $messages;
    }
    public function getServerId(): ?string{
        return $this->server_id;
    }
    public function setServerId(?string $server_id): void{
        if($server_id){
            if(!Utils::validDiscordSnowflake($server_id)){
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
            $this->server_id
        ] = unserialize($data);
    }
}
