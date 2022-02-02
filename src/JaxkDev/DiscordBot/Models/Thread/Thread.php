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

namespace JaxkDev\DiscordBot\Models\Thread;

class Thread
{


    /** @var string|null */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $server_id;

    /** @var int */
    private $duration;

    /** @var bool */
    private $private;

    /** @var ?int In seconds | null when disabled. */
    private $rate_limit = null;


    /** @var string*/
    private $threadOwner = null;

    /** @var null|string */
    private $user_id = null;

    //Pins can be found via API::fetchPinnedMessages();

    //Webhooks can be found via API::fetchWebhooks();

    /**
     * ThreadChannel constructor.
     *
     * @param string                $name
     * @param string                $server_id
     * @param string                $threadOwner
     * @param bool                  $private
     * @param int                   $duration
     * @param int|null              $rate_limit
     * @param string|null           $userID
     * @param string|null           $id
     * 
     */
    public function __construct(
        string $name,
        string $server_id,
        string $threadOwner,
        bool $private,
        int $duration,
        ?int $rate_limit = null,
        ?string $userID = null,
        ?string $id
    ) {
        // parent::__construct($name, $position, $server_id, null, $id);

        $this->setName($name);
        $this->setServerID($server_id);
        $this->setOwner($threadOwner);
        $this->setPrivate($private);
        $this->setDuration($duration);
        $this->setRateLimit($rate_limit);

        $this->setUserID($userID);
        $this->setID($id);
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /** Sets a thread name.
     * 
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    /** 
     * @return string
     */
    public function getServerID(): string
    {
        return $this->server_id;
    }
    /** 
     * @param string $server_id
     * @return void
     */
    public function setServerID(string $server_id): void
    {
        $this->server_id = $server_id;
    }

    /** Checks if the given thread is private.
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->private;
    }
    /** Creates a boolean for privating a thread. 
     * 
     * @param bool $private
     * @return void
     * 
     */
    public function setPrivate(bool $private): void
    {
        $this->private = $private;
    }
    /** @return int */
    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    /** Get's the rate limit (Slowmode) in seconds for the given thread.
     * @return int|null
     */
    public function getRateLimit(): ?int
    {
        return $this->rate_limit;
    }

    /**
     * @param int|null $rate_limit 0-21600 seconds.
     */
    public function setRateLimit(?int $rate_limit): void
    {
        if ($rate_limit !== null and ($rate_limit < 0 or $rate_limit > 21600)) {
            throw new \AssertionError("Rate limit '$rate_limit' is outside the bounds 0-21600.");
        }
        $this->rate_limit = $rate_limit;
    }

    /** @return string */
    public function getOwner(): string
    {
        return $this->threadOwner;
    }
    /** Sets a thread owner.
     * @param string $threadOwner
     * @return void
     */
    public function setOwner(string $threadOwner): void
    {
        $this->threadOwner = $threadOwner;
    }

    /** @return string|null */
    public function getUserID(): ?string
    {
        return $this->user_id;
    }

    /**
     * @param string|null $user_id
     * @return void
     * */
    public function setUserID(?string $user_id): void
    {
        $this->user_id = $user_id;
    }
    /** 
     * @return string|null
     */
    public function getID(): ?string
    {
        return $this->id;
    }

    /** 
     * @param string|null $id
     * @return void
     */
    public function setID(?string $id): void
    {
        $this->id = $id;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->server_id,
            $this->private,
            $this->duration,
            $this->rate_limit,
            $this->threadOwner,
            $this->user_id,
            $this->id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->server_id,
            $this->private,
            $this->duration,
            $this->rate_limit,
            $this->threadOwner,
            $this->user_id,
            $this->id
        ] = unserialize($data);
    }
}
