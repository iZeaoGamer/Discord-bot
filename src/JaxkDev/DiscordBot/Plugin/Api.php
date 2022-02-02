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

namespace JaxkDev\DiscordBot\Plugin;

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
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestRevokeInvite;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestSendFile;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestSendMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestRevokeBan;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUnpinMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdatePresence;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateNickname;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateRole;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateWebhook;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestMessageBulkDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestJoinVoiceChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestMoveVoiceChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadCreate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadUpdate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadMessageCreate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestLeaveVoiceChannel;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestMoveMember;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestMuteMember;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUnmuteMember;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestServerAuditLog;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestServerTransfer;
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
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateReaction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateReaction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDeleteReaction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchReaction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFetchWelcomeScreen;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestUpdateWelcomeScreen;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateServerFromTemplate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCrossPostMessage;
use JaxkDev\DiscordBot\Libs\React\Promise\PromiseInterface;
use JaxkDev\DiscordBot\Models\User\Activity;
use JaxkDev\DiscordBot\Models\Server\Ban;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Channels\VoiceChannel;
use JaxkDev\DiscordBot\Models\Thread\Thread;
use JaxkDev\DiscordBot\Models\Channels\DMChannel;
use JaxkDev\DiscordBot\Models\Interactions\Command\Command;
use JaxkDev\DiscordBot\Models\Server\ServerTemplate;
use JaxkDev\DiscordBot\Models\Server\Server;
use JaxkDev\DiscordBot\Models\Channels\Stage;
use JaxkDev\DiscordBot\Models\Server\Sticker;
use JaxkDev\DiscordBot\Models\Server\ServerScheduledEvent;
use JaxkDev\DiscordBot\Models\Channels\Messages\Reaction;
use JaxkDev\DiscordBot\Models\Server\Emoji;
use JaxkDev\DiscordBot\Models\Server\Invite;
use JaxkDev\DiscordBot\Models\User\Member;
use JaxkDev\DiscordBot\Models\Channels\Messages\Message;
use JaxkDev\DiscordBot\Models\Channels\Messages\Webhook as WebhookMessage;
use JaxkDev\DiscordBot\Models\Server\Webhook;
use JaxkDev\DiscordBot\Models\Server\Role;
use JaxkDev\DiscordBot\Models\Channels\Messages\Embed\Embed;
use JaxkDev\DiscordBot\Models\Interactions\Interaction;

use Discord\Builders\MessageBuilder;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestAcceptInvite;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestTimedOutMember;
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
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestFollowupMessage;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadAchive;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadJoin;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadLeave;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestThreadUnachive;

use function JaxkDev\DiscordBot\Libs\React\Promise\reject as rejectPromise;
/**
 * For internal and developers use for interacting with the discord bot.
 *
 * @see Main::getApi() To get instance.
 * @see Storage For all discord data.
 */
class Api
{

    /** @var Main */
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /** Accepts an invite invitation.
     * 
     * @param Invite $invite
     * 
     * @return PromiseInterface Resolves with a Invite Model.
     */
    public function acceptInvite(Invite $invite): PromiseInterface{
        $pk = new RequestAcceptInvite($invite);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Joins a new thread
     * 
     * @param Thread $thread
     * @param string $parent_id ID of the channel the thread is in.
     * 
     * @return PromiseInterface Resolves with no data.
    */
    public function joinThread(Thread $thread, string $parent_id): PromiseInterface{
        if(!Utils::validDiscordSnowflake($parent_id)){
            return rejectPromise(new ApiRejection("Channel ID: {$parent_id} is invalid."));
        }
        $pk = new RequestThreadJoin($thread, $parent_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Leaves a thread
     * 
     * @param Thread $thread
     * @param string $parent_id ID of the channel the thread is in.
     * 
     * @return PromiseInterface Resolves with no data.
    */
    public function leaveThread(Thread $thread, string $parent_id): PromiseInterface{
        if(!Utils::validDiscordSnowflake($parent_id)){
            return rejectPromise(new ApiRejection("Channel ID: {$parent_id} is invalid."));
        }
        $pk = new RequestThreadLeave($thread, $parent_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

     /** Achives a thread
     * 
     * @param Thread $thread
     * @param string $parent_id ID of the channel the thread is in.
     * 
     * @return PromiseInterface Resolves with no data.
    */
    public function achiveThread(Thread $thread, string $parent_id): PromiseInterface{
        if(!Utils::validDiscordSnowflake($parent_id)){
            return rejectPromise(new ApiRejection("Channel ID: {$parent_id} is invalid."));
        }
        $pk = new RequestThreadAchive($thread, $parent_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

     /** Un achives a thread
     * 
     * @param Thread $thread
     * @param string $parent_id ID of the channel the thread is in.
     * 
     * @return PromiseInterface Resolves with no data.
    */
    public function unachiveThread(Thread $thread, string $parent_id): PromiseInterface{
        if(!Utils::validDiscordSnowflake($parent_id)){
            return rejectPromise(new ApiRejection("Channel ID: {$parent_id} is invalid."));
        }
        $pk = new RequestThreadUnachive($thread, $parent_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates an interaction response without the hack.
     * @param Interaction $interaction
     * @param MessageBuilder|null $builder
     * @param strong $content
     * @param Embed|null $embed
     * @param bool $ephemeral
     * 
     * @return PromiseInterface Resolves with a Message Model.
     */
    public function createInteractionResponse(Interaction $interaction, ?MessageBuilder $builder = null, string $content = "", ?Embed $embed = null, bool $ephemeral = false): PromiseInterface{
        $pk = new RequestRespondInteraction($interaction, $builder, $content, $embed, $ephemeral);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
     /** Responds to an interaction with respondWithMessage().
     * @param Interaction $interaction
     * @param MessageBuilder|null $builder
     * @param strong $content
     * @param Embed|null $embed
     * @param bool $ephemeral
     * 
     * @return PromiseInterface Resolves with a Message Model.
     */
    public function respondInteractionWithMessage(Interaction $interaction, ?MessageBuilder $builder = null, string $content = "", ?Embed $embed = null, bool $ephemeral = false): PromiseInterface{
        $pk = new RequestFollowupMessage($interaction, $builder, $content, $embed, $ephemeral);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /** Creates a guild or global command
     * @param Command $command
     * @param Permission[] $permissions
     * 
     * @return PromiseInterface Resolves with a Command Model.
     */
    public function createCommand(Command $command, array $permissions = []): PromiseInterface
    {
        /** @var Permission[] $permissions */
        $pk = new RequestCreateCommand($command, $permissions);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Updates a guild or global command - ID must be present.
     * @param Command $command
     * @param Permission[] $permissions
     * 
     * @return PromiseInterface Resolves with a Command Model.
     */
    public function updateCommand(Command $command, array $permissions = []): PromiseInterface
    {
        /** @var Permission[] $permissions */
        $pk = new RequestUpdateCommand($command, $permissions);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Deletes a guild or global command.
     * @param string $id
     * @param string|null $server_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteCommand(string $id, ?string $server_id = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($id)) {
            return rejectPromise(new ApiRejection("Command ID: {$id} is invalid."));
        }
        if ($server_id) {
            if (!Utils::validDiscordSnowflake($server_id)) {
                return rejectPromise(new ApiRejection("Server ID: {$server_id} is invalid."));
            }
        }
        $pk = new RequestDeleteCommand($id, $server_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /** Fetches all guild/global commands
     * @param string|null $server_id - Null when fetching all global commands.
     * 
     * @return PromiseInterface Resolves with a array of Commands
     */
    public function fetchCommand(?string $server_id = null): PromiseInterface
    {
        if ($server_id) {
            if (!Utils::validDiscordSnowflake($server_id)) {
                return rejectPromise(new ApiRejection("Server ID: {$server_id} is invalid."));
            }
        }
        $pk = new RequestFetchCommands($server_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }


    /** Cross posts a message to the followed servers.
     * @param string $message_id
     * @param string $channel_id
     * 
     * @return PromiseInterface Resolves with a Message Model.
     */
    public function crossPostMessage(string $message_id, string $channel_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Message ID: '{$message_id}' is invalid!"));
        }
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Channel ID: '{$channel_id}' is invalid!"));
        }
        $pk = new RequestCrossPostMessage($message_id, $channel_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }


    /** Fetches a server's welcome screen.
     * @param string $server_id
     * 
     * @return PromiseInterface Resolves with a Welcome Screen Model.
     */
    public function fetchWelcomeScreen(string $server_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Server ID: {$server_id} is invalid."));
        }
        $pk = new RequestFetchWelcomeScreen($server_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /** Updates a server's welcome screen.
     * @param string $server_id
     * @param bool $enabled
     * @param array{"channel_id": string, "description": string, "emoji_id": ?string, "emoji_name": ?string} $options
     * @param string $description
     * 
     * @return PromiseInterface Resolves with a Welcome Screen Model.
     */
    public function updateWelcomeScreen(string $server_id, bool $enabled, array $options, string $description): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Server ID: {$server_id} is invalid."));
        }
        if (strlen($description) > 140) {
            return rejectPromise(new ApiRejection("Description must be below 140 characters long."));
        }
        $pk = new RequestUpdateWelcomeScreen($server_id, $enabled, $options, $description);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates a reaction.
     * @param Reaction $reaction
     * 
     * @return PromiseInterface Resolves with a Reaction Model.
     */
    public function createReaction(Reaction $reaction): PromiseInterface
    {
        $pk = new RequestCreateReaction($reaction);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Updates a reaction. ID must be present.
     * @param Reaction $reaction
     * 
     * @return PromiseInterface Resolves with a Reaction Model.
     */
    public function updateReaction(Reaction $reaction): PromiseInterface
    {
        $pk = new RequestUpdateReaction($reaction);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Deletes a reaction.
     * @param string $channel_id
     * @param string $message_id
     * @param string $reaction_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteReaction(string $channel_id, string $message_id, string $reaction_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Channel ID: {$channel_id} is invalid!"));
        }
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Message ID: {$message_id} is invalid!"));
        }
        if (!Utils::validDiscordSnowflake($reaction_id)) {
            return rejectPromise(new ApiRejection("Reaction ID: {$reaction_id} is invalid!"));
        }
        $pk = new RequestDeleteReaction($channel_id, $message_id, $reaction_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Fetches a reaction.
     * @param string $channel_id
     * @param string $message_id
     * @param string $reaction_id
     * 
     * @return PromiseInterface Resolves with a Reaction Model.
     */
    public function fetchReaction(string $channel_id, string $message_id, string $reaction_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Channel ID: {$channel_id} is invalid!"));
        }
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Message ID: {$message_id} is invalid!"));
        }
        if (!Utils::validDiscordSnowflake($reaction_id)) {
            return rejectPromise(new ApiRejection("Reaction ID: {$reaction_id} is invalid!"));
        }
        $pk = new RequestFetchReaction($channel_id, $message_id, $reaction_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates a scheduled event within a guild.
     * @param ServerScheduledEvent
     * 
     * @return PromiseInterface Resolves with a ServerScheduledEvent Model.
     */
    public function createEvent(ServerScheduledEvent $schedule): PromiseInterface
    {
        $pk = new RequestScheduleCreate($schedule);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Updates a scheduled event within a guild.
     * @param ServerScheduledEvent
     * 
     * @return PromiseInterface Resolves with a ServerScheduledEvent Model.
     */
    public function updateEvent(ServerScheduledEvent $schedule): PromiseInterface
    {
        $pk = new RequestScheduleUpdate($schedule);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Deletes a scheduled event within a guild.
     * @param string $serverId
     * @param string $id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteEvent(string $serverId, string $id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($serverId)) {
            return rejectPromise(new ApiRejection("Server ID: {$serverId} is invalid!"));
        }
        if (!Utils::validDiscordSnowflake($id)) {
            return rejectPromise(new ApiRejection("Server Scheduled Event ID: {$id} is invalid!"));
        }

        $pk = new RequestScheduleDelete($serverId, $id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates a server template within a guild. 
     * @param ServerTemplate
     * 
     * @return PromiseInterface Resolves with a ServerTemplate model.
     */
    public function createTemplate(ServerTemplate $template): PromiseInterface
    {
        $pk = new RequestTemplateCreate($template);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /** Updates a server template within a guild. 
     * @param ServerTemplate
     * 
     * @return PromiseInterface Resolves with a ServerTemplate model.
     */
    public function updateTemplate(ServerTemplate $template): PromiseInterface
    {
        $pk = new RequestTemplateUpdate($template);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /**  Deletes a server template using server Id and Template code. 
     * @param string $server_id
     * @param string $code
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteTemplate(string $server_id, string $code): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Server ID: {$server_id} is invalid!"));
        }
        $pk = new RequestTemplateDelete($server_id, $code);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /** Updates a emoji within a guild.
     * @param Emoji $emoji
     * 
     * @return PromiseInterface Resolves with a Emoji Model.
     */
    public function updateEmoji(Emoji $emoji): PromiseInterface
    {
        $pk = new RequestEmojiUpdate($emoji);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates a stage channel.
     * @param Stage $stage
     * 
     * @return PromiseInterface Resolves with a Stage Model.
     */
    public function createStage(Stage $stage): PromiseInterface
    {
        $pk = new RequestStageCreate($stage);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Updates a stage channel.
     * @param Stage $stage
     * @return PromiseInterface Resolves with a Stage Model.
     */
    public function updateStage(Stage $stage): PromiseInterface
    {
        $pk = new RequestStageUpdate($stage);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /** Deletes a stage channel.
     * @param string $server_id
     * @param string $stage_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteStage(string $server_id, string $stage_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Server ID: {$server_id} is invalid!"));
        }
        if (!Utils::validDiscordSnowflake($stage_id)) {
            return rejectPromise(new ApiRejection("Stage ID: {$stage_id} is invalid!"));
        }
        $pk = new RequestStageDelete($server_id, $stage_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates a sticker.
     * @param Sticker $sticker
     * 
     * @return PromiseInterface Resolves with a Sticker Model.
     */
    public function createSticker(Sticker $sticker): PromiseInterface
    {
        $pk = new RequestStickerCreate($sticker);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /** Updates a sticker.
     * @param Sticker $sticker
     * 
     * @return PromiseInterface Resolves with a Sticker Model.
     */
    public function updateSticker(Sticker $sticker): PromiseInterface
    {
        $pk = new RequestStickerUpdate($sticker);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Deletes a sticker
     * @param string $id
     * @param string $serverId
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteSticker(string $id, string $serverId): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($id)) {
            return rejectPromise(new ApiRejection("Sticker ID: {$id} is invalid."));
        }
        if (!Utils::validDiscordSnowflake($serverId)) {
            return rejectPromise(new ApiRejection("Server ID: {$serverId} is invalid."));
        }
        $pk = new RequestStickerDelete($id, $serverId);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates a reply delay
     * @param Message $message
     * @param int $delay (In seconds)
     * 
     * @return PromiseInterface Resolves with a Message model.
     */
    public function delayReply(Message $message, int $delay): PromiseInterface
    {
        if ($delay <= 0) {
            return rejectPromise(new ApiRejection("Reply Delay counter: {$delay} must be above 0."));
        }
        $pk = new RequestDelayReply($message, $delay);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates a delete delay 
     * @param string $message_id
     * @param string $channel_id
     * @param int $delay (In seconds)
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function delayDelete(string $message_id, string $channel_id, int $delay): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Invalid message id {$message_id}"));
        }
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel id {$channel_id}"));
        }
        if ($delay <= 0) {
            return rejectPromise(new ApiRejection("Delete Delay counter: {$delay} must be above 0."));
        }
        $pk = new RequestDelayDelete($message_id, $channel_id, $delay);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates an interaction. 
     * 
     * @param MessageBuilder $builder
     * @param Message $message
     * 
     * @return PromiseInterface Resolves with a Interaction Model. If Interaction not found, returns a Message Model instead.
     */
    public function createInteraction(MessageBuilder $builder, Message $message, bool $ephemeral = false): PromiseInterface
    {
        $pk = new RequestCreateInteraction($builder, $message, $ephemeral);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Modifys an interaction.
     * @param MessageBuilder $builder
     * @param Message $message
     * 
     * @return PromiseInterface Resolves with a Interaction Model. If Interaction not found, returns a Message Model instead.
     */
    public function modifyInteraction(MessageBuilder $builder, Message $message, bool $ephemeral = false): PromiseInterface
    {
        if (strlen($message->getContent()) > 2000) {
            return rejectPromise(new ApiRejection("Message content cannot be larger than 2000 characters for bots."));
        }
        $pk = new RequestModifyInteraction($builder, $message, $ephemeral);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Creates a normal webhook inside a channel.
     *
     * @param Webhook $webhook
     * 
     * @return PromiseInterface Resolves with a Webhook model.
     */
    public function createWebhook(Webhook $webhook): PromiseInterface
    {
        if ($webhook->getType() !== Webhook::TYPE_NORMAL) {
            return rejectPromise(new ApiRejection("Only normal webhooks can be created right now."));
        }
        if (!Utils::validDiscordSnowflake($webhook->getChannelId())) {
            return rejectPromise(new ApiRejection("Webhook channel ID is invalid."));
        }
        if ($webhook->getId() !== null or $webhook->getToken() !== null) {
            return rejectPromise(new ApiRejection("Webhook already has an ID/token, it cannot be created twice."));
        }
        $pk = new RequestCreateWebhook($webhook);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Update a webhooks name or avatar.
     *
     * @param Webhook $webhook
     * 
     * @return PromiseInterface Resolves with a Webhook model.
     */
    public function updateWebhook(Webhook $webhook): PromiseInterface
    {
        if ($webhook->getType() !== Webhook::TYPE_NORMAL) {
            return rejectPromise(new ApiRejection("Only normal webhooks can be edited right now."));
        }
        if ($webhook->getId() === null or $webhook->getToken() === null) {
            return rejectPromise(new ApiRejection("Webhook does not have an ID/token, it cannot be edited before being created."));
        }
        if (!Utils::validDiscordSnowflake($webhook->getId())) {
            return rejectPromise(new ApiRejection("Invalid webhook ID '{$webhook->getId()}'."));
        }
        $pk = new RequestUpdateWebhook($webhook);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Delete a webhook
     *
     * @param string $channel_id
     * @param string $webhook_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteWebhook(string $channel_id, string $webhook_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($webhook_id)) {
            return rejectPromise(new ApiRejection("Invalid webhook ID '$webhook_id'."));
        }
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        $pk = new RequestDeleteWebhook($channel_id, $webhook_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    //createServer will not be added due to security issues.

    /**
     * Leave a discord server.
     *
     * @param string $server_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function leaveServer(string $server_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Invalid server ID '$server_id'."));
        }
        $pk = new RequestLeaveServer($server_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Fetch all webhooks that are linked to a channel.
     *
     * @param string $channel_id
     * 
     * @return PromiseInterface Resolves with an array of Webhook models.
     */
    public function fetchWebhooks(string $channel_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        $pk = new RequestFetchWebhooks($channel_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Fetch all the pinned messages in a channel.
     *
     * Note you could fetch individual messages by id using fetchMessage from channel::pins but this is easier.
     *
     * @param string $channel_id
     * @param string|null $thread_id
     * 
     * @return PromiseInterface Resolves with an array of Message models.
     */
    public function fetchPinnedMessages(string $channel_id, ?string $thread_id = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if($thread_id){
        if(!Utils::validDiscordSnowflake($thread_id)){
            return rejectPromise(new ApiRejection("Thread Channel ID: {$thread_id} is invalid."));
        }
    }
        $pk = new RequestFetchPinnedMessages($channel_id, $thread_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Fetch a message by ID.
     *
     * @param string $channel_id
     * @param string $message_id
     * @param string|null $thread_id
     * 
     * @return PromiseInterface Resolves with a Message model.
     */
    public function fetchMessage(string $channel_id, string $message_id, ?string $thread_id = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Invalid message ID '$message_id'."));
        }
        if($thread_id){
        if(!Utils::validDiscordSnowflake($thread_id)){
            return rejectPromise(new ApiRejection("Thread Channel ID: {$thread_id} is invalid."));
        }
    }
        $pk = new RequestFetchMessage($channel_id, $message_id, $thread_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Pin a message to the channel.
     *
     * @param string $channel_id
     * @param string $message_id
     * @param string|null $thread_id
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with a Message Model.
     */
    public function pinMessage(string $channel_id, string $message_id, ?string $thread_id = null, ?string $reason = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Invalid message ID '$message_id'."));
        }
        if($thread_id){
        if(!Utils::validDiscordSnowflake($thread_id)){
            return rejectPromise(new ApiRejection("Thread Channel ID: {$thread_id} is invalid."));
        }
    }
        $pk = new RequestPinMessage($channel_id, $message_id, $thread_id, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Un-pin a message to the channel.
     *
     * @param string $channel_id
     * @param string $message_id
     * @param string|null $thread_id
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function unpinMessage(string $channel_id, string $message_id, ?string $thread_id = null, ?string $reason = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Invalid message ID '$message_id'."));
        }
        if($thread_id){
        if(!Utils::validDiscordSnowflake($thread_id)){
            return rejectPromise(new ApiRejection("Thread Channel ID: {$thread_id} is invalid."));
        }
    }
        $pk = new RequestUnpinMessage($channel_id, $message_id, $thread_id, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Create a role.
     *
     * @param Role $role
     * 
     * @return PromiseInterface Resolves with a Role model.
     */
    public function createRole(Role $role): PromiseInterface
    {
        $pk = new RequestCreateRole($role);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Update a already created role, ID must be present.
     *
     * Note you cannot change the hoisted position of the 'everyone' role, or move any role higher than the bots highest role.
     *
     * If hoisted position changed, all roles that move to account for the change will emit an updated event.
     *
     * @param Role $role
     * 
     * @return PromiseInterface Resolves with a Role model.
     */
    public function updateRole(Role $role): PromiseInterface
    {
        if ($role->getId() === null) {
            return rejectPromise(new ApiRejection("Role ID must be present when updating."));
        }
        $pk = new RequestUpdateRole($role);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Delete a role.
     *
     * @param string $server_id
     * @param string $role_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteRole(string $server_id, string $role_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Invalid server ID '$server_id'."));
        }
        if (!Utils::validDiscordSnowflake($role_id)) {
            return rejectPromise(new ApiRejection("Invalid role ID '$role_id'."));
        }
        $pk = new RequestDeleteRole($server_id, $role_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Remove a role from a member.
     *
     * @param string $member_id
     * @param string $role_id
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function removeRole(string $member_id, string $role_id, ?string $reason = null): PromiseInterface
    {
        [$sid, $uid] = explode(".", $member_id);
        if (!Utils::validDiscordSnowflake($sid) or !Utils::validDiscordSnowflake($uid)) {
            return rejectPromise(new ApiRejection("Invalid member ID '$member_id'."));
        }
        if (!Utils::validDiscordSnowflake($role_id)) {
            return rejectPromise(new ApiRejection("Invalid role ID '$role_id'."));
        }
        $pk = new RequestRemoveRole($sid, $uid, $role_id, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Give the member a role.
     *
     * @param string $member_id
     * @param string $role_id
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with a Role Model.
     */
    public function addRole(string $member_id, string $role_id, ?string $reason = null): PromiseInterface
    {
        [$sid, $uid] = explode(".", $member_id);
        if (!Utils::validDiscordSnowflake($sid) or !Utils::validDiscordSnowflake($uid)) {
            return rejectPromise(new ApiRejection("Invalid member ID '$member_id'."));
        }
        if (!Utils::validDiscordSnowflake($role_id)) {
            return rejectPromise(new ApiRejection("Invalid role ID '$role_id'."));
        }
        $pk = new RequestAddRole($sid, $uid, $role_id, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Remove a single reaction.
     *
     * @param string $channel_id
     * @param string $message_id
     * @param string $user_id
     * @param string $emoji Raw emoji eg '👍'
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function removeReaction(string $channel_id, string $message_id, string $user_id, string $emoji): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Invalid message ID '$message_id'."));
        }
        if (!Utils::validDiscordSnowflake($user_id)) {
            return rejectPromise(new ApiRejection("Invalid user ID '$user_id'."));
        }
        $pk = new RequestRemoveReaction($channel_id, $message_id, $user_id, $emoji);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Remove all reactions on a message.
     *
     * @param string      $channel_id
     * @param string      $message_id
     * @param string|null $emoji If no emoji specified ALL reactions by EVERYONE will be deleted,
     *                                  if specified everyone's reaction with that emoji will be removed.
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function removeAllReactions(string $channel_id, string $message_id, ?string $emoji = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Invalid message ID '$message_id'."));
        }
        $pk = new RequestRemoveAllReactions($channel_id, $message_id, $emoji);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Add a reaction to a message.
     *
     * Note, If you have already reacted with the emoji provided it will still respond with a successful promise resolution.
     *
     * @param string $channel_id
     * @param string $message_id
     * @param string $emoji            MUST BE THE ACTUAL EMOJI CHARACTER eg '👍'
     * 
     * @return PromiseInterface Resolves with a Message Model.
     */
    public function addReaction(string $channel_id, string $message_id, string $emoji): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Invalid message ID '$message_id'."));
        }
        $pk = new RequestAddReaction($channel_id, $message_id, $emoji);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * "Generally bots should not implement this. However, if a bot is responding to a command and expects the computation
     * to take a few seconds, this endpoint may be called to let the user know that the bot is processing their message."
     * The 'typing' effect will last for 10s
     *
     * DO NOT ABUSE THIS.
     *
     * @param string $channel_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function broadcastTyping(string $channel_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        $pk = new RequestBroadcastTyping($channel_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Sends a new presence to replace the current one the bot has.
     *
     * @param Activity $activity
     * @param string $status See Member::STATUS_ constants.
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function updateBotPresence(Activity $activity, string $status = Member::STATUS_ONLINE): PromiseInterface
    {
        if (!in_array($status, [Member::STATUS_ONLINE, Member::STATUS_IDLE, Member::STATUS_OFFLINE, Member::STATUS_DND])) {
            return rejectPromise(new ApiRejection("Invalid status '$status'."));
        }
        $pk = new RequestUpdatePresence($activity, $status);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Attempt to ban a member.
     *
     * @param Ban $ban
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function initialiseBan(Ban $ban): PromiseInterface
    {
        $pk = new RequestInitialiseBan($ban);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Attempt to revoke a ban.
     *
     * @param string $server_id
     * @param string $user_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function revokeBan(string $server_id, string $user_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Invalid server ID '$server_id'."));
        }
        if (!Utils::validDiscordSnowflake($user_id)) {
            return rejectPromise(new ApiRejection("Invalid user ID '$user_id'."));
        }
        $pk = new RequestRevokeBan($server_id, $user_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Attempt to kick a member.
     *
     * @param string $member_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function kickMember(string $member_id): PromiseInterface
    {
        [$sid, $uid] = explode(".", $member_id);
        if (!Utils::validDiscordSnowflake($sid) or !Utils::validDiscordSnowflake($uid)) {
            return rejectPromise(new ApiRejection("Invalid member ID '$member_id'."));
        }
        $pk = new RequestKickMember($sid, $uid);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /**
     * Attempt to timeout a member.
     *
     * @param string $member_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function timeoutMember(string $member_id, int $seconds): PromiseInterface
    {
        [$sid, $uid] = explode(".", $member_id);
        if (!Utils::validDiscordSnowflake($sid) or !Utils::validDiscordSnowflake($uid)) {
            return rejectPromise(new ApiRejection("Invalid member ID '$member_id'."));
        }
        if ($seconds <= 0) {
            return rejectPromise(new ApiRejection("Invalid time: '{$seconds}'."));
        }
        $pk = new RequestTimedOutMember($sid, $uid, $seconds);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Sends the Message to discord.
     *
     * @param Message $message
     * 
     * @return PromiseInterface Resolves with a Message model.
     */
    public function sendMessage(Message $message): PromiseInterface
    {
        if ($message instanceof WebhookMessage) {
            //You can execute webhooks yourself using Api::fetchWebhooks() and use its token.
            return rejectPromise(new ApiRejection("Webhook messages cannot be sent, only received."));
        }
        if (strlen($message->getContent()) > 2000) {
            return rejectPromise(new ApiRejection("Message content cannot be larger than 2000 characters for bots."));
        }
        $pk = new RequestSendMessage($message);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Send a local file to a text channel.
     *
     * @param string      $channel_id
     * @param string      $file_path Full file path on disk.
     * @param string      $message   Optional text/message to send with the file
     * @param string|null $file_name Optional file_name to show in discord, Prefix with 'SPOILER_' to make as spoiler.
     * 
     * @return PromiseInterface Resolves with a Message model.
     */
    public function sendFile(string $channel_id, string $file_path, string $message = "", string $file_name = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if (!is_file($file_path)) {
            return rejectPromise(new ApiRejection("Invalid file path '$file_path' no such file exists."));
        }
        if (strlen($message) > 2000) {
            return rejectPromise(new ApiRejection("Message cannot be larger than 2000 characters for bots."));
        }
        if ($file_name === null) {
            $file_name = basename($file_path);
        }
        $pk = new RequestSendFile($channel_id, $file_name, $file_path, $message);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Edit a sent message.
     *
     * Note you can't convert a 'REPLY' message to a normal 'MESSAGE'.
     *
     * @param Message $message
     * 
     * @return PromiseInterface Resolves with a Message model.
     */
    public function editMessage(Message $message): PromiseInterface
    {
        if ($message->getId() === null) {
            return rejectPromise(new ApiRejection("Message must have a valid ID to be able to edit it."));
        }
        if (!Utils::validDiscordSnowflake($message->getId())) {
            return rejectPromise(new ApiRejection("Message ID: {$message->getId()} is invalid."));
        }
        if (strlen($message->getContent()) > 2000) {
            return rejectPromise(new ApiRejection("Message content cannot be larger than 2000 characters for bots."));
        }
        $pk = new RequestEditMessage($message);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Delete a sent message.
     *
     * @param string $message_id
     * @param string $channel_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteMessage(string $message_id, string $channel_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Invalid message ID '$message_id'."));
        }
        $pk = new RequestDeleteMessage($message_id, $channel_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /**
     * Deletes a mass amount of messages, from order of time sent.
     *
     * @param string $channel_id
     * @param int $deleteLimit
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function bulkDelete(string $channel_id, int $deleteLimit, ?string $reason = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        if ($deleteLimit <= 0) {
            return rejectPromise(new ApiRejection("Delete limit {$deleteLimit} is invalid."));
        }

        $pk = new RequestMessageBulkDelete($channel_id, $deleteLimit);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Create a server channel.
     *
     * @param ServerChannel $channel CategoryChannel, TextChannel or VoiceChannel.
     * 
     * @return PromiseInterface Resolves with a Channel model.
     */
    public function createChannel(ServerChannel $channel): PromiseInterface
    {
        $pk = new RequestCreateChannel($channel);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Update a server channel, ID Must be present.
     *
     * Note, Pins can NOT be updated directly.
     *
     * @see Api::pinMessage()
     * @see Api::unpinMessage()
     *
     * @param ServerChannel $channel
     * 
     * @return PromiseInterface Resolves with a Channel model.
     */
    public function updateChannel(ServerChannel $channel): PromiseInterface
    {
        if ($channel->getId() === null) {
            return rejectPromise(new ApiRejection("Channel ID must be present."));
        }
        if (!Utils::validDiscordSnowflake($channel->getId())) {
            return rejectPromise(new ApiRejection("Channel ID: {$channel->getId()} is invalid."));
        }
        $pk = new RequestUpdateChannel($channel);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /**
     * Delete a channel in a server, you cannot delete private channels (DM's)
     *
     * @param string $server_id
     * @param string $channel_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteChannel(string $server_id, string $channel_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Invalid server ID '$server_id'."));
        }
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        $pk = new RequestDeleteChannel($server_id, $channel_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /**
     * Create a DM channel.
     *
     * @param DMChannel $channel
     * 
     * @return PromiseInterface Resolves with a Channel model.
     */
    public function createDMChannel(DMChannel $channel): PromiseInterface
    {
        $pk = new RequestCreateDMChannel($channel);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Update a DM channel, ID Must be present.
     *
     * @param DMChannel $channel
     * 
     * @return PromiseInterface Resolves with a Channel model.
     */
    public function updateDMChannel(DMChannel $channel): PromiseInterface
    {
        if ($channel->getId() === null) {
            return rejectPromise(new ApiRejection("DM Channel ID must be present."));
        }
        if (!Utils::validDiscordSnowflake($channel->getId())) {
            return rejectPromise(new ApiRejection("Channel ID: {$channel->getId()} is invalid."));
        }
        $pk = new RequestUpdateDMChannel($channel);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    /**
     * Delete a channel in a server
     *
     * @param string $channel_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteDMChannel(string $channel_id): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        $pk = new RequestDeleteDMChannel($channel_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** Creates a server from a server template.
     * @param Server $server
     * @param string $server_name
     * @param string|null $server_icon
     * 
     * @return PromiseInterface Resolves with a new Server model. 
     */
    public function createServerFromTemplate(Server $server, string $template_code, string $server_name, ?string $server_icon = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server->getId())) {
            return rejectPromise(new ApiRejection("Invalid server ID: '{$server->getId()}'."));
        }
        if (!Utils::validDiscordSnowflake($template_code)) {
            return rejectPromise(new ApiRejection("Invalid Template Code: '{$template_code}'."));
        }
        if ($server_icon !== null) {
            if (!str_starts_with($server_icon, "https://cdn.discordapp.com/icons/")) {
                return rejectPromise(new ApiRejection("Server Icon '{$server_icon}' is invalid."));
            }
        }
        if (strlen($server_name) < 2 or strlen($server_name > 100)) {
            return rejectPromise(new ApiRejection("Server Name: " . strlen($server_name) . " is invalid. Must be somewhere from 2-100 characters long."));
        }
        $pk = new RequestCreateServerFromTemplate($server, $template_code, $server_name, $server_icon);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Transfers Server Ownership to another user.
     * @param string $server_id
     * @param string $user_id
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function transferOwnership(string $server_id, string $user_id, ?string $reason = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Invalid server ID '$server_id'."));
        }
        if (!Utils::validDiscordSnowflake($user_id)) {
            return rejectPromise(new ApiRejection("Invalid user ID '$user_id'."));
        }
        $pk = new RequestServerTransfer($server_id, $user_id, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Searches members within the given parmeters through out the Discord server.
     * @param string $server_id
     * @param string $user_id
     * @param int $limit
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function searchMembers(string $server_id, string $user_id, int $limit): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Invalid server ID '$server_id'."));
        }
        if (!Utils::validDiscordSnowflake($user_id)) {
            return rejectPromise(new ApiRejection("User ID: {$user_id} is invalid."));
        }

        $pk = new RequestSearchMembers($server_id, $user_id, $limit);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Searches Audit log within the given parmeters through out the discord server.
     * @param string $server_id
     * @param string $user_id
     * @param int $action_type
     * @param string|null $before
     * @param int $limit
     * 
     * @return PromiseInterface Resolves with a AuditLog Model.
     */
    public function searchAuditLog(string $server_id, string $user_id, int $action_type, ?string $before, int $limit): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Invalid server ID '$server_id'."));
        }
        if (!Utils::validDiscordSnowflake($user_id)) {
            return rejectPromise(new ApiRejection("Invalid user ID '$user_id'."));
        }
        $pk = new RequestServerAuditLog($server_id, $user_id, $action_type, $before, $limit);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }



    /**
     * Joins a voice channel. ID must be present!
     * 
     * @param VoiceChannel $channel
     * 
     * @return PromiseInterface Resolves with a Voice Channel model.
     */
    public function joinVoiceChannel(VoiceChannel $channel, bool $isDeafened, bool $isMuted): PromiseInterface
    {
        if ($channel->getId() === null) {
            return rejectPromise(new ApiRejection("Voice Channel ID must be present."));
        }
        if (!Utils::validDiscordSnowflake($channel->getId())) {
            return rejectPromise(new ApiRejection("Voice Channel ID: {$channel->getId()} is invalid!"));
        }
        $pk = new RequestJoinVoiceChannel($channel, $isDeafened, $isMuted);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Leaves a voice channel. Left Voice channel ID must be present.
     * @param VoiceChannel $channel
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function leaveVoiceChannel(VoiceChannel $channel): PromiseInterface
    {
        if ($channel->getId() === null) {
            return rejectPromise(new ApiRejection("Voice Channel ID must be present."));
        }
        if (!Utils::validDiscordSnowflake($channel->getId())) {
            return rejectPromise(new ApiRejection("Voice Channel ID: {$channel->getId()} is invalid!"));
        }
        $pk = new RequestLeaveVoiceChannel($channel);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Moves to another voice channel. Moved Voice Channel ID must be present.
     * 
     * @param VoiceChannel $channel
     * 
     * @return PromiseInterface Resolves with a Voice Channel Model.
     */
    public function moveVoiceChannel(VoiceChannel $channel): PromiseInterface
    {
        if ($channel->getId() === null) {
            return rejectPromise(new ApiRejection("Voice Channel ID must be present."));
        }
        if (!Utils::validDiscordSnowflake($channel->getId())) {
            return rejectPromise(new ApiRejection("Voice Channel ID: {$channel->getId()} is invalid!"));
        }
        $pk = new RequestMoveVoiceChannel($channel);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Moves a member to another voice channel. Moved to Voice Channel ID Must be present.
     * @param string $userID
     * @param VoiceChannel $channel
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with a Voice Channel Model.
     */
    public function moveMember(string $userID, VoiceChannel $channel, ?string $reason = null): PromiseInterface
    {
        if ($channel->getId() === null) {
            return rejectPromise(new ApiRejection("Voice Channel ID must be present."));
        }
        if (!Utils::validDiscordSnowflake($channel->getId())) {
            return rejectPromise(new ApiRejection("Voice Channel ID: {$channel->getId()} is invalid!"));
        }
        if (!Utils::validDiscordSnowflake($userID)) {
            return rejectPromise(new ApiRejection("Invalid Member ID '$userID'."));
        }
        $pk = new RequestMoveMember($userID, $channel, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Mutes a member that's in the current Voice Channel. Muted member's Voice Channel ID must be present.
     * @param string $userID
     * @param VoiceChannel $channel
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with a VoiceChannel Model.
     */
    public function muteMember(string $userID, VoiceChannel $channel, ?string $reason = null): PromiseInterface
    {
        if ($channel->getId() === null) {
            return rejectPromise(new ApiRejection("Voice Channel ID must be present."));
        }
        if (!Utils::validDiscordSnowflake($channel->getId())) {
            return rejectPromise(new ApiRejection("Voice Channel ID: {$channel->getId()} is invalid!"));
        }

        if (!Utils::validDiscordSnowflake($userID)) {
            return rejectPromise(new ApiRejection("Invalid Member ID '$userID'."));
        }
        $pk = new RequestMuteMember($userID, $channel, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
    
    /** Unmutes a Member that's in the current boice channel. Unmuted Member's Voice Channel ID must be present. 
     * @param string $userID
     * @param VoiceChannel $channel
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with a VoiceChannel Model.
    */
    public function unmuteMember(string $userID, VoiceChannel $channel, ?string $reason = null): PromiseInterface{
        if ($channel->getId() === null) {
            return rejectPromise(new ApiRejection("Voice Channel ID must be present."));
        }
        if (!Utils::validDiscordSnowflake($channel->getId())) {
            return rejectPromise(new ApiRejection("Voice Channel ID: {$channel->getId()} is invalid!"));
        }

        if (!Utils::validDiscordSnowflake($userID)) {
            return rejectPromise(new ApiRejection("Invalid Member ID '$userID'."));
        }
        $pk = new RequestUnMuteMember($userID, $channel, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** 
     * Starts a thread in a channel.
     * 
     * @param Thread $channel
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with a Thread Channel model.
     */
    public function startChannelThread(Thread $channel, ?string $reason = null): PromiseInterface
    {
        $pk = new RequestThreadCreate($channel, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Starts a thread using message IDS.
     * 
     * @param string $message_id
     * @param string $channel_id
     * @param string $name
     * @param int $duration
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with a Message Model.
     */
    public function startMessageThread(string $message_id, string $channel_id, string $name, int $duration, ?string $reason = null): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($message_id)) {
            return rejectPromise(new ApiRejection("Invalid message ID '$message_id'."));
        }
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        $pk = new RequestThreadMessageCreate($message_id, $channel_id, $name, $duration, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** 
     * Updates a thread.
     * 
     * @param Thread $channel
     * 
     * @return PromiseInterface Resolves with a Thread Channel Model.
     */
    public function updateThread(Thread $channel, string $parent_id)
    {
        if ($channel->getId() === null) {
            return rejectPromise(new ApiRejection("Thread Channel ID: {$channel->getId()} must be present."));
        }
        if (!Utils::validDiscordSnowflake($channel->getId())) {
            return rejectPromise(new ApiRejection("Thread Channel ID: {$channel->getId()} is invalid!"));
        }
        if(!Utils::validDiscordSnowflake($parent_id)){
            return rejectPromise(new ApiRejection("Channel ID: {$parent_id} is invalid."));
        }
        $pk = new RequestThreadUpdate($channel, $parent_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /** 
     * Deletes a thread.
     * 
     * @param string $server_id
     * @param string $channel_id
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function deleteThread(string $server_id, string $channel_id)
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Invalid server ID '$server_id'."));
        }
        if (!Utils::validDiscordSnowflake($channel_id)) {
            return rejectPromise(new ApiRejection("Invalid channel ID '$channel_id'."));
        }
        $pk = new RequestThreadDelete($channel_id, $server_id);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Initialise if possible the given invite.
     *
     * @param Invite $invite
     * 
     * @return PromiseInterface Resolves with a Invite model.
     */
    public function initialiseInvite(Invite $invite): PromiseInterface
    {
        $pk = new RequestInitialiseInvite($invite);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Revoke an initialised invite.
     *
     * @param string $server_id
     * @param string $invite_code
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function revokeInvite(string $server_id, string $invite_code): PromiseInterface
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            return rejectPromise(new ApiRejection("Invalid server ID '$server_id'."));
        }
        $pk = new RequestRevokeInvite($server_id, $invite_code);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }

    /**
     * Update a members nickname (set to null to remove)
     *
     * @param string $member_id
     * @param null|string $nickname Null to remove nickname.
     * @param string|null $reason
     * 
     * @return PromiseInterface Resolves with no data.
     */
    public function updateNickname(string $member_id, ?string $nickname = null, ?string $reason = null): PromiseInterface
    {
        [$sid, $uid] = explode(".", $member_id);
        if (!Utils::validDiscordSnowflake($sid) or !Utils::validDiscordSnowflake($uid)) {
            return rejectPromise(new ApiRejection("Invalid member ID '$member_id'."));
        }
        $pk = new RequestUpdateNickname($sid, $uid, $nickname, $reason);
        $this->plugin->writeOutboundData($pk);
        return ApiResolver::create($pk->getUID());
    }
}
