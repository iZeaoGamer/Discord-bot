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


    /** @var int */
    private $duration;

    /** @var bool */
    private $private;

    /** @var ?int In seconds | null when disabled. */
    private $rate_limit = null;


    /** @var null|string*/
    private $threadOwner = null;

    /** @var null|string */
    private $user_id = null;

    //Pins can be found via API::fetchPinnedMessages();

    //Webhooks can be found via API::fetchWebhooks();

    /**
     * TextChannel constructor.
     *
     * @param string      $name
     * @param int      $position
     * @param string      $server_id
     * @param string $threadOwner
     * @param bool      $private
     * @param int $duration
     * @param int|null    $rate_limit
  
     * @param string|null $userID
     * @param string|null $id
     */
    public function __construct(string $name, int $position, string $server_id, string $threadOwner, bool $private, int $duration,
                                   ?int $rate_limit = null, ?string $userID = null, ?string $id = null){
        parent::__construct($name, $position, $server_id, null, $id);
        $this->setOwner($threadOwner);
        $this->setPrivate($private);
        $this->setDuration($duration);
        $this->setRateLimit($rate_limit);
    
        $this->setUserID($userID);
    }
    /** Creates a boolean for privating a thread. 
     * 
     * @param bool $private
     * @return void
     * 
     */
    public function setPrivate(bool $private): void{
        $this->private = $private;
    }

    /** Checks if the given thread is private.
     * @return bool
     */
    public function isPrivate(): bool{
        return $this->private;
    }
    /** @return void */
    public function setDuration(int $duration): void{
        $this->duration = $duration;
    }
    /** @return int */
    public function getDuration(): int{
        return $this->duration;
    }

    /** Get's the rate limit (Slowmode) in seconds for the given thread.
     * @return int|null
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
     * @param string $threadOwner
     * @return void
     */
    public function setOwner(string $threadOwner): void{
        $this->threadOwner = $threadOwner;
    }

    /** @return string */
    public function getOwner(): string{
        return $this->threadOwner;
    }
    /**
     * @param string|null $user_id
     * @return void
     * */
    public function setUserID(?string $user_id): void{
        $this->user_id = $user_id;
    }
    /** @return string|null */
    public function getUserID(): ?string{
        return $this->user_id;
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
            $this->duration,
            $this->rate_limit,
            $this->threadOwner,
            $this->user_id
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
            $this->duration,
            $this->rate_limit,
            $this->threadOwner,
            $this->user_id
        ] = unserialize($data);
    }
}