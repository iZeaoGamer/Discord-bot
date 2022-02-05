<?php

namespace JaxkDev\DiscordBot\Models\WebSockets;

use JaxkDev\DiscordBot\Models\Server\Emoji;

use JaxkDev\DiscordBot\Plugin\Utils;

class MessageReaction implements \Serializable
{


    /** @var string */
    private $message_id;

    /** @var string */
    private $channel_id;

    /** @var Emoji */
    private $emoji;


    /** @var string|null */
    private $user_id;

    /** @var string|null */
    private $server_id; //null when in dms.

    /** @var string|null */
    private $reaction_id; //null when creating.

    /** MessageReaction Constructor
     * 
     * @param string                $message_id
     * @param string                $channel_id
     * @param Emoji                 $emoji
     * @param string|null           $user_id
     * @param string|null           $server_id
     * @param string|null           $reaction_id
     * 
     */
    public function __construct(
        string $message_id,
        string $channel_id,
        Emoji $emoji,
        ?string $user_id = null,
        ?string $server_id = null,
        ?string $reaction_id = null
    ) {
        $this->setMessageId($message_id);
        $this->setChannelId($channel_id);
        $this->setEmoji($emoji);
        $this->setUserId($user_id);
        $this->setServerId($server_id);
        $this->setReactionId($reaction_id);
    }
    public function getMessageId(): string
    {
        return $this->message_id;
    }
    public function setMessageId(string $message_id): void
    {
        if (!Utils::validDiscordSnowflake($message_id)) {
            throw new \AssertionError("Channel ID: {$message_id} is invalid.");
        }
        $this->message_id = $message_id;
    }
    public function getChannelId(): string
    {
        return $this->channel_id;
    }
    public function setChannelId(string $channel_id): void
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            throw new \AssertionError("Channel ID: {$channel_id} is invalid.");
        }
        $this->channel_id = $channel_id;
    }
    public function getEmoji(): Emoji
    {
        return $this->emoji;
    }
    public function setEmoji(Emoji $emoji): void
    {
        $this->emoji = $emoji;
    }
    public function getUserId(): ?string
    {
        return $this->user_id;
    }
    public function setUserId(?string $user_id): void
    {
        if ($user_id !== null and !Utils::validDiscordSnowflake($user_id)) {
            throw new \AssertionError("User ID: {$user_id} is invalid.");
        }
        $this->user_id = $user_id;
    }
    public function getServerId(): ?string
    {
        return $this->server_id;
    }
    public function setServerId(?string $server_id): void
    {
        if ($server_id !== null and !Utils::validDiscordSnowflake($server_id)) {
            throw new \AssertionError("Server ID: {$server_id} is invalid.");
        }
        $this->server_id = $server_id;
    }
    public function getReactionId(): ?string
    {
        return $this->reaction_id;
    }
    public function setReactionId(?string $reaction_id): void
    {
        $this->reaction_id = $reaction_id;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->message_id,
            $this->channel_id,
            $this->emoji,
            $this->user_id,
            $this->server_id,
            $this->reaction_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->message_id,
            $this->channel_id,
            $this->emoji,
            $this->user_id,
            $this->server_id,
            $this->reaction_id
        ] = unserialize($data);
    }
}
