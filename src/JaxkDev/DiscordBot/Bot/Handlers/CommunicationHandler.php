<?php

/** @noinspection PhpUnhandledExceptionInspection */

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

namespace JaxkDev\DiscordBot\Bot\Handlers;

use Carbon\Carbon;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Channel as DiscordChannel;
use Discord\Parts\Thread\Thread as DiscordThread;
use Discord\Parts\Channel\Message as DiscordMessage;
use Discord\Parts\Channel\Overwrite as DiscordOverwrite;
use Discord\Parts\Channel\Webhook as DiscordWebhook;
use Discord\Parts\Embed\Embed as DiscordEmbed;
use Discord\Parts\Guild\Emoji as DiscordEmoji;
use Discord\Parts\Channel\Reaction as DiscordReaction;
use Discord\Parts\Guild\GuildTemplate as DiscordTemplate;
use Discord\Parts\Channel\StageInstance as DiscordStage;
use Discord\Parts\Interactions\Interaction as DiscordInteraction;
use Discord\Parts\Guild\Guild as DiscordGuild;
use Discord\Parts\Guild\Widget as DiscordWidget;
use Discord\Parts\Guild\WelcomeScreen as DiscordWelcomeScreen;
use Discord\Parts\Guild\Invite as DiscordInvite;
use Discord\Parts\Guild\Role as DiscordRole;
use Discord\Parts\User\Activity as DiscordActivity;
use Discord\Parts\Guild\AuditLog\AuditLog as DiscordAuditLog;
use Discord\Parts\User\Member as DiscordMember;
use Discord\Parts\User\User as DiscordUser;
use Discord\Parts\Guild\ScheduledEvent as DiscordScheduledEvent;
use Discord\Repository\Channel\WebhookRepository as DiscordWebhookRepository;
use Discord\Repository\Guild\InviteRepository as DiscordInviteRepository;
use Discord\Repository\Guild\GuildCommandRepository as DiscordGuildCommandRepository;
use Discord\Repository\Interaction\GlobalCommandRepository as DiscordGlobalCommandRepository;
use Discord\Parts\Guild\Sticker as DiscordSticker;
use Discord\Parts\OAuth\Application as DiscordApplication;
use Discord\Parts\Interactions\Command\Command as DiscordCommand;
use Discord\Parts\Interactions\Command\Option as DiscordCommandOption;
use Discord\Parts\Interactions\Command\Choice as DiscordChoice;
use Discord\Parts\Interactions\Command\Overwrite as DiscordCommandOverwrite;
use Discord\Parts\Interactions\Command\Permission as DiscordCommandPermission;
use Discord\Parts\Interactions\Request\InteractionData as DiscordInteractData;
use Discord\Parts\Interactions\Request\Option as DiscordInteractDataOption;
use Discord\Parts\Interactions\Request\Resolved as DiscordResolved;

use Discord\Builders\MessageBuilder;
use JaxkDev\DiscordBot\Bot\Client;
use JaxkDev\DiscordBot\Bot\ModelConverter;
use JaxkDev\DiscordBot\Communication\BotThread;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestAddReaction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestBroadcastTyping;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateRole;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateWebhook;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDeleteChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDeleteMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDeleteRole;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDeleteWebhook;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestEditMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestAddRole;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchPinnedMessages;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchWebhooks;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestInitialiseBan;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestInitialiseInvite;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestKickMember;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestLeaveServer;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestPinMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestRemoveAllReactions;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestRemoveReaction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestRemoveRole;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestRevokeBan;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestRevokeInvite;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestSendFile;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestSendMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdatePresence;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUnpinMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateNickname;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateRole;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateWebhook;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestMessageBulkDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadCreate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadUpdate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCrossPostMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadMessageCreate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestJoinVoiceChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestMoveVoiceChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestLeaveVoiceChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestMoveMember;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestMuteMember;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUnmuteMember;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestServerTransfer;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestServerAuditLog;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestSearchMembers;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestModifyInteraction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateInteraction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDelayReply;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDelayDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStickerUpdate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStageCreate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStageUpdate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStageDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestEmojiUpdate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestTemplateCreate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestTemplateUpdate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestTemplateDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestScheduleCreate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestScheduleUpdate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestScheduleDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchReaction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateReaction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateReaction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDeleteReaction;
use JaxkDev\DiscordBot\Communication\Packets\Resolution;
use JaxkDev\DiscordBot\Communication\Packets\Heartbeat;
use JaxkDev\DiscordBot\Communication\Packets\Packet;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestAcceptInvite;
use JaxkDev\DiscordBot\Models\Channels\CategoryChannel;
use JaxkDev\DiscordBot\Models\Channels\TextChannel;
use JaxkDev\DiscordBot\Models\Channels\VoiceChannel;
use JaxkDev\DiscordBot\Models\User\Member;
use JaxkDev\DiscordBot\Models\Channels\Messages\Reply;

use JaxkDev\DiscordBot\Models\Server\Role;
use JaxkDev\DiscordBot\Plugin\ApiRejection;
use Monolog\Logger;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use function React\Promise\reject;

use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateServerFromTemplate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchWelcomeScreen;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestTimedOutMember;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateWelcomeScreen;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateDMChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateDMChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDeleteDMChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateCommand;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateCommand;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDeleteCommand;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchCommands;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStickerCreate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStickerDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestRespondInteraction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadAchive;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadJoin;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadLeave;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadUnachive;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFollowupMessage;
use JaxkDev\DiscordBot\Models\Channels\Messages\Webhook as WebhookMessage;
use JaxkDev\DiscordBot\Plugin\Utils;
use Discord\WebSockets\Event;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestBeginPrune;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchPrune;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchWidgetSettings;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestModifyWidget;

class CommunicationHandler
{

    /** @var Client */
    private $client;

    /** @var float|null */
    private $lastHeartbeat = null;

    /** @var Logger */
    private $logger;

    /**
     * Listener for when the interaction is called.
     *
     * @var callable|null
     */
    private $listener;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->logger = $client->getLogger();
    }

    //--- Handlers:

    public function handle(Packet $pk): void
    {
        //Internals:
        if ($pk instanceof Heartbeat) {
            $this->lastHeartbeat = $pk->getHeartbeat();
            return;
        }

        //API Check:
        if ($this->client->getThread()->getStatus() !== BotThread::STATUS_READY) {
            $this->resolveRequest($pk->getUID(), false, "Thread not ready for API Requests.");
            return;
        }

        //API Packets:
        if ($pk instanceof RequestUpdateNickname) $this->handleUpdateNickname($pk);
        elseif ($pk instanceof RequestUpdatePresence) $this->handleUpdatePresence($pk);
        elseif ($pk instanceof RequestBroadcastTyping) $this->handleBroadcastTyping($pk);
        elseif ($pk instanceof RequestSendMessage) $this->handleSendMessage($pk);
        elseif ($pk instanceof RequestSendFile) $this->handleSendFile($pk);
        elseif ($pk instanceof RequestEditMessage) $this->handleEditMessage($pk);
        elseif ($pk instanceof RequestAddReaction) $this->handleAddReaction($pk);
        elseif ($pk instanceof RequestRemoveReaction) $this->handleRemoveReaction($pk);
        elseif ($pk instanceof RequestRemoveAllReactions) $this->handleRemoveAllReactions($pk);
        elseif ($pk instanceof RequestDeleteMessage) $this->handleDeleteMessage($pk);
        elseif ($pk instanceof RequestFetchMessage) $this->handleFetchMessage($pk);
        elseif ($pk instanceof RequestFetchPinnedMessages) $this->handleFetchPinnedMessages($pk);
        elseif ($pk instanceof RequestFetchWebhooks) $this->handleFetchWebhooks($pk);
        elseif ($pk instanceof RequestPinMessage) $this->handlePinMessage($pk);
        elseif ($pk instanceof RequestUnpinMessage) $this->handleUnpinMessage($pk);
        elseif ($pk instanceof RequestAddRole) $this->handleAddRole($pk);
        elseif ($pk instanceof RequestRemoveRole) $this->handleRemoveRole($pk);
        elseif ($pk instanceof RequestCreateRole) $this->handleCreateRole($pk);
        elseif ($pk instanceof RequestUpdateRole) $this->handleUpdateRole($pk);
        elseif ($pk instanceof RequestDeleteRole) $this->handleDeleteRole($pk);
        elseif ($pk instanceof RequestKickMember) $this->handleKickMember($pk);
        elseif ($pk instanceof RequestInitialiseInvite) $this->handleInitialiseInvite($pk);
        elseif ($pk instanceof RequestRevokeInvite) $this->handleRevokeInvite($pk);
        elseif ($pk instanceof RequestCreateChannel) $this->handleCreateChannel($pk);
        elseif ($pk instanceof RequestUpdateChannel) $this->handleUpdateChannel($pk);
        elseif ($pk instanceof RequestDeleteChannel) $this->handleDeleteChannel($pk);
        elseif ($pk instanceof RequestInitialiseBan) $this->handleInitialiseBan($pk);
        elseif ($pk instanceof RequestRevokeBan) $this->handleRevokeBan($pk);
        elseif ($pk instanceof RequestCreateWebhook) $this->handleCreateWebhook($pk);
        elseif ($pk instanceof RequestUpdateWebhook) $this->handleUpdateWebhook($pk);
        elseif ($pk instanceof RequestDeleteWebhook) $this->handleDeleteWebhook($pk);
        elseif ($pk instanceof RequestLeaveServer) $this->handleLeaveServer($pk);
        elseif ($pk instanceof RequestMessageBulkDelete) $this->handleBulkDelete($pk);
        elseif ($pk instanceof RequestCrossPostMessage) $this->handleCrossPost($pk);
        elseif ($pk instanceof RequestThreadCreate) $this->handleChannelStartThread($pk);
        elseif ($pk instanceof RequestThreadUpdate) $this->handleThreadUpdate($pk);
        elseif ($pk instanceof RequestThreadDelete) $this->handleThreadDelete($pk);
        elseif ($pk instanceof RequestThreadMessageCreate) $this->handleMessageStartThread($pk);
        elseif ($pk instanceof RequestJoinVoiceChannel) $this->handleVoiceChannelJoin($pk);
        elseif ($pk instanceof RequestMoveVoiceChannel) $this->handleVoiceChannelMove($pk);
        elseif ($pk instanceof RequestLeaveVoiceChannel) $this->handleVoiceChannelLeave($pk);
        elseif ($pk instanceof RequestMoveMember) $this->handleMoveMember($pk);
        elseif ($pk instanceof RequestMuteMember) $this->handleMuteMember($pk);
        elseif ($pk instanceof RequestUnmuteMember) $this->handleUnmuteMember($pk);
        elseif ($pk instanceof RequestServerTransfer) $this->handleServerTransfer($pk);
        elseif ($pk instanceof RequestServerAuditLog) $this->handleAuditLog($pk);
        elseif ($pk instanceof RequestSearchMembers) $this->handleSearchMembers($pk);
        elseif ($pk instanceof RequestModifyInteraction) $this->handleModifyInteraction($pk);
        elseif ($pk instanceof RequestCreateInteraction) $this->handleCreateInteraction($pk);
        elseif ($pk instanceof RequestDelayReply) $this->handleDelayReply($pk);
        elseif ($pk instanceof RequestDelayDelete) $this->handleDelayDelete($pk);
        elseif ($pk instanceof RequestStageCreate) $this->handleStageCreate($pk);
        elseif ($pk instanceof RequestStageUpdate) $this->handleStageUpdate($pk);
        elseif ($pk instanceof RequestStageDelete) $this->handleStageDelete($pk);
        elseif ($pk instanceof RequestEmojiUpdate) $this->handleServerEmojiUpdate($pk);
        elseif ($pk instanceof RequestTemplateCreate) $this->handleTemplateCreate($pk);
        elseif ($pk instanceof RequestTemplateUpdate) $this->handleTemplateUpdate($pk);
        elseif ($pk instanceof RequestTemplateDelete) $this->handleTemplateDelete($pk);
        elseif ($pk instanceof RequestScheduleCreate) $this->handleScheduleCreate($pk);
        elseif ($pk instanceof RequestScheduleUpdate) $this->handleScheduleUpdate($pk);
        elseif ($pk instanceof RequestScheduleDelete) $this->handleScheduleDelete($pk);
        elseif ($pk instanceof RequestFetchReaction) $this->handleFetchReaction($pk);
        elseif ($pk instanceof RequestCreateReaction) $this->handleCreateReaction($pk);
        elseif ($pk instanceof RequestUpdateReaction) $this->handleUpdateReaction($pk);
        elseif ($pk instanceof RequestDeleteReaction) $this->handleDeleteReaction($pk);
        elseif ($pk instanceof RequestCreateServerFromTemplate) $this->handleCreateServerFromTemplate($pk);
        elseif ($pk instanceof RequestFetchWelcomeScreen) $this->handleFetchWelcomeScreen($pk);
        elseif ($pk instanceof RequestUpdateWelcomeScreen) $this->handleUpdateWelcomeScreen($pk);
        elseif ($pk instanceof RequestTimedOutMember) $this->handleTimedOutMember($pk);
        elseif ($pk instanceof RequestStickerCreate) $this->handleStickerCreate($pk);
        elseif ($pk instanceof RequestStickerUpdate) $this->handleStickerUpdate($pk);
        elseif ($pk instanceof RequestStickerDelete) $this->handleStickerDelete($pk);
        elseif ($pk instanceof RequestCreateDMChannel) $this->handleCreateDMChannel($pk);
        elseif ($pk instanceof RequestUpdateDMChannel) $this->handleUpdateDMChannel($pk);
        elseif ($pk instanceof RequestDeleteDMChannel) $this->handleDeleteDMChannel($pk);
        elseif ($pk instanceof RequestCreateCommand) $this->handleCreateCommand($pk);
        elseif ($pk instanceof RequestUpdateCommand) $this->handleUpdateCommand($pk);
        elseif ($pk instanceof RequestDeleteCommand) $this->handleDeleteCommand($pk);
        elseif ($pk instanceof RequestFetchCommands) $this->handleFetchCommands($pk);
        elseif ($pk instanceof RequestRespondInteraction) $this->handleInteractionRespond($pk);
        elseif ($pk instanceof RequestFollowupMessage) $this->handleFollowupMessage($pk);
        elseif ($pk instanceof RequestThreadAchive) $this->handleThreadAchive($pk);
        elseif ($pk instanceof RequestThreadUnachive) $this->handleThreadUnachive($pk);
        elseif ($pk instanceof RequestThreadJoin) $this->handleThreadJoin($pk);
        elseif ($pk instanceof RequestThreadLeave) $this->handleThreadLeave($pk);
        elseif ($pk instanceof RequestAcceptInvite) $this->handleInviteAccept($pk);
        elseif ($pk instanceof RequestFetchPrune) $this->handleServerFetchPrune($pk);
        elseif ($pk instanceof RequestBeginPrune) $this->handleServerBeginPrune($pk);
        elseif ($pk instanceof RequestFetchWidgetSettings) $this->handleServerFetchWidget($pk);
        elseif ($pk instanceof RequestModifyWidget) $this->handleServerUpdateWidget($pk);
    }
    private function handleInteractionRespond(RequestRespondInteraction $pk): void
    {
        $interaction = $pk->getInteraction();
        $builder = $pk->getMessageBuilder() ?? MessageBuilder::new();
        $embed = $pk->getEmbed();
        $content = $pk->getContent();

        if ($embed) {
            $de = new DiscordEmbed($this->client->getDiscordClient());
            if ($embed->getType() !== null) $de->setType($embed->getType());
            if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
            if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
            if ($embed->getColour() !== null) $de->setColor($embed->getColour());
            if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
            if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
            if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
            if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
            if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
            if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
            foreach ($embed->getFields() as $f) {
                $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
            }
            $builder = $builder->setEmbeds([$de]);
        }
        $builder = $builder->setContent($content);

        /** @var DiscordInteraction $di */
        $di = $this->client->getDiscordClient()->getFactory()->create(DiscordInteraction::class, [], true);

        $di->id = $interaction->getId();
        $di->application_id = $interaction->getApplicationID();
        $di->type = $interaction->getType();
        $data = new DiscordInteractData($this->client->getDiscordClient());
        $data->id = $interaction->getInteractionData()->getId();
        $data->name = $interaction->getInteractionData()->getName();
        // if($interaction->getInteractionData()->getType() !== null){
        $data->type = $interaction->getInteractionData()->getType();
        $data->component_type = $interaction->getInteractionData()->getComponentType();

        /** @var DiscordInteractDataOption[] $options */
        $options = [];
        if ($interaction->getInteractionData()->getOptions() !== null) {
            foreach ($interaction->getInteractionData()->getOptions() as $option) {
                $opt = new DiscordInteractDataOption($this->client->getDiscordClient());
                $opt->name = $option->getName();
                $opt->type = $option->getType();
                $opt->value = $option->getValue();
                $opt->focused = $option->isFocused();
                $options[] = $opt;
            }
        }
        $data->options = $options;
        $data->custom_id = $interaction->getInteractionData()->getCustomId();
        $data->values = $interaction->getInteractionData()->getSelected();
        $data->target_id = $interaction->getInteractionData()->getTargetId();
        $data->guild_id = $interaction->getInteractionData()->getServerId();
        $resolved = new DiscordResolved($this->client->getDiscordClient());
        $resolvedModel = $interaction->getInteractionData()->getResolved();
        $di->channel_id = $interaction->getChannelId();
        $di->token = $interaction->getToken();
        $di->version = $interaction->getVersion();
        if ($interaction->getServerId()) {
            $this->getServer($pk, $interaction->getServerId(), function (DiscordGuild $guild) use ($builder, $resolvedModel, $resolved, $di, $pk, $interaction) {
                if ($resolvedModel) {

                    $users = [];
                    /** @var DiscordUser $user */
                    foreach ($this->client->getDiscordClient()->users as $user) {
                        if (isset($resolvedModel->getUsers()[$user->id])) {
                            $users[$user->id][] = $user;
                        }
                    }

                    $channels = [];
                    /** @var DiscordChannel $channel */
                    foreach ($guild->channels as $channel) {
                        if (isset($resolvedModel->getChannels()[$channel->id])) {
                            $channels[$channel->id][] = $channel;
                        }
                    }
                    $members = [];
                    /** @var DiscordMember $member */
                    foreach ($guild->members as $member) {
                        if (isset($resolvedModel->getMembers()[$member->id])) {
                            $members[$member->id][] = $member;
                        }
                    }
                    $roles = [];
                    /** @var DiscordRole $role */
                    foreach ($guild->roles as $role) {
                        if (isset($resolvedModel->getRoles()[$role->id])) {
                            $roles[$role->id][] = $role;
                        }
                    }
                    $resolved->members = $members;
                    $resolved->users = $users;
                    $resolved->channels = $channels;
                    $resolved->roles = $roles;
                    $resolved->guild_id = $resolvedModel->getServerId();
                }

                $di->guild_id = $interaction->getServerId();
                $di->guild = $guild;
                /** @var DiscordChannel $channel */
                foreach ($guild->channels as $channel) {
                    if ($channel->id === $interaction->getChannelId()) {
                        $di->channel = $channel;
                    }
                }
                /** @var DiscordMember $member */
                foreach ($guild->members as $member) {
                    if ($interaction->getMember()) {
                        if ($member->id === $interaction->getMember()->getUserId()) {
                            $di->member = $member;
                        }
                    }
                }
                /** @var DiscordUser $user */
                foreach ($this->client->getDiscordClient()->users as $user) {
                    if ($interaction->getUser()) {
                        if ($member->id === $interaction->getUser()->getId()) {
                            $di->user = $user;
                        }
                    }
                }
                if ($interaction->getType() === 4) {
                    return; //todo somewhat implement autocomplete support to Responding to an interaction.
                    //for now, do not respond to auto complete interactions.
                }
                if ($interaction->getType() === 3) {
                    $di->acknowledge()->then(function () use ($builder, $di, $pk) {
                        $di->updateMessage($builder);
                    });
                } else {
                    $di->acknowledgeWithResponse($pk->isEphemeral())->then(function () use ($builder, $di, $pk) {
                        $di->updateOriginalResponse($builder)->then(function (DiscordMessage $message) use ($pk) {
                            $this->resolveRequest($pk->getUID(), true, "Successfully executed new Interaction class.", [ModelConverter::genModelMessage($message)]);
                        }, function (\Throwable $e) use ($pk) {
                            $this->resolveRequest($pk->getUID(), false, "Failed to execute new interaction class.", [$e->getMessage(), $e->getTraceAsString()]);
                        });
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to acknowledge new Interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                }
            });
        } else {
            if ($resolvedModel) {
                $users = [];
                /** @var DiscordUser $user */
                foreach ($this->client->getDiscordClient()->users as $user) {
                    if (isset($resolvedModel->getUsers()[$user->id])) {
                        $users[$user->id][] = $user;
                    }
                }

                $channels = [];
                /** @var DiscordChannel $channel */
                foreach ($this->client->getDiscordClient()->private_channels as $channel) {
                    if (isset($resolvedModel->getChannels()[$channel->id])) {
                        $channels[$channel->id][] = $channel;
                    }
                }

                $resolved->users = $users;
                $resolved->channels = $channels;
            }
            /** @var DiscordChannel $channel */
            foreach ($this->client->getDiscordClient()->private_channels as $channel) {
                if ($channel->id === $interaction->getChannelId()) {
                    $di->channel = $channel;
                }
            }

            if ($interaction->getType() === 3) {
                $di->acknowledge()->then(function () use ($builder, $di, $pk) {
                    $di->updateMessage($builder);
                });
            } else {
                $di->acknowledgeWithResponse($pk->isEphemeral())->then(function () use ($builder, $di, $pk) {
                    print_r("Ackknowledged new interaction class.");
                    $di->updateOriginalResponse($builder)->then(function (DiscordMessage $message) use ($pk) {
                        $this->resolveRequest($pk->getUID(), true, "Successfully executed new Interaction class.", [ModelConverter::genModelMessage($message)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to execute new interaction class.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to acknowledge new Interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                });
            }
        }
    }
    /**
     * Sets the callable listener for the button. The `$callback` will be called when the button
     * is pressed.
     *
     * If you do not respond to or acknowledge the `Interaction`, it will be acknowledged for you.
     * Note that if you intend to respond to or acknowledge the interaction inside a promise, you should
     * return a promise that resolves *after* you respond or acknowledge.
     *
     * The callback will only be called once with the `$oneOff` parameter set to true.
     * This can be changed to false, and the callback will be called each time the button is pressed.
     * To remove the listener, you can pass `$callback` as null.
     *
     * The button listener will not persist when the bot restarts.
     *
     * @param callable $callback Callback to call when the button is pressed. Will be called with the interaction object.
     * @param bool     $oneOff   Whether the listener should be removed after the button is pressed for the first time.
     *
     * @throws \LogicException
     * 
     */
    public function setListener(?callable $callback, bool $ephemeral = false, bool $oneOff = false)
    {
        $discord = $this->client->getDiscordClient();
        // Remove any existing listener
        if ($this->listener) {
            $discord->removeListener(Event::INTERACTION_CREATE, $this->listener);
        }

        if ($callback == null) {
            return $this;
        }


        $this->listener = function (DiscordInteraction $interaction) use ($callback, $ephemeral, $oneOff) {

            $response = $callback($interaction);
            $ack = function () use ($interaction, $ephemeral) {
                // attempt to acknowledge interaction if it has not already been responded to.
                try {
                    $interaction->acknowledge($ephemeral);
                } catch (\Exception $e) {
                }
            };

            if ($response instanceof PromiseInterface) {
                $response->then($ack);
            } else {
                $ack();
            }

            if ($oneOff) {
                $this->removeListener();
            }
        };

        $discord->on(Event::INTERACTION_CREATE, $this->listener);
    }

    /**
     * Removes the listener from the button.
     *
     * @return $this
     */
    public function removeListener()
    {
        $this->setListener(null);
    }
    private function handleFollowupMessage(RequestFollowupMessage $pk): void
    {
        $interaction = $pk->getInteraction();
        $builder = $pk->getMessageBuilder() ?? MessageBuilder::new();
        $embed = $pk->getEmbed();
        $content = $pk->getContent();

        if ($embed) {
            $de = new DiscordEmbed($this->client->getDiscordClient());
            if ($embed->getType() !== null) $de->setType($embed->getType());
            if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
            if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
            if ($embed->getColour() !== null) $de->setColor($embed->getColour());
            if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
            if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
            if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
            if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
            if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
            if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
            foreach ($embed->getFields() as $f) {
                $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
            }
            $builder = $builder->setEmbeds([$de]);
        }
        $builder = $builder->setContent($content);

        /** @var DiscordInteraction $di */
        $di = $this->client->getDiscordClient()->getFactory()->create(DiscordInteraction::class, [], true);

        $di->id = $interaction->getId();
        $di->application_id = $interaction->getApplicationID();
        $di->type = $interaction->getType();
        $data = new DiscordInteractData($this->client->getDiscordClient());
        $data->id = $interaction->getInteractionData()->getId();
        $data->name = $interaction->getInteractionData()->getName();
        // if($interaction->getInteractionData()->getType() !== null){
        $data->type = $interaction->getInteractionData()->getType();
        $data->component_type = $interaction->getInteractionData()->getComponentType();

        /** @var DiscordInteractDataOption[] $options */
        $options = [];
        if ($interaction->getInteractionData()->getOptions() !== null) {
            foreach ($interaction->getInteractionData()->getOptions() as $option) {
                $opt = new DiscordInteractDataOption($this->client->getDiscordClient());
                $opt->name = $option->getName();
                $opt->type = $option->getType();
                $opt->value = $option->getValue();
                $opt->focused = $option->isFocused();
                $options[] = $opt;
            }
        }
        $data->options = $options;
        $data->custom_id = $interaction->getInteractionData()->getCustomId();
        $data->values = $interaction->getInteractionData()->getSelected();
        $data->target_id = $interaction->getInteractionData()->getTargetId();
        $data->guild_id = $interaction->getInteractionData()->getServerId();
        $resolved = new DiscordResolved($this->client->getDiscordClient());
        $resolvedModel = $interaction->getInteractionData()->getResolved();
        $di->channel_id = $interaction->getChannelId();
        $di->token = $interaction->getToken();
        $di->version = $interaction->getVersion();
        if ($interaction->getServerId()) {
            $this->getServer($pk, $interaction->getServerId(), function (DiscordGuild $guild) use ($builder, $resolvedModel, $resolved, $di, $pk, $interaction) {
                if ($resolvedModel) {

                    $users = [];
                    /** @var DiscordUser $user */
                    foreach ($this->client->getDiscordClient()->users as $user) {
                        if (isset($resolvedModel->getUsers()[$user->id])) {
                            $users[$user->id][] = $user;
                        }
                    }

                    $channels = [];
                    /** @var DiscordChannel $channel */
                    foreach ($guild->channels as $channel) {
                        if (isset($resolvedModel->getChannels()[$channel->id])) {
                            $channels[$channel->id][] = $channel;
                        }
                    }
                    $members = [];
                    /** @var DiscordMember $member */
                    foreach ($guild->members as $member) {
                        if (isset($resolvedModel->getMembers()[$member->id])) {
                            $members[$member->id][] = $member;
                        }
                    }
                    $roles = [];
                    /** @var DiscordRole $role */
                    foreach ($guild->roles as $role) {
                        if (isset($resolvedModel->getRoles()[$role->id])) {
                            $roles[$role->id][] = $role;
                        }
                    }
                    $resolved->members = $members;
                    $resolved->users = $users;
                    $resolved->channels = $channels;
                    $resolved->roles = $roles;
                    $resolved->guild_id = $resolvedModel->getServerId();
                }

                $di->guild_id = $interaction->getServerId();
                $di->guild = $guild;
                /** @var DiscordChannel $channel */
                foreach ($guild->channels as $channel) {
                    if ($channel->id === $interaction->getChannelId()) {
                        $di->channel = $channel;
                    }
                }
                /** @var DiscordMember $member */
                foreach ($guild->members as $member) {
                    if ($interaction->getMember()) {
                        if ($member->id === $interaction->getMember()->getUserId()) {
                            $di->member = $member;
                        }
                    }
                }
                /** @var DiscordUser $user */
                foreach ($this->client->getDiscordClient()->users as $user) {
                    if ($interaction->getUser()) {
                        if ($member->id === $interaction->getUser()->getId()) {
                            $di->user = $user;
                        }
                    }
                }
                if ($interaction->getType() === 4) {
                    return; //todo somewhat implement autocomplete support to Responding to an interaction.
                    //for now, do not respond to auto complete interactions.
                }

                //   if($interaction->getType() === 3){
                $this->setListener(function (DiscordInteraction $interaction) use ($builder, $pk) {
                    if ($interaction->type === 3) {

                        $this->logger->info("Listened to Interaction.");
                        try {

                            $interaction->acknowledge($pk->isEphemeral())->then(function () use ($interaction, $pk, $builder) {
                                $interaction->sendFollowupMessage($builder, $pk->isEphemeral())->then(function (DiscordMessage $message) use ($pk) {
                                    $this->resolveRequest($pk->getUID(), true, "Sent followup message.", [ModelConverter::genModelMessage($message)]);
                                    $this->client->getLogger()->info("Sent followup message.");
                                }, function (\Throwable $e) use ($pk) {
                                    $this->client->getLogger()->info("Failed to send Followup message. {$e->getMessage()}");
                                    $this->resolveRequest($pk->getUID(), false, "Failed to send followup message.", [$e->getMessage(), $e->getTraceAsString()]);
                                });
                            });
                        } catch (\Throwable $e) {
                        }
                    }
                });
            });
        } else {
            if ($resolvedModel) {
                $users = [];
                /** @var DiscordUser $user */
                foreach ($this->client->getDiscordClient()->users as $user) {
                    if (isset($resolvedModel->getUsers()[$user->id])) {
                        $users[$user->id][] = $user;
                    }
                }

                $channels = [];
                /** @var DiscordChannel $channel */
                foreach ($this->client->getDiscordClient()->private_channels as $channel) {
                    if (isset($resolvedModel->getChannels()[$channel->id])) {
                        $channels[$channel->id][] = $channel;
                    }
                }

                $resolved->users = $users;
                $resolved->channels = $channels;
            }
            /** @var DiscordChannel $channel */
            foreach ($this->client->getDiscordClient()->private_channels as $channel) {
                if ($channel->id === $interaction->getChannelId()) {
                    $di->channel = $channel;
                }
            }

            $this->setListener(function (DiscordInteraction $interaction) use ($builder, $pk) {
                if ($interaction->type === 3) {
                    $this->logger->info("Listened to Interaction.");
                    try {

                        $interaction->acknowledge($pk->isEphemeral())->then(function () use ($interaction, $pk, $builder) {
                            $interaction->sendFollowupMessage($builder, $pk->isEphemeral())->then(function (DiscordMessage $message) use ($interaction, $pk) {
                                $this->resolveRequest($pk->getUID(), true, "Sent followup message.", [ModelConverter::genModelMessage($message)]);
                                $this->client->getLogger()->info("Sent followup message.");
                                $interaction->responded = false;
                            }, function (\Throwable $e) use ($pk) {
                                $this->client->getLogger()->info("Failed to send Followup message. {$e->getMessage()}");
                                $this->resolveRequest($pk->getUID(), false, "Failed to send followup message.", [$e->getMessage(), $e->getTraceAsString()]);
                            });
                        });
                    } catch (\Throwable $e) {
                    }
                }
            });
        }
    }
    private function handleCreateCommand(RequestCreateCommand $pk): void
    {
        $command = $pk->getCommand();
        $permissions = $pk->getPermissions();
        $builder = MessageBuilder::new();
        try {
            $this->client->getDiscordClient()->listenCommand($command->getName(), function (DiscordInteraction $interaction) use ($command, $builder, $pk) {

                try {

                    /** @var DiscordChoice[] */
                    $choices = [];
                    foreach ($command->getOptions() as $option) {
                        foreach ($option->getChoices() as $choiceModel) {
                            $choice = new DiscordChoice($this->client->getDiscordClient());
                            $choice->name = $choiceModel->getName();
                            $choice->value = $choiceModel->getValue();
                            $choices[] = $choice;
                        }
                    }
                    if ($interaction->type === 4) {
                        $interaction->autoCompleteResult($choices)->then(function () use ($pk, $builder, $interaction) {
                            $this->resolveRequest($pk->getUID(), true, "Added Discord Interaction Model AutoComplete.", [ModelConverter::genModelInteraction($interaction)]);
                        });
                    } else {
                        $interaction->acknowledgeWithResponse()->then(function () use ($pk, $builder, $interaction) {

                            $this->resolveRequest($pk->getUID(), true, "Added Discord Interaction model.", [ModelConverter::genModelInteraction($interaction)]);
                        });
                    }
                } catch (\Throwable $e) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to add interaction model. {$e->getMessage()}", [$e->getMessage(), $e->getTraceAsString()]);
                }
                $this->resolveRequest($pk->getUID(), true, "Listened to command successfully!", [ModelConverter::genModelInteraction($interaction)]);
                $this->logger->info("Listened to command successfully!");
            });
        } catch (\Throwable $e) {
        }
        if ($command->getServerId()) {
            $this->getServer($pk, $command->getServerId(), function (DiscordGuild $guild) use ($permissions, $pk, $command) {
                /** @var DiscordCommand $c */
                $c = $guild->commands->create([
                    "name" => $command->getName()
                ]);
                $c->type = $command->getType();
                if ($command->getApplicationId()) {
                    $c->application_id = $command->getApplicationId();
                }
                if (!empty($command->getDescription())) {
                    $c->description = $command->getDescription();
                }
                if ($command->getServerId()) {
                    $c->guild_id = $command->getServerId();
                }
                $options = [];
                foreach ($command->getOptions() as $option) {

                    $do = new DiscordCommandOption($this->client->getDiscordClient());
                    $do = $do->setType($option->getType());
                    $do = $do->setName($option->getName());
                    $do = $do->setDescription($option->getDescription());
                    $do = $do->setRequired($option->isRequired());
                    $do = $do->setChannelTypes($option->getChannelTypes());
                    $do = $do->setAutoComplete($option->isAutoComplete());
                    foreach ($option->getChoices() as $choice) {
                        $ch = new DiscordChoice($this->client->getDiscordClient());
                        $ch = $ch->setName($choice->getName());
                        $ch = $ch->setValue($choice->getValue());
                        if ($do->type === $do::STRING || $do->type === $do::INTEGER || $do->type === $do::NUMBER) {
                            $do = $do->addChoice($ch);
                        }
                    }
                    $options[] = $do;
                    /** @var DiscordCommandOption[] $subs */
                    $subs = [];
                    foreach ($option->getSubOptions() as $sub) {
                        $do2 = new DiscordCommandOption($this->client->getDiscordClient());
                        $do2 = $do2->setType($sub->getType());
                        $do2 = $do2->setName($sub->getName());
                        $do2 = $do2->setDescription($sub->getDescription());
                        $do2 = $do2->setRequired($sub->isRequired());
                        $do2 = $do2->setChannelTypes($sub->getChannelTypes());
                        $do2 = $do2->setAutoComplete($sub->isAutoComplete());
                        foreach ($option->getChoices() as $choice) {
                            $ch = new DiscordChoice($this->client->getDiscordClient());
                            $ch = $ch->setName($choice->getName());
                            $ch = $ch->setValue($choice->getValue());
                            if ($do2->type === $do2::STRING || $do2->type === $do2::INTEGER || $do2->type === $do2::NUMBER) {
                                $do2 = $do2->addChoice($ch);
                            }
                        }
                        /** @var DiscordCommandOption[] $subs */
                        $subs[] = $do2;
                    }
                }
                $c->options = $options;

                /** @var DiscordCommandOption $subOption */
                foreach ($options as $subOption) {
                    $subOption->options = $subs;
                }
                $c->default_permission = $command->isDefaultPermission();

                $guild->commands->save($c)->then(function (DiscordCommand $command2) use ($permissions, $command, $pk) {

                    /** @var DiscordCommandPermission[] $perms */
                    $perms = [];
                    $overwrite = new DiscordCommandOverwrite($this->client->getDiscordClient());
                    $overwrite->id = $command2->id;
                    $overwrite->application_id = $command->getApplicationId();
                    $overwrite->guild_id = $command->getServerId();

                    foreach ($permissions as $permission) {
                        $perm = new DiscordCommandPermission($this->client->getDiscordClient());
                        $perm->id = $permission->getId();
                        $perm->type = $permission->getType();
                        $perm->permission = $permission->isAllowed();
                        $perms[] = $perm;
                    }
                    $overwrite->permissions = $perms;
                    $command2->setOverwrite($overwrite)->done(
                        function () use ($pk) {
                            $this->resolveRequest($pk->getUID(), true, "An Overwrite object has been created.");
                        },
                        function (\Throwable $e) use ($pk) {
                            $this->resolveRequest($pk->getUID(), false, "Failed to create overwrite object.", [$e->getMessage(), $e->getTraceAsString()]);
                        }
                    );

                    $this->resolveRequest($pk->getUID(), true, "Created Command with success!", [ModelConverter::genModelCommand($command2)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to create command.", [$e->getMessage(), $e->getTraceAsString()]);
                });
            });
        } else {
            $app = $this->getApplication();

            /** @var DiscordCommand $c */
            $c = $app->commands->create([
                "name" => $command->getName()
            ]);
            $c->type = $command->getType();
            if ($command->getApplicationId()) {
                $c->application_id = $command->getApplicationId();
            }
            if (!empty($command->getDescription())) {
                $c->description = $command->getDescription();
            }
            $options = [];
            foreach ($command->getOptions() as $option) {
                $do = new DiscordCommandOption($this->client->getDiscordClient());
                $do = $do->setType($option->getType());
                $do = $do->setName($option->getName());
                $do = $do->setDescription($option->getDescription());
                $do = $do->setRequired($option->isRequired());
                $do = $do->setChannelTypes($option->getChannelTypes());
                $do = $do->setAutoComplete($option->isAutoComplete());
                foreach ($option->getChoices() as $choice) {
                    $ch = new DiscordChoice($this->client->getDiscordClient());
                    $ch = $ch->setName($choice->getName());
                    $ch = $ch->setValue($choice->getValue());
                    if ($do->type === $do::STRING || $do->type === $do::INTEGER || $do->type === $do::NUMBER) {
                        $do = $do->addChoice($ch);
                    }
                }
                $options[] = $do;
                $subs = [];
                foreach ($option->getSubOptions() as $sub) {
                    $do2 = new DiscordCommandOption($this->client->getDiscordClient());
                    $do2 = $do2->setType($sub->getType());
                    $do2 = $do2->setName($sub->getName());
                    $do2 = $do2->setDescription($sub->getDescription());
                    $do2 = $do2->setRequired($sub->isRequired());
                    $do2 = $do2->setChannelTypes($sub->getChannelTypes());
                    $do2 = $do2->setAutoComplete($sub->isAutoComplete());
                    foreach ($option->getChoices() as $choice) {
                        $ch = new DiscordChoice($this->client->getDiscordClient());
                        $ch = $ch->setName($choice->getName());
                        $ch = $ch->setValue($choice->getValue());
                        if ($do2->type === $do2::STRING || $do2->type === $do2::INTEGER || $do2->type === $do2::NUMBER) {
                            $do2 = $do2->addChoice($ch);
                        }
                    }
                    /** @var DiscordCommandOption[] $sub */
                    $subs[] = $do2;
                }
            }
            $c->options = $options;

            /** @var DiscordCommandOption $subOption */
            foreach ($options as $subOption) {
                $subOption->options = $subs;
            }
            $c->default_permission = $command->isDefaultPermission();
            $app->commands->save($c)->then(function (DiscordCommand $command2) use ($permissions, $command, $pk) {
                /** @var DiscordCommandPermission[] $perms */
                $perms = [];
                $overwrite = new DiscordCommandOverwrite($this->client->getDiscordClient());
                $overwrite->id = $command2->id;
                $overwrite->application_id = $command->getApplicationId();
                $overwrite->guild_id = $command->getServerId();

                foreach ($permissions as $permission) {
                    $perm = new DiscordCommandPermission($this->client->getDiscordClient());
                    $perm->id = $permission->getId();
                    $perm->type = $permission->getType();
                    $perm->permission = $permission->isAllowed();
                    $perms[] = $perm;
                }
                $overwrite->permissions = $perms;
                $command2->setOverwrite($overwrite)->done(
                    function () use ($command2, $command, $pk) {
                        $this->resolveRequest($pk->getUID(), true, "An Overwrite object has been created.");
                    },
                    function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to create overwrite object.", [$e->getMessage(), $e->getTraceAsString()]);
                    }
                );
                $this->resolveRequest($pk->getUID(), true, "Created Command with success!", [ModelConverter::genModelCommand($command2)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create command.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        }
    }
    private function handleUpdateCommand(RequestUpdateCommand $pk): void
    {
        $command = $pk->getCommand();
        $builder = MessageBuilder::new();
        $permissions = $pk->getPermissions();
        if ($command->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Command ID must be present!");
            return;
        }
        $permissions = $pk->getPermissions();
        if ($command->getServerId()) {
            $this->getServer($pk, $command->getServerId(), function (DiscordGuild $guild) use ($pk, $permissions, $command) {
                $fetch = $guild->commands->fetch($command->getId());
                $fetch->then(function (DiscordCommand $c) use ($guild, $command, $permissions, $pk) {
                    $c->type = $command->getType();
                    if ($command->getApplicationId()) {
                        $c->application_id = $command->getApplicationId();
                    }
                    if (!empty($command->getDescription())) {
                        $c->description = $command->getDescription();
                    }
                    if ($command->getServerId()) {
                        $c->guild_id = $command->getServerId();
                    }
                    /** @var DiscordCommandOption[] $options */
                    $options = [];
                    foreach ($command->getOptions() as $option) {
                        $do = new DiscordCommandOption($this->client->getDiscordClient());
                        $do = $do->setType($option->getType());
                        $do = $do->setName($option->getName());
                        $do = $do->setDescription($option->getDescription());
                        $do = $do->setRequired($option->isRequired());
                        $do = $do->setChannelTypes($option->getChannelTypes());
                        $do = $do->setAutoComplete($option->isAutoComplete());
                        foreach ($option->getChoices() as $choice) {
                            $ch = new DiscordChoice($this->client->getDiscordClient());
                            $ch = $ch->setName($choice->getName());
                            $ch = $ch->setValue($choice->getValue());
                            if ($do->type === $do::STRING || $do->type === $do::INTEGER || $do->type === $do::NUMBER) {
                                $do = $do->addChoice($ch);
                            }
                        }
                        $options[] = $do;
                        $subs = [];
                        foreach ($option->getSubOptions() as $sub) {
                            $do2 = new DiscordCommandOption($this->client->getDiscordClient());
                            $do2 = $do2->setType($sub->getType());
                            $do2 = $do2->setName($sub->getName());
                            $do2 = $do2->setDescription($sub->getDescription());
                            $do2 = $do2->setRequired($sub->isRequired());
                            $do2 = $do2->setChannelTypes($sub->getChannelTypes());
                            $do2 = $do2->setAutoComplete($sub->isAutoComplete());
                            foreach ($sub->getChoices() as $choice) {
                                $ch = new DiscordChoice($this->client->getDiscordClient());
                                $ch = $ch->setName($choice->getName());
                                $ch = $ch->setValue($choice->getValue());
                                if ($do2->type === $do2::STRING || $do2->type === $do::INTEGER || $do2->type === $do2::NUMBER) {
                                    $do2 = $do2->addChoice($ch);
                                }
                            }
                            /** @var DiscordCommandOption[] $sub */
                            $sub[] = $do2;
                        }
                    }
                    $c->options = $options;

                    /** @var DiscordCommandOption $subOption */
                    foreach ($options as $subOption) {
                        $subOption->options = $sub;
                    }
                    $c->default_permission = $command->isDefaultPermission();
                    $guild->commands->save($c)->then(function (DiscordCommand $command2) use ($permissions, $command, $pk) {

                        /** @var DiscordCommandPermission[] $perms */
                        $perms = [];
                        $overwrite = new DiscordCommandOverwrite($this->client->getDiscordClient());
                        $overwrite->id = $command2->id;
                        $overwrite->application_id = $command->getApplicationId();
                        $overwrite->guild_id = $command->getServerId();

                        foreach ($permissions as $permission) {
                            $perm = new DiscordCommandPermission($this->client->getDiscordClient());
                            $perm->id = $permission->getId();
                            $perm->type = $permission->getType();
                            $perm->permission = $permission->isAllowed();
                            $perms[] = $perm;
                        }
                        $overwrite->permissions = $perms;
                        $command2->setOverwrite($overwrite)->done(
                            function () use ($pk) {
                                $this->resolveRequest($pk->getUID(), true, "An Overwrite object has been created.");
                            },
                            function (\Throwable $e) use ($pk) {
                                $this->resolveRequest($pk->getUID(), false, "Failed to create overwrite object.", [$e->getMessage(), $e->getTraceAsString()]);
                            }
                        );
                        $this->resolveRequest($pk->getUID(), true, "Updated Command with success!", [ModelConverter::genModelCommand($command2)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to create command.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                });
            });
        } else {
            $app = $this->getApplication();
            $fetch = $app->commands->fetch($command->getId());
            $fetch->then(function (DiscordCommand $c) use ($permissions, $app, $command, $pk) {
                $c->type = $command->getType();
                if ($command->getApplicationId()) {
                    $c->application_id = $command->getApplicationId();
                }
                if (!empty($command->getDescription())) {
                    $c->description = $command->getDescription();
                }
                $options = [];
                foreach ($command->getOptions() as $option) {
                    $do = new DiscordCommandOption($this->client->getDiscordClient());
                    $do = $do->setType($option->getType());
                    $do = $do->setName($option->getName());
                    $do = $do->setDescription($option->getDescription());
                    $do = $do->setRequired($option->isRequired());
                    $do = $do->setChannelTypes($option->getChannelTypes());
                    $do = $do->setAutoComplete($option->isAutoComplete());
                    foreach ($option->getChoices() as $choice) {
                        $ch = new DiscordChoice($this->client->getDiscordClient());
                        $ch = $ch->setName($choice->getName());
                        $ch = $ch->setValue($choice->getValue());
                        if ($do->type === $do::STRING || $do->type === $do::INTEGER || $do->type === $do::NUMBER) {
                            $do = $do->addChoice($ch);
                        }
                    }
                    $options[] = $do;
                    /** @var DiscordCommandOption[] $subs */
                    $subs = [];
                    foreach ($option->getSubOptions() as $sub) {
                        $do2 = new DiscordCommandOption($this->client->getDiscordClient());
                        $do2 = $do2->setType($sub->getType());
                        $do2 = $do2->setName($sub->getName());
                        $do2 = $do2->setDescription($sub->getDescription());
                        $do2 = $do2->setRequired($sub->isRequired());
                        $do2 = $do2->setChannelTypes($sub->getChannelTypes());
                        $do2 = $do2->setAutoComplete($sub->isAutoComplete());
                        foreach ($option->getChoices() as $choice) {
                            $ch = new DiscordChoice($this->client->getDiscordClient());
                            $ch = $ch->setName($choice->getName());
                            $ch = $ch->setValue($choice->getValue());
                            if ($do2->type === $do2::STRING || $do2->type === $do2::INTEGER || $do2->type === $do2::NUMBER) {
                                $do2 = $do2->addChoice($ch);
                            }
                        }
                        /** @var DiscordCommandOption[] $subs */
                        $subs[] = $do2;
                    }
                }
                $c->options = $options;

                /** @var DiscordCommandOption $subOption */
                foreach ($options as $subOption) {
                    $subOption->options = $subs;
                }
                $c->default_permission = $command->isDefaultPermission();
                $app->commands->save($c)->then(function (DiscordCommand $command2) use ($permissions, $command, $pk) {
                    /** @var DiscordCommandPermission[] $perms */
                    $perms = [];
                    $overwrite = new DiscordCommandOverwrite($this->client->getDiscordClient());
                    $overwrite->id = $command2->id;
                    $overwrite->application_id = $command->getApplicationId();
                    $overwrite->guild_id = $command->getServerId();

                    foreach ($permissions as $permission) {
                        $perm = new DiscordCommandPermission($this->client->getDiscordClient());
                        $perm->id = $permission->getId();
                        $perm->type = $permission->getType();
                        $perm->permission = $permission->isAllowed();
                        $perms[] = $perm;
                    }
                    $overwrite->permissions = $perms;
                    $command2->setOverwrite($overwrite)->done(
                        function () use ($pk) {
                            $this->resolveRequest($pk->getUID(), true, "An Overwrite object has been created.");
                        },
                        function (\Throwable $e) use ($pk) {
                            $this->resolveRequest($pk->getUID(), false, "Failed to create overwrite object.", [$e->getMessage(), $e->getTraceAsString()]);
                        }
                    );
                    $this->resolveRequest($pk->getUID(), true, "Created Command with success!", [ModelConverter::genModelCommand($command2)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to create command.", [$e->getMessage(), $e->getTraceAsString()]);
                });
            });
        }
    }
    private function handleDeleteCommand(RequestDeleteCommand $pk): void
    {
        if ($pk->getServerId()) {
            $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
                $guild->commands->fetch($pk->getId())->then(function (DiscordCommand $command) use ($guild, $pk) {
                    $guild->commands->delete($command)->then(function () use ($pk) {
                        $this->resolveRequest($pk->getUID(), true, "Guild Command deleted.");
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to delete Guild Command.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to delete Guild Command ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                });
            });
        } else {
            $this->getApplication()->commands->fetch($pk->getId())->then(function (DiscordCommand $command) use ($pk) {
                $this->getApplication()->commands->delete($command)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Command deleted.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to delete Command.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to delete Command ({$pk->getUID()}) - {$e->getMessage()}");
                });
            });
        }
    }
    private function handleFetchCommands(RequestFetchCommands $pk): void
    {
        if ($pk->getServerId()) {
            $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {

                $guild->commands->freshen()->then(function (DiscordGuildCommandRepository $repository) use ($pk) {
                    $commands = [];
                    /** @var DiscordCommand $command */
                    foreach ($repository->toArray() as $command) {
                        $commands[] = ModelConverter::genModelCommand($command);
                    }
                    $this->resolveRequest($pk->getUID(), true, "Fetched guild commands.", $commands);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to fetch guild commands.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to fetch guild commands ({$pk->getUID()}) - freshen error: {$e->getMessage()}");
                });
            });
        } else {
            $this->getApplication()->commands->freshen()->then(function (DiscordGlobalCommandRepository $repository) use ($pk) {
                $commands = [];
                /** @var DiscordCommand $command */
                foreach ($repository->toArray() as $command) {
                    $commands[] = ModelConverter::genModelCommand($command);
                }
                $this->resolveRequest($pk->getUID(), true, "Fetched global commands.", $commands);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch global commands.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to fetch global commands ({$pk->getUID()}) - freshen error: {$e->getMessage()}");
            });
        }
    }
    private function handleDelayReply(RequestDelayReply $pk): void
    {
        $message = $pk->getMessage();
        if ($message->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Message Id must be present!");
            return;
        }
        $this->getMessage($pk, $message->getChannelId(), $message->getId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($message, $pk) {
            $text = "";
            if ($message instanceof WebhookMessage) {
                $embed = $message->getEmbeds();
            } else {
                $embed = $message->getEmbed();
            }
            if (is_array($embed)) {
                foreach ($embed as $embed) {
                    if ($embed->getDescription() !== null) {
                        $text = $embed->getDescription();
                    }
                }
            } else {
                if (empty($message->getContent()) && $embed->getDescription() !== null) {
                    $text = $embed->getDescription();
                } else {
                    $text = $message->getContent();
                }
            }

            $msg->delayedReply($text, 1000 * $pk->getDelay())->then(function () use ($msg, $pk) {
                $this->resolveRequest($pk->getUID(), true, "Delayed reply with success!", [ModelConverter::genModelMessage($msg)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delay send message.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleDelayDelete(RequestDelayDelete $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($pk) {

            $msg->delayedDelete(1000 * $pk->getDelay())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Delayed delete with success!");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delay delete.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }



    private function handleDeleteWebhook(RequestDeleteWebhook $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), null, function (DiscordChannel $channel) use ($pk) {
            $channel->webhooks->fetch($pk->getWebhookId())->then(function (DiscordWebhook $webhook) use ($channel, $pk) {
                $channel->webhooks->delete($webhook)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID());
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to delete webhook.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to delete webhook ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delete webhook.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to delete webhook ({$pk->getUID()}) - fetch error: {$e->getMessage()}");
            });
        });
    }

    private function handleUpdateWebhook(RequestUpdateWebhook $pk): void
    {
        if ($pk->getWebhook()->getId() === null) {
            throw new \AssertionError("Webhook ID must be present.");
        }
        $this->getChannel($pk, $pk->getWebhook()->getChannelId(), null, function (DiscordChannel $channel) use ($pk) {
            $channel->webhooks->fetch($pk->getWebhook()->getId())->then(function (DiscordWebhook $webhook) use ($channel, $pk) {
                $webhook->name = $pk->getWebhook()->getName();
                $webhook->avatar = $pk->getWebhook()->getAvatar();
                /** @phpstan-ignore-line avatar can be null. */
                $channel->webhooks->save($webhook)->then(function (DiscordWebhook $webhook) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully updated webhook.", [ModelConverter::genModelWebhook($webhook)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update webhook.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to update webhook ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update webhook.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update webhook ({$pk->getUID()}) - fetch error: {$e->getMessage()}");
            });
        });
    }

    private function handleCreateWebhook(RequestCreateWebhook $pk): void
    {
        $this->getChannel($pk, $pk->getWebhook()->getChannelId(), null, function (DiscordChannel $channel) use ($pk) {
            $channel->webhooks->save($channel->webhooks->create([
                'name' => $pk->getWebhook()->getName(),
                'avatar' => $pk->getWebhook()->getAvatar()
            ]))->then(function (DiscordWebhook $webhook) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Successfully created webhook.", [ModelConverter::genModelWebhook($webhook)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create webhook.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to create webhook ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleTemplateCreate(RequestTemplateCreate $pk): void
    {
        $this->getServer($pk, $pk->getTemplate()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->templates->save($guild->templates->create([
                'name' => $pk->getTemplate()->getName(),
                'description' => $pk->getTemplate()->getDescription()
            ]))->then(function (DiscordTemplate $template) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Successfully created Guild Template.", [ModelConverter::genModelServerTemplate($template)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create guild template.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleTemplateUpdate(RequestTemplateUpdate $pk): void
    {
        if ($pk->getTemplate()->getCode() === null) {
            throw new \AssertionError("Template code must be present.");
        }
        $this->getServer($pk, $pk->getTemplate()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->template->fetch($pk->getTemplate()->getCode())->then(function (DiscordTemplate $template) use ($guild, $pk) {
                $template->name = $pk->getTemplate()->getName();
                $template->description = $pk->getTemplate()->getDescription();
                /** @phpstan-ignore-line avatar can be null. */
                $guild->templates->save($template)->then(function (DiscordTemplate $template) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully updated template.", [ModelConverter::genModelServerTemplate($template)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update template.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to update template ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update template.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update template ({$pk->getUID()}) - fetch error: {$e->getMessage()}");
            });
        });
    }
    private function handleTemplateDelete(RequestTemplateDelete $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->templates->fetch($pk->getCode())->then(function (DiscordTemplate $template) use ($guild, $pk) {
                $guild->templates->delete($template)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully deleted Template.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to delete template.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to delete template ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delete template.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to delete template ({$pk->getUID()}) - fetch error: {$e->getMessage()}");
            });
        });
    }
    private function handleScheduleCreate(RequestScheduleCreate $pk): void
    {
        $this->getServer($pk, $pk->getScheduledEvent()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->guild_scheduled_events->save($guild->scheduled_events->create([
                'description' => $pk->getScheduledEvent()->getDescription()
            ]))->then(function (DiscordScheduledEvent $schedule) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Successfully created Guild Scheduled Event.", [ModelConverter::genModelScheduledEvent($schedule)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create guild Scheduled Event.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleScheduleUpdate(RequestScheduleUpdate $pk): void
    {
        if ($pk->getScheduledEvent()->getId() === null) {
            throw new \AssertionError("Guild Schedule ID must be present.");
        }
        $this->getServer($pk, $pk->getScheduledEvent()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->guild_scheduled_events->fetch($pk->getScheduledEvent()->getId())->then(function (DiscordScheduledEvent $schedule) use ($guild, $pk) {
                $schedule->description = $pk->getScheduledEvent()->getDescription();
                /** @phpstan-ignore-line avatar can be null. */
                $guild->guild_scheduled_events->save($schedule)->then(function (DiscordScheduledEvent $schedule) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully updated Schedule Event.", [ModelConverter::genModelScheduledEvent($schedule)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update Scheduled Event.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to update scheduled event ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update scheduled event.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update scheduled event ({$pk->getUID()}) - fetch error: {$e->getMessage()}");
            });
        });
    }
    private function handleScheduleDelete(RequestScheduleDelete $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->guild_scheduled_events->fetch($pk->getId())->then(function (DiscordScheduledEvent $schedule) use ($guild, $pk) {
                $guild->guild_scheduled_events->delete($schedule)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully deleted Scheduled Event.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to delete Scheduled Event.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to delete Scheduled Event ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delete Scheduled Event.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to delete Scheduled Event ({$pk->getUID()}) - fetch error: {$e->getMessage()}");
            });
        });
    }

    private function handleFetchWebhooks(RequestFetchWebhooks $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), null, function (DiscordChannel $channel) use ($pk) {
            $channel->webhooks->freshen()->then(function (DiscordWebhookRepository $repository) use ($pk) {
                $webhooks = [];
                /** @var DiscordWebhook $webhook */
                foreach ($repository->toArray() as $webhook) {
                    $webhooks[] = ModelConverter::genModelWebhook($webhook);
                }
                $this->resolveRequest($pk->getUID(), true, "Fetched webhooks.", $webhooks);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch webhooks.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to fetch webhooks ({$pk->getUID()}) - freshen error: {$e->getMessage()}");
            });
        });
    }

    private function handleFetchPinnedMessages(RequestFetchPinnedMessages $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), $pk->getThreadId(), function ($channel) use ($pk) {
            /** @var DiscordChannel|DiscordThread $channel */
            $channel->getPinnedMessages()->then(function (Collection $collection) use ($pk) {
                $messages = [];
                foreach ($collection->toArray() as $message) {
                    $messages[] = ModelConverter::genModelMessage($message);
                }
                $this->resolveRequest($pk->getUID(), true, "Fetched pinned messages.", $messages);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch pinned messages.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to fetch pinned messages ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleFetchMessage(RequestFetchMessage $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $message) use ($pk) {
            $this->resolveRequest($pk->getUID(), true, "Fetched message.", [ModelConverter::genModelMessage($message)]);
        });
    }

    private function handleUnpinMessage(RequestUnpinMessage $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), $pk->getThreadId(), function ($channel) use ($pk) {
            /** @var DiscordChannel|DiscordThread $channel */
            $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $message) use ($channel, $pk) {
                $channel->unpinMessage($message, $pk->getReason())->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully unpinned the message.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to unpin the message.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to pin the message ({$pk->getUID()}) - {$e->getMessage()}");
                });
            });
        });
    }

    private function handlePinMessage(RequestPinMessage $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), $pk->getThreadId(), function (DiscordChannel $channel) use ($pk) {
            /** @var DiscordChannel|DiscordThread $channel */
            $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $message) use ($channel, $pk) {
                $channel->pinMessage($message, $pk->getReason())->then(function (DiscordMessage $message) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully pinned the message.", [ModelConverter::genModelMessage($message)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to pin the message.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to pin the message ({$pk->getUID()}) - {$e->getMessage()}");
                });
            });
        });
    }

    private function handleLeaveServer(RequestLeaveServer $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $this->client->getDiscordClient()->guilds->leave($guild)->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID());
            }, function (\Throwable $e) use ($pk) {
                //Shouldn't happen unless not in server/connection issues.
                $this->resolveRequest($pk->getUID(), false, "Failed to leave server.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to leave server ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleServerFetchWidget(RequestFetchWidgetSettings $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->getWidgetSettings()->then(function (DiscordWidget $widget) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Fetched Discord Widget Settings.", [ModelConverter::genModelServerWidget($widget)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch Widget settings.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to fetch Widget settings ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleServerUpdateWidget(RequestModifyWidget $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->updateWidgetSettings([
                "enabled" => $pk->isEnabled(),
                "channel_id" => $pk->getChannelId()
            ])->then(function (DiscordWidget $widget) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Modified Widget Settings", [ModelConverter::genModelServerWidget($widget)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to modify Widget settings.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to modify widget settings ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleServerFetchPrune(RequestFetchPrune $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->getPruneCount([
                "days" => $pk->getDays(),
                "include_roles" => $pk->getIncludedRoles()
            ])->then(function (int $count) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Fetched server prune count.", [$count]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch server prune count.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to fetch server prune count ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleServerBeginPrune(RequestBeginPrune $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->beginPrune([
                "days" => $pk->getDays(),
                "compute_prune_count" => $pk->isPruneCount(),
                "include_roles" => $pk->getIncludedRoles()
            ], $pk->getReason())->then(function (?int $count) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Server pruning started.", [$count ?? 0]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to start Server pruning.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to start server pruning ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleCreateRole(RequestCreateRole $pk): void
    {
        $this->getServer($pk, $pk->getRole()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $r = $pk->getRole();
            $guild->createRole([
                'name' => $r->getName(),
                'color' => $r->getColour(),
                'permissions' => $r->getPermissions()->getBitwise(),
                'hoist' => $r->isHoisted(),
                'position' => $r->getHoistedPosition(),
                'mentionable' => $r->isMentionable()
            ])->then(function (DiscordRole $role) use ($pk) {
                $this->handleUpdateRolePosition($pk->getRole())->then(function () use ($role, $pk) {
                    $this->resolveRequest($pk->getUID(), true, "Created role.", [ModelConverter::genModelRole($role)]);
                }, function (ApiRejection $rejection) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, $rejection->getMessage(), [$rejection->getMessage(), $rejection->getTraceAsString()]);
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create role.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to create role ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleUpdateRolePosition(Role $role): PromiseInterface
    {
        if ($role->getId() === null) {
            return reject(new ApiRejection("Role does not have a ID."));
        }
        if ($role->getId() === $role->getServerId()) {
            return reject(new ApiRejection("Cannot move the default 'everyone' role."));
        }
        $promise = new Deferred();

        $this->client->getDiscordClient()->guilds->fetch($role->getServerId())->done(function (DiscordGuild $guild) use ($promise, $role) {
            //Sort
            $arr = $guild->roles->toArray();
            $keys = array_values(array_map(function (DiscordRole $role) {
                return $role->position;
            }, $arr));
            $val = array_keys($arr);
            try {
                $data = array_combine($keys, $val); //Throws valueError on >= PHP8, returns false on < PHP8.
            } catch (\ValueError $e) {
                $promise->reject(new ApiRejection("Internal error occurred while updating role positions. (" . $e->getMessage() . ")"));
                return;
            }
            /** @var DiscordRole|null $k */
            $k = $arr[$role->getId()];
            if ($k === null) {
                $promise->reject(new ApiRejection("Cannot update role positions, role not found."));
                return;
            }
            //shift
            $diff = $role->getHoistedPosition() - $k->position; //How much are we shifting.
            if ($diff === 0) {
                $this->logger->debug("Not updating role position ({$k->id}), no difference found.");
                $promise->resolve();
                return;
            }
            $v = $k->id;
            $k = $k->position;
            if ($diff > 0) {
                for ($i = $k + 1; $i <= $k + $diff; $i++) {
                    $data[$i - 1] = $data[$i];
                }
                $data[$k + $diff] = $v;
            } else {
                for ($i = $k - 1; $i >= $k + $diff; $i--) {
                    $data[$i + 1] = $data[$i];
                }
                $data[$k + $diff] = $v;
            }
            //save
            $guild->updateRolePositions($data, "Updated roles")->then(function (DiscordGuild $guild) use ($promise) {
                $promise->resolve();
            }, function (\Throwable $e) use ($promise) {
                $promise->reject(new ApiRejection("Failed to update role positions.", [$e->getMessage(), $e->getTraceAsString()]));
                $this->logger->debug("Failed to update role positions, error: {$e->getMessage()}");
            });
        }, function (\Throwable $e) use ($promise) {
            $promise->reject(new ApiRejection("Failed to fetch server.", [$e->getMessage(), $e->getTraceAsString()]));
            $this->logger->debug("Failed to update role position - server error: {$e->getMessage()}");
        });

        return $promise->promise();
    }

    private function handleUpdateRole(RequestUpdateRole $pk): void
    {
        if ($pk->getRole()->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Failed to update role.", ["Role ID must be present."]);
            return;
        }
        $this->getServer($pk, $pk->getRole()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->roles->fetch($pk->getRole()->getId())->then(function (DiscordRole $role) use ($guild, $pk) {
                $role->position = $pk->getRole()->getHoistedPosition();
                $role->hoist = $pk->getRole()->isHoisted();
                $role->mentionable = $pk->getRole()->isMentionable();
                $role->name = $pk->getRole()->getName();
                $role->color = $pk->getRole()->getColour();
                $role->permissions->bitwise = $pk->getRole()->getPermissions()->getBitwise();
                $guild->roles->save($role)->then(function (DiscordRole $role) use ($pk) {
                    if ($pk->getRole()->getId() !== $pk->getRole()->getServerId()) {
                        $this->handleUpdateRolePosition($pk->getRole())->then(function () use ($role, $pk) {
                            $this->resolveRequest($pk->getUID(), true, "Updated role & position.", [ModelConverter::genModelRole($role)]);
                        }, function (ApiRejection $rejection) use ($pk) {
                            $this->resolveRequest($pk->getUID(), false, "Updated role however failed to update position: " . $rejection->getMessage(), [$rejection->getMessage(), $rejection->getTraceAsString()]);
                        });
                    } else {
                        $this->resolveRequest($pk->getUID(), true, "Updated role.", [ModelConverter::genModelRole($role)]);
                    }
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update role.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to create role ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update role.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update role ({$pk->getUID()}) - role error: {$e->getMessage()}");
            });
        });
    }

    private function handleDeleteRole(RequestDeleteRole $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->roles->fetch($pk->getRoleId())->then(function (DiscordRole $role) use ($pk, $guild) {
                $guild->roles->delete($role)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Deleted role.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to delete role.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to delete role ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch role.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to delete role ({$pk->getUID()}) - fetch role: {$e->getMessage()}");
            });
        });
    }

    private function handleRemoveRole(RequestRemoveRole $pk): void
    {
        $this->getMember($pk, $pk->getServerId(), $pk->getUserId(), function (DiscordMember $dMember) use ($pk) {
            $dMember->removeRole($pk->getRoleId(), $pk->getReason())->done(function () use ($pk) {

                $this->resolveRequest($pk->getUID(), true, "Removed role.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to remove role.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to remove role ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleAddRole(RequestAddRole $pk): void
    {
        $this->getMember($pk, $pk->getServerId(), $pk->getUserId(), function (DiscordMember $dMember) use ($pk) {
            $dMember->addRole($pk->getRoleId(), $pk->getReason())->done(function () use ($dMember, $pk) {
                foreach ($dMember->roles as $role) {
                    if ($role->id === $pk->getRoleId()) {

                        $this->resolveRequest($pk->getUID(), true, "Added role.", [ModelConverter::genModelRole($role)]);
                    }
                }
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to add role.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to add role ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleRemoveReaction(RequestRemoveReaction $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($pk) {
            $msg->deleteReaction($pk->getUserId() === $this->client->getDiscordClient()->id ? DiscordMessage::REACT_DELETE_ME : DiscordMessage::REACT_DELETE_ID, $pk->getEmoji(), $pk->getUserId())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Successfully removed reaction.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to remove reaction.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to remove reaction ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleRemoveAllReactions(RequestRemoveAllReactions $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($pk) {
            $msg->deleteReaction(($e = $pk->getEmoji()) === null ? DiscordMessage::REACT_DELETE_ALL : DiscordMessage::REACT_DELETE_EMOJI, $e)->then(function () use ($pk, $e) {
                $this->resolveRequest($pk->getUID(), true, "Successfully bulk removed all " . ($e === null ? "" : "'$e' ") . "reactions");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to bulk remove reactions.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to bulk remove reactions ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleAddReaction(RequestAddReaction $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($pk) {
            $msg->react($pk->getEmoji())->then(function () use ($msg, $pk) {
                $this->resolveRequest($pk->getUID(), true, "Reaction added.", [ModelConverter::genModelMessage($msg)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to react to message.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to react to message ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleFetchReaction(RequestFetchReaction $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($pk) {
            $msg->reactions->fetch($pk->getReactionId())->done(function (DiscordReaction $reaction) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Fetched Reaction Message.", [ModelConverter::genModelReaction($reaction)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch Reaction Message.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleCreateReaction(RequestCreateReaction $pk): void
    {
        $this->getMessage($pk, $pk->getReaction()->getChannelId(), $pk->getReaction()->getMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($pk) {
            $r = $pk->getReaction();
            $dr = $msg->reactions->create([
                "emoji" => $r->getEmoji()
            ]);
            $dr->messageId = $r->getMessageId();
            $dr->channelId = $r->getChannelId();
            if ($r->getServerId() !== null) {
                $dr->guild_id = $r->getServerId();
            }
            if ($r->getCount() !== null) {
                $dr->count = $r->getCount();
            }
            if ($r->isBotReacted() !== null) {
                $dr->me = $r->isBotReacted();
            }
            if ($r->getId() !== null) {
                $dr->id = $r->getId();
            }
            $msg->reactions->save($dr)->then(function (DiscordReaction $reaction) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Created Reaction.", [ModelConverter::genModelReaction($reaction)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create reaction.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleUpdateReaction(RequestUpdateReaction $pk): void
    {
        if ($pk->getReaction()->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Reaction ID must be present!");
            return;
        }
        $this->getMessage($pk, $pk->getReaction()->getChannelId(), $pk->getReaction()->getMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($pk) {

            $msg->reactions->fetch($pk->getReaction()->getID())->then(function (DiscordReaction $dr) use ($msg, $pk) {
                $r = $pk->getReaction();

                $dr->messageId = $r->getMessageId();
                $dr->channelId = $r->getChannelId();
                $dr->emoji = $r->getEmoji();
                $dr->guild_id = $r->getServerId();
                $dr->id = $r->getId();
                $dr->count = $r->getCount();
                $dr->me = $r->isBotReacted();
                $msg->reactions->save($dr)->then(function (DiscordReaction $reaction) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Updated Reaction.", [ModelConverter::genModelReaction($reaction)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update reaction.", [$e->getMessage(), $e->getTraceAsString()]);
                });
            });
        });
    }
    private function handleDeleteReaction(RequestDeleteReaction $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $message) use ($pk) {
            $message->reactions->fetch($pk->getReactionId())->done(function (DiscordReaction $reaction) use ($message, $pk) {
                $message->reactions->delete($reaction)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Reaction deleted.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to delete Reaction.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to delete Reaction ({$pk->getUID()}) - {$e->getMessage()}");
                });
            });
        });
    }





    private function handleStageCreate(RequestStageCreate $pk): void
    {
        $this->getServer($pk, $pk->getStage()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $c = $pk->getStage();
            /** @var DiscordStage $dc */
            $dc = $guild->stage_instances->create([
                'channel_id' => $c->getChannelID(),
                'topic' => $c->getTopic(),
                'guild_id' => $guild->id
            ]);
            $dc->topic = $c->getTopic();
            $dc->privacy_level = $c->getPrivacyLevel();
            $guild->stage_instances->save($dc)->then(function (DiscordStage $stage) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Created Stage.", [ModelConverter::genModelStage($stage)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create Stage.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to create stage ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleStageUpdate(RequestStageUpdate $pk): void
    {
        if ($pk->getStage()->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Failed to update stage.", ["Stage ID must be present."]);
            return;
        }
        $this->getServer($pk, $pk->getStage()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->stage_instances->fetch($pk->getStage()->getId())->then(function (DiscordStage $dc) use ($guild, $pk) {
                $c = $pk->getStage();
                $dc->id = $c->getId();
                $dc->channel_id = $c->getChannelId();
                $dc->topic = $c->getTopic();
                $dc->privacy_level = $c->getPrivacyLevel();
                $dc->guild_id = $c->getServerId();
                $guild->stage_instances->save($dc)->then(function (DiscordStage $stage) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Updated Stage.", [ModelConverter::genModelStage($stage)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update Stage.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to update stage ({$pk->getUID()}) - {$e->getMessage()}");
                });
            });
        });
    }
    private function handleStageDelete(RequestStageDelete $pk): void
    {
        $this->getStage($pk, $pk->getServerId(), $pk->getStageId(), function (DiscordGuild $guild, DiscordStage $stage) use ($pk) {
            $guild->stage_instances->delete($stage)->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Stage deleted.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delete stage.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to delete stage ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleBulkDelete(RequestMessageBulkDelete $pk): void
    {
        $this->getChannel($pk, $pk->getChannelID(), $pk->getThreadId(), function ($channel) use ($pk) {
            /** @var DiscordChannel|DiscordThread $channel */
            $channel->getMessageHistory([
                "limit" => $pk->getValue()
            ])->then(function ($messages) use ($pk, $channel) {
                /** @var DiscordMessage[] */
                $msgs = [];
                foreach ($messages as $message) {

                    if ($message instanceof DiscordMessage) {
                        $msgs[] = $message;
                    }
                }
                $filter = array_filter($msgs, function (DiscordMessage $message): bool {
                    $model = ModelConverter::genModelMessage($message);
                    $days = Utils::toDays(Utils::toSeconds($model->getTimestamp()));
                    return $days < 14;
                });
                $channel->deleteMessages($filter, $pk->getReason());




                $this->resolveRequest($pk->getUID());
                $this->logger->debug("Message Bulk - success ({$pk->getUID()})");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to Bulk message.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to Bulk Message. ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleChannelStartThread(RequestThreadCreate $pk): void
    {

        $this->getServer($pk, $pk->getChannel()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $c = $pk->getChannel();
            /** @var DiscordChannel $dc */
            $dc = $guild->channels->create([
                'name' => $c->getName(),
                'guild_id' => $guild->id
            ]);

            /** @var DiscordThread $thread */
            $thread = $dc->threads->create([
                'name' => $c->getName(),
                'guild_id' => $guild->id
            ]);

            $c = $pk->getChannel();

            $dc->startThread($pk->getChannel()->getName(), $pk->isPrivate(), $pk->getDuration(), $pk->getReason())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID());
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create thread.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleThreadDelete(RequestThreadDelete $pk): void
    {


        $this->getServer($pk, $pk->getServerID(), function (DiscordGuild $guild) use ($pk) {
            $guild->channels->fetch($pk->getChannelID())->then(function (DiscordChannel $discord) use ($pk) {
                $discord->threads->delete($discord)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Thread Channel deleted.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to delete Thread channel.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to delete channel ({$pk->getUID()}) - {$e->getMessage()}");
                });
            });
        });
    }
    private function handleThreadUpdate(RequestThreadUpdate $pk): void
    {
        $id = $pk->getChannelId();
        $threadID = $pk->getThreadChannel()->getId();
        if (!$threadID) {
            return;
        }

        $this->getServer($pk, $pk->getThreadChannel()->getServerID(), function (DiscordGuild $guild) use ($threadID, $id, $pk) {
            $guild->channels->fetch($id)->then(function (DiscordChannel $discord) use ($threadID, $pk) {
                $discord->threads->fetch($threadID)->then(function (DiscordThread $thread) use ($threadID, $discord, $pk) {
                    $thread->id = $threadID;
                    $thread->guild_id = $pk->getThreadChannel()->getServerID();
                    $thread->name = $pk->getThreadChannel()->getName();
                    $thread->owner_id = $pk->getThreadChannel()->getOwner();
                    $thread->auto_archive_duration = $pk->getThreadChannel()->getDuration();
                    $thread->archiver_id = $pk->getThreadChannel()->getUserID();


                    $discord->threads->save($thread)->then(function (DiscordChannel $channel) use ($pk) {
                        $this->resolveRequest($pk->getUID(), true, "Updated channel.", [ModelConverter::genModelChannel($channel)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to update channel ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to update channel ({$pk->getUID()}) - channel error: {$e->getMessage()}");
                });
            });
        });
    }

    private function handleThreadAchive(RequestThreadAchive $pk)
    {
        $id = $pk->getChannelId();
        $threadID = $pk->getThreadChannel()->getId();
        if (!$threadID) {
            return;
        }

        $this->getServer($pk, $pk->getThreadChannel()->getServerID(), function (DiscordGuild $guild) use ($threadID, $id, $pk) {
            $guild->channels->fetch($id)->then(function (DiscordChannel $discord) use ($threadID, $pk) {
                $discord->threads->fetch($threadID)->then(function (DiscordThread $thread) use ($threadID, $discord, $pk) {
                    $thread->archive()->done(function () use ($pk) {
                        $this->resolveRequest($pk->getUID(), true, "Successfully achived thread.");
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to achive thread.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                });
            });
        });
    }

    private function handleThreadUnachive(RequestThreadUnachive $pk)
    {
        $id = $pk->getChannelId();
        $threadID = $pk->getThreadChannel()->getId();
        if (!$threadID) {
            return;
        }

        $this->getServer($pk, $pk->getThreadChannel()->getServerID(), function (DiscordGuild $guild) use ($threadID, $id, $pk) {
            $guild->channels->fetch($id)->then(function (DiscordChannel $discord) use ($threadID, $pk) {
                $discord->threads->fetch($threadID)->then(function (DiscordThread $thread) use ($threadID, $discord, $pk) {
                    $thread->unarchive()->done(function () use ($pk) {
                        $this->resolveRequest($pk->getUID(), true, "Successfully unachived thread.");
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to unachive thread.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                });
            });
        });
    }
    private function handleThreadJoin(RequestThreadJoin $pk)
    {

        $id = $pk->getChannelId();
        $threadID = $pk->getThreadChannel()->getId();
        if (!$threadID) {
            return;
        }

        $this->getServer($pk, $pk->getThreadChannel()->getServerID(), function (DiscordGuild $guild) use ($threadID, $id, $pk) {
            $guild->channels->fetch($id)->then(function (DiscordChannel $discord) use ($threadID, $pk) {
                $discord->threads->fetch($threadID)->then(function (DiscordThread $thread) use ($threadID, $discord, $pk) {
                    $thread->join()->done(function () use ($pk) {
                        $this->resolveRequest($pk->getUID(), true, "Successfully joined thread.");
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to join thread.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                });
            });
        });
    }
    private function handleThreadLeave(RequestThreadLeave $pk)
    {

        $id = $pk->getChannelId();
        $threadID = $pk->getThreadChannel()->getId();
        if (!$threadID) {
            return;
        }

        $this->getServer($pk, $pk->getThreadChannel()->getServerID(), function (DiscordGuild $guild) use ($threadID, $id, $pk) {
            $guild->channels->fetch($id)->then(function (DiscordChannel $discord) use ($threadID, $pk) {
                $discord->threads->fetch($threadID)->then(function (DiscordThread $thread) use ($threadID, $discord, $pk) {
                    $thread->leave()->done(function () use ($pk) {
                        $this->resolveRequest($pk->getUID(), true, "Successfully left thread.");
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to leave thread.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                });
            });
        });
    }

    private function handleMessageStartThread(RequestThreadMessageCreate $pk): void
    {
        $this->getMessage($pk, $pk->getChannelID(), $pk->getMessageID(), $pk->getThreadId(), function (DiscordMessage $message) use ($pk) {
            $message->startThread($pk->getName(), $pk->getDuration(), $pk->getReason())->then(function () use ($message, $pk) {
                $this->resolveRequest($pk->getUID(), false, "Successfully created thread message.", [ModelConverter::genModelMessage($message)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to bulk delete messages.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleCrossPost(RequestCrossPostMessage $pk): void
    {

        $this->getMessage($pk, $pk->getChannelID(), $pk->getMessageID(), $pk->getThreadId(), function (DiscordMessage $discord) use ($pk) {
            $discord->crosspost()->done(function (DiscordMessage $message) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Cross posted with success!", [ModelConverter::genModelMessage($message)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to Cross post message.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }

    /** DM Channel creation/modification/deletion */
    private function handleCreateDMChannel(RequestCreateDMChannel $pk)
    {
        $c = $pk->getChannel();

        $privatechannels = $this->client->getDiscordClient()->private_channels;
        $dc = $privatechannels->create([
            'owner_id' => $c->getOwnerId(),
            'recipient_id' => $c->getRecipientId()
        ]);
        $dc->type = DiscordChannel::TYPE_DM;
        if ($c->getApplicationId() !== null) {
            $dc->application_id = $c->getApplicationId();
        }
        $privatechannels->save($dc)->then(function (DiscordChannel $channel) use ($pk) {
            $this->resolveRequest($pk->getUID(), true, "Created channel.", [ModelConverter::genModelDMChannel($channel)]);
        }, function (\Throwable $e) use ($pk) {
            $this->resolveRequest($pk->getUID(), false, "Failed to create channel.", [$e->getMessage(), $e->getTraceAsString()]);
            $this->logger->debug("Failed to create channel ({$pk->getUID()}) - {$e->getMessage()}");
        });
    }
    private function handleUpdateDMChannel(RequestUpdateDMChannel $pk)
    {
        $privatechannels = $this->client->getDiscordClient()->private_channels;
        $privatechannels->fetch($pk->getChannel()->getId())->then(function (DiscordChannel $dc) use ($privatechannels, $pk) {
            $channel = $pk->getChannel();
            if ($dc->type !== DiscordChannel::TYPE_DM) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", ["Channel type change is not allowed."]);
                return;
            }
            $dc->owner_id = $channel->getOwnerId();
            $dc->recipient_id = $channel->getRecipientId();
            if ($channel->getApplicationId() !== null) {
                $dc->application_id = $channel->getApplicationId();
            }
            $privatechannels->save($dc)->then(function (DiscordChannel $channel) use ($pk) {
                $model = ModelConverter::genModelDMChannel($channel);
                if ($model === null) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update DM Channel.", []);
                } else {
                    $this->resolveRequest($pk->getUID(), true, "Updated channel.", [$model]);
                }
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update channel ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleDeleteDMChannel(RequestDeleteDMChannel $pk)
    {
        $client = $this->client->getDiscordClient();
        $client->private_channels->fetch($pk->getChannelId())->then(function (DiscordChannel $channel) use ($pk, $client) {
            $client->private_channels->delete($channel)->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Channel deleted.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delete channel.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to delete channel ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }



    private function handleCreateChannel(RequestCreateChannel $pk): void
    {
        $this->getServer($pk, $pk->getChannel()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $c = $pk->getChannel();
            /** @var DiscordChannel $dc */
            $dc = $guild->channels->create([
                'name' => $c->getName(),
                'position' => $c->getPosition(),
                'guild_id' => $guild->id
            ]);
            if ($c->getCategoryId() !== null) {
                $dc->parent_id = $c->getCategoryId();
            }
            foreach ($c->getAllMemberPermissions() as $id => [$allowed, $denied]) {
                $dc->overwrites->push($dc->overwrites->create([
                    'id' => $id,
                    "type" => DiscordOverwrite::TYPE_MEMBER,
                    "allow" => strval($allowed === null ? 0 : $allowed->getBitwise()),
                    "deny" => strval($denied === null ? 0 : $denied->getBitwise())
                ]));
            }
            foreach ($c->getAllRolePermissions() as $id => [$allowed, $denied]) {
                $dc->overwrites->push($dc->overwrites->create([
                    'id' => $id,
                    "type" => DiscordOverwrite::TYPE_ROLE,
                    "allow" => strval($allowed === null ? 0 : $allowed->getBitwise()),
                    "deny" => strval($denied === null ? 0 : $denied->getBitwise())
                ]));
            }
            if ($c instanceof CategoryChannel) {
                $dc->type = DiscordChannel::TYPE_CATEGORY;
            } elseif ($c instanceof VoiceChannel) {
                $dc->type = DiscordChannel::TYPE_VOICE;
                $dc->bitrate = $c->getBitrate();
                $dc->user_limit = $c->getMemberLimit();
            } elseif ($c instanceof TextChannel) {
                $dc->topic = $c->getTopic();
                $dc->nsfw = $c->isNsfw();
                $dc->rate_limit_per_user = $c->getRateLimit() ?? 0;
            } else {
                throw new \AssertionError("What channel type is this ?? '" . get_class($c) . "'");
            }
            $guild->channels->save($dc)->then(function (DiscordChannel $channel) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Created channel.", [ModelConverter::genModelChannel($channel)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create channel.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to create channel ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleUpdateChannel(RequestUpdateChannel $pk): void
    {
        if ($pk->getChannel()->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", ["Channel ID must be present."]);
            return;
        }
        $this->getServer($pk, $pk->getChannel()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->channels->fetch($pk->getChannel()->getId())->then(function (DiscordChannel $dc) use ($guild, $pk) {
                $channel = $pk->getChannel();
                $dc->name = $pk->getChannel()->getName();
                $dc->position = $pk->getChannel()->getPosition();
                if ($pk->getChannel()->getCategoryId() !== null) {
                    $dc->parent_id = $pk->getChannel()->getCategoryId();
                }
                $dc->overwrites->clear();
                foreach ($channel->getAllMemberPermissions() as $id => [$allowed, $denied]) {
                    $dc->overwrites->push($dc->overwrites->create([
                        'id' => $id,
                        "type" => DiscordOverwrite::TYPE_MEMBER,
                        "allow" => strval($allowed === null ? 0 : $allowed->getBitwise()),
                        "deny" => strval($denied === null ? 0 : $denied->getBitwise())
                    ]));
                }
                foreach ($channel->getAllRolePermissions() as $id => [$allowed, $denied]) {
                    $dc->overwrites->push($dc->overwrites->create([
                        'id' => $id,
                        "type" => DiscordOverwrite::TYPE_ROLE,
                        "allow" => strval($allowed === null ? 0 : $allowed->getBitwise()),
                        "deny" => strval($denied === null ? 0 : $denied->getBitwise())
                    ]));
                }
                if ($channel instanceof CategoryChannel) {
                    if ($dc->type !== DiscordChannel::TYPE_CATEGORY) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", ["Channel type change is not allowed."]);
                        return;
                    }
                } elseif ($channel instanceof VoiceChannel) {
                    if ($dc->type !== DiscordChannel::TYPE_VOICE) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", ["Channel type change is not allowed."]);
                        return;
                    }
                    $dc->bitrate = $channel->getBitrate();
                    $dc->user_limit = $channel->getMemberLimit();
                } elseif ($channel instanceof TextChannel) {
                    if ($dc->type !== DiscordChannel::TYPE_TEXT) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", ["Channel type change is not allowed."]);
                        return;
                    }
                    $dc->topic = $channel->getTopic();
                    $dc->nsfw = $channel->isNsfw();
                    $dc->rate_limit_per_user = $channel->getRateLimit() ?? 0;
                } else {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", ["Channel type is unknown."]);
                    throw new \AssertionError("What channel type is this ?? '" . get_class($channel) . "'");
                }
                $guild->channels->save($dc)->then(function (DiscordChannel $channel) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Updated channel.", [ModelConverter::genModelChannel($channel)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to update channel ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update channel.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update channel ({$pk->getUID()}) - channel error: {$e->getMessage()}");
            });
        });
    }

    private function handleDeleteChannel(RequestDeleteChannel $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $this->getChannel($pk, $pk->getChannelId(), null, function (DiscordChannel $channel) use ($guild, $pk) {
                $guild->channels->delete($channel)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Channel deleted.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to delete channel.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to delete channel ({$pk->getUID()}) - {$e->getMessage()}");
                });
            });
        });
    }

    private function handleBroadcastTyping(RequestBroadcastTyping $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), null, function (DiscordChannel $channel) use ($pk) {
            $channel->broadcastTyping()->done(function () use ($pk) {
                $this->resolveRequest($pk->getUID());
                $this->logger->debug("BroadcastTyping - success ({$pk->getUID()})");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to broadcast typing.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to broadcast typing ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleUpdateNickname(RequestUpdateNickname $pk): void
    {
        $this->getMember($pk, $pk->getServerId(), $pk->getUserId(), function (DiscordMember $dMember) use ($pk) {
            $dMember->setNickname($pk->getNickname(), $pk->getReason())->done(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Updated nickname.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update nickname.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update nickname ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleServerTransfer(RequestServerTransfer $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->transferOwnership($pk->getUserId(), $pk->getReason())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Transferred guild.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to transfer guild.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleServerEmojiUpdate(RequestEmojiUpdate $pk): void
    {
        $this->getServer($pk, $pk->getEmoji()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->emojis->fetch($pk->getEmoji()->getId())->then(function (DiscordEmoji $emoji) use ($guild, $pk) {
                $emoji->name = $pk->getEmoji()->getName();
                $emoji->guild_id = $pk->getEmoji()->getServerId();
                $emoji->managed = $pk->getEmoji()->isManaged();
                $emoji->require_colons = $pk->getEmoji()->isColonsRequired();
                $emoji->roles = $pk->getEmoji()->getRoles();
                $emoji->animated = $pk->getEmoji()->isAnimated();
                $emoji->available = $pk->getEmoji()->isAvailable();

                $guild->emojis->save($emoji)->then(function (DiscordSticker $sticker) use ($pk, $emoji) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully updated Guild emoji.", [ModelConverter::genModelEmoji($emoji)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update guild emoji.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to update guild emoji ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update guild emoji", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update guild emoji ({$pk->getUID()}) - fetch error: {$e->getMessage()}");
            });
        });
    }
    private function handleStickerCreate(RequestStickerCreate $pk): void
    {
        $serverId = $pk->getSticker()->getServerId();
        if ($serverId === null) {
            $this->resolveRequest($pk->getUID(), false, "Server ID must be present.");
            return;
        }
        $this->getServer($pk, $serverId, function (DiscordGuild $guild) use ($serverId, $pk) {
            /** @var DiscordSticker $sticker */
            $sticker = $guild->stickers->create([
                "name" => $pk->getSticker()->getName(),
                "description" => $pk->getSticker()->getDescription(),
                "tags" => $pk->getSticker()->getTags()
            ]);
            $sticker->type = $pk->getSticker()->getType();
            $sticker->format_type = $pk->getSticker()->getFormatType();
            $sticker->available = $pk->getSticker()->isAvailable();
            $sticker->user = $pk->getSticker()->getUser();
            $sticker->sort_value = $pk->getSticker()->getSortValue();
            $guild->stickers->save($sticker)->then(function (DiscordSticker $sticker) use ($serverId, $pk, $guild) {
                $this->resolveRequest($pk->getUID(), true, "Successfully created guild sticker.", [ModelConverter::genModelSticker($sticker)]);
            }, function (\Throwable $e) use ($serverId, $pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create guild sticker.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        }, function (\Throwable $e) use ($serverId, $pk) {
            $this->resolveRequest($pk->getUID(), false, "Failed to fetch Guild ID: {$serverId}.", [$e->getMessage(), $e->getTraceAsString()]);
        });
    }


    private function handleStickerUpdate(RequestStickerUpdate $pk): void
    {
        $serverId = $pk->getSticker()->getServerId();
        if ($serverId === null) {
            $this->resolveRequest($pk->getUID(), false, "Server ID must be present.");
            return;
        }
        $this->getServer($pk, $serverId, function (DiscordGuild $guild) use ($pk) {
            $guild->stickers->fetch($pk->getSticker()->getId())->then(function (DiscordSticker $sticker) use ($guild, $pk) {
                $sticker->name = $pk->getSticker()->getName();
                $sticker->description = $pk->getSticker()->getDescription();
                $guild->stickers->save($sticker)->then(function (DiscordSticker $sticker) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully updated Guild Sticker.", [ModelConverter::genModelSticker($sticker)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update guild sticker.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to update guild sticker ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update guild sticker", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update guild sticker ({$pk->getUID()}) - fetch error: {$e->getMessage()}");
            });
        });
    }
    private function handleStickerDelete(RequestStickerDelete $pk): void
    {
        $serverId = $pk->getServerId();
        $id = $pk->getId();
        $this->getServer($pk, $serverId, function (DiscordGuild $guild) use ($id, $serverId, $pk) {
            $guild->stickers->fetch($id)->then(function (DiscordSticker $sticker) use ($guild, $pk) {
                $guild->stickers->delete($sticker)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully deleted Guild Sticker.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to delete guild sticker.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to delete guild sticker ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delete guild sticker", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to delete guild sticker ({$pk->getUID()}) - fetch error: {$e->getMessage()}");
            });
        });
    }
    private function handleCreateServerFromTemplate(RequestCreateServerFromTemplate $pk): void
    {
        $icon = $pk->getServerIcon();
        $name = $pk->getServerName();
        $code = $pk->getTemplateCode();
        $this->getTemplate($pk, $code, $pk->getServer()->getId(), function (DiscordTemplate $template) use ($pk, $code, $name, $icon) {
            $template->createGuild([
                $name,
                $icon
            ])->done(function (DiscordGuild $newGuild) use ($pk, $name) {
                $this->resolveRequest($pk->getUID(), true, "Created new guild with name: {$name} successfully!", [ModelConverter::genModelServer($newGuild)]);
            }, function (\Throwable $e) use ($name, $pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to create new guild {$name}: {$e->getMessage()}", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }



    private function handleAuditLog(RequestServerAuditLog $pk): void
    {
        print_r("\n\nSearching Audit Log via CommunicationHandler..");
        $this->getMember($pk, $pk->getServerId(), $pk->getUserId(), function (DiscordMember $member, DiscordGuild $guild) use ($pk) {
            print_r("\n\nFound Member. Getting Audit Log Object..");
            $guild->getAuditLog([
                "user_id" => $member,
                "action_type" => $pk->getActionType(),
                "before" => $pk->getBefore(),
                "limit" => $pk->getLimit()
            ])->then(function (DiscordAuditLog $log) use ($pk) {
                print_r("\n\nGot Audit Log.");
                $this->resolveRequest($pk->getUID(), true, "Searched AuditLog with success!.", [ModelConverter::genModelAuditLog($log)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to search audit log.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        }, function (\Throwable $e2) use ($pk) {
            $this->resolveRequest($pk->getUID(), false, "Unable to fetch audit log member.", [$e2->getTrace(), $e2->getTraceAsString()]);
        });
    }
    private function handleFetchWelcomeScreen(RequestFetchWelcomeScreen $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->getWelcomeScreen()->then(function (DiscordWelcomeScreen $screen) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Fetched Welcome Screen!", [ModelConverter::genModelWelcomeScreen($screen)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch Welcome Screen!", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleUpdateWelcomeScreen(RequestUpdateWelcomeScreen $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->updateWelcomeScreen([
                'enabled' => $pk->isEnabled(),
                'welcome_channels' =>
                $pk->getOptions(),
                'description' => $pk->getDescription()
            ])->then(function (DiscordWelcomeScreen $screen) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Updated Welcome Screen to server {$pk->getServerId()} with success", [ModelConverter::genModelWelcomeScreen($screen)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update Welcome Screen!", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleSearchMembers(RequestSearchMembers $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->searchMembers([
                "query" => $pk->getSearchedUserId(),
                "limit" => $pk->getLimit()
            ])->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Searched with success!.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to search audit log.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }


    private function handleUpdatePresence(RequestUpdatePresence $pk): void
    {
        $activity = $pk->getActivity();
        $presence = new DiscordActivity($this->client->getDiscordClient(), [
            'name' => $activity->getName(),
            'type' => $activity->getType()
        ]);

        try {
            $this->client->getDiscordClient()->updatePresence($presence, $pk->getStatus() === Member::STATUS_IDLE, $pk->getStatus());
            $this->resolveRequest($pk->getUID());
        } catch (\Throwable $e) {
            $this->resolveRequest($pk->getUID(), false, $e->getMessage());
        }
    }
    private function handleVoiceChannelJoin(RequestJoinVoiceChannel $pk): void
    {
        $channel = $pk->getChannel();
        if ($channel->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Voice Channel ID must be present");
            return;
        }
        $voiceChannel = $this->client->getDiscordClient()->getChannel($channel->getId());
        if ($voiceChannel === null) {
            $this->resolveRequest($pk->getUID(), false, "Voice Channel with ID: {$channel->getId()} does not exist!");
            return;
        }
        if ($voiceChannel->type !== $voiceChannel::TYPE_VOICE) {
            $this->resolveRequest($pk->getUID(), false, "Channel: {$channel->getId()} is not a voice channel, silly!");
            return;
        }

        $this->client->getDiscordClient()->joinVoiceChannel($voiceChannel, $pk->isMuted(), $pk->isDeafend(), null, true)->then(function () use ($voiceChannel, $channel, $pk) {
            $this->resolveRequest($pk->getUID(), true, "Successfully joined Voice Channel ID: {$channel->getId()}", [ModelConverter::genModelVoiceChannel($voiceChannel)]);
        }, function (\Throwable $e) use ($pk, $channel) {
            $this->resolveRequest($pk->getUID(), false, "Unable to Join Voice Channel ID: {$channel->getId()}", [$e->getMessage(), $e->getTraceAsString()]);
        });
    }
    private function handleVoiceChannelLeave(RequestLeaveVoiceChannel $pk): void
    {
        $channel = $pk->getChannel();
        if ($channel->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Unable to leave Voice Channel.", ["ID Must be present!"]);
            return;
        }
        try {
            $voiceClient = $this->client->getDiscordClient()->getVoiceClient($channel->getServerID());
            if ($voiceClient === null) {
                $this->resolveRequest($pk->getUID(), false, "Bot isn't in a voice channel.");
                return;
            }
            if ($voiceClient->getChannel()->type !== $voiceClient->getChannel()::TYPE_VOICE) {
                $this->resolveRequest($pk->getUID(), false, "Unable to leave Voice Channel. Is this channel corrupted?");
                return;
            }
            $this->resolveRequest($pk->getUID(), true, "Successfully left voice channel.");
            $voiceClient->close();
        } catch (\Throwable $e) {
            $this->resolveRequest($pk->getUID(), false, $e->getMessage());
        }
    }
    private function handleVoiceChannelMove(RequestMoveVoiceChannel $pk): void
    {
        $channel = $pk->getChannel();
        if ($channel->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Voice Channel ID must be present");
            return;
        }
        $voiceChannel = $this->client->getDiscordClient()->getChannel($channel->getId());
        if ($voiceChannel === null) {
            $this->resolveRequest($pk->getUID(), false, "Voice Channel with ID: {$channel->getId()} does not exist!");
            return;
        }
        if ($voiceChannel->type !== $voiceChannel::TYPE_VOICE) {
            $this->resolveRequest($pk->getUID(), false, "Channel: {$channel->getId()} is not a voice channel, silly!");
            return;
        }
        try {
            $voiceClient = $this->client->getDiscordClient()->getVoiceClient($channel->getServerID());
            if ($voiceClient === null) {
                $this->resolveRequest($pk->getUID(), false, "Bot isn't in a voice channel.");
                return;
            }
            $voiceClient->switchChannel($voiceChannel);
            $this->resolveRequest($pk->getUID());
        } catch (\Throwable $e) {
            $this->resolveRequest($pk->getUID(), false, $e->getMessage());
        }
    }
    private function handleMoveMember(RequestMoveMember $pk): void
    {
        $channel = $pk->getChannel();
        if ($channel->getID() === null) {
            $this->resolveRequest($pk->getUID(), false, "Channel ID must be present.");
            return;
        }
        $this->getChannel($pk, $channel->getID(), null, function (DiscordChannel $discordChannel) use ($channel, $pk) {
            if ($discordChannel->type !== $discordChannel::TYPE_VOICE) {
                $this->resolveRequest($pk->getUID(), false, "Channel {$channel->getId()} is not a voice channel.");
                return;
            }
            $discordChannel->moveMember($pk->getUserId(), $pk->getReason())->then(function () use ($discordChannel, $pk, $channel) {
                $this->resolveRequest($pk->getUID(), true, "Succcessfully moved member to {$channel->getID()}!", [ModelConverter::genModelVoiceChannel($discordChannel)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to move member.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleMuteMember(RequestMuteMember $pk): void
    {
        $channel = $pk->getChannel();
        if ($channel->getID() === null) {
            $this->resolveRequest($pk->getUID(), false, "Channel ID must be present.");
            return;
        }
        $this->getChannel($pk, $channel->getID(), null, function (DiscordChannel $discordChannel) use ($channel, $pk) {
            if ($discordChannel->type !== $discordChannel::TYPE_VOICE) {
                $this->resolveRequest($pk->getUID(), false, "Channel ID: {$channel->getId()} is not a Voice Channel.");
                return;
            }
            $discordChannel->muteMember($pk->getUserId(), $pk->getReason())->then(function () use ($pk, $discordChannel, $channel) {
                $this->resolveRequest($pk->getUID(), true, "Succcessfully moved member to {$channel->getID()}!", [ModelConverter::genModelVoiceChannel($discordChannel)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to move member.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleUnmuteMember(RequestUnmuteMember $pk): void
    {
        $channel = $pk->getChannel();
        if ($channel->getID() === null) {
            $this->resolveRequest($pk->getUID(), false, "Channel ID must be present.");
            return;
        }
        $this->getChannel($pk, $channel->getID(), null, function (DiscordChannel $discordChannel) use ($channel, $pk) {
            if ($discordChannel->type !== $discordChannel::TYPE_VOICE) {
                $this->resolveRequest($pk->getUID(), false, "Channel ID: {$channel->getId()} is not a Voice Channel.");
                return;
            }
            $discordChannel->unmuteMember($pk->getUserId(), $pk->getReason())->then(function () use ($pk, $discordChannel, $channel) {
                $this->resolveRequest($pk->getUID(), true, "Succcessfully unmuted member in {$channel->getID()}!", [ModelConverter::genModelVoiceChannel($discordChannel)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to unmute member.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }


    private function handleSendFile(RequestSendFile $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), $pk->getThreadId(), function (DiscordChannel $channel) use ($pk) {
            if (!$channel->allowText()) {
                $this->resolveRequest($pk->getUID(), false, "Failed to send file, Invalid channel - text is not allowed.");
                $this->logger->debug("Failed to send file ({$pk->getUID()}) - Channel does not allow text.");
                return;
            }
            $builder = MessageBuilder::new();
            $builder = $builder->addFile($pk->getFilePath(), $pk->getFileName());
            $builder = $builder->setContent($pk->getMessage());
            $builder = $builder->setTTS($pk->isTTS());
            $channel->sendMessage($builder)->then(function (DiscordMessage $message) use ($pk) {

                $this->resolveRequest($pk->getUID(), true, "Successfully sent file.", [ModelConverter::genModelMessage($message)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to send file.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to send file ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleModifyInteraction(RequestModifyInteraction $pk): void
    {
        $m = $pk->getMessage();
        if ($m->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Message ID must be present.");
            return;
        }
        $this->getMessage($pk, $m->getChannelId(), $m->getId(), $pk->getThreadId(), function (DiscordMessage $message) use ($m, $pk) {
            $builder = $pk->getMessageBuilder();
            if ($m instanceof WebhookMessage) {
                $e = $m->getEmbeds();
            } else {
                $e = $m->getEmbed();
            }
            $de = null;
            if ($e !== null) {
                if (is_array($e)) {
                    $embeds = [];
                    foreach ($e as $embed) {
                        $de = new DiscordEmbed($this->client->getDiscordClient());
                        if ($embed->getType() !== null) $de->setType($embed->getType());
                        if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
                        if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
                        if ($embed->getColour() !== null) $de->setColor($embed->getColour());
                        if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
                        if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
                        if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
                        if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
                        if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
                        if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
                        foreach ($embed->getFields() as $f) {
                            $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
                        }
                        $embeds[] = $de;
                    }
                    $builder = $builder->setEmbeds($embeds);
                } else {
                    $embed = $e;
                    $de = new DiscordEmbed($this->client->getDiscordClient());
                    if ($embed->getType() !== null) $de->setType($embed->getType());
                    if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
                    if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
                    if ($embed->getColour() !== null) $de->setColor($embed->getColour());
                    if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
                    if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
                    if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
                    if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
                    if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
                    if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
                    foreach ($e->getFields() as $f) {
                        $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
                    }
                    $builder = $builder->setEmbeds([$de]);
                }
            }
            $builder = $builder->setContent($m->getContent());
            $builder = $builder->setStickers($m->getStickers());
            $builder = $builder->setTTS($m->isTTS());
            if ($m instanceof Reply) {
                if ($m->getReferencedMessageId() === null) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to modify interaction.", ["Reply message has no referenced message ID."]);
                    $this->logger->debug("Failed to modify interaction ({$pk->getUID()}) - Reply message has no referenced message ID.");
                    return;
                }
                $this->getMessage($pk, $m->getChannelId(), $m->getReferencedMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($builder, $pk, $de) {
                    $builder = $builder->setReplyTo($msg);
                    $msg->edit($builder)->done(function (DiscordMessage $msg) use ($pk) {

                        $interaction = $msg->interaction;

                        if ($interaction === null) {
                            $this->resolveRequest($pk->getUID(), false, "Interaction was not found in message. (Data turned into Message Model)", [ModelConverter::genModelMessage($msg)]);
                            return;
                        }
                        $ephemeral = $pk->isEphemeral();
                        $this->resolveRequest($pk->getUID(), true, "Interaction sent.", [ModelConverter::genModelInteraction($interaction, $pk->getMessageBuilder(), $ephemeral)]);
                        $this->logger->debug("Sent Interaction ({$pk->getUID()})");
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to modify interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to modify interaction ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                });
            } else {

                $message->edit($pk->getMessageBuilder())->done(function (DiscordMessage $msg) use ($pk) {
                    $interaction = $msg->interaction;

                    if ($interaction === null) {
                        $this->resolveRequest($pk->getUID(), false, "Interaction was not found in message. (Data turned into Message Model)", [ModelConverter::genModelMessage($msg)]);
                        return;
                    }
                    $ephemeral = $pk->isEphemeral();
                    $this->resolveRequest($pk->getUID(), true, "Interaction sent.", [ModelConverter::genModelInteraction($interaction, $pk->getMessageBuilder(), $ephemeral)]);
                    $this->logger->debug("Sent Interaction ({$pk->getUID()})");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to modify interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to modify interaction ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }
            $message->edit($builder)->done(function (DiscordMessage $msg) use ($pk) {
                $interaction = $msg->interaction;

                if ($interaction === null) {
                    $this->resolveRequest($pk->getUID(), false, "Interaction was not found in message. (Data turned into Message Model)", [ModelConverter::genModelMessage($msg)]);
                    return;
                }
                $ephemeral = $pk->isEphemeral();
                $this->resolveRequest($pk->getUID(), true, "Interaction sent.", [ModelConverter::genModelInteraction($interaction)]);
                $this->logger->debug("Sent Interaction ({$pk->getUID()})");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to modify interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to modify interaction ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleCreateInteraction(RequestCreateInteraction $pk): void
    {
        $this->getChannel($pk, $pk->getMessage()->getChannelId(), $pk->getThreadId(), function (DiscordChannel $channel) use ($pk) {
            $m = $pk->getMessage();
            $builder = $pk->getMessageBuilder();
            if ($m instanceof WebhookMessage) {
                $e = $m->getEmbeds();
            } else {
                $e = $m->getEmbed();
            }
            $de = null;
            if ($e !== null) {
                if (is_array($e)) {
                    $embeds = [];
                    foreach ($e as $embed) {
                        $de = new DiscordEmbed($this->client->getDiscordClient());
                        if ($embed->getType() !== null) $de->setType($embed->getType());
                        if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
                        if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
                        if ($embed->getColour() !== null) $de->setColor($embed->getColour());
                        if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
                        if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
                        if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
                        if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
                        if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
                        if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
                        foreach ($embed->getFields() as $f) {
                            $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
                        }
                        $embeds[] = $de;
                    }
                    $builder = $builder->setEmbeds($embeds);
                } else {
                    $embed = $e;
                    $de = new DiscordEmbed($this->client->getDiscordClient());
                    if ($embed->getType() !== null) $de->setType($embed->getType());
                    if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
                    if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
                    if ($embed->getColour() !== null) $de->setColor($embed->getColour());
                    if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
                    if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
                    if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
                    if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
                    if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
                    if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
                    foreach ($e->getFields() as $f) {
                        $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
                    }
                    $builder = $builder->setEmbeds([$de]);
                }
            }
            $builder = $builder->setContent($m->getContent());
            $builder = $builder->setStickers($m->getStickers());
            $builder = $builder->setTTS($m->isTTS());
            if ($m instanceof Reply) {
                if ($m->getReferencedMessageId() === null) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to send interaction.", ["Reply message has no referenced message ID."]);
                    $this->logger->debug("Failed to send interaction ({$pk->getUID()}) - Reply message has no referenced message ID.");
                    return;
                }
                $this->getMessage($pk, $m->getChannelId(), $m->getReferencedMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($channel, $builder, $pk, $de) {
                    $builder = $builder->setReplyTo($msg);
                    if ($pk->isEphemeral()) {
                        $builder->_setFlags(64);
                    }
                    $channel->sendMessage($builder)->done(function (DiscordMessage $msg) use ($builder, $pk) {
                        $interaction = $msg->interaction;

                        if ($interaction === null) {
                            $this->resolveRequest($pk->getUID(), true, "Interaction was not found in message (Data turned into Message model)", [ModelConverter::genModelMessage($msg)]);
                            return;
                        }
                        $ephemeral = $pk->isEphemeral();
                        $this->resolveRequest($pk->getUID(), true, "Interaction sent.", [ModelConverter::genModelInteraction($interaction, $builder, $ephemeral)]);

                        $this->logger->debug("Sent Interaction ({$pk->getUID()})");
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to send Interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to send interaction ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                });
            } else {
                if ($pk->isEphemeral()) {
                    $builder->_setFlags(64);
                }
                $channel->sendMessage($builder)->done(function (DiscordMessage $msg) use ($builder, $pk) {
                    $interaction = $msg->interaction;

                    if ($interaction === null) {
                        $this->resolveRequest($pk->getUID(), true, "Interaction was not found in message (Data turned into Message model)", [ModelConverter::genModelMessage($msg)]);
                        return;
                    }
                    $ephemeral = $pk->isEphemeral();
                    $this->resolveRequest($pk->getUID(), true, "Interaction sent.", [ModelConverter::genModelInteraction($interaction, $builder, $ephemeral)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to send Interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to send Interation ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }
        });
    }

    private function handleSendMessage(RequestSendMessage $pk): void
    {
        $this->getChannel($pk, $pk->getMessage()->getChannelId(), $pk->getThreadId(), function (DiscordChannel $channel) use ($pk) {
            $m = $pk->getMessage();
            if ($m instanceof WebhookMessage) {
                $e = $m->getEmbeds();
            } else {
                $e = $m->getEmbed();
            }
            $de = null;
            if ($e !== null) {
                if (is_array($e)) {
                    foreach ($e as $embed) {
                        $de = new DiscordEmbed($this->client->getDiscordClient());
                        if ($embed->getType() !== null) $de->setType($embed->getType());
                        if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
                        if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
                        if ($embed->getColour() !== null) $de->setColor($embed->getColour());
                        if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
                        if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
                        if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
                        if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
                        if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
                        if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
                        foreach ($embed->getFields() as $f) {
                            $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
                        }
                    }
                } else {
                    $embed = $e;
                    $de = new DiscordEmbed($this->client->getDiscordClient());
                    if ($embed->getType() !== null) $de->setType($embed->getType());
                    if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
                    if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
                    if ($embed->getColour() !== null) $de->setColor($embed->getColour());
                    if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
                    if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
                    if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
                    if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
                    if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
                    if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
                    foreach ($e->getFields() as $f) {
                        $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
                    }
                }
            }
            if ($m instanceof Reply) {
                if ($m->getReferencedMessageId() === null) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to send.", ["Reply message has no referenced message ID."]);
                    $this->logger->debug("Failed to send message ({$pk->getUID()}) - Reply message has no referenced message ID.");
                    return;
                }
                $this->getMessage($pk, $m->getChannelId(), $m->getReferencedMessageId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($channel, $pk, $de) {

                    $channel->sendMessage($pk->getMessage()->getContent(), $pk->getMessage()->isTTS(), $de, null, $msg)->done(function (DiscordMessage $msg) use ($pk) {
                        $this->resolveRequest($pk->getUID(), true, "Message sent.", [ModelConverter::genModelMessage($msg)]);
                        $this->logger->debug("Sent message ({$pk->getUID()})");
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to send.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to send message ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                });
            } else {
                $channel->sendMessage($m->getContent(), $m->isTTS(), $de)->done(function (DiscordMessage $msg) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Message sent.", [ModelConverter::genModelMessage($msg)]);
                    $this->logger->debug("Sent message ({$pk->getUID()})");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to send.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to send message ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }
        });
    }

    private function handleEditMessage(RequestEditMessage $pk): void
    {
        $message = $pk->getMessage();
        if ($message->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "No message ID provided.");
            return;
        }
        $this->getChannel($pk, $pk->getMessage()->getChannelId(), $pk->getThreadId(), function (DiscordChannel $channel) use ($pk) {

            $builder = MessageBuilder::new();
            $m = $pk->getMessage();
            if ($m instanceof WebhookMessage) {
                $e = $m->getEmbeds();
            } else {
                $e = $m->getEmbed();
            }
            $de = null;
            if ($e !== null) {
                if (is_array($e)) {
                    $embeds = [];
                    foreach ($e as $embed) {
                        $de = new DiscordEmbed($this->client->getDiscordClient());
                        if ($embed->getType() !== null) $de->setType($embed->getType());
                        if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
                        if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
                        if ($embed->getColour() !== null) $de->setColor($embed->getColour());
                        if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
                        if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
                        if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
                        if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
                        if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
                        if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
                        foreach ($embed->getFields() as $f) {
                            $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
                        }
                        $embeds[] = $de;
                    }
                    $builder = $builder->setEmbeds($embeds);
                } else {
                    $embed = $e;
                    $de = new DiscordEmbed($this->client->getDiscordClient());
                    if ($embed->getType() !== null) $de->setType($embed->getType());
                    if ($embed->getTitle() !== null) $de->setTitle($embed->getTitle());
                    if ($embed->getUrl() !== null) $de->setURL($embed->getUrl());
                    if ($embed->getColour() !== null) $de->setColor($embed->getColour());
                    if ($embed->getAuthor()->getName() !== null) $de->setAuthor($embed->getAuthor()->getName(), $embed->getAuthor()->getIconUrl() ?? "", $embed->getAuthor()->getUrl() ?? "");
                    if ($embed->getThumbnail()->getUrl() !== null) $de->setThumbnail($embed->getThumbnail()->getUrl());
                    if ($embed->getImage()->getUrl() !== null) $de->setImage($embed->getImage()->getUrl());
                    if ($embed->getDescription() !== null) $de->setDescription($embed->getDescription());
                    if ($embed->getFooter()->getText() !== null) $de->setFooter($embed->getFooter()->getText(), $embed->getFooter()->getIconUrl() ?? "");
                    if ($embed->getTimestamp() !== null) $de->setTimestamp($embed->getTimestamp());
                    foreach ($e->getFields() as $f) {
                        $de->addFieldValues($f->getName(), $f->getValue(), $f->isInline());
                    }
                    $builder = $builder->setEmbeds([$de]);
                }
            }
            $this->getMessage($pk, $m->getChannelId(), $m->getId(), $pk->getThreadId(), function (DiscordMessage $msg) use ($m, $builder, $channel, $pk, $de) {

                $builder = $builder->setContent($m->getContent());
                $builder = $builder->setStickers($m->getStickers());
                $builder = $builder->setTTS($m->isTTS());

                $msg->edit($builder)->done(function (DiscordMessage $dMessage) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Message edited.", [ModelConverter::genModelMessage($dMessage)]);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to edit message.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to edit message ({$pk->getUID()}) - {$e->getMessage()}");
                });
            });
        });
    }

    private function handleDeleteMessage(RequestDeleteMessage $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), $pk->getThreadId(), function (DiscordMessage $dMessage) use ($pk) {
            $dMessage->delete()->done(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Successfully deleted message.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delete message.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to delete message ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleKickMember(RequestKickMember $pk): void
    {
        $this->getMember($pk, $pk->getServerId(), $pk->getUserId(), function (DiscordMember $member, DiscordGuild $guild) use ($pk) {
            $guild->members->kick($member)->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Member kicked.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to kick member.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to kick member ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleTimedOutMember(RequestTimedOutMember $pk): void
    {
        $this->getMember($pk, $pk->getServerId(), $pk->getUserId(), function (DiscordMember $member, DiscordGuild $guild) use ($pk) {
            $seconds = $pk->getSeconds();

            $time = Carbon::createSafe(
                Utils::toYears($seconds),
                Utils::toMonths($seconds),
                Utils::toDays($seconds),

                Utils::toHours($seconds),
                Utils::toMinutes($seconds),
                $seconds
            );
            if ($time !== false) {
                $member->timeoutMember($time)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Member Timedout.");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to timeout member.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to timeout member ({$pk->getUID()}) - {$e->getMessage()}");
                });
            } else {
                $this->resolveRequest($pk->getUID(), false, "Failed to create carbon timestamp.");
            }
        });
    }

    private function handleInitialiseBan(RequestInitialiseBan $pk): void
    {
        $this->getServer($pk, $pk->getBan()->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->bans->ban($pk->getBan()->getUserId(), $pk->getBan()->getDaysToDelete(), $pk->getBan()->getReason())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Member banned.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to ban member.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to ban member ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleRevokeBan(RequestRevokeBan $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->unban($pk->getUserId())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Member unbanned.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to unban member.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to unban member ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleInitialiseInvite(RequestInitialiseInvite $pk): void
    {
        $invite = $pk->getInvite();
        $this->getChannel($pk, $invite->getChannelId(), null, function (DiscordChannel $channel) use ($pk, $invite) {
            /** @phpstan-ignore-next-line Poorly documented function on discord.php's side. */
            $channel->createInvite([
                "max_age" => $invite->getMaxAge(),
                "max_uses" => $invite->getMaxUses(),
                "temporary" => $invite->isTemporary(),
                "unique" => true
            ])->done(function (DiscordInvite $dInvite) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Invite initialised.", [ModelConverter::genModelInvite($dInvite)]);
                $this->logger->debug("Invite initialised ({$pk->getUID()})");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to initialise.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to initialise invite ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleInviteAccept(RequestAcceptInvite $pk): void
    {
        $invite = $pk->getInvite();
        $this->getServer($pk, $invite->getServerId(), function (DiscordGuild $guild) use ($pk) {
            /** @phpstan-ignore-next-line Poorly documented function on discord.php's side. */
            $guild->invites->freshen()->done(function (DiscordInviteRepository $invites) use ($pk) {
                /** @var DiscordInvite $invite */
                $invite = $invites->offsetGet($pk->getInvite()->getCode());
                $invite->accept()->done(function () use ($invite, $pk) {
                    $this->resolveRequest($pk->getUID(), true, "Invite accepted.", [ModelConverter::genModelInvite($invite)]);
                    $this->logger->debug("Invite accepted ({$pk->getUID()})");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to accept invite.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to accept invite ({$pk->getUID()}) - {$e->getMessage()}");
                });
            });
        });
    }

    private function handleRevokeInvite(RequestRevokeInvite $pk): void
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->invites->freshen()->done(function (DiscordInviteRepository $invites) use ($pk) {
                /** @var DiscordInvite $dInvite */
                $dInvite = $invites->offsetGet($pk->getInviteCode());
                $invites->delete($dInvite)->done(function (DiscordInvite $dInvite) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Invite revoked.");
                    $this->logger->debug("Invite revoked ({$pk->getUID()})");
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to revoke.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to revoke invite ({$pk->getUID()}) - {$e->getMessage()}");
                });
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to freshen invites.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to revoke invite ({$pk->getUID()}) - invite freshen error: {$e->getMessage()}");
            });
        });
    }

    //---------------------------------------------------

    private function getServer(Packet $pk, string $server_id, callable $cb): void
    {
        $this->client->getDiscordClient()->guilds->fetch($server_id)->done(function (DiscordGuild $guild) use ($cb) {
            $cb($guild);
        }, function (\Throwable $e) use ($pk) {
            $this->resolveRequest($pk->getUID(), false, "Failed to fetch server.", [$e->getMessage(), $e->getTraceAsString()]);
            $this->logger->debug("Failed to process request (" . get_class($pk) . "|{$pk->getUID()}) - server error: {$e->getMessage()}");
        });
    }
    private function getTemplate(Packet $pk, string $code, string $server_id, callable $cb): void
    {
        $this->getServer($pk, $server_id, function (DiscordGuild $guild) use ($code, $cb) {
            $guild->templates->fetch($code)->done(function (DiscordTemplate $template) use ($cb) {
                $cb($template);
            });
        });
    }
    private function getStage(Packet $pk, string $server_id, string $stage_id, callable $cb): void
    {
        $this->getServer($pk, $server_id, function (DiscordGuild $guild) use ($cb, $pk, $stage_id) {
            $guild->stage_instances->fetch($stage_id)->done(function (DiscordStage $stage) use ($guild, $cb) {
                $cb($guild, $stage);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch Stage.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to process request (" . get_class($pk) . "|{$pk->getUID()}) - message error: {$e->getMessage()}");
            });
        });
    }

    //Includes DM Channels.
    private function getChannel(Packet $pk, string $channel_id, ?string $thread_id = null, callable $cb): void
    {
        $c = $this->client->getDiscordClient()->getChannel($channel_id);
        if ($c === null) {
            /** @var DiscordUser|null $u */
            $u = $this->client->getDiscordClient()->users->offsetGet($channel_id);
            if ($u === null) {
                $this->resolveRequest($pk->getUID(), false, "Failed to find channel/user.", ["Failed to find channel from local storage."]);
                $this->logger->debug("Failed to process request (" . get_class($pk) . "|{$pk->getUID()}) - channel error: Failed to find channel from local storage.");
            } else {
                $u->getPrivateChannel()->then(function (DiscordChannel $channel) use ($cb) {
                    $cb($channel);
                }, function (\Throwable $e) use ($pk) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to fetch private channel.", [$e->getMessage(), $e->getTraceAsString()]);
                    $this->logger->debug("Failed to process request (" . get_class($pk) . "|{$pk->getUID()}) - private channel error: {$e->getMessage()}");
                });
            }
        } else {
            if ($thread_id) {
                $c->threads->fetch($thread_id)->then(function (DiscordThread $thread) use ($cb, $pk) {
                    $cb($thread);
                });
            }
            $cb($c);
        }
    }
    private function getApplication(): DiscordApplication
    {
        $app = $this->client->getDiscordClient()->application;
        return $app;
    }


    private function getMessage(Packet $pk, string $channel_id, string $message_id, ?string $thread_id = null, callable $cb): void
    {
        $this->getChannel($pk, $channel_id, $thread_id, function ($channel) use ($pk, $message_id, $cb) {

            $channel->messages->fetch($message_id)->done(function (DiscordMessage $dMessage) use ($cb) {
                $cb($dMessage);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch message.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to process request (" . get_class($pk) . "|{$pk->getUID()}) - message error: {$e->getMessage()}");
            });
        });
    }

    private function getMember(Packet $pk, string $server_id, string $user_id, callable $cb): void
    {
        $this->getServer($pk, $server_id, function (DiscordGuild $guild) use ($pk, $user_id, $cb) {
            $guild->members->fetch($user_id)->then(function (DiscordMember $member) use ($guild, $cb) {
                $cb($member, $guild);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch member.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to process request (" . get_class($pk) . "|{$pk->getUID()}) - member error: {$e->getMessage()}");
            });
        });
    }

    //---------------------------------------------------

    private function resolveRequest(int $pid, bool $successful = true, string $response = "Success.", array $data = []): void
    {
        $pk = new Resolution($pid, $successful, $response, $data);
        $this->client->getThread()->writeOutboundData($pk);
    }

    public function sendHeartbeat(): void
    {
        $pk = new Heartbeat(microtime(true));
        $this->client->getThread()->writeOutboundData($pk);
    }

    public function checkHeartbeat(): void
    {
        if ($this->lastHeartbeat === null) return;
        if (($diff = (microtime(true) - $this->lastHeartbeat)) > $this->client->getConfig()['protocol']['heartbeat_allowance']) {
            $this->logger->emergency("Plugin has not responded for {$diff} seconds, closing thread.");
            $this->client->close();
        }
    }

    public function getLastHeartbeat(): ?float
    {
        return $this->lastHeartbeat;
    }
}
