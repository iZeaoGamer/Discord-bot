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

use JaxkDev\DiscordBot\Communication\Packets\Resolution as ResolutionPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\DiscordDataDump as DiscordDataDumpPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\BanAdd as BanAddPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\BanRemove as BanRemovePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ChannelCreate as ChannelCreatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ChannelDelete as ChannelDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ChannelUpdate as ChannelUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ChannelPinsUpdate as ChannelPinsUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\InviteCreate as InviteCreatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\InviteDelete as InviteDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MemberJoin as MemberJoinPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MemberLeave as MemberLeavePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MemberUpdate as MemberUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MessageDelete as MessageDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MessageSent as MessageSentPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MessageUpdate as MessageUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MessageReactionAdd as MessageReactionAddPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MessageReactionRemove as MessageReactionRemovePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MessageReactionRemoveAll as MessageReactionRemoveAllPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MessageReactionRemoveEmoji as MessageReactionRemoveEmojiPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\PresenceUpdate as PresenceUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\RoleCreate as RoleCreatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\RoleDelete as RoleDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\RoleUpdate as RoleUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerJoin as ServerJoinPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerLeave as ServerLeavePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerUpdate as ServerUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\DiscordReady as DiscordReadyPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\VoiceStateUpdate as VoiceStateUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\MessageBulkDelete as MessageBulkDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ThreadCreate as ThreadCreatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ThreadUpdate as ThreadUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ThreadDelete as ThreadDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\DMChannelCreate as DMChannelCreatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\DMChannelUpdate as DMChannelUpdatePacket;
use Jaxkdev\DiscordBot\Communication\Packets\Discord\DMChannelDelete as DMChannelDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\TypingStart as TypingStartPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\InteractionCreate as InteractionCreatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerStickerUpdate as ServerStickerUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\StageCreate as StageCreatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\StageUpdate as StageUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\StageDelete as StageDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerEmojiUpdate as ServerEmojiUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerScheduledEventCreate as ServerScheduledEventCreatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerScheduledEventUpdate as ServerScheduledEventUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerScheduledEventDelete as ServerScheduledEventDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerScheduledEventUserAdd as ServerScheduledEventUserAddPacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\ServerScheduledEventUserRemove as ServerScheduledEventUserRemovePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\UserUpdate as UserUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\WebhooksUpdate as WebhooksUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\IntergrationCreate as IntergrationCreatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\IntergrationUpdate as IntergrationUpdatePacket;
use JaxkDev\DiscordBot\Communication\Packets\Discord\IntergrationDelete as IntergrationDeletePacket;
use JaxkDev\DiscordBot\Communication\Packets\Heartbeat as HeartbeatPacket;
use JaxkDev\DiscordBot\Communication\Packets\Packet;
use JaxkDev\DiscordBot\Models\Activity;
use JaxkDev\DiscordBot\Models\Channels\TextChannel;
use JaxkDev\DiscordBot\Models\Channels\VoiceChannel;
use JaxkDev\DiscordBot\Models\Member;
use JaxkDev\DiscordBot\Plugin\Events\BanCreated as BanCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\BanDeleted as BanDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ChannelDeleted as ChannelDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ChannelPinsUpdated as ChannelPinsUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ChannelUpdated as ChannelUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\InviteCreated as InviteCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\InviteDeleted as InviteDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\MemberJoined as MemberJoinedEvent;
use JaxkDev\DiscordBot\Plugin\Events\MemberLeft as MemberLeftEvent;
use JaxkDev\DiscordBot\Plugin\Events\MemberUpdated as MemberUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\MessageDeleted as MessageDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\MessageReactionAdd as MessageReactionAddEvent;
use JaxkDev\DiscordBot\Plugin\Events\MessageReactionRemove as MessageReactionRemoveEvent;
use JaxkDev\DiscordBot\Plugin\Events\MessageReactionRemoveAll as MessageReactionRemoveAllEvent;
use JaxkDev\DiscordBot\Plugin\Events\MessageReactionRemoveEmoji as MessageReactionRemoveEmojiEvent;
use JaxkDev\DiscordBot\Plugin\Events\MessageSent as MessageSentEvent;
use JaxkDev\DiscordBot\Plugin\Events\MessageUpdated as MessageUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\DiscordReady as DiscordReadyEvent;
use JaxkDev\DiscordBot\Plugin\Events\PresenceUpdated as PresenceUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\RoleCreated as RoleCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\RoleDeleted as RoleDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\RoleUpdated as RoleUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerDeleted as ServerDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerJoined as ServerJoinedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerUpdated as ServerUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\VoiceChannelMemberJoined as VoiceChannelMemberJoinedEvent;
use JaxkDev\DiscordBot\Plugin\Events\VoiceChannelMemberLeft as VoiceChannelMemberLeftEvent;
use JaxkDev\DiscordBot\Plugin\Events\VoiceChannelMemberMoved as VoiceChannelMemberMovedEvent;
use JaxkDev\DiscordBot\Plugin\Events\VoiceStateUpdated as VoiceStateUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ThreadCreated as ThreadCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ThreadUpdated as ThreadUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ThreadDeleted as ThreadDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\DMChannelCreated as DMChannelCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\DMChannelUpdated as DMChannelUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\DMChannelDeleted as DMChannelDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ChannelCreated as ChannelCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\MessageBulkDeleted as MessageBulkDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\TypingStart as TypingStartEvent;
use JaxkDev\DiscordBot\Plugin\Events\InteractionCreated as InteractionCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerStickerUpdated as ServerStickerUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\StageCreated as StageCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\StageDeleted as StageDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\StageUpdated as StageUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerEmojiUpdated as ServerEmojiUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerScheduledCreated as ServerScheduledCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerScheduledUpdated as ServerScheduledUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerScheduledDeleted as ServerScheduledDeletedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerScheduledUserAdded as ServerScheduledUserAddedEvent;
use JaxkDev\DiscordBot\Plugin\Events\ServerScheduledUserRemoved as ServerScheduledUserRemovedEvent;
use JaxkDev\DiscordBot\Plugin\Events\UserUpdated as UserUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\WebhooksUpdated as WebhooksUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\IntergrationCreated as IntergrationCreatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\IntergrationUpdated as IntergrationUpdatedEvent;
use JaxkDev\DiscordBot\Plugin\Events\IntergrationDeleted as IntergrationDeletedEvent;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Channels\ThreadChannel;
use JaxkDev\DiscordBot\Models\Channels\DMChannel;
use JaxkDev\DiscordBot\Models\Messages\Message;
use JaxkDev\DiscordBot\Models\Interactions\Command\Command;

class BotCommunicationHandler
{

    /** @var Main */
    private $plugin;

    /** @var float|null */
    private $lastHeartbeat = null;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function handle(Packet $packet): void
    {
        // If's instances instead of ID switching due to phpstan/types.
        if ($packet instanceof ResolutionPacket) {
            ApiResolver::handleResolution($packet);
            return;
        }
        if ($packet instanceof HeartbeatPacket) {
            $this->lastHeartbeat = $packet->getHeartbeat();
            return;
        }

        if ($packet instanceof PresenceUpdatePacket) $this->handlePresenceUpdate($packet);
        elseif ($packet instanceof ThreadCreatePacket) $this->handleThreadCreate($packet);
        elseif ($packet instanceof ThreadUpdatePacket) $this->handleThreadUpdate($packet);
        elseif ($packet instanceof ThreadDeletePacket) $this->handleThreadDelete($packet);
        elseif ($packet instanceof MessageBulkDeletePacket) $this->handleMessageBulkDelete($packet);
        elseif ($packet instanceof VoiceStateUpdatePacket) $this->handleVoiceStateUpdate($packet);
        elseif ($packet instanceof MemberJoinPacket) $this->handleMemberJoin($packet);
        elseif ($packet instanceof MemberLeavePacket) $this->handleMemberLeave($packet);
        elseif ($packet instanceof MemberUpdatePacket) $this->handleMemberUpdate($packet);
        elseif ($packet instanceof MessageSentPacket) $this->handleMessageSent($packet);
        elseif ($packet instanceof MessageUpdatePacket) $this->handleMessageUpdate($packet);
        elseif ($packet instanceof MessageDeletePacket) $this->handleMessageDelete($packet);
        elseif ($packet instanceof MessageReactionAddPacket) $this->handleMessageReactionAdd($packet);
        elseif ($packet instanceof MessageReactionRemovePacket) $this->handleMessageReactionRemove($packet);
        elseif ($packet instanceof MessageReactionRemoveAllPacket) $this->handleMessageReactionRemoveAll($packet);
        elseif ($packet instanceof MessageReactionRemoveEmojiPacket) $this->handleMessageReactionRemoveEmoji($packet);
        elseif ($packet instanceof ChannelCreatePacket) $this->handleChannelCreate($packet);
        elseif ($packet instanceof ChannelUpdatePacket) $this->handleChannelUpdate($packet);
        elseif ($packet instanceof ChannelDeletePacket) $this->handleChannelDelete($packet);
        elseif ($packet instanceof DMChannelCreatePacket) $this->handleDMChannelCreate($packet);
        elseif ($packet instanceof DMChannelUpdatePacket) $this->handleDMChannelUpdate($packet);
        elseif ($packet instanceof DMChannelDeletePacket) $this->handleDMChannelDelete($packet);
        elseif ($packet instanceof ChannelPinsUpdatePacket) $this->handleChannelPinsUpdate($packet);
        elseif ($packet instanceof RoleCreatePacket) $this->handleRoleCreate($packet);
        elseif ($packet instanceof RoleUpdatePacket) $this->handleRoleUpdate($packet);
        elseif ($packet instanceof RoleDeletePacket) $this->handleRoleDelete($packet);
        elseif ($packet instanceof InviteCreatePacket) $this->handleInviteCreate($packet);
        elseif ($packet instanceof InviteDeletePacket) $this->handleInviteDelete($packet);
        elseif ($packet instanceof BanAddPacket) $this->handleBanAdd($packet);
        elseif ($packet instanceof BanRemovePacket) $this->handleBanRemove($packet);
        elseif ($packet instanceof ServerJoinPacket) $this->handleServerJoin($packet);
        elseif ($packet instanceof ServerLeavePacket) $this->handleServerLeave($packet);
        elseif ($packet instanceof ServerUpdatePacket) $this->handleServerUpdate($packet);
        elseif ($packet instanceof TypingStartPacket) $this->handleTypingStart($packet);
        elseif ($packet instanceof InteractionCreatePacket) $this->handleInteraction($packet);
        elseif ($packet instanceof DiscordDataDumpPacket) $this->handleDataDump($packet);
        elseif ($packet instanceof ServerStickerUpdatePacket) $this->handleServerSticker($packet);
        elseif ($packet instanceof StageCreatePacket) $this->handleStageCreate($packet);
        elseif ($packet instanceof StageUpdatePacket) $this->handleStageUpdate($packet);
        elseif ($packet instanceof StageDeletePacket) $this->handleStageDelete($packet);
        elseif ($packet instanceof ServerEmojiUpdatePacket) $this->handleEmojiUpdate($packet);
        elseif ($packet instanceof ServerScheduledEventCreatePacket) $this->handleScheduleCreate($packet);
        elseif ($packet instanceof ServerScheduledEventUpdatePacket) $this->handleScheduleUpdate($packet);
        elseif ($packet instanceof ServerScheduledEventDeletePacket) $this->handleScheduleDelete($packet);
        elseif ($packet instanceof ServerScheduledEventUserAddPacket) $this->handleScheduleAddUser($packet);
        elseif ($packet instanceof ServerScheduledEventUserRemovePacket) $this->handleScheduleRemoveUser($packet);
        elseif ($packet instanceof UserUpdatePacket) $this->handleUserUpdate($packet);
        elseif ($packet instanceof WebhooksUpdatePacket) $this->handleWebhooksUpdate($packet);
        elseif ($packet instanceof IntergrationCreatePacket) $this->handleIntergrationCreate($packet);
        elseif ($packet instanceof IntergrationUpdatePacket) $this->handleIntergrationUpdate($packet);
        elseif ($packet instanceof IntergrationDeletePacket) $this->handleIntergrationDelete($packet);
        elseif ($packet instanceof DiscordReadyPacket) $this->handleReady();
    }

    private function handleReady(): void
    {
        //Default activity, Feel free to change activity after ReadyEvent.
        $ac = new Activity("Bot is loading.. Please stand by!", Activity::TYPE_PLAYING);
        $this->plugin->getApi()->updateBotPresence($ac, Member::STATUS_DND)->otherwise(function (ApiRejection $a) {
            $this->plugin->getLogger()->logException($a);
        });

        (new DiscordReadyEvent($this->plugin))->call();
    }
    private function handleUserUpdate(UserUpdatePacket $packet): void
    {
        (new UserUpdatedEvent($this->plugin, $packet->getUser(), $packet->getOldUser()))->call();
        Storage::updateUser($packet->getUser());
    }
    private function handleWebhooksUpdate(WebhooksUpdatePacket $packet): void
    {
        (new WebhooksUpdatedEvent($this->plugin, $packet->getServer(), $packet->getChannel()))->call();
        //todo implement storaging for webhooks updates.
    }
    private function handleIntergrationCreate(IntergrationCreatePacket $packet): void
    {
        (new IntergrationCreatedEvent($this->plugin, $packet->getIntergration()))->call();
        //todo implemment Storaging for Intergrations.
    }
    private function handleIntergrationUpdate(IntergrationUpdatePacket $packet): void
    {
        (new IntergrationUpdatedEvent($this->plugin, $packet->getIntergration()))->call();
        //todo implemment Storaging for Intergrations.
    }
    private function handleIntergrationDelete(IntergrationDeletePacket $packet): void
    {
        (new IntergrationDeletedEvent($this->plugin, $packet->getOldIntergration()))->call();
        //todo implemment Storaging for Intergrations.
    }

    private function handleScheduleCreate(ServerScheduledEventCreatePacket $packet): void
    {
        (new ServerScheduledCreatedEvent($this->plugin, $packet->getScheduledEvent()))->call();
        Storage::addSchedule($packet->getScheduledEvent());
    }
    private function handleScheduleUpdate(ServerScheduledEventUpdatePacket $packet): void
    {
        (new ServerScheduledUpdatedEvent($this->plugin, $packet->getScheduledEvent()))->call();
        Storage::updateSchedule($packet->getScheduledEvent());
    }
    private function handleScheduleDelete(ServerScheduledEventDeletePacket $packet): void
    {
        (new ServerScheduledDeletedEvent($this->plugin, $packet->getScheduledEvent()))->call();
        Storage::removeSchedule($packet->getScheduledEvent()->getId());
    }
    private function handleScheduleAddUser(ServerScheduledEventUserAddPacket $packet): void
    {
        (new ServerScheduledUserAddedEvent($this->plugin, $packet->getScheduledEvent(), $packet->getServer(), $packet->getUser()))->call();
    }
    private function handleScheduleRemoveUser(ServerScheduledEventUserRemovePacket $packet): void
    {
        (new ServerScheduledUserRemovedEvent($this->plugin, $packet->getScheduledEvent(), $packet->getServer(), $packet->getUser()));
    }

    private function handleEmojiUpdate(ServerEmojiUpdatePacket $packet): void
    {
        (new ServerEmojiUpdatedEvent($this->plugin, $packet->getEmoji()))->call();
    }
    private function handleServerSticker(ServerStickerUpdatePacket $packet): void
    {
        (new ServerStickerUpdatedEvent($this->plugin, $packet->getSticker()))->call();
    }
    private function handleInteraction(InteractionCreatePacket $packet): void
    {
        (new InteractionCreatedEvent($this->plugin, $packet->getInteraction()))->call();
    }
    private function handleStageCreate(StageCreatePacket $packet)
    {
        (new StageCreatedEvent($this->plugin, $packet->getStage()))->call();
        Storage::addStage($packet->getStage());
    }
    private function handleStageUpdate(StageUpdatePacket $packet)
    {
        (new StageUpdatedEvent($this->plugin, $packet->getStage()))->call();
        Storage::updateStage($packet->getStage());
    }
    private function handleStageDelete(StageDeletePacket $packet)
    {
        (new StageDeletedEvent($this->plugin, $packet->getStage()))->call();
        Storage::removeStage($packet->getStage()->getId());
    }


    //Uses the storage (aka cache)
    private function handleVoiceStateUpdate(VoiceStateUpdatePacket $packet): void
    {
        $member = Storage::getMember($packet->getMemberId());
        if ($member === null) {
            throw new \AssertionError("Member '{$packet->getMemberId()}' not found in storage.");
        }
        $state = $packet->getVoiceState();
        if ($state->getChannelId() === null) {
            $channel = Storage::getMembersVoiceChannel($packet->getMemberId());
            if ($channel === null) {
                throw new \AssertionError("Voice Channel '{$state->getChannelId()}' not found in storage.");
            }
            (new VoiceChannelMemberLeftEvent($this->plugin, $member, $channel))->call();
            $member->setVoiceState(null);
            $members = $channel->getMembers();
            if (($key = array_search($packet->getMemberId(), $members)) !== false) {
                unset($members[$key]);
            }
            $channel->setMembers($members);
            Storage::updateMember($member);
            Storage::updateChannel($channel);
            Storage::unsetMembersVoiceChannel($packet->getMemberId());
        } else {
            $channel = Storage::getChannel($state->getChannelId());
            if (!$channel instanceof ServerChannel) {
                throw new \AssertionError("Channel '{$state->getChannelId()}' not found in storage.");
            }
            if (!$channel instanceof VoiceChannel) {
                throw new \AssertionError("Channel '{$state->getChannelId()}' not a voice channel.");
            }
            if (in_array($packet->getMemberId(), $channel->getMembers())) {
                //Member did not leave/join/transfer voice channel but muted/deaf/self_muted/self_deafen etc.
                (new VoiceStateUpdatedEvent($this->plugin, $member, $state))->call();
                $member->setVoiceState($packet->getVoiceState());
                Storage::updateMember($member);
            } else {
                if ($channel->getMemberLimit() !== 0 and sizeof($channel->getMembers()) >= $channel->getMemberLimit()) {
                    //Shouldn't ever happen.
                    throw new \AssertionError("Channel '{$state->getChannelId()}' shouldn't have room for this member.");
                }
                $previous = Storage::getMembersVoiceChannel($packet->getMemberId());
                if ($previous !== null and $previous->getId() !== $state->getChannelId()) {
                    (new VoiceChannelMemberMovedEvent($this->plugin, $member, $previous, $channel, $state))->call();
                    $members = $previous->getMembers();
                    if (($key = array_search($packet->getMemberId(), $members)) !== false) {
                        unset($members[$key]);
                    }
                    $previous->setMembers($members);
                    Storage::updateChannel($previous);
                } else {
                    (new VoiceChannelMemberJoinedEvent($this->plugin, $member, $channel, $state))->call();
                }
                $member->setVoiceState($packet->getVoiceState());
                $members = $channel->getMembers();
                $members[] = $packet->getMemberId();
                $channel->setMembers($members);
                Storage::updateMember($member);
                Storage::updateChannel($channel);
                Storage::setMembersVoiceChannel($packet->getMemberId(), $state->getChannelId());
            }
        }
    }

    private function handlePresenceUpdate(PresenceUpdatePacket $packet): void
    {
        $member = Storage::getMember($packet->getMemberId());
        if ($member === null) {
            throw new \AssertionError("Member '{$packet->getMemberID()}' not found in storage.");
        }
        (new PresenceUpdatedEvent($this->plugin, $member, $packet->getStatus(), $packet->getClientStatus(), $packet->getActivities()))->call();
        $member->setStatus($packet->getStatus());
        $member->setClientStatus($packet->getClientStatus());
        $member->setActivities($packet->getActivities());
        Storage::updateMember($member);
    }

    /** @deprecated You can now bulk delete messages without this event.
     * @see Storage::getMessages() for all messages in total.
     * @see Storage::getMessagesByServer() for all messages per server.
     * @see Storage::getMessagesByChannel() for all messages per channel.
     * @see Storage::getMessagesByUser() for all messages per user
     * @see Storage::bulkRemove() for bulk removing messages, and the limit.
     * In future updates, this event may be removed, and replaced by handleMessageDelete event.
     * 
     */
    private function handleMessageBulkDelete(MessageBulkDeletePacket $packet): void
    {
        (new MessageBulkDeletedEvent($this->plugin, $packet->getMessage()))->call();
    }

    private function handleMessageSent(MessageSentPacket $packet): void
    {
        (new MessageSentEvent($this->plugin, $packet->getMessage()))->call();
        Storage::addMessage($packet->getMessage());
    }

    private function handleMessageUpdate(MessageUpdatePacket $packet): void
    {

        (new MessageUpdatedEvent($this->plugin, $packet->getMessage(), $packet->getOldMessage()))->call();
        Storage::updateMessage($packet->getMessage());
    }

    private function handleMessageDelete(MessageDeletePacket $packet): void
    {
        (new MessageDeletedEvent($this->plugin, $packet->getMessage()))->call();
        $message = $packet->getMessage();
        if ($message instanceof Message) {
            $id = $message->getId();
        } else {
            $id = (string)$message["message_id"];
        }

        Storage::removeMessage($id);
    }
    private function handleTypingStart(TypingStartPacket $packet): void
    {
        (new TypingStartEvent($this->plugin, $packet->getUserId(), $packet->getChannelId(), $packet->getServerId()))->call();
    }

    private function handleMessageReactionAdd(MessageReactionAddPacket $packet): void
    {
        $channel = Storage::getChannel($packet->getChannelId());
        if (!$channel instanceof ServerChannel) {
            throw new \AssertionError("Channel '{$packet->getChannelId()}' does not exist in storage.");
        }
        $member = Storage::getMember($packet->getMemberId());
        if ($member === null) {
            throw new \AssertionError("Member '{$packet->getMemberId()}' does not exist in storage.");
        }
        (new MessageReactionAddEvent($this->plugin, $packet->getEmoji(), $packet->getMessageId(), $channel, $member))->call();
    }

    private function handleMessageReactionRemove(MessageReactionRemovePacket $packet): void
    {
        $channel = Storage::getChannel($packet->getChannelId());
        if (!$channel instanceof ServerChannel) {
            throw new \AssertionError("Channel '{$packet->getChannelId()}' does not exist in storage.");
        }
        $member = Storage::getMember($packet->getMemberId());
        if ($member === null) {
            throw new \AssertionError("Member '{$packet->getMemberId()}' does not exist in storage.");
        }
        (new MessageReactionRemoveEvent($this->plugin, $packet->getEmoji(), $packet->getMessageId(), $channel, $member))->call();
    }

    private function handleMessageReactionRemoveAll(MessageReactionRemoveAllPacket $packet): void
    {
        $channel = Storage::getChannel($packet->getChannelId());
        if (!$channel instanceof ServerChannel) {
            throw new \AssertionError("Channel '{$packet->getChannelId()}' does not exist in storage.");
        }
        (new MessageReactionRemoveAllEvent($this->plugin, $packet->getMessageId(), $channel))->call();
    }

    private function handleMessageReactionRemoveEmoji(MessageReactionRemoveEmojiPacket $packet): void
    {
        $channel = Storage::getChannel($packet->getChannelId());
        if (!$channel instanceof ServerChannel) {
            throw new \AssertionError("Channel '{$packet->getChannelId()}' does not exist in storage.");
        }
        (new MessageReactionRemoveEmojiEvent($this->plugin, $packet->getEmoji(), $packet->getMessageId(), $channel))->call();
    }


    private function handleThreadCreate(ThreadCreatePacket $packet): void
    {
        (new ThreadCreatedEvent($this->plugin, $packet->getChannel()))->call();
        Storage::addThread($packet->getChannel());
    }
    private function handleThreadUpdate(ThreadUpdatePacket $packet): void
    {
        (new ThreadUpdatedEvent($this->plugin, $packet->getChannel()))->call();
        Storage::updateThread($packet->getChannel());
    }
    private function handleThreadDelete(ThreadDeletePacket $packet): void
    {
        $c = Storage::getThread($packet->getChannelId());
        if (!$c instanceof ThreadChannel) {
            throw new \AssertionError("Thread Channel '{$packet->getChannelId()} not found in storage.");
        }
        (new ThreadDeletedEvent($this->plugin, $c))->call();
        Storage::removeThread($packet->getChannelID());
    }
    private function handleDMChannelCreate(DMChannelCreatePacket $packet): void
    {
        (new DMChannelCreatedEvent($this->plugin, $packet->getChannel()))->call();
        Storage::addDMChannel($packet->getChannel());
    }
    private function handleDMChannelUpdate(DMChannelUpdatePacket $packet): void
    {
        (new DMChannelUpdatedEvent($this->plugin, $packet->getChannel(), $packet->getOldChannel()))->call();
        Storage::updateDMChannel($packet->getChannel());
    }
    private function handleDMChannelDelete(DMChannelDeletePacket $packet): void
    {
        $c = Storage::getDMChannel($packet->getChannelId());
        if (!$c instanceof DMChannel) {
            throw new \AssertionError("DM Channel '{$packet->getChannelId()} not found in storage.");
        }
        (new DMChannelDeletedEvent($this->plugin, $c))->call();
        Storage::removeDMChannel($packet->getChannelID());
    }
    private function handleChannelCreate(ChannelCreatePacket $packet): void
    {
        (new ChannelCreatedEvent($this->plugin, $packet->getChannel()))->call();
        Storage::addChannel($packet->getChannel());
    }

    private function handleChannelUpdate(ChannelUpdatePacket $packet): void
    {
        (new ChannelUpdatedEvent($this->plugin, $packet->getChannel(), $packet->getOldChannel()))->call();

        Storage::updateChannel($packet->getChannel());
    }

    private function handleChannelDelete(ChannelDeletePacket $packet): void
    {
        $c = Storage::getChannel($packet->getChannelId());
        if (!$c instanceof ServerChannel) {
            throw new \AssertionError("Server Channel '{$packet->getChannelId()}' not found in storage.");
        }
        (new ChannelDeletedEvent($this->plugin, $c))->call();
        Storage::removeChannel($packet->getChannelId());
    }

    private function handleChannelPinsUpdate(ChannelPinsUpdatePacket $packet): void
    {
        $c = Storage::getChannel($packet->getChannelId());
        if (!$c instanceof ServerChannel or !$c instanceof TextChannel) {
            throw new \AssertionError("Text Channel '{$packet->getChannelId()}' not found in storage.");
        }
        (new ChannelPinsUpdatedEvent($this->plugin, $c))->call();
    }

    private function handleRoleCreate(RoleCreatePacket $packet): void
    {
        (new RoleCreatedEvent($this->plugin, $packet->getRole()))->call();
        Storage::addRole($packet->getRole());
    }

    private function handleRoleUpdate(RoleUpdatePacket $packet): void
    {
        (new RoleUpdatedEvent($this->plugin, $packet->getRole(), $packet->getOldRole()))->call();
        Storage::updateRole($packet->getRole());
    }

    private function handleRoleDelete(RoleDeletePacket $packet): void
    {
        $r = Storage::getRole($packet->getRoleId());
        if ($r === null) {
            throw new \AssertionError("Role '{$packet->getRoleId()}' not found in storage.");
        }
        (new RoleDeletedEvent($this->plugin, $r))->call();
        Storage::removeRole($packet->getRoleId());
    }

    private function handleInviteCreate(InviteCreatePacket $packet): void
    {
        (new InviteCreatedEvent($this->plugin, $packet->getInvite()))->call();
        Storage::addInvite($packet->getInvite());
    }

    private function handleInviteDelete(InviteDeletePacket $packet): void
    {
        $i = Storage::getInvite($packet->getInviteCode());
        if ($i === null) {
            throw new \AssertionError("Invite '{$packet->getInviteCode()}' not found in storage.");
        }
        (new InviteDeletedEvent($this->plugin, $i))->call();
        Storage::removeInvite($packet->getInviteCode());
    }

    private function handleBanAdd(BanAddPacket $packet): void
    {
        (new BanCreatedEvent($this->plugin, $packet->getBan()))->call();
        Storage::addBan($packet->getBan());
    }

    private function handleBanRemove(BanRemovePacket $packet): void
    {
        $ban = Storage::getBan($packet->getBanId());
        if ($ban === null) {
            throw new \AssertionError("Ban '{$packet->getBanId()}' not found in storage.");
        }
        (new BanDeletedEvent($this->plugin, $ban))->call();
        Storage::removeBan($packet->getBanId());
    }

    private function handleMemberJoin(MemberJoinPacket $packet): void
    {
        $server = Storage::getServer($packet->getMember()->getServerId());
        if ($server === null) {
            throw new \AssertionError("Server '{$packet->getMember()->getServerId()}' not found for member '{$packet->getMember()->getId()}'");
        }
        (new MemberJoinedEvent($this->plugin, $packet->getMember()))->call();
        Storage::addMember($packet->getMember());
        Storage::addUser($packet->getUser());
    }

    private function handleMemberUpdate(MemberUpdatePacket $packet): void
    {
        (new MemberUpdatedEvent($this->plugin, $packet->getMember(), $packet->getOldMember()))->call();
        Storage::updateMember($packet->getMember());
    }

    private function handleMemberLeave(MemberLeavePacket $packet): void
    {
        //When leaving server this is emitted.
        if (($u = Storage::getBotUser()) !== null and $u->getId() === explode(".", $packet->getMemberID())[1]) return;

        $member = Storage::getMember($packet->getMemberID());
        if ($member === null) {
            throw new \AssertionError("Member '{$packet->getMemberID()}' not found in storage.");
        }

        $server = Storage::getServer($member->getServerId());
        if ($server === null) {
            throw new \AssertionError("Server '{$member->getServerId()}' not found for member '{$member->getId()}'");
        }

        (new MemberLeftEvent($this->plugin, $member))->call();
        Storage::removeMember($packet->getMemberID());
    }

    private function handleServerJoin(ServerJoinPacket $packet): void
    {
        (new ServerJoinedEvent(
            $this->plugin,
            $packet->getServer(),
            $packet->getThreads(),
            $packet->getRoles(),
            $packet->getChannels(),
            $packet->getMembers(),
            $packet->getTemplates(),
            $packet->getScheduledEvents(),
            $packet->getMessages(),
            $packet->getCommands(),
        ))->call();

        Storage::addServer($packet->getServer());
        foreach ($packet->getMembers() as $member) {
            Storage::addMember($member);
        }
        foreach ($packet->getRoles() as $role) {
            Storage::addRole($role);
        }
        foreach ($packet->getChannels() as $channel) {
            Storage::addChannel($channel);
        }
        foreach ($packet->getThreads() as $thread) {
            Storage::addThread($thread);
        }
        foreach ($packet->getTemplates() as $template) {
            Storage::addTemplate($template);
        }
        foreach ($packet->getScheduledEvents() as $scheduled) {
            Storage::addSchedule($scheduled);
        }
        foreach ($packet->getMessages() as $message) {
            Storage::addMessage($message);
        }
        foreach ($packet->getCommands() as $command) {
            Storage::addCommand($command);
        }
    }

    private function handleServerUpdate(ServerUpdatePacket $packet): void
    {
        (new ServerUpdatedEvent($this->plugin, $packet->getServer(), $packet->getOldServer()))->call();
        Storage::updateServer($packet->getServer());
    }

    private function handleServerLeave(ServerLeavePacket $packet): void
    {
        $server = Storage::getServer($packet->getServerId());
        if ($server === null) {
            throw new \AssertionError("Server '{$packet->getServerId()}' not found in storage.");
        }
        (new ServerDeletedEvent($this->plugin, $server))->call();
        Storage::removeServer($packet->getServerId());
    }

    private function handleDataDump(DiscordDataDumpPacket $packet): void
    {
        foreach ($packet->getServers() as $server) {
            Storage::addServer($server);
        }
        foreach ($packet->getThreads() as $thread) {
            Storage::addThread($thread);
        }
        foreach ($packet->getChannels() as $channel) {
            Storage::addChannel($channel);
        }
        foreach ($packet->getRoles() as $role) {
            Storage::addRole($role);
        }
        foreach ($packet->getBans() as $ban) {
            Storage::addBan($ban);
        }
        foreach ($packet->getInvites() as $invite) {
            Storage::addInvite($invite);
        }
        foreach ($packet->getMembers() as $member) {
            Storage::addMember($member);
        }
        foreach ($packet->getUsers() as $user) {
            Storage::addUser($user);
        }
        foreach ($packet->getTemplates() as $template) {
            Storage::addTemplate($template);
        }
        foreach ($packet->getScheduledEvents() as $schedule) {
            Storage::addSchedule($schedule);
        }
        foreach ($packet->getMessages() as $message) {
            Storage::addMessage($message);
        }
        foreach ($packet->getCommands() as $command) {
            Storage::addCommand($command);
        }

        if ($packet->getBotUser() !== null) {
            Storage::setBotUser($packet->getBotUser());
        }
        Storage::setTimestamp($packet->getTimestamp());
        $this->plugin->getLogger()->debug("Handled data dump (" . $packet->getTimestamp() . ") (" . $packet->getSize() . ")");
    }

    /**
     * Checks last KNOWN Heartbeat timestamp with current time, does not check pre-start condition.
     */
    public function checkHeartbeat(): void
    {
        if ($this->lastHeartbeat === null) return;
        if (($diff = microtime(true) - $this->lastHeartbeat) > $this->plugin->getPluginConfig()["protocol"]["heartbeat_allowance"]) {
            $this->plugin->getLogger()->emergency("DiscordBot has not responded for {$diff} seconds, disabling plugin.");
            $this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
        }
    }

    public function sendHeartbeat(): void
    {
        $this->plugin->writeOutboundData(new HeartbeatPacket(microtime(true)));
    }

    public function getLastHeartbeat(): ?float
    {
        return $this->lastHeartbeat;
    }
}
