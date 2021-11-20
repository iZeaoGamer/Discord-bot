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
use Discord\Parts\Channel\Channel as DiscordChannel;
use Discord\Parts\Channel\Message as DiscordMessage;
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
use Discord\Parts\Permissions\RolePermission as DiscordRolePermission;
use Discord\Parts\User\Activity as DiscordActivity;
use Discord\Parts\User\Member as DiscordMember;
use Discord\Parts\User\User as DiscordUser;
use Discord\Parts\Guild\Guild as DiscordServer;
use Discord\Parts\WebSockets\VoiceStateUpdate as DiscordVoiceStateUpdate;
use JaxkDev\DiscordBot\Models\Activity;
use JaxkDev\DiscordBot\Models\Ban;
use JaxkDev\DiscordBot\Models\Channels\CategoryChannel;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Channels\TextChannel;
use JaxkDev\DiscordBot\Models\Channels\VoiceChannel;
use JaxkDev\DiscordBot\Models\Invite;
use JaxkDev\DiscordBot\Models\Member;
use JaxkDev\DiscordBot\Models\Messages\Attachment;
use JaxkDev\DiscordBot\Models\Messages\Embed\Author;
use JaxkDev\DiscordBot\Models\Messages\Embed\Embed;
use JaxkDev\DiscordBot\Models\Messages\Embed\Field;
use JaxkDev\DiscordBot\Models\Messages\Embed\Footer;
use JaxkDev\DiscordBot\Models\Messages\Embed\Image;
use JaxkDev\DiscordBot\Models\Messages\Embed\Video;
use JaxkDev\DiscordBot\Models\Messages\Message;
use JaxkDev\DiscordBot\Models\Messages\Reply as ReplyMessage;
use JaxkDev\DiscordBot\Models\Messages\Webhook as WebhookMessage;
use JaxkDev\DiscordBot\Models\Permissions\ChannelPermissions;
use JaxkDev\DiscordBot\Models\Permissions\RolePermissions;
use JaxkDev\DiscordBot\Models\Role;
use JaxkDev\DiscordBot\Models\Server;
use JaxkDev\DiscordBot\Models\User;
use JaxkDev\DiscordBot\Models\VoiceState;
use JaxkDev\DiscordBot\Models\Webhook;

abstract class ModelConverter{

    static public function genModelVoiceState(DiscordVoiceStateUpdate $stateUpdate): VoiceState{
        if($stateUpdate->guild_id === null){
            throw new AssertionError("Not handling DM Voice states.");
        }
        return new VoiceState($stateUpdate->session_id, $stateUpdate->channel_id, $stateUpdate->deaf, $stateUpdate->mute,
            $stateUpdate->self_deaf, $stateUpdate->self_mute, $stateUpdate->self_stream??false, $stateUpdate->self_video,
            $stateUpdate->suppress);
    }

    static public function genModelWebhook(DiscordWebhook $webhook): Webhook{
        return new Webhook($webhook->type, $webhook->channel_id, $webhook->name, $webhook->id, $webhook->user->id,
            $webhook->avatar, $webhook->token);
    }

    static public function genModelActivity(DiscordActivity $discordActivity): Activity{
        /** @var \stdClass{"end" => int|null, "start" => int|null} $timestamps */
        $timestamps = $discordActivity->timestamps;
        /** @var \stdClass{"id" => string|null, "size" => int[]|null} $party */
        $party = $discordActivity->party;
        /** @var \stdClass{"large_image" => string|null, "large_text" => string|null, "small_image" => string|null, "small_text" => string|null} $assets */
        $assets = $discordActivity->assets;
        //** @var \stdClass{"join" => string|null, "spectate" => string|null, "match" => string|null} $secrets  TODO, Cant confirm this. no one has any secrets so I cant see any valid data. */
        //$secrets = $discordActivity->secrets;
        return new Activity($discordActivity->name, $discordActivity->type, $discordActivity->created_at->getTimestamp(),
            $discordActivity->url, $timestamps->start??null, $timestamps->end??null,
            $discordActivity->application_id, $discordActivity->details, $discordActivity->state, $discordActivity->emoji,
            $party->id??null, ($party->size??[])[0]??null,($party->size??[])[1]??null,
            $assets->large_image??null, $assets->large_text??null, $assets->small_image??null,
            $assets->small_text??null, /*$secrets->join??null, $secrets->spectate??null,
            $secrets->match??null,*/ $discordActivity->instance, $discordActivity->flags);
    }

    static public function genModelMember(DiscordMember $discordMember): Member{
        $m = new Member($discordMember->id, $discordMember->joined_at === null ? 0 : $discordMember->joined_at->getTimestamp(),
            $discordMember->guild_id, [], $discordMember->nick, $discordMember->premium_since === null ? null : $discordMember->premium_since->getTimestamp());

        $bitwise = $discordMember->guild->roles->offsetGet($discordMember->guild_id)->permissions->bitwise; //Everyone perms.
        $roles = [];

        //O(2n) -> O(n) by using same loop for permissions to add roles.
        if($discordMember->guild->owner_id === $discordMember->id){
            $bitwise |= 0x8; // Add administrator permission
            foreach($discordMember->roles ?? [] as $role){
                $roles[] = $role->id;
            }
        }else{
            /* @var DiscordRole */
            foreach($discordMember->roles ?? [] as $role){
                $roles[] = $role->id;
                $bitwise |= $role->permissions->bitwise;
            }
        }

        $m->setPermissions(new RolePermissions((($bitwise & RolePermissions::ROLE_PERMISSIONS["administrator"]) !== 0) ? 2147483647 : $bitwise));
        $m->setRoles($roles);
        return $m;
    }

    static public function genModelUser(DiscordUser $user): User{
        return new User($user->id, $user->username, $user->discriminator, $user->avatar, $user->bot??false,
            $user->public_flags??0);
    }

    static public function genModelServer(DiscordServer $discordServer): Server{
        return new Server($discordServer->id, $discordServer->name, $discordServer->region, $discordServer->owner_id,
            $discordServer->large, $discordServer->member_count, $discordServer->icon);
    }

    /**
     * @template T of ServerChannel
     * @param DiscordChannel $dc
     * @param T $c
     * @return T
     */
    static private function applyPermissionOverwrites(DiscordChannel $dc, $c){
        /** @var DiscordOverwrite $overwrite */
        foreach($dc->overwrites as $overwrite){
            $allowed = new ChannelPermissions($overwrite->allow->bitwise);
            $denied = new ChannelPermissions($overwrite->deny->bitwise);
            if($overwrite->type === DiscordOverwrite::TYPE_MEMBER){
                $c->setAllowedMemberPermissions($overwrite->id, $allowed);
                $c->setDeniedMemberPermissions($overwrite->id, $denied);
            }elseif($overwrite->type === DiscordOverwrite::TYPE_ROLE){
                $c->setAllowedRolePermissions($overwrite->id, $allowed);
                $c->setDeniedRolePermissions($overwrite->id, $denied);
            }else{
                throw new AssertionError("Overwrite type unknown ? ({$overwrite->type})");
            }
        }
        return $c;
    }

    /**
     * Generates a model based on whatever type $channel is. (Excludes game store/group type)
     * @param DiscordChannel $channel
     * @return ?ServerChannel Null if type is invalid/unused.
     */
    static public function genModelChannel(DiscordChannel $channel): ?ServerChannel{
        switch($channel->type){
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

    static public function genModelCategoryChannel(DiscordChannel $discordChannel): CategoryChannel{
        if($discordChannel->type !== DiscordChannel::TYPE_CATEGORY){
            throw new AssertionError("Discord channel type must be `category` to generate model category channel.");
        }
        if($discordChannel->guild_id === null){
            throw new AssertionError("Guild ID must be present.");
        }
        return self::applyPermissionOverwrites($discordChannel, new CategoryChannel($discordChannel->name, $discordChannel->position,
            $discordChannel->guild_id, $discordChannel->id));
    }

    static public function genModelVoiceChannel(DiscordChannel $discordChannel): VoiceChannel{
        if($discordChannel->type !== DiscordChannel::TYPE_VOICE){
            throw new AssertionError("Discord channel type must be `voice` to generate model voice channel.");
        }
        if($discordChannel->guild_id === null){
            throw new AssertionError("Guild ID must be present.");
        }
        $ids = array_map(function($id) use($discordChannel){
            return $discordChannel->guild->id.".$id";
        }, array_keys($discordChannel->members->toArray()));
        return self::applyPermissionOverwrites($discordChannel, new VoiceChannel($discordChannel->bitrate, $discordChannel->user_limit,
            $discordChannel->name, $discordChannel->position, $discordChannel->guild_id, $ids,
            $discordChannel->parent_id, $discordChannel->id));
    }

    static public function genModelTextChannel(DiscordChannel $discordChannel): TextChannel{
        if($discordChannel->type !== DiscordChannel::TYPE_TEXT and $discordChannel->type !== DiscordChannel::TYPE_NEWS){
            throw new AssertionError("Discord channel type must be `text|news` to generate model text channel.");
        }
        if($discordChannel->guild_id === null){
            throw new AssertionError("Guild ID must be present.");
        }
        return self::applyPermissionOverwrites($discordChannel, new TextChannel($discordChannel->topic??"", $discordChannel->name,
            $discordChannel->position, $discordChannel->guild_id, $discordChannel->nsfw??false, $discordChannel->rate_limit_per_user,
            $discordChannel->parent_id, $discordChannel->id));
    }

    static public function genModelMessage(DiscordMessage $discordMessage): Message{
        if($discordMessage->author === null){
            throw new AssertionError("Discord message does not have a author, cannot generate model message.");
        }
        $attachments = [];
        foreach($discordMessage->attachments as $attachment){
            $attachments[] = self::genModelAttachment($attachment);
        }
        $guild_id = $discordMessage->guild_id??($discordMessage->author instanceof DiscordMember ? $discordMessage->author->guild_id : null);
        if($discordMessage->type === DiscordMessage::TYPE_NORMAL){
            if($discordMessage->webhook_id === null){
                $e = $discordMessage->embeds->first();
                if($e !== null){
                    $e = self::genModelEmbed($e);
                }
                $author = $guild_id === null ? $discordMessage->author->id : $guild_id.".".$discordMessage->author->id;
                return new Message($discordMessage->channel_id, $discordMessage->id, $discordMessage->content, $e,
                    $author, $guild_id, $discordMessage->timestamp->getTimestamp(), $attachments, $discordMessage->mention_everyone,
                    array_keys($discordMessage->mentions->toArray()), array_keys($discordMessage->mention_roles->toArray()),
                    array_keys($discordMessage->mention_channels->toArray()));
            }else{
                $embeds = [];
                foreach($discordMessage->embeds as $embed){
                    $embeds[] = self::genModelEmbed($embed);
                }
                $author = $guild_id === null ? $discordMessage->author->id : $guild_id.".".$discordMessage->author->id;
                return new WebhookMessage($discordMessage->channel_id, $discordMessage->webhook_id, $embeds, $discordMessage->id,
                    $discordMessage->content, $author, $guild_id, $discordMessage->timestamp->getTimestamp(), $attachments,
                    $discordMessage->mention_everyone, array_keys($discordMessage->mentions->toArray()),
                    array_keys($discordMessage->mention_roles->toArray()), array_keys($discordMessage->mention_channels->toArray()));
            }
        }elseif($discordMessage->type === DiscordMessage::TYPE_REPLY){
            if($discordMessage->referenced_message === null){
                throw new AssertionError("No referenced message on a REPLY message.");
            }
            $e = $discordMessage->embeds->first();
            if($e !== null){
                $e = self::genModelEmbed($e);
            }
            $author = $guild_id === null ? $discordMessage->author->id : $guild_id.".".$discordMessage->author->id;
            return new ReplyMessage($discordMessage->channel_id, $discordMessage->referenced_message->id, $discordMessage->id,
                $discordMessage->content, $e, $author, $guild_id, $discordMessage->timestamp->getTimestamp(), $attachments,
                $discordMessage->mention_everyone, array_keys($discordMessage->mentions->toArray()),
                array_keys($discordMessage->mention_roles->toArray()), array_keys($discordMessage->mention_channels->toArray()));
        }
        throw new AssertionError("Discord message type not supported.");
    }

    static public function genModelAttachment(\stdClass $attachment): Attachment{
        return new Attachment($attachment->id, $attachment->filename, $attachment->content_type, $attachment->size,
            $attachment->url, $attachment->width??null, $attachment->height??null);
    }

    static public function genModelEmbed(DiscordEmbed $discordEmbed): Embed{
        $fields = [];
        foreach(array_values($discordEmbed->fields->toArray()) as $field){
            $fields[] = self::genModelEmbedField($field);
        }
        return new Embed($discordEmbed->title, $discordEmbed->type, $discordEmbed->description, $discordEmbed->url,
            $discordEmbed->timestamp instanceof Carbon ? $discordEmbed->timestamp->getTimestamp() : (int)$discordEmbed->timestamp,
            $discordEmbed->color, $discordEmbed->footer === null ? new Footer() : self::genModelEmbedFooter($discordEmbed->footer),
            $discordEmbed->image === null ? new Image() : self::genModelEmbedImage($discordEmbed->image),
            $discordEmbed->thumbnail === null ? new Image() : self::genModelEmbedImage($discordEmbed->thumbnail),
            $discordEmbed->video === null ? new Video() : self::genModelEmbedVideo($discordEmbed->video),
            $discordEmbed->author === null ? new Author() : self::genModelEmbedAuthor($discordEmbed->author),
            $fields);
    }

    static public function genModelEmbedFooter(DiscordFooter $footer): Footer{
        return new Footer($footer->text, $footer->icon_url);
    }

    static public function genModelEmbedImage(DiscordImage $image): Image{
        return new Image($image->url, $image->width, $image->height);
    }

    static public function genModelEmbedVideo(DiscordVideo $video): Video{
        return new Video($video->url, $video->width, $video->height);
    }

    static public function genModelEmbedAuthor(DiscordAuthor $author): Author{
        return new Author($author->name, $author->url, $author->icon_url);
    }

    static public function genModelEmbedField(DiscordField $field): Field{
        return new Field($field->name, $field->value, $field->inline);
    }

    static public function genModelRolePermission(DiscordRolePermission $rolePermission): RolePermissions{
        return new RolePermissions($rolePermission->bitwise);
    }

    static public function genModelRole(DiscordRole $discordRole): Role{
        return new Role($discordRole->name, $discordRole->color, $discordRole->hoist, $discordRole->position, $discordRole->mentionable,
            $discordRole->guild_id, self::genModelRolePermission($discordRole->permissions), $discordRole->id);
    }

    static public function genModelInvite(DiscordInvite $invite): Invite{
        return new Invite($invite->guild_id, $invite->channel_id, $invite->max_age, $invite->max_uses, $invite->temporary,
        $invite->code, $invite->created_at->getTimestamp(), $invite->guild_id.".".$invite->inviter->id, $invite->uses);
    }

    static public function genModelBan(DiscordBan $ban): Ban{
        return new Ban($ban->guild_id, $ban->user_id, $ban->reason);
    }
}