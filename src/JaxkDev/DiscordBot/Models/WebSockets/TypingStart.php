<?php

namespace JaxkDev\DiscordBot\Models\WebSockets;

use JaxkDev\DiscordBot\Plugin\Utils;

class TypingStart implements \Serializable
{

    /** @var string */
    private $channel_id;

    /** @var string */
    private $user_id;

    /** @var int|null */
    public $timestamp; //null when creating.

    /** @var string|null */
    public $server_id; //null when in dms

    /** TypingStart Constructor
     * 
     * @param string                $channel_id
     * @param string                $user_id
     * @param int|null              $timestamp
     * @param string|null           $server_id
     * 
     */
    public function __construct(string $channel_id, string $user_id, ?int $timestamp = null, ?string $server_id = null)
    {
        $this->setChannelId($channel_id);
        $this->setUserId($user_id);
        $this->setTimestamp($timestamp);
        $this->setServerId($server_id);
    }
    public function getChannelId(): string{
        return $this->channel_id;
    }
    public function setChannelId(string $channel_id): void{
        if(!Utils::validDiscordSnowflake($channel_id)){
            throw new \AssertionError("Channel ID: {$channel_id} is invalid.");
        }
        $this->channel_id = $channel_id;
    }
    public function getUserId(): string{
        return $this->user_id;
    }
    public function setUserId(string $user_id): void{
        if(!Utils::validDiscordSnowflake($user_id)){
            throw new \AssertionError("User ID: {$user_id} is invalid.");
        }
        $this->user_id = $user_id;
    }
    public function getTimestamp(): ?int{
        return $this->timestamp;
    }
    public function setTimestamp(?int $timestamp){
        $this->timestamp = $timestamp;
    }
    public function getServerId(): ?string{
        return $this->server_id;
    }
    public function setServerId(?string $server_id): void{
        if($server_id !== null and !Utils::validDiscordSnowflake($server_id)){
            throw new \AssertionError("Server ID: {$server_id} is invalid.");
        }
        $this->server_id = $server_id;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->channel_id,
            $this->user_id,
            $this->timestamp,
            $this->server_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->channel_id,
            $this->user_id,
            $this->timestamp,
            $this->server_id
        ] = unserialize($data);
    }
}
