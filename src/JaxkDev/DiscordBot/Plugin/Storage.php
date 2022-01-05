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

use JaxkDev\DiscordBot\Models\Ban;
use JaxkDev\DiscordBot\Models\Channels\CategoryChannel;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Channels\VoiceChannel;
use JaxkDev\DiscordBot\Models\Channels\ThreadChannel;
use JaxkDev\DiscordBot\Models\Invite;
use JaxkDev\DiscordBot\Models\Member;
use JaxkDev\DiscordBot\Models\Role;
use JaxkDev\DiscordBot\Models\Messages\Message;
use JaxkDev\DiscordBot\Models\Server;
use JaxkDev\DiscordBot\Models\User;
use JaxkDev\DiscordBot\Models\ServerTemplate;
use JaxkDev\DiscordBot\Models\ServerScheduledEvent;
use JaxkDev\DiscordBot\Models\Channels\Stage;
use JaxkDev\DiscordBot\Models\Channels\DMChannel;

use JaxkDev\DiscordBot\Plugin\Events\MessageDeleted as MessageDeletedEvent;

/*
 * Notes:
 * unset() on the removes doesnt destroy the objects until all references are unset....
 */

class Storage
{

    /** @var Server[] */
    private static $server_map = [];

    /** @var Array<string, Message> */
    private static $message_map = [];

    /** @var Array<string, string[]> */
    private static $message_server_map = [];

    /** @var Array<string, string[]> */
    private static $message_user_map = [];

    /** @var Array<string, ServerChannel> */
    private static $channel_map = [];

    /** @var Array<string, DMChannel> */
    private static $dm_channel_map = [];

    /** @var Array<string, string[]> */
    private static $channel_server_map = [];
    /** @var Array<string, ThreadChannel> */
    private static $thread_map = [];

    /** @var Array<string, string[]> */
    private static $thread_server_map = [];


    /** @var Array<string, string[]> */
    private static $channel_category_map = [];

    /** @var Array<string, string[]> */
    private static $category_server_map = [];

    /** @var Array<string, string> Link member to voice channel they're currently in. */
    private static $voiceChannelmember_map = [];

    /** @var Array<string, Member> */
    private static $member_map = [];

    /** @var Array<string, string[]> */
    private static $member_server_map = [];

    /** @var Array<string, User> */
    private static $user_map = [];

    /** @var Array<string, Role> */
    private static $role_map = [];

    /** @var Array<string, string[]> */
    private static $role_server_map = [];

    /** @var Array<string, Ban> */
    private static $ban_map = [];

    /** @var Array<string, string[]> */
    private static $ban_server_map = [];

    /** @var Array<string, Invite> */
    private static $invite_map = [];

    /** @var Array<string, string[]> */
    private static $invite_server_map = [];

    /** @var Array<string, Stage> */
    private static $stage_map = [];

    /** @var Array<string, string[]> */
    private static $stage_server_map = [];

    /** @var Array<string, ServerTemplate> */
    private static $template_map = [];

    /** @var Array<string, string[]> */
    private static $template_server_map = [];

    /** @var Array<string, ServerScheduledEvent> */
    private static $scheduled_map = [];

    /** @var Array<string, string[]> */
    private static $scheduled_server_map = [];

    /** @var null|User */
    private static $bot_user = null;

    /** @var int */
    private static $timestamp = 0;

    /** @param string $id
     * @return ServerScheduledEvent|null
     */
    public static function getSchedule(string $id): ?ServerScheduledEvent
    {
        return self::$scheduled_map[$id] ?? null;
    }
    /** @return ServerScheduledEvent[] */
    public static function getScheduledEvents(): array
    {
        return array_values(self::$scheduled_map);
    }

    public static function addSchedule(ServerScheduledEvent $schedule): void
    {
        if ($schedule->getId() === null) {
            throw new \AssertionError("Failed to add Server Template to storage, Template Code not found.");
        }
        if (isset(self::$scheduled_map[$schedule->getId()])) return;
        self::$scheduled_server_map[$schedule->getServerId()][] = $schedule->getId();
        self::$scheduled_map[$schedule->getId()] = $schedule;
    }
    public static function updateSchedule(ServerScheduledEvent $schedule): void
    {
        if ($schedule->getId() === null) {
            throw new \AssertionError("Failed to update Server Scheduled Event in storage, ID not found.");
        }
        if (!isset(self::$scheduled_map[$schedule->getId()])) {
            self::addSchedule($schedule);
        } else {
            self::$scheduled_map[$schedule->getId()] = $schedule;
        }
    }
    public static function removeSchedule(string $id): void
    {
        $schedule = self::getSchedule($id);
        if (!$schedule instanceof ServerScheduledEvent) return; //Already deleted or not added.
        unset(self::$scheduled_map[$id]);
        $server_id = $schedule->getServerId();

        $i = array_search($id, self::$scheduled_server_map[$server_id], true);
        if ($i === false || is_string($i)) return; //Not in this servers template map.
        array_splice(self::$scheduled_server_map[$server_id], $i, 1);
    }
    /**
     * @param string $server_id
     * @return ServerScheduledEvent[]
     */
    public static function getScheduledEventsByServer(string $server_id): array
    {
        $events = [];
        foreach ((self::$scheduled_server_map[$server_id] ?? []) as $id) {
            $s = self::getSchedule($id);
            if ($s !== null) $events[] = $s;
        }
        return $events;
    }
    public static function getTemplate(string $code): ?ServerTemplate
    {
        return self::$template_map[$code] ?? null;
    }
    /** @return ServerTemplate[] */
    public static function getTemplates(): array
    {
        return array_values(self::$template_map);
    }
    public static function addTemplate(ServerTemplate $template): void
    {
        if ($template->getCode() === null) {
            throw new \AssertionError("Failed to add Server Template to storage, Template Code not found.");
        }
        if (isset(self::$template_map[$template->getCode()])) return;
        self::$template_server_map[$template->getServerId()][] = $template->getCode();
        self::$template_map[$template->getCode()] = $template;
    }
    public static function updateTemplate(ServerTemplate $template): void
    {
        if ($template->getCode() === null) {
            throw new \AssertionError("Failed to update Server Template in storage, Template Code not found.");
        }
        if (!isset(self::$template_map[$template->getCode()])) {
            self::addTemplate($template);
        } else {
            self::$template_map[$template->getCode()] = $template;
        }
    }
    public static function removeTemplate(string $code): void
    {
        $template = self::getTemplate($code);
        if (!$template instanceof ServerTemplate) return; //Already deleted or not added.
        unset(self::$template_map[$code]);
        $server_id = $template->getServerId();

        $i = array_search($code, self::$template_server_map[$server_id], true);
        if ($i === false || is_string($i)) return; //Not in this servers template map.
        array_splice(self::$template_server_map[$server_id], $i, 1);
    }
    /**
     * @param string $server_id
     * @return ServerTemplate[]
     */
    public static function getTemplatesByServer(string $server_id): array
    {
        $templates = [];
        foreach ((self::$stage_server_map[$server_id] ?? []) as $id) {
            $t = self::getTemplate($id);
            if ($t !== null) $templates[] = $t;
        }
        return $templates;
    }
    public static function getStage(string $id): ?Stage
    {
        return self::$stage_map[$id];
    }
    /** @return Stage[] */
    public static function getStages(): array
    {
        return array_values(self::$stage_map);
    }
    public static function addStage(Stage $stage): void
    {
        if ($stage->getId() === null) {
            throw new \AssertionError("Failed to add stage channel to storage, ID not found.");
        }
        if (isset(self::$stage_map[$stage->getId()])) return;
        self::$stage_server_map[$stage->getServerId()][] = $stage->getId();
        self::$stage_map[$stage->getId()] = $stage;
    }
    public static function updateStage(Stage $stage)
    {
        if ($stage->getId() === null) {
            throw new \AssertionError("Failed to update stage channel in storage, ID not found.");
        }
        if (!isset(self::$stage_map[$stage->getId()])) {
            self::addStage($stage);
        } else {
            self::$stage_map[$stage->getId()] = $stage;
        }
    }

    public static function removeStage(string $stage_id): void
    {
        $channel = self::getStage($stage_id);
        if (!$channel instanceof Stage) return; //Already deleted or not added.
        unset(self::$stage_map[$stage_id]);
        $server_id = $channel->getServerId();

        $i = array_search($stage_id, self::$stage_server_map[$server_id], true);
        if ($i === false || is_string($i)) return; //Not in this servers stage map.
        array_splice(self::$stage_server_map[$server_id], $i, 1);
    }
    /**
     * @param string $server_id
     * @return Stage[]
     */
    public static function getStagesByServer(string $server_id): array
    {
        $channels = [];
        foreach ((self::$stage_server_map[$server_id] ?? []) as $id) {
            $c = self::getStage($id);
            if ($c !== null) $channels[] = $c;
        }
        return $channels;
    }
    public static function getServer(string $id): ?Server
    {
        return self::$server_map[$id] ?? null;
    }
    /**
     * @return Server[]
     */
    public static function getServers(): array
    {
        return array_values(self::$server_map);
    }

    public static function addServer(Server $server): void
    {
        if (isset(self::$server_map[($id = $server->getId())])) return; //Already added.
        self::$server_map[$id] = $server;
        self::$channel_server_map[$id] = [];
        self::$category_server_map[$id] = [];
        self::$member_server_map[$id] = [];
        self::$role_server_map[$id] = [];
        self::$invite_server_map[$id] = [];
        self::$ban_server_map[$id] = [];
        self::$stage_server_map[$id] = [];
        self::$template_server_map[$id] = [];
        self::$scheduled_server_map[$id] = [];
        self::$message_server_map[$id] = [];
    }

    public static function updateServer(Server $server): void
    {
        if (!isset(self::$server_map[$server->getId()])) {
            self::addServer($server);
        } else {
            self::$server_map[$server->getId()] = $server;
        }
    }

    /**
     * NOTICE, Removes all linked members,channels and roles.
     * @param string $server_id
     */
    public static function removeServer(string $server_id): void
    {
        if (!isset(self::$server_map[$server_id])) return; //Was never added or already deleted.
        unset(self::$server_map[$server_id]);
        //Remove servers channels.
        foreach (self::$channel_server_map[$server_id] as $cid) {
            unset(self::$channel_map[$cid]);
        }
        unset(self::$channel_server_map[$server_id]);
        //Remove servers category's.
        foreach (self::$category_server_map[$server_id] as $cid) {
            unset(self::$channel_map[$cid]); //Category's are channels.
        }
        unset(self::$channel_server_map[$server_id]);
        //Remove servers members.
        foreach (self::$member_server_map[$server_id] as $mid) {
            unset(self::$member_map[$mid]);
        }
        unset(self::$member_server_map[$server_id]);
        //Remove servers roles.
        foreach (self::$role_server_map[$server_id] as $rid) {
            unset(self::$role_map[$rid]);
        }
        unset(self::$role_server_map[$server_id]);
        //Remove servers invites.
        foreach (self::$invite_server_map[$server_id] as $iid) {
            unset(self::$invite_map[$iid]);
        }
        unset(self::$invite_server_map[$server_id]);
        //Remove servers bans.
        foreach (self::$ban_server_map[$server_id] as $bid) {
            unset(self::$ban_map[$bid]);
        }
        foreach (self::$stage_server_map[$server_id] as $sid) {
            unset(self::$stage_map[$sid]);
        }
        foreach (self::$template_server_map[$server_id] as $tid) {
            unset(self::$template_map[$tid]);
        }
        foreach (self::$scheduled_server_map[$server_id] as $sid) {
            unset(self::$scheduled_map[$sid]);
        }
        foreach (self::$message_server_map[$server_id] as $mid) {
            unset(self::$message_map[$mid]);
        }
        unset(self::$ban_server_map[$server_id]);
    }

    /** @param string $id
     * @return ThreadChannel|null
     */
    public static function getThread(string $id): ?ThreadChannel
    {
        return self::$thread_map[$id] ?? null;
    }

    /** @return ThreadChannel[] */
    public static function getThreads(): array
    {
        return array_values(self::$thread_map);
    }

    /**
     * @param string $server_id
     * @return ThreadChannel[]
     */
    public static function getThreadsByServer(string $server_id): array
    {
        $channels = [];
        foreach ((self::$thread_server_map[$server_id] ?? []) as $id) {
            $c = self::getThread($id);
            if ($c !== null) $channels[] = $c;
        }
        return $channels;
    }

    /** 
     * Checks if the given channel ID is a thread-type of channel.
     * 
     * @param string $id
     * @return bool
     */
    public static function isThread(string $id): bool
    {
        $channel = Storage::getChannel($id);
        if ($channel instanceof ServerChannel) {
            return false;
        }
        $thread = Storage::getThread($id);
        if (!$thread instanceof ThreadChannel) {
            return false;
        }
        return true;
    }
    public static function addThread(ThreadChannel $channel): void
    {
        if ($channel->getId() === null) {
            throw new \AssertionError("Failed to add thread channel to storage, ID not found.");
        }
        if (isset(self::$thread_map[$channel->getId()])) return;
        self::$thread_server_map[$channel->getServerId()][] = $channel->getId();
        self::$thread_map[$channel->getId()] = $channel;
    }

    public static function updateThread(ThreadChannel $channel): void
    {
        if ($channel->getId() === null) {
            throw new \AssertionError("Failed to update thread channel in storage, ID not found.");
        }
        if (!isset(self::$thread_map[$channel->getId()])) {
            self::addThread($channel);
        } else {
            self::$thread_map[$channel->getId()] = $channel;
        }
    }

    public static function removeThread(string $channel_id): void
    {
        $channel = self::getThread($channel_id);
        if (!$channel instanceof ThreadChannel) return; //Already deleted or not added.
        unset(self::$thread_map[$channel_id]);
        $server_id = $channel->getServerId();

        //if($channel instanceof ServerChannel){
        $i = array_search($channel_id, self::$thread_server_map[$server_id], true);
        if ($i === false || is_string($i)) return; //Not in this servers thread map.
        array_splice(self::$thread_server_map[$server_id], $i, 1);
        //  }
    }
    /** @param string $id
     * @return DMChannel|null
     */
    public static function getDMChannel(string $id): ?DMChannel
    {
        return self::$dm_channel_map[$id] ?? null;
    }

    /** @return DMChannel[] */
    public static function getDMChannels(): array
    {
        return array_values(self::$dm_channel_map);
    }

    /** 
     * Checks if the given channel ID is a dm-type of channel.
     * 
     * @param string $id
     * @return bool
     */
    public static function isDMChannel(string $id): bool
    {
        $channel = Storage::getChannel($id);
        if ($channel instanceof ServerChannel) {
            return false;
        }
        $thread = Storage::getThread($id);
        if ($thread instanceof ThreadChannel) {
            return false;
        }
        $dm = Storage::getDMChannel($id);
        if (!$dm instanceof DMChannel) {
            return false;
        }
        return true;
    }
    public static function addDMChannel(DMChannel $channel): void
    {
        if ($channel->getId() === null) {
            throw new \AssertionError("Failed to add DM channel to storage, ID not found.");
        }
        if (isset(self::$dm_channel_map[$channel->getId()])) return;
        self::$dm_channel_map[$channel->getId()] = $channel;
    }

    public static function updateDMChannel(DMChannel $channel): void
    {
        if ($channel->getId() === null) {
            throw new \AssertionError("Failed to update DM channel in storage, ID not found.");
        }
        if (!isset(self::$dm_channel_map[$channel->getId()])) {
            self::addDMChannel($channel);
        } else {
            self::$dm_channel_map[$channel->getId()] = $channel;
        }
    }

    public static function removeDMChannel(string $channel_id): void
    {
        $channel = self::getDMChannel($channel_id);
        if (!$channel instanceof DMChannel) return; //Already deleted or not added.
        unset(self::$dm_channel_map[$channel_id]);
    }



    /** @param string $id
     * @return ServerChannel|null
     */
    public static function getChannel(string $id): ?ServerChannel
    {
        return self::$channel_map[$id] ?? null;
    }
    /** @return ServerChannel[] */
    public static function getChannels(): array
    {
        return array_values(self::$channel_map);
    }

    /**
     * @param string $server_id
     * @return ServerChannel[]
     */
    public static function getChannelsByServer(string $server_id): array
    {
        $channels = [];
        foreach ((self::$channel_server_map[$server_id] ?? []) as $id) {
            $c = self::getChannel($id);
            if ($c instanceof ServerChannel) $channels[] = $c;
        }
        return $channels;
    }

    /**
     * @param string $category_id
     * @return ServerChannel[]
     */
    public static function getChannelsByCategory(string $category_id): array
    {
        $channels = [];
        foreach ((self::$channel_category_map[$category_id] ?? []) as $id) {
            $c = self::getChannel($id);
            if ($c instanceof ServerChannel) {
                if ($c instanceof CategoryChannel) {
                    throw new \AssertionError("Channel '" . $c->getId() . "' error 0x0002 (Report this on github if you see this)");
                } else {
                    $channels[] = $c;
                }
            }
        }
        return $channels;
    }

    /**
     * @param string $server_id
     * @return CategoryChannel[]
     */
    public static function getCategoriesByServer(string $server_id): array
    {
        $channels = [];
        foreach ((self::$category_server_map[$server_id] ?? []) as $id) {
            $c = self::getChannel($id);
            if ($c instanceof ServerChannel) {
                if (!$c instanceof CategoryChannel) {
                    throw new \AssertionError("Channel '" . $c->getId() . "' error 0x0001 (Report this on github if you see this)");
                } else {
                    $channels[] = $c;
                }
            }
        }
        return $channels;
    }
    public static function addMessage(Message $message): void
    {
        if ($message->getId() === null) {
            throw new \AssertionError("Failed to add message to storage, ID not found.");
        }
        if (isset(self::$message_map[$message->getId()])) return;
        if ($message->getServerId() !== null) {
            self::$message_server_map[$message->getServerId()][] = $message->getId();
        }
        if ($message->getAuthorId() !== null) {
            $member = self::getMember($message->getAuthorId());
            if ($member !== null) {
                self::$message_user_map[$member->getUserId()][] = $message->getId();
            }
        }
        self::$message_map[$message->getId()] = $message;
    }
    public static function updateMessage(Message $message): void
    {
        if ($message->getId() === null) {
            throw new \AssertionError("Failed to update message in storage, ID not found.");
        }
        if (!isset(self::$message_map[$message->getId()])) {
            self::addMessage($message);
        } else {
            self::$message_map[$message->getId()] = $message;
        }
    }
    public static function removeMessage(string $message_id): void
    {
        $message = self::getMessage($message_id);
        if (!$message instanceof Message) return; //Already deleted or not added.
        unset(self::$message_map[$message_id]);
        $server_id = $message->getServerId();
        if ($server_id !== null) {
            $i = array_search($message_id, self::$message_server_map[$server_id], true);
            if ($i === false || is_string($i)) return; //Not in this servers message map.
            array_splice(self::$message_server_map[$server_id], $i, 1);
        }


        if ($message->getAuthorId() !== null) {
            $member = self::getMember($message->getAuthorId());
            if ($member !== null) {
                $user_id = $member->getUserId();
                $i = array_search($message_id, self::$message_user_map[$user_id], true);
                if ($i === false || is_string($i)) return; //Not in this users message map.
                array_splice(self::$message_user_map[$user_id], $i, 1);
            }
        }
    }

    /** 
     * @param string $channel_id
     * @param int $limit
     * @return void
     */
    public static function bulkRemove(string $channel_id, int $limit): void
    {
        $deleted = 0;
        for ($i = $limit; $i > 0; $i -= 100) {
            if ($i > 100) {
                Main::get()->getAPI()->bulkDelete($channel_id, 100);
            } else {
                Main::get()->getAPI()->bulkDelete($channel_id, $i);
            }
        }
        /** @var Message[] $msgs */
        $msgs = [];
        foreach (self::getMessagesByChannel($channel_id) as $message) {
            $deleted += 1;
            if ($deleted <= $limit) {
                /** @var Message[] $msgs */
                $msgs[] = $message;
            }
        }

        $ev = new MessageDeletedEvent(Main::get(), $msgs);
        $ev->call();
        foreach ($msgs as $message) {
            self::removeMessage($message->getId());


            Main::get()->getLogger()->info("Message deleted event has been called.");
        }
    }

    /**
     * @param Message $message
     * @return bool
     */
    public static function isOldMessage(Message $message): bool
    {
        $seconds = Utils::toSeconds($message->getTimestamp());
        $days = Utils::toDays($seconds);
        if ($days < 14) {
            return false;
        }
        return true;
    }





    /** @param string $message_id
     * @return Message|null
     */
    public static function getMessage(string $message_id): ?Message
    {
        return self::$message_map[$message_id] ?? null;
    }

    /** @return Message[] */
    public static function getMessagesByChannel(string $channel_id): array
    {
        $messages = [];
        foreach (self::getMessages() as $message) {
            if ($channel_id === $message->getChannelId()) {

                $messages[] = $message;
            }
        }
        return $messages;
    }

    /** @param string $server_id
     * @return Message[]
     */
    public static function getMessagesByServer(string $server_id): array
    {
        $messages = [];
        foreach ((self::$message_server_map[$server_id] ?? []) as $id) {
            $m = self::getMessage($id);
            if ($m !== null) $messages[] = $m;
        }
        return $messages;
    }

    /** @param string $user_id
     * @return Message[]
     */
    public static function getMessagesByUser(string $user_id): array
    {
        $messages = [];
        foreach ((self::$message_user_map[$user_id] ?? []) as $id) {
            $m = self::getMessage($id);
            if ($m !== null) $messages[] = $m;
        }
        return $messages;
    }
    /** @return Message[] */
    public static function getMessages(): array
    {
        return array_values(self::$message_map);
    }
    public static function addChannel(ServerChannel $channel): void
    {
        if ($channel->getId() === null) {
            throw new \AssertionError("Failed to add channel to storage, ID not found.");
        }
        if (isset(self::$channel_map[$channel->getId()])) return;
        if ($channel instanceof CategoryChannel) {
            self::$category_server_map[$channel->getServerId()][] = $channel->getId();
        } else {
            self::$channel_server_map[$channel->getServerId()][] = $channel->getId();
            self::$channel_category_map[$channel->getCategoryId()][] = $channel->getId();
        }
        self::$channel_map[$channel->getId()] = $channel;
    }

    public static function updateChannel(ServerChannel $channel): void
    {
        if ($channel->getId() === null) {
            throw new \AssertionError("Failed to update channel in storage, ID not found.");
        }
        if (!isset(self::$channel_map[$channel->getId()])) {
            self::addChannel($channel);
        } else {
            self::$channel_map[$channel->getId()] = $channel;
        }
    }

    public static function removeChannel(string $channel_id): void
    {
        $channel = self::getChannel($channel_id);
        if (!$channel instanceof ServerChannel) return; //Already deleted or not added.
        unset(self::$channel_map[$channel_id]);
        $server_id = $channel->getServerId();
        if ($channel instanceof CategoryChannel) {
            if (isset(self::$channel_category_map[$channel_id])) unset(self::$channel_category_map[$channel_id]);
            $i = array_search($channel_id, self::$category_server_map[$server_id], true);
            if ($i === false || is_string($i)) return; //Not in this servers category map.
            array_splice(self::$category_server_map[$server_id], $i, 1);
        } elseif ($channel instanceof ServerChannel) {
            $i = array_search($channel_id, self::$channel_server_map[$server_id], true);
            if ($i === false || is_string($i)) return; //Not in this servers channel map.
            array_splice(self::$channel_server_map[$server_id], $i, 1);
        }
    }

    public static function getMember(string $id): ?Member
    {
        return self::$member_map[$id] ?? null;
    }
    /** @return Member[] */
    public static function getMembers(): array
    {
        return array_values(self::$member_map);
    }

    /**
     * @param string $server_id
     * @return Member[]
     */
    public static function getMembersByServer(string $server_id): array
    {
        $members = [];
        foreach ((self::$member_server_map[$server_id] ?? []) as $id) {
            $m = self::getMember($id);
            if ($m !== null) $members[] = $m;
        }
        return $members;
    }

    public static function addMember(Member $member): void
    {
        if (isset(self::$member_map[$member->getId()])) return;
        self::$member_server_map[$member->getServerId()][] = $member->getId();
        self::$member_map[$member->getId()] = $member;
    }

    public static function updateMember(Member $member): void
    {
        if (!isset(self::$member_map[$member->getId()])) {
            self::addMember($member);
        } else {
            self::$member_map[$member->getId()] = $member;
        }
    }

    public static function removeMember(string $member_id): void
    {
        $member = self::getMember($member_id);
        if ($member === null) return; //Already deleted or not added.
        $server_id = $member->getServerId();
        unset(self::$member_map[$member_id]);
        $i = array_search($member_id, self::$member_server_map[$server_id], true);
        if ($i === false || is_string($i)) return; //Not in this servers member map.
        array_splice(self::$member_server_map[$server_id], $i, 1);
    }

    public static function getUser(string $id): ?User
    {
        return self::$user_map[$id] ?? null;
    }

    public static function addUser(User $user): void
    {
        self::$user_map[$user->getId()] = $user;
    }
    /**
     * @return User[]
     */
    public static function getUsers(): array
    {
        return array_values(self::$user_map);
    }
    /**
     * @internal
     */
    public static function setMembersVoiceChannel(string $member_id, string $voice_channel_id): void
    {
        if (!((self::$channel_map[$voice_channel_id] ?? null) instanceof VoiceChannel)) {
            throw new \AssertionError("Voice channel '$voice_channel_id' does not exist in storage.");
        }
        self::$voiceChannelmember_map[$member_id] = $voice_channel_id;
    }

    /**
     * Returns the voice channel the specified member is currently in.
     *
     * @param string $member_id
     * @return VoiceChannel|null
     */
    public static function getMembersVoiceChannel(string $member_id): ?VoiceChannel
    {
        if (($id = self::$voiceChannelmember_map[$member_id] ?? null) === null) return null;
        $c = self::$channel_map[$id];
        return ($c instanceof VoiceChannel) ? $c : null;
    }

    /**
     * @internal
     */
    public static function unsetMembersVoiceChannel(string $member_id): void
    {
        unset(self::$voiceChannelmember_map[$member_id]);
    }

    /**
     * Same function as addUser because no links are kept for users.
     * @param User $user
     */
    public static function updateUser(User $user): void
    {
        //No links can overwrite.
        self::addUser($user);
    }

    public static function removeUser(string $user_id): void
    {
        unset(self::$user_map[$user_id]);
    }

    public static function getRole(string $id): ?Role
    {
        return self::$role_map[$id] ?? null;
    }
    /** @return Role[] */
    public static function getRoles(): array
    {
        return array_values(self::$role_map);
    }

    /**
     * @param string $server_id
     * @return Role[]
     */
    public static function getRolesByServer(string $server_id): array
    {
        $roles = [];
        foreach ((self::$role_server_map[$server_id] ?? []) as $id) {
            $r = self::getRole($id);
            if ($r !== null) {
                $roles[] = $r;
            }
        }
        return $roles;
    }

    public static function addRole(Role $role): void
    {
        if ($role->getId() === null) {
            throw new \AssertionError("Failed to add role to storage, ID not found.");
        }
        if (isset(self::$role_map[$role->getId()])) return;
        self::$role_server_map[$role->getServerId()][] = $role->getId();
        self::$role_map[$role->getId()] = $role;
    }

    public static function updateRole(Role $role): void
    {
        if ($role->getId() === null) {
            throw new \AssertionError("Failed to update role in storage, ID not found.");
        }
        if (!isset(self::$role_map[$role->getId()])) {
            self::addRole($role);
        } else {
            self::$role_map[$role->getId()] = $role;
        }
    }

    public static function removeRole(string $role_id): void
    {
        $role = self::getRole($role_id);
        if ($role === null) return; //Already deleted or not added.
        $server_id = $role->getServerId();
        unset(self::$role_map[$role_id]);
        $i = array_search($role_id, self::$role_server_map[$server_id], true);
        if ($i === false || is_string($i)) return; //Not in this servers role map.
        array_splice(self::$role_server_map[$server_id], $i, 1);
    }

    public static function getBan(string $id): ?Ban
    {
        return self::$ban_map[$id] ?? null;
    }
    /** @return Ban[] */
    public static function getBans(): array
    {
        return array_values(self::$ban_map);
    }
    /**
     * @param string $server_id
     * @return Ban[]
     */
    public static function getBansByServer(string $server_id): array
    {
        $bans = [];
        foreach ((self::$ban_server_map[$server_id] ?? []) as $member) {
            $b = self::getBan($member);
            if ($b !== null) $bans[] = $b;
        }
        return $bans;
    }

    public static function addBan(Ban $ban): void
    {
        if (isset(self::$ban_map[$ban->getId()])) return;
        self::$ban_map[$ban->getId()] = $ban;
        self::$ban_server_map[$ban->getServerId()][] = $ban->getId();
    }

    public static function removeBan(string $id): void
    {
        $ban = self::getBan($id);
        if ($ban === null) return; //Already deleted or not added.
        $serverId = $ban->getServerId();
        unset(self::$ban_map[$id]);
        $i = array_search($id, self::$ban_server_map[$serverId], true);
        if ($i === false || is_string($i)) return; //Not in this servers ban map.
        array_splice(self::$ban_server_map[$serverId], $i, 1);
    }

    public static function getInvite(string $code): ?Invite
    {
        return self::$invite_map[$code] ?? null;
    }
    /** @return Invite[] */
    public static function getInvites(): array
    {
        return array_values(self::$invite_map);
    }
    /**
     * @param string $server_id
     * @return Invite[]
     */
    public static function getInvitesByServer(string $server_id): array
    {
        $invites = [];
        foreach ((self::$invite_server_map[$server_id] ?? []) as $id) {
            $i = self::getInvite($id);
            if ($i !== null) $invites[] = $i;
        }
        return $invites;
    }

    public static function addInvite(Invite $invite): void
    {
        if ($invite->getCode() === null) {
            throw new \AssertionError("Failed to add invite to storage, Code not found.");
        }
        if (isset(self::$invite_map[$invite->getCode()])) return;
        self::$invite_server_map[$invite->getServerId()][] = $invite->getCode();
        self::$invite_map[$invite->getCode()] = $invite;
    }

    public static function updateInvite(Invite $invite): void
    {
        if ($invite->getCode() === null) {
            throw new \AssertionError("Failed to update invite in storage, Code not found.");
        }
        if (!isset(self::$invite_map[$invite->getCode()])) {
            self::addinvite($invite);
        } else {
            self::$invite_map[$invite->getCode()] = $invite;
        }
    }

    public static function removeInvite(string $code): void
    {
        $invite = self::getinvite($code);
        if ($invite === null) return; //Already deleted or not added.
        $server_id = $invite->getServerId();
        unset(self::$invite_map[$code]);
        $i = array_search($code, self::$invite_server_map[$server_id], true);
        if ($i === false || is_string($i)) return; //Not in this servers invite map.
        array_splice(self::$invite_server_map[$server_id], $i, 1);
    }

    public static function getBotUser(): ?User
    {
        return self::$bot_user;
    }

    public static function setBotUser(User $user): void
    {
        self::$bot_user = $user;
    }

    public static function getBotMemberByServer(string $server_id): ?Member
    {
        $u = self::getBotUser();
        if ($u === null) return null;
        return self::getMember("{$server_id}.{$u->getId()}");
    }

    public static function getTimestamp(): int
    {
        return self::$timestamp;
    }

    public static function setTimestamp(int $timestamp): void
    {
        self::$timestamp = $timestamp;
    }

    /**
     * Serializes entire storage, ONLY USE FOR DEBUGGING PURPOSES.
     */
    public static function serializeStorage(): string
    {
        return serialize([1, (new \ReflectionClass("\JaxkDev\DiscordBot\Plugin\Storage"))->getStaticProperties()]);
    }

    //Disabled for public, this should ONLY be used by active developers of DiscordBot.
    /*public static function loadStorage(string $file): bool{
        MainLogger::getLogger()->debug("[DiscordBot] Loading storage from '$file'...");
        $data = file_get_contents($file);
        if($data === false) return false;
        $data = unserialize($data);
        if(!is_array($data) or sizeof($data) !== 2 or !is_int($data[0])) return false;
        $storage = $data[1];
        self::$bot_user = $storage["bot_user"];
        self::$timestamp = $storage["timestamp"];
        self::$invite_server_map = $storage["invite_server_map"];
        self::$invite_map = $storage["invite_map"];
        self::$ban_server_map = $storage["ban_server_map"];
        self::$ban_map = $storage["ban_map"];
        self::$role_server_map = $storage["role_server_map"];
        self::$role_map = $storage["role_map"];
        self::$user_map = $storage["user_map"];
        self::$member_server_map = $storage["member_server_map"];
        self::$member_map = $storage["member_map"];
        self::$category_server_map = $storage["category_server_map"];
        self::$channel_category_map = $storage["channel_category_map"];
        self::$channel_server_map = $storage["channel_server_map"];
        self::$channel_map = $storage["channel_map"];
        self::$server_map = $storage["server_map"];
        MainLogger::getLogger()->debug("[DiscordBot] Successfully loaded storage from '$file'.");
        return true;
    }*/
}
