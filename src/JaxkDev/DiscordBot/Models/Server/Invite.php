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

namespace JaxkDev\DiscordBot\Models\Server;

use JaxkDev\DiscordBot\Models\User\User;
use JaxkDev\DiscordBot\Plugin\Utils;

class Invite implements \Serializable
{


    public const TARGET_TYPE_STREAM = 1;
    public const TARGET_TYPE_EMBEDDED_APPLICATION = 2;

    /** @var string|null Also used as ID internally, null when creating model. */
    private $code;

    /** @var string */
    private $server_id;

    /** @var string */
    private $channel_id;

    /** @var int How long in seconds from creation time to expire, 0 for never. */
    private $max_age;

    /** @var int|null Timestamp null when creating model. */
    private $created_at;

    /** @var bool */
    private $temporary;

    /** @var int How many times has this invite been used | NOTICE: This does not get updated when used */
    private $uses;

    /** @var int 0 for unlimited uses */
    private $max_uses;

    /** @var string|null Member ID, null when creating model. */
    private $creator;

    /** @var int|null */
    private $target_type;

    /** @var User|null */
    private $target_user;

    /** @var object|null */
    private $target_application;

    /** @var int|null */
    private $approximate_presence_count;

    /** @var int|null */
    private $approximate_member_count;

    /** @var int|null */
    private $expires_at;

    /** @var object|null */
    private $stage_instance;

    /** @var object|null */
    private $server_scheduled_event;


    /** Invite Constructor
     * 
     * @param string                $server_id
     * @param string                $channel_id
     * @param int                   $max_age
     * @param int                   $max_uses
     * @param bool                  $temporary
     * @param string|null           $code
     * @param int|null              $created_at
     * @param string|null           $creator
     * @param int                   $uses
     * @param int|null              $target_type
     * @param User|null             $target_user
     * @param object|null           $target_application
     * @param int|null              $approximate_presence_count
     * @param int|null              $approximate_member_count
     * @param int|null              $expires_at
     * @param object|null           $stage_instance
     * @param object|null           $server_scheduled_event
     * 
     */
    public function __construct(
        string $server_id,
        string $channel_id,
        int $max_age,
        int $max_uses,
        bool $temporary,
        ?string $code = null,
        ?int $created_at = null,
        ?string $creator = null,
        int $uses = 0,
        ?int $target_type = null,
        ?User $target_user = null,
        ?object $target_application = null,
        ?int $approximate_presence_count = null,
        ?int $approximate_member_count = null,
        ?int $expires_at = null,
        ?object $stage_instance = null,
        ?object $server_scheduled_event = null

    ) {
        $this->setServerId($server_id);
        $this->setChannelId($channel_id);
        $this->setMaxAge($max_age);
        $this->setMaxUses($max_uses);
        $this->setTemporary($temporary);
        $this->setCode($code);
        $this->setCreatedAt($created_at);
        $this->setCreator($creator);
        $this->setUses($uses);
        $this->setTargetType($target_type);
        $this->setTargetUser($target_user);
        $this->setTargetApplication($target_application);
        $this->setEstimatePresenceCount($approximate_presence_count);
        $this->setEstimateMemberCount($approximate_member_count);
        $this->setExpiresAt($expires_at);
        $this->setStageInstance($stage_instance);
        $this->setServerScheduledEvent($server_scheduled_event);
    }



    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getServerId(): string
    {
        return $this->server_id;
    }

    public function setServerId(string $server_id): void
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            throw new \AssertionError("Server ID '$server_id' is invalid.");
        }
        $this->server_id = $server_id;
    }

    public function getChannelId(): string
    {
        return $this->channel_id;
    }

    public function setChannelId(string $channel_id): void
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            throw new \AssertionError("Channel ID '$channel_id' is invalid.");
        }
        $this->channel_id = $channel_id;
    }

    public function getMaxAge(): int
    {
        return $this->max_age;
    }

    /**  @param int $max_age 0 for eternity. */
    public function setMaxAge(int $max_age): void
    {
        if ($max_age > 604800 or $max_age < 0) {
            throw new \AssertionError("Max age '$max_age' is outside bounds 0-604800.");
        }
        $this->max_age = $max_age;
    }

    public function getCreatedAt(): ?int
    {
        return $this->created_at;
    }

    public function setCreatedAt(?int $created_at): void
    {
        if ($created_at !== null and $created_at > time()) {
            throw new \AssertionError("Time travel has been attempted, '$created_at' is in the future !");
        }
        $this->created_at = $created_at;
    }

    public function isTemporary(): bool
    {
        return $this->temporary;
    }

    public function setTemporary(bool $temporary): void
    {
        $this->temporary = $temporary;
    }

    public function getUses(): int
    {
        return $this->uses;
    }

    public function setUses(int $uses): void
    {
        if ($this->max_uses !== 0 and $uses > $this->max_uses) {
            throw new \AssertionError("Uses '$uses' is bigger than max uses '$this->max_uses'.");
        }
        $this->uses = $uses;
    }

    public function getMaxUses(): int
    {
        return $this->max_uses;
    }

    public function setMaxUses(int $max_uses): void
    {
        if ($max_uses < 0 or $max_uses > 100) {
            throw new \AssertionError("Max uses '$max_uses' is outside the bounds 0-100.");
        }
        $this->max_uses = $max_uses;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setCreator(?string $creator): void
    {
        $this->creator = $creator;
    }
    public function getTargetType(): ?int
    {
        return $this->target_type;
    }
    public function setTargetType(?int $target_type): void
    {
        if ($target_type) {
            if ($target_type < self::TARGET_TYPE_STREAM or $target_type > self::TARGET_TYPE_EMBEDDED_APPLICATION) {
                throw new \AssertionError("Target Type {$target_type} is invalid. Must be between 1-2.");
            }
        }
        $this->target_type = $target_type;
    }
    public function getTargetUser(): ?User{
        return $this->target_user;
    }
    public function setTargetUser(?User $user): void{
        if($user){
            if($user->getId() === null){
                throw new \AssertionError("User ID must be present.");
            }
            if(!Utils::validDiscordSnowflake($user->getId())){
                throw new \AssertionError("User ID: {$user->getId()} is invalid.");
            }
        }
        $this->target_user = $user;
    }
    public function getTargetApplication(): ?object{
        return $this->target_application;
    }
    public function setTargetApplication(?object $application): void{
        $this->target_application = $application;
    }
    public function getEstimatePresenceCount(): ?int{
        return $this->approximate_presence_count;
    }
    public function setEstimatePresenceCount(?int $approximate_presence_count){
        $this->approximate_presence_count = $approximate_presence_count;
    }
    public function getEstimateMemberCount(): ?int{
        return $this->approximate_member_count;
    }
    public function setEstimateMemberCount(?int $approximate_member_count): void{
        $this->approximate_member_count = $approximate_member_count;
    }
    public function getExpiresAt(): ?int{
        return $this->expires_at;
    }
    public function setExpiresAt(?int $expires_at){
        $this->expires_at = $expires_at;
    }
    public function getStageInstance(): ?object{
        return $this->stage_instance;
    }
    public function setStageInstance(?object $stage_instance): void{
        $this->stage_instance = $stage_instance;
    }
    public function getServerScheduledEvent(): ?object{
        return $this->server_scheduled_event;
    }
    public function setServerScheduledEvent(?object $server_scheduled_event){
        $this->server_scheduled_event = $server_scheduled_event;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->code,
            $this->server_id,
            $this->channel_id,
            $this->max_age,
            $this->created_at,
            $this->temporary,
            $this->uses,
            $this->max_uses,
            $this->creator,
            $this->target_type,
            $this->target_user,
            $this->target_application,
            $this->approximate_member_count,
            $this->approximate_presence_count,
            $this->expires_at,
            $this->stage_instance,
            $this->server_scheduled_event
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->code,
            $this->server_id,
            $this->channel_id,
            $this->max_age,
            $this->created_at,
            $this->temporary,
            $this->uses,
            $this->max_uses,
            $this->creator,
            $this->target_type,
            $this->target_user,
            $this->target_application,
            $this->approximate_member_count,
            $this->approximate_presence_count,
            $this->expires_at,
            $this->stage_instance,
            $this->server_scheduled_event
        ] = unserialize($data);
    }
}
