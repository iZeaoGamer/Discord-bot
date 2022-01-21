<?php

/*
 * This file is a part of the DiscordPHP project.
 *
 * Copyright (c) 2015-present David Cole <david.cole1340@gmail.com>
 *
 * This file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace Discord\Parts\Guild\AuditLog;

use Discord\Parts\Part;

/**
 * Represents an Change in the audit log.
 *
 * @see https://discord.com/developers/docs/resources/audit-log#audit-log-change-object
 *
 * @property mixed        $new_value
 * @property mixed        $old_value
 * @property string       $key
 */
class Change extends Part
{

    /** @var string[] */
    public const KEY_TYPE = [
        "afk_channel_id",
        "afk_timeout",
        "allow",
        "application_id",
        "archived",
        "asset",
        "auto_archive_duration",
        "available",
        "avatar_hash",
        "banner_hash",
        "bitrate",
        "channel_id",
        "code",
        "color",
        "communication_disabled_until",
        "deaf",
        "default_auto_archive_duration",
        "default_message_notifications",
        "deny",
        "description",
        "discovery_splash_hash",
        "enable_emoticons",
        "entity_type",
        "expire_behavior",
        "expire_grace_period",
        "explicit_content_filter",
        "format_type",
        "guild_id",
        "hoist",
        "icon_hash",
        "id",
        "inviter_id",
        "location",
        "locked",
        "max_age",
        "max_uses",
        "mentionable",
        "mfa_level",
        "mute",
        "name",
        "nick",
        "nsfw",
        "owner_id",
        "permission_overwrites",
        "permissions",
        "position",
        "preferred_locale",
        "privacy_level",
        "prune_delete_days",
        "public_updates_channel_id",
        "rate_limit_per_user",
        "region",
        "rules_channel_id",
        "splash_hash",
        "status",
        "system_channel_id",
        "tags",
        "temporary",
        "topic",
        "type",
        "unicode_emoji",
        "user_limit",
        "uses",
        "vanity_url_code",
        "verification_level",
        "widget_channel_id",
        "widget_enabled",
        "add",
        "remove"
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'new_value',
        'old_value',
        'key'
    ];
}
