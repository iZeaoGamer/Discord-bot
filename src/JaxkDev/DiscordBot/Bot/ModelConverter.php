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

namespace JaxkDev\DiscordBot\Bot;

use AssertionError;
use Carbon\Carbon;
use Discord\Builders\Components\Component;
use Discord\Parts\Channel\Channel as DiscordChannel;
use Discord\Parts\Channel\Message as DiscordMessage;
use Discord\Parts\Guild\Sticker as DiscordSticker;
use Discord\Parts\Thread\Thread as DiscordThread;
use Discord\Parts\Channel\Reaction as DiscordReaction;
use Discord\Parts\Channel\Overwrite as DiscordOverwrite;
use Discord\Parts\Channel\Webhook as DiscordWebhook;
use Discord\Parts\Embed\Author as DiscordAuthor;
use Discord\Parts\Embed\Embed as DiscordEmbed;
use Discord\Parts\Embed\Field as DiscordField;
use Discord\Parts\Embed\Footer as DiscordFooter;
use Discord\Parts\Embed\Image as DiscordImage;
use Discord\Parts\Embed\Video as DiscordVideo;
use Discord\Parts\Guild\Ban as DiscordBan;
use Discord\Parts\Guild\Invite as DiscordInvite;
use Discord\Parts\Guild\Role as DiscordRole;
use Discord\Parts\Guild\Emoji as DiscordEmoji;
use Discord\Parts\Guild\ScheduledEvent as DiscordScheduledEvent;
use Discord\Parts\Permissions\RolePermission as DiscordRolePermission;
use Discord\Parts\User\Activity as DiscordActivity;
use Discord\Parts\User\Member as DiscordMember;
use Discord\Parts\User\User as DiscordUser;
use Discord\Parts\Guild\Guild as DiscordServer;
use Discord\Parts\Interactions\Interaction as DiscordInteraction;
use Discord\Parts\WebSockets\VoiceStateUpdate as DiscordVoiceStateUpdate;
use Discord\Parts\Channel\StageInstance as DiscordStage;
use Discord\Parts\Guild\GuildTemplate as DiscordServerTemplate;
use Discord\Parts\Guild\AuditLog\AuditLog as DiscordAuditLog;
use Discord\Parts\Guild\AuditLog\Entry as DiscordEntryLog;
use Discord\Parts\Guild\AuditLog\Options as DiscordEntryOptions;
use Discord\Parts\Guild\WelcomeScreen as DiscordWelcomeScreen;
use Discord\Parts\Guild\WelcomeChannel as DiscordWelcomeChannel;
use Discord\Parts\OAuth\Application as DiscordApplication;
use Discord\Parts\Interactions\Command\Choice as DiscordChoice;
use Discord\Parts\Interactions\Command\Command as DiscordCommand;
use Discord\Parts\Interactions\Command\Option as DiscordCommandOption;
use Discord\Parts\Interactions\Command\Overwrite as DiscordCommandOverwrite;
use Discord\Parts\Interactions\Command\Permission as DiscordCommandPermission;
use Discord\Parts\Interactions\Request\Resolved as DiscordResolved;
use Discord\Parts\User\Client as DiscordClient;
use JaxkDev\DiscordBot\Models\Activity;
use JaxkDev\DiscordBot\Models\Ban;
use JaxkDev\DiscordBot\Models\Channels\CategoryChannel;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Channels\TextChannel;
use JaxkDev\DiscordBot\Models\Channels\VoiceChannel;
use JaxkDev\DiscordBot\Models\Channels\DMChannel;
use JaxkDev\DiscordBot\Models\Invite;
use JaxkDev\DiscordBot\Models\Member;
use JaxkDev\DiscordBot\Models\Messages\Attachment;
use JaxkDev\DiscordBot\Models\Messages\Embed\Author;
use Discord\Parts\Interactions\Request\InteractionData as DiscordInteractData;
use Discord\Parts\Interactions\Request\Option as DiscordInteractOption;
use JaxkDev\DiscordBot\Models\Messages\Embed\Embed;
use JaxkDev\DiscordBot\Models\Messages\Embed\Field;
use JaxkDev\DiscordBot\Models\Messages\Embed\Footer;
use JaxkDev\DiscordBot\Models\Messages\Embed\Image;
use JaxkDev\DiscordBot\Models\Messages\Embed\Video;
use JaxkDev\DiscordBot\Models\Messages\Message;
use JaxkDev\DiscordBot\Models\Messages\CommandMessage;
use JaxkDev\DiscordBot\Models\Messages\Reply as ReplyMessage;
use JaxkDev\DiscordBot\Models\Messages\Webhook as WebhookMessage;
use JaxkDev\DiscordBot\Models\Messages\Reaction;
use JaxkDev\DiscordBot\Models\Permissions\ChannelPermissions;
use JaxkDev\DiscordBot\Models\Permissions\RolePermissions;
use JaxkDev\DiscordBot\Models\Channels\ThreadChannel;
use JaxkDev\DiscordBot\Models\Interactions\Interaction;
use JaxkDev\DiscordBot\Models\Interactions\Request\InteractionData;
use JaxkDev\DiscordBot\Models\Interactions\Request\Option;
use JaxkDev\DiscordBot\Models\Role;
use JaxkDev\DiscordBot\Models\Server;
use JaxkDev\DiscordBot\Models\User;
use JaxkDev\DiscordBot\Models\VoiceState;
use JaxkDev\DiscordBot\Models\Webhook;
use JaxkDev\DiscordBot\Models\ServerScheduledEvent;
use Discord\Builders\MessageBuilder;
use JaxkDev\DiscordBot\Models\AuditLog\AuditLog;
use JaxkDev\DiscordBot\Models\AuditLog\Options;
use JaxkDev\DiscordBot\Models\AuditLog\Entry;
use JaxkDev\DiscordBot\Models\Sticker;
use JaxkDev\DiscordBot\Models\Channels\Stage;
use JaxkDev\DiscordBot\Models\Emoji;
use JaxkDev\DiscordBot\Models\ServerTemplate;
use JaxkDev\DiscordBot\Models\WelcomeScreen;
use JaxkDev\DiscordBot\Models\WelcomeChannel;
use JaxkDev\DiscordBot\Models\OAuth\Application;
use Jaxkdev\DiscordBot\Models\Client;
use JaxkDev\DiscordBot\Models\Interactions\Command\Choice;
use JaxkDev\DiscordBot\Models\Interactions\Command\Option as CommandOption;
use JaxkDev\DiscordBot\Models\Interactions\Command\Overwrite as CommandOverwrite;
use JaxkDev\DiscordBot\Models\Interactions\Command\Permission as CommandPermission;
use JaxkDev\DiscordBot\Models\Interactions\Command\Command;
use JaxkDev\DiscordBot\Models\Interactions\Request\Resolved;

use JaxkDev\DiscordBot\Models\Channels\Overwrite;
use JaxkDev\DiscordBot\Plugin\Bitwise;
use JaxkDev\DiscordBot\Models\Permissions\Permissions;

abstract class ModelConverter
{
    static public function genModelChoice(DiscordChoice $choice): Choice
    {
        return new Choice($choice->name, $choice->value);
    }
    static public function genModelCommandOption(DiscordCommandOption $option): CommandOption
    {
        $choices = [];
        if ($option->choices) {
            foreach ($option->choices as $choice) {
                $choices[] = self::genModelChoice($choice);
            }
        }
        $options = [];
        foreach ($option->options as $option) {
            $options[] = new CommandOption(
                $option->type,
                $option->name,
                $option->description,
                $option->required,
                $choices,
                $options,
                $option->channel_types,
                $option->min_value,
                $option->max_value,
                $option->autocomplete
            );
        }
        return new CommandOption(
            $option->type,
            $option->name,
            $option->description,
            $option->required,
            $choices,
            $options,
            $option->channel_types,
            $option->min_value,
            $option->max_value,
            $option->autoComplete
        );
    }
    static public function genModelCommandOverwrite(DiscordCommandOverwrite $overwrite): CommandOverwrite
    {
        $permissions = [];
        foreach ($overwrite->permissions as $permission) {
            $permissions[] = self::genModelCommandPermission($permission);
        }
        return new CommandOverwrite(
            $overwrite->id,
            $overwrite->application_id,
            $overwrite->server_id,
            $permissions
        );
    }
    static public function genModelCommandPermission(DiscordCommandPermission $permission): CommandPermission
    {
        return new CommandPermission(
            $permission->id,
            $permission->type,
            $permission->permission
        );
    }
    static public function genModelCommand(DiscordCommand $command): Command
    {
        $options = [];
        foreach ($command->options ?? [] as $option) {
            if ($option) {
                $options[] = self::genModelCommandOption($option);
            }
        }
        $overwrites = [];
        /** @var DiscordCommandOverwrite $overwrite */
        foreach ($command->overwrites as $overwrite) {
            $overwrites[] = self::genModelCommandOverwrite($overwrite);
        }
        return new Command(
            $command->type,
            $command->name,
            $command->description,
            $command->default_permission,
            $overwrites,
            $options,
            $command->id,
            $command->application_id,
            $command->server_id,
            $command->version
        );
    }
    static public function genModelResolved(DiscordResolved $resolve): Resolved
    {
        $users = [];
        if ($resolve->users) {
            foreach ($resolve->users as $snowflake => $user) {
                $users[$snowflake][] = self::genModelUser($user);
            }
        }
        $members = [];
        if ($resolve->members) {
            foreach ($resolve->members as $snowflake => $member) {
                $members[$snowflake][] = self::genModelMember($member);
            }
        }
        $roles = [];
        if ($resolve->roles) {
            foreach ($resolve->roles as $snowflake => $role) {
                $roles[$snowflake][] = self::genModelRole($role);
            }
        }
        $channels = [];
        if ($resolve->channels) {
            foreach ($resolve->channels as $snowflake => $channel) {
                if ($channel instanceof DiscordThread) {
                    $channels[$snowflake][] = self::genModelThread($channel);
                }
                if ($channel instanceof DiscordChannel) {
                    $channels[$snowflake][] = self::genModelChannel($channel);
                }
            }
        }
        $messages = [];
        if ($resolve->messages) {
            foreach ($resolve->messages as $snowflake => $message) {
                $messages[$snowflake][] = self::genModelMessage($message);
            }
        }
        return new Resolved(
            $users,
            $members,
            $roles,
            $channels,
            $messages,
            $resolve->guild_id
        );
    }
    static public function genModelClient(DiscordClient $client): Client
    {
        $servers = [];
        /** @var DiscordServer $server */
        foreach ($client->guilds as $server) {
            $servers[] = self::genModelServer($server);
        }
        $users = [];
        /** @var DiscordUser $user */
        foreach ($client->users as $user) {
            $users[] = self::genModelUser($user);
        }
        $channels = [];
        /** @var DiscordChannel $channel */
        foreach ($client->private_channels as $channel) {
            $c = self::genModelDMChannel($channel);
            if ($c !== null) {
                $channels[] = $c;
            }
        }
        return new Client(
            $client->id,
            $client->username,
            $client->discriminator,
            $client->avatar,
            $client->bot ?? false,
            self::genModelApplication($client->application),
            $servers,
            $users,
            $channels


        );
    }
    static public function genModelApplication(DiscordApplication $application): Application
    {
        return new Application(
            $application->name,
            $application->description,
            $application->invite_url,
            $application->id,
            $application->icon,
            $application->rpc_origins,
            ($application->owner !== null ? self::genModelUser($application->owner) : null
            ),
            $application->bot_public,
            $application->bot_require_code_grant,
            $application->terms_of_service_url,
            $application->privacy_policy_url,
            $application->verify_key,
            $application->team
        );
    }
    static public function genModelWelcomeScreen(DiscordWelcomeScreen $screen): WelcomeScreen
    {
        $channels = [];
        foreach ($screen->welcome_channels as $welcome) {
            $channels[] = self::genModelWelcomeChannel($welcome);
        }
        return new WelcomeScreen(
            $screen->description,
            $channels
        );
    }
    static public function genModelWelcomeChannel(DiscordWelcomeChannel $channel)
    {
        return new WelcomeChannel(
            $channel->channel_id,
            $channel->description,
            $channel->emoji_id,
            $channel->emoji_name
        );
    }
    static public function genOverwrite(DiscordOverwrite $overwrite): Overwrite
    {
        return new Overwrite(
            $overwrite->channel_id,
            $overwrite->type,
            new ChannelPermissions(
                $overwrite->allow->bitwise
            ),
            new ChannelPermissions(
                $overwrite->deny->bitwise,
                $overwrite->id
            )
        );
    }
    static public function genModelAuditLog(DiscordAuditLog $log): AuditLog
    {
        /** @var WebHook[] $webhooks */
        $webhooks = [];
        foreach ($log->webhooks as $webhook) {
            $webhooks[] = self::genModelWebhook($webhook);
        }

        /** @var User[] $users */
        $users = [];
        foreach ($log->users as $user) {
            $users[] = self::genModelUser($user);
        }

        /** @var Entry[] $entries */
        $entries = [];
        foreach ($log->audit_log_entries as $entry) {
            $entries[] = self::genModelEntryLog($entry);
        }

        /** @var ServerScheduledEvent[] $schedules */
        $schedules = [];
        foreach ($log->guild_scheduled_events as $schedule) {
            $schedules[] = ModelConverter::genModelScheduledEvent($schedule);
        }
        /** @var ThreadChannel[] $threads */
        $threads = [];
        foreach ($log->threads as $thread) {
            $threads[] = self::genModelThread($thread);
        }
        return new AuditLog(
            $log->guild_id,
            $webhooks,
            $users,
            $entries,
            $schedules,
            $threads


        );
    }
    static public function genModelEntryLog(DiscordEntryLog $entry): Entry
    {
        return new Entry(
            $entry->id,
            $entry->user_id,
            $entry->target_id,
            $entry->action_type,
            self::genModelAuditLogOptions($entry->options),
            $entry->reason
        );
    }
    static public function genModelAuditLogOptions(DiscordEntryOptions $option): Options
    {
        return new Options(
            $option->delete_member_days,
            $option->members_removed,
            $option->channel_id,
            $option->message_id,
            $option->count,
            $option->id,
            $option->type,
            $option->role_name
        );
    }

    static public function genModelEmoji(DiscordEmoji $emoji): Emoji
    {
        return new Emoji(
            $emoji->name,
            $emoji->guild_id,
            $emoji->managed,
            $emoji->id,
            $emoji->require_colons,
            array_keys($emoji->roles->toArray()),
            ($emoji->user !== null ? self::genModelUser($emoji->user) : null),
            $emoji->animated,
            $emoji->available
        );
    }
    static public function genModelServerTemplate(DiscordServerTemplate $template): ServerTemplate
    {
        return new ServerTemplate(
            $template->name,
            $template->description,
            $template->source_guild_id,
            $template->code,
            $template->usage_count,
            $template->creator_id,
            $template->created_at->getTimestamp(),
            $template->updated_at->getTimestamp(),
            $template->is_dirty
        );
    }
    static function genModelScheduledEvent(DiscordScheduledEvent $schedule): ServerScheduledEvent
    {
        return new ServerScheduledEvent(
            $schedule->guild_id,
            $schedule->id,
            $schedule->channel_id,
            $schedule->creator_id,
            $schedule->description,
            $schedule->scheduled_start_time->getTimestamp(),
            ($schedule->scheduled_end_time !== null ? $schedule->scheduled_end_time->getTimestamp() : null),
            $schedule->privacy_level,
            $schedule->status,
            $schedule->entity_type,
            $schedule->entity_id,
            $schedule->user_count
        );
    }

    /** @param DiscordInteraction
     * @return Interaction
     */
    static public function genModelInteraction($interact): ?Interaction
    {
        $interaction = null;
        if ($interact instanceof DiscordInteraction) {
            $interaction = new Interaction(
                $interact->application_id,
                $interact->type,
                ($interact->user !== null ? self::genModelUser($interact->user) : null),
                $interact->guild_id,
                $interact->channel_id,
                $interact->id,
                ($interact->data !== null ? self::genModelData($interact->data) : null),
                $interact->token,
                $interact->version,
                ($interact->message !== null ? self::genModelMessage($interact->message) : null),
                ($interact->member !== null ? self::genModelMember($interact->member) : null)
            );
        }
        return $interaction;
    }
    static public function genModelData(DiscordInteractData $data): InteractionData
    {
        $resolved = ($data->resolved ? self::genModelResolved($data->resolved) : null);
        $options = [];

        /** @var DiscordInteractOption $option */
        foreach ($data->options as $option) {
            $options[] = self::genModelOption($option);
        }
        return new InteractionData(
            $data->name,
            $data->type ?? $data->component_type,
            $data->id,
            $data->values,
            $data->custom_id,
            ($data->resolved !== null ? self::genModelResolved($data->resolved) : null),
            $data->target_id,
            $data->guild_id,
            $options
        );
    }
    static function genModelOption(DiscordInteractOption $option): Option
    {
        return new Option(
            $option->name,
            $option->type,
            $option->value,
            $option->focused ?? false
        );
    }
    static public function genModelVoiceState(DiscordVoiceStateUpdate $stateUpdate): VoiceState
    {
        if ($stateUpdate->guild_id === null) { //todo add dm channels support.
            throw new AssertionError("Not handling DM Voice states.");
        }
        return new VoiceState(
            $stateUpdate->session_id,
            $stateUpdate->channel_id,
            $stateUpdate->deaf,
            $stateUpdate->mute,
            $stateUpdate->self_deaf,
            $stateUpdate->self_mute,
            $stateUpdate->self_stream ?? false,
            $stateUpdate->self_video,
            $stateUpdate->suppress
        );
    }

    static public function genModelWebhook(DiscordWebhook $webhook): Webhook
    {
        return new Webhook(
            $webhook->type,
            $webhook->channel_id,
            $webhook->name,
            $webhook->id,
            $webhook->user->id,
            $webhook->avatar,
            $webhook->token
        );
    }
    static function genModelStage(DiscordStage $stage): Stage
    {
        return new Stage(
            $stage->guild_id,
            $stage->channel_id,
            $stage->topic,
            $stage->id,
            $stage->privacy_level,
            $stage->discoverable_disabled
        );
    }

    static public function genModelActivity(DiscordActivity $discordActivity): Activity
    {
        /** @var \stdClass{"end" => int|null, "start" => int|null} $timestamps */
        $timestamps = $discordActivity->timestamps;
        /** @var \stdClass{"id" => string|null, "size" => int[]|null} $party */
        $party = $discordActivity->party;
        /** @var \stdClass{"large_image" => string|null, "large_text" => string|null, "small_image" => string|null, "small_text" => string|null} $assets */
        $assets = $discordActivity->assets;
        //** @var \stdClass{"join" => string|null, "spectate" => string|null, "match" => string|null} $secrets  TODO, Cant confirm this. no one has any secrets so I cant see any valid data. */
        //$secrets = $discordActivity->secrets;
        return new Activity(
            $discordActivity->name,
            $discordActivity->type,
            $discordActivity->created_at->getTimestamp(),
            $discordActivity->url,
            $timestamps->start ?? null,
            $timestamps->end ?? null,
            $discordActivity->application_id,
            $discordActivity->details,
            $discordActivity->state,
            $discordActivity->emoji,
            $party->id ?? null,
            ($party->size ?? [])[0] ?? null,
            ($party->size ?? [])[1] ?? null,
            $assets->large_image ?? null,
            $assets->large_text ?? null,
            $assets->small_image ?? null,
            $assets->small_text ?? null, /*$secrets->join??null, $secrets->spectate??null,
            $secrets->match??null,*/
            $discordActivity->instance,
            $discordActivity->flags
        );
    }

    static public function genModelMember(DiscordMember $discordMember): Member
    {
        $m = new Member(
            $discordMember->id,
            $discordMember->joined_at === null ? 0 : $discordMember->joined_at->getTimestamp(),
            $discordMember->guild_id,
            [],
            $discordMember->nick,
            $discordMember->premium_since === null ? null : $discordMember->premium_since->getTimestamp()
        );

        $bitwise = $discordMember->guild->roles->offsetGet($discordMember->guild_id)->permissions->bitwise; //Everyone perms.
        $roles = [];

        //O(2n) -> O(n) by using same loop for permissions to add roles.
        if ($discordMember->guild->owner_id === $discordMember->id) {
            $bitwise = Bitwise::set($bitwise, Permissions::ROLE_PERMISSIONS['administrator']); // Add administrator permission
            foreach ($discordMember->roles ?? [] as $role) {
                $roles[] = $role->id;
            }
        } else {
            /* @var DiscordRole */
            foreach ($discordMember->roles ?? [] as $role) {
                $roles[] = $role->id;
                $bitwise = Bitwise::or($bitwise, $role->permissions->bitwise);
            }
        }
        $bitwise = Bitwise::and($bitwise, RolePermissions::ROLE_PERMISSIONS["administrator"]); // Add administrator permission
        $m->setPermissions(new RolePermissions($bitwise));
        $m->setRoles($roles);
        return $m;
    }

    static public function genModelUser(DiscordUser $user): User
    {
        return new User(
            $user->id,
            $user->username,
            $user->discriminator,
            $user->avatar,
            $user->bot ?? false,
            $user->public_flags ?? 0
        );
    }

    static public function genModelServer(DiscordServer $discordServer): Server
    {
        $schedules = [];
        /** @var DiscordScheduledEvent */
        foreach ($discordServer->guild_scheduled_events as $schedule) {
            $schedules[] = self::genModelScheduledEvent($schedule);
        }

        $templates = [];
        /** @var DiscordServerTemplate $template */
        foreach ($discordServer->templates as $template) {
            $templates[] = self::genModelServerTemplate($template);
        }
        $roles = [];
        /** @var DiscordRole $role */
        foreach ($discordServer->roles as $role) {
            $roles[] = self::genModelRole($role);
        }
        $channels = [];
        /** @var DiscordChannel $channel */
        foreach ($discordServer->channels as $channel) {
            $serverChannel = self::genModelChannel($channel);
            if ($serverChannel) {
                $channels[] = $serverChannel;
            }
        }

        $members = [];
        /** @var DiscordMember $member */
        foreach ($discordServer->members as $member) {
            $members[] = self::genModelMember($member);
        }
        //todo

        return new Server(
            $discordServer->id,
            $discordServer->name,
            $discordServer->region, // deprecated (//todo remove)
            $discordServer->owner_id,
            $discordServer->large,
            $discordServer->member_count,
            $discordServer->icon,
            ($discordServer->welcome_screen !== null ? self::genModelWelcomeScreen($discordServer->welcome_screen) : null),
            $discordServer->afk_channel_id,
            $discordServer->afk_timeout,
            $discordServer->splash,
            $discordServer->discovery_splash,
            $discordServer->verification_level,
            $discordServer->default_message_notifications,
            $discordServer->explicit_content_filter,
            $discordServer->mfa_level,
            $discordServer->application_id,
            $discordServer->widget_enabled ?? false,
            $discordServer->widget_channel_id ?? null,
            $discordServer->system_channel_id ?? null,
            $discordServer->system_channel_flags ?? 1,
            $discordServer->rules_channel_id ?? null,
            $discordServer->vanity_url_code ?? null,
            $discordServer->description ?? null,
            $discordServer->banner ?? null,
            $discordServer->premium_tier ?? 0,
            $discordServer->premium_subscription_count ?? 0,
            $discordServer->preferred_locale,
            $discordServer->public_updates_channel_id ?? null,
            $discordServer->nsfw_level ?? 0,
            $discordServer->premium_progress_bar_enabled ?? false,
            $schedules,
            $templates,
            $roles,
            $channels,
            $members
        );
    }

    /**
     * @param DiscordChannel $dc
     * @param mixed $c
     * @return mixed
     */
    static private function applyPermissionOverwrites(DiscordChannel $dc, mixed $c): mixed
    {
        /** @var DiscordOverwrite $overwrite */
        foreach ($dc->overwrites as $overwrite) {
            $allowed = new ChannelPermissions($overwrite->allow->bitwise);
            $denied = new ChannelPermissions($overwrite->deny->bitwise);
            if ($overwrite->type === DiscordOverwrite::TYPE_MEMBER) {
                $c->setAllowedMemberPermissions($overwrite->id, $allowed);
                $c->setDeniedMemberPermissions($overwrite->id, $denied);
            } elseif ($overwrite->type === DiscordOverwrite::TYPE_ROLE) {
                $c->setAllowedRolePermissions($overwrite->id, $allowed);
                $c->setDeniedRolePermissions($overwrite->id, $denied);
            } else {
                throw new AssertionError("Overwrite type unknown ? ({$overwrite->type})");
            }
        }
        return $c;
    }

    static function genModelDMChannel(DiscordChannel $channel): ?DMChannel
    {
        switch ($channel->type) {
            case DiscordChannel::TYPE_DM:
                $users = [];
                /** @var DiscordUser $user */
                foreach ($channel->recipients as $user) {
                    $users[] = self::genModelUser($user);
                }
                return new DMChannel(
                    $channel->recipient_id,
                    $channel->owner_id,
                    $channel->id,
                    $users,
                    $channel->application_id
                );
            default: //todo add group dm support
                return null;
        }
    }


    /**
     * Generates a model based on whatever type $channel is. (Excludes game store/group type)
     * @param DiscordChannel $channel
     * @return ?ServerChannel Null if type is invalid/unused.
     */
    static public function genModelChannel(DiscordChannel $channel): ?ServerChannel
    {
        switch ($channel->type) {
            case DiscordChannel::TYPE_TEXT:
            case DiscordChannel::TYPE_NEWS:
                return self::genModelTextChannel($channel);
            case DiscordChannel::TYPE_VOICE:
                return self::genModelVoiceChannel($channel);
            case DiscordChannel::TYPE_CATEGORY:
                return self::genModelCategoryChannel($channel);
            default:
                return null;
        }
    }
    static public function genModelThread(DiscordThread $thread): ThreadChannel
    {
        return new ThreadChannel(
            $thread->name,
            $thread->guild_id,
            $thread->owner_id,
            $thread->locked,
            $thread->auto_archive_duration,
            $thread->rate_limit_per_user,
            $thread->archiver_id,
            $thread->id
        );
    }

    static public function genModelCategoryChannel(DiscordChannel $discordChannel): CategoryChannel
    {
        if ($discordChannel->type !== DiscordChannel::TYPE_CATEGORY) {
            throw new AssertionError("Discord channel type must be `category` to generate model category channel.");
        }
        if ($discordChannel->guild_id === null) {
            throw new AssertionError("Guild ID must be present.");
        }
        return self::applyPermissionOverwrites($discordChannel, new CategoryChannel(
            $discordChannel->name,
            $discordChannel->position,
            $discordChannel->guild_id,
            $discordChannel->id
        ));
    }

    static public function genModelVoiceChannel(DiscordChannel $discordChannel): VoiceChannel
    {
        if ($discordChannel->type !== DiscordChannel::TYPE_VOICE) {
            throw new AssertionError("Discord channel type must be `voice` to generate model voice channel.");
        }
        if ($discordChannel->guild_id === null) {
            throw new AssertionError("Guild ID must be present.");
        }
        $ids = array_map(function ($id) use ($discordChannel) {
            return $discordChannel->guild->id . ".$id";
        }, array_keys($discordChannel->members->toArray()));
        return self::applyPermissionOverwrites($discordChannel, new VoiceChannel(
            $discordChannel->bitrate,
            $discordChannel->user_limit,
            $discordChannel->name,
            $discordChannel->position,
            $discordChannel->guild_id,
            $ids,
            $discordChannel->parent_id,
            $discordChannel->id
        ));
    }

    static public function genModelTextChannel(DiscordChannel $discordChannel): TextChannel
    {
        if ($discordChannel->type !== DiscordChannel::TYPE_TEXT and $discordChannel->type !== DiscordChannel::TYPE_NEWS) {
            throw new AssertionError("Discord channel type must be `text|news` to generate model text channel.");
        }
        if ($discordChannel->guild_id === null) {
            throw new AssertionError("Guild ID must be present.");
        }
        return self::applyPermissionOverwrites($discordChannel, new TextChannel(
            $discordChannel->topic ?? "",
            $discordChannel->name,
            $discordChannel->position,
            $discordChannel->guild_id,
            $discordChannel->nsfw ?? false,
            $discordChannel->rate_limit_per_user,
            $discordChannel->parent_id,
            $discordChannel->id,
            $discordChannel->last_message_id,
            $discordChannel->permissions
        ));
    }
    static public function genModelSticker(DiscordSticker $sticker): Sticker
    {
        return new Sticker(
            $sticker->name,
            $sticker->description,
            $sticker->type,
            $sticker->format_type,
            $sticker->available,
            $sticker->guild_id,
            ($sticker->user !== null ? self::genModelUser($sticker->user) : null),
            $sticker->sort_value,
            $sticker->tags,
            $sticker->id,
            $sticker->pack_id
        );
    }
    static public function genModelReaction(DiscordReaction $react)
    {
        return new Reaction(
            $react->message_id,
            $react->channel_id,
            self::genModelEmoji($react->emoji),
            $react->guild_id,
            $react->id,
            $react->count,
            $react->me
        );
    }
    static public function genModelMessage(DiscordMessage $discordMessage): Message
    {
        if ($discordMessage->author === null) {
            throw new AssertionError("Discord message does not have a author, cannot generate model message.");
        }
        $attachments = [];
        foreach ($discordMessage->attachments as $attachment) {
            $attachments[] = self::genModelAttachment($attachment);
        }
        $guild_id = $discordMessage->guild_id ?? ($discordMessage->author instanceof DiscordMember ? $discordMessage->author->guild_id : null);
        $author = $guild_id === null ? $discordMessage->author->id : $guild_id . "." . $discordMessage->author->id;
        if ($discordMessage->type === DiscordMessage::TYPE_NORMAL) {
            if ($discordMessage->webhook_id === null) {
                $e = $discordMessage->embeds->first();
                if ($e !== null) {
                    $e = self::genModelEmbed($e);
                }
                $author = $guild_id === null ? $discordMessage->author->id : $guild_id . "." . $discordMessage->author->id;
                return new Message(
                    $discordMessage->channel_id,
                    $discordMessage->id,
                    $discordMessage->content,
                    $e,
                    $author,
                    $guild_id,
                    $discordMessage->timestamp->getTimestamp(),
                    $attachments,
                    $discordMessage->mention_everyone,
                    array_keys($discordMessage->mentions->toArray()),
                    array_keys($discordMessage->mention_roles->toArray()),
                    array_keys($discordMessage->mention_channels->toArray()),
                    array_keys($discordMessage->sticker_items->toArray()),
                    $discordMessage->link,
                    $discordMessage->tts
                );
            } else {
                $embeds = [];
                foreach ($discordMessage->embeds as $embed) {
                    $embeds[] = self::genModelEmbed($embed);
                }
                $author = $guild_id === null ? $discordMessage->author->id : $guild_id . "." . $discordMessage->author->id;


                return new WebhookMessage(
                    $discordMessage->channel_id,
                    $discordMessage->webhook_id,
                    $embeds,
                    $discordMessage->id,
                    $discordMessage->content,
                    $author,
                    $guild_id,
                    $discordMessage->timestamp->getTimestamp(),
                    $attachments,
                    $discordMessage->mention_everyone,
                    array_keys($discordMessage->mentions->toArray()),
                    array_keys($discordMessage->mention_roles->toArray()),
                    array_keys($discordMessage->mention_channels->toArray()),
                    array_keys($discordMessage->sticker_items->toArray()),
                    $discordMessage->link,
                    $discordMessage->tts
                );
            }
        } elseif ($discordMessage->type === DiscordMessage::TYPE_REPLY) {
            if ($discordMessage->referenced_message === null) {
                throw new AssertionError("No referenced message on a REPLY message.");
            }
            $e = $discordMessage->embeds->first();
            if ($e !== null) {
                $e = self::genModelEmbed($e);
            }
            $author = $guild_id === null ? $discordMessage->author->id : $guild_id . "." . $discordMessage->author->id;
            return new ReplyMessage(
                $discordMessage->channel_id,
                $discordMessage->referenced_message->id,
                $discordMessage->id,
                $discordMessage->content,
                $e,
                $author,
                $guild_id,
                $discordMessage->timestamp->getTimestamp(),
                $attachments,
                $discordMessage->mention_everyone,
                array_keys($discordMessage->mentions->toArray()),
                array_keys($discordMessage->mention_roles->toArray()),
                array_keys($discordMessage->mention_channels->toArray()),
                array_keys($discordMessage->sticker_items->toArray()),
                $discordMessage->link,
                $discordMessage->tts
            );
        } elseif ($discordMessage->type === DiscordMessage::TYPE_APPLICATION_COMMAND) {
            $e = $discordMessage->embeds->first();
            if ($e !== null) {
                $e = self::genModelEmbed($e);
            }
            /** @var Component[] $array */
            $array = [];
            /** @var Component $com */
            foreach ($discordMessage->components as $com) {
                $array[] = $com;
            }
            return new CommandMessage(
                $discordMessage->channel_id,
                ($discordMessage->interaction !== null ? self::genModelInteraction($discordMessage->interaction) : null),
                $discordMessage->application_id ?? null,
                $array,
                $discordMessage->id,
                $discordMessage->content,
                $e,
                $author,
                $guild_id,
                $discordMessage->timestamp->getTimestamp(),
                $attachments,
                $discordMessage->mention_everyone,
                array_keys($discordMessage->mentions->toArray()),
                array_keys($discordMessage->mention_roles->toArray()),
                array_keys($discordMessage->mention_channels->toArray()),
                array_keys($discordMessage->sticker_items->toArray()),
                $discordMessage->link,
                $discordMessage->tts,

            );
        }
        throw new AssertionError("Discord message type not supported.");
    }

    static public function genModelAttachment(\stdClass $attachment): Attachment
    {
        return new Attachment(
            $attachment->id,
            $attachment->filename,
            $attachment->content_type,
            $attachment->size,
            $attachment->url,
            $attachment->width ?? null,
            $attachment->height ?? null
        );
    }

    static public function genModelEmbed(DiscordEmbed $discordEmbed): Embed
    {
        $fields = [];
        foreach (array_values($discordEmbed->fields->toArray()) as $field) {
            $fields[] = self::genModelEmbedField($field);
        }
        return new Embed(
            $discordEmbed->title,
            $discordEmbed->type,
            $discordEmbed->description,
            $discordEmbed->url,
            $discordEmbed->timestamp instanceof Carbon ? $discordEmbed->timestamp->getTimestamp() : (int)$discordEmbed->timestamp,
            $discordEmbed->color,
            $discordEmbed->footer === null ? new Footer() : self::genModelEmbedFooter($discordEmbed->footer),
            $discordEmbed->image === null ? new Image() : self::genModelEmbedImage($discordEmbed->image),
            $discordEmbed->thumbnail === null ? new Image() : self::genModelEmbedImage($discordEmbed->thumbnail),
            $discordEmbed->video === null ? new Video() : self::genModelEmbedVideo($discordEmbed->video),
            $discordEmbed->author === null ? new Author() : self::genModelEmbedAuthor($discordEmbed->author),
            $fields
        );
    }

    static public function genModelEmbedFooter(DiscordFooter $footer): Footer
    {
        return new Footer(
            $footer->text,
            $footer->icon_url,
            $footer->proxy_icon_url
        );
    }

    static public function genModelEmbedImage(DiscordImage $image): Image
    {
        return new Image(
            $image->url,
            $image->width,
            $image->height,
            $image->proxy_url,

        );
    }

    static public function genModelEmbedVideo(DiscordVideo $video): Video
    {
        return new Video(
            $video->url,
            $video->width,
            $video->height,
            $video->proxy_url
        );
    }

    static public function genModelEmbedAuthor(DiscordAuthor $author): Author
    {
        return new Author(
            $author->name,
            $author->url,
            $author->icon_url,
            $author->proxy_icon_url
        );
    }

    static public function genModelEmbedField(DiscordField $field): Field
    {
        return new Field(
            $field->name,
            $field->value,
            $field->inline
        );
    }

    static public function genModelRolePermission(DiscordRolePermission $rolePermission): RolePermissions
    {
        return new RolePermissions(
            $rolePermission->bitwise
        );
    }

    static public function genModelRole(DiscordRole $discordRole): Role
    {
        return new Role(
            $discordRole->name,
            $discordRole->color,
            $discordRole->hoist,
            $discordRole->position,
            $discordRole->mentionable,
            $discordRole->guild_id,
            $discordRole->managed,
            $discordRole->icon,
            self::genModelRolePermission($discordRole->permissions),
            $discordRole->id

        );
    }

    static public function genModelInvite(DiscordInvite $invite): Invite
    {
        return new Invite(
            $invite->guild_id ?? "",
            $invite->channel_id ?? "",
            $invite->max_age,
            $invite->max_uses,
            $invite->temporary,
            $invite->code,
            $invite->created_at->getTimestamp(),
            $invite->guild_id . "." . ($invite->inviter !== null ? $invite->inviter->id : null),
            $invite->uses,
            $invite->target_type,
            $invite->target_user,
            $invite->target_application,
            $invite->approximate_presence_count,
            $invite->approximate_member_count,
            ($invite->expires_at !== null ? $invite->expires_at->getTimestamp() : null),
            $invite->stage_instance,
            $invite->guild_scheduled_event
        );
    }

    static public function genModelBan(DiscordBan $ban): Ban
    {
        return new Ban(
            $ban->guild_id,
            $ban->user_id,
            $ban->reason
        );
    }
}
