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

namespace JaxkDev\DiscordBot\Models\Permissions;


use Discord\Helpers\Bitwise;

abstract class Permissions implements \Serializable
{

        /**
     * Array of permissions that only apply to stage channels.
     *
     * @var array
     */
    public const STAGE_PERMISSIONS = [
        'connect' => 20,
        'mute_members' => 22,
        'deafen_members' => 23,
        'move_members' => 24,
        'request_to_speak' => 32,
        'manage_events' => 33,
    ];

    /**
     * Array of permissions that only apply to voice channels.
     *
     * @var array
     */
    public const VOICE_PERMISSIONS = [
        'priority_speaker' => 8,
        'stream' => 9,
        'connect' => 20,
        'speak' => 21,
        'mute_members' => 22,
        'deafen_members' => 23,
        'move_members' => 24,
        'use_vad' => 25,
        'manage_events' => 33,
        'start_embedded_activities' => 39,
    ];

    /**
     * Array of permissions that only apply to text channels.
     *
     * @var array
     */
    public const TEXT_PERMISSIONS = [
        'add_reactions' => 6,
        'send_messages' => 11,
        'send_tts_messages' => 12,
        'manage_messages' => 13,
        'embed_links' => 14,
        'attach_files' => 15,
        'read_message_history' => 16,
        'mention_everyone' => 17,
        'use_external_emojis' => 18,
        'use_application_commands' => 31,
        'manage_threads' => 34,
        'create_public_threads' => 35,
        'create_private_threads' => 36,
        'use_external_stickers' => 37,
        'send_messages_in_threads' => 38,
    ];

    /**
     * Array of permissions that can only be applied to roles.
     *
     * @var array
     */
    public const ROLE_PERMISSIONS = [
        'kick_members' => 1,
        'ban_members' => 2,
        'administrator' => 3,
        'manage_guild' => 5,
        'view_audit_log' => 7,
        'view_guild_insights' => 19,
        'change_nickname' => 26,
        'manage_nicknames' => 27,
        'manage_emojis_and_stickers' => 30,
        'manage_events' => 33,
        'moderate_members' => 40,
    ];

    /**
     * Array of permissions for all roles.
     *
     * @var array
     */
    public const ALL_PERMISSIONS = [
        'create_instant_invite' => 0,
        'manage_channels' => 4,
        'view_channel' => 10,
        'manage_roles' => 28,
        'manage_webhooks' => 29,
    ];

    /** @var int|string */
    private $bitwise;

    /** @var Array<string, bool> */
    private $permissions = [];

    public function __construct($bitwise = 0)
    {
        $this->setBitwise($bitwise);
    }

    public function getBitwise(): int
    {
        return $this->bitwise;
    }

    public function setBitwise(int $bitwise): void
    {
        $this->bitwise = $bitwise;
    }

    /**
     * Returns all the permissions possible and the current state, or an empty array if not initialised.
     * @return Array<string, bool>
     */
    public function getPermissions(): array
    {
        if (sizeof($this->permissions) === 0) {
            $this->recalculatePermissions();
        }
        return $this->permissions;
    }

    public function getPermission(string $permission): ?bool
    {
        if (sizeof($this->permissions) === 0) {
            $this->recalculatePermissions();
        }
        return $this->permissions[$permission] ?? null;
    }

    public function setPermission(string $permission, bool $state = true): Permissions
    {
        if (sizeof($this->permissions) === 0) {
            $this->recalculatePermissions();
        }
        $permission = strtolower($permission);
        $posPermissions = $this->getPossiblePermissions();

        if (!in_array($permission, array_keys($posPermissions))) {
            throw new \AssertionError("Invalid permission '{$permission}' for a '" . get_parent_class($this) . "'");
        }

        if ($this->permissions[$permission] === $state) return $this;
        $this->permissions[$permission] = $state;
        $this->bitwise ^= $posPermissions[$permission]; //todo test this first.
      return $this;
    }

    /**
     * @internal Using current bitwise recalculate permissions.
     */
    private function recalculatePermissions(): void
    {
        if($this->bitwise < 0){
            throw new \AssertionError("Bitwise cannot be negative numbers.");
        }
        $this->permissions = [];
        $possiblePerms = $this->getPossiblePermissions();
        foreach ($possiblePerms as $name => $v) {
            $this->permissions[$name] = (Bitwise::test($this->bitwise, $v));
        }
    }

    /**
     * @return Array<string, int>
     */
    abstract static function getPossiblePermissions(): array;

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize($this->bitwise);
    }

    public function unserialize($data): void
    {
        $this->bitwise = unserialize($data);
    }
}
