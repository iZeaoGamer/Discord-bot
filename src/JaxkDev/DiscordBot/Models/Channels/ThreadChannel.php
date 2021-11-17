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

class ThreadChannel extends ServerChannel{

    /** @var null|int */
    private $timestamp;

    /** @var bool */
    private $private;

    /** @var ?int In seconds | null when disabled. */
    private $rate_limit = null;


    /** @var null|string*/
    private $threadOwner = null;

    /** @var null|string */
    private $parent_id = null;

    //Pins can be found via API::fetchPinnedMessages();

    //Webhooks can be found via API::fetchWebhooks();

    /**
     * TextChannel constructor.
     *
     * @param string      $topic
     * @param string      $name
     * @param int         $position
     * @param string      $server_id
     * @param bool        $nsfw
     * @param int|null    $rate_limit
     * @param string|null $category_id
     * @param string|null $id
     */
 //   $thread->id, $thread->guild_id, $thread->name, $thread->last_message_id, $thread->last_pin_timestamp->getTimestamp(), $thread->rate_limit_per_user, $thread->owner_id, $thread->parent_id,
   // $thread->message_count, $thread->member_count);
    public function __construct(string $name, int $position, string $server_id, bool $private, ?int $timestamp = null,
                                   ?int $rate_limit = null, ?string $threadOwner = null, ?string $parent_id = null, ?string $id = null){
        parent::__construct($name, $position, $server_id, null, $id);
        $this->setPrivate($private);
        $this->setTimestamp($timestamp);
        $this->setRateLimit($rate_limit);
        $this->setOwner($threadOwner);
        $this->setParentID($parent_id);
    }
    /** Creates a boolean for privating a thread. 
     * 
     * @param bool $private
     * 
     */
    public function setPrivate(bool $private){
        $this->private = $private;
    }

    /** Checks if the given thread is private.
     * @return bool
     */
    public function isPrivate(): bool{
        return $this->private;
    }

    /** Sets a Timestamp for the given thread.
     * @param null|int $timestamp
     * 
     */
    public function setTimestamp(?int $timestamp){
        $this->timestamp = $timestamp;
    }

    /** Get's the current timestamp for the given thread.
     * @return null|int
     */
    public function getTimestamp(): ?int{
        return $this->timestamp;
    }

    /** Get's the rate limit (Slowmode) in seconds for the given thread.
     * @return null|int
    */
    public function getRateLimit(): ?int{
        return $this->rate_limit;
    }

    /**
     * @param int|null $rate_limit 0-21600 seconds.
     */
    public function setRateLimit(?int $rate_limit): void{
        if($rate_limit !== null and ($rate_limit < 0 or $rate_limit > 21600)){
            throw new \AssertionError("Rate limit '$rate_limit' is outside the bounds 0-21600.");
        }
        $this->rate_limit = $rate_limit;
    }

    /** Sets a thread owner.
     */
    public function setOwner(?string $threadOwner){
        $this->threadOwner = $threadOwner;
    }
    public function getOwner(): ?string{
        return $this->threadOwner;
    }
    public function setParentID(?string $parent_id){
        $this->parent_id = $parent_id;
    }
    public function getParentID(): ?string{
        return $this->parent_id;
    }


    //----- Serialization -----//

    public function serialize(): ?string{
        return serialize([
            $this->id,
            $this->name,
            $this->position,
            $this->member_permissions,
            $this->role_permissions,
            $this->server_id,
            $this->private,
            $this->timestamp,
            $this->rate_limit,
            $this->threadOwner,
            $this->parent_id
        ]);
    }

    public function unserialize($data): void{
        [
            $this->id,
            $this->name,
            $this->position,
            $this->member_permissions,
            $this->role_permissions,
            $this->server_id,
            $this->private,
            $this->timestamp,
            $this->rate_limit,
            $this->threadOwner,
            $this->parent_id
        ] = unserialize($data);
    }
}