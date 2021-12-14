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

use Discord\Helpers\Collection;
use Discord\Parts\Channel\Channel as DiscordChannel;
use Discord\Parts\Thread\Thread as DiscordThread;
use Discord\Parts\Channel\Message as DiscordMessage;
use Discord\Parts\Channel\Overwrite as DiscordOverwrite;
use Discord\Parts\Channel\Webhook as DiscordWebhook;
use Discord\Parts\Embed\Embed as DiscordEmbed;
use Discord\Parts\Channel\StageInstance as DiscordStage;
use Discord\Parts\Interactions\Interaction as DiscordInteraction;
use Discord\Parts\Guild\Guild as DiscordGuild;
use Discord\Parts\Guild\Invite as DiscordInvite;
use Discord\Parts\Guild\Role as DiscordRole;
use Discord\Parts\User\Activity as DiscordActivity;
use Discord\Parts\User\Member as DiscordMember;
use Discord\Parts\User\User as DiscordUser;
use Discord\Repository\Channel\WebhookRepository as DiscordWebhookRepository;
use Discord\Repository\Guild\InviteRepository as DiscordInviteRepository;
use Discord\Repository\Guild\StageInstanceRepository as DiscordStageRepository;
use Discord\Parts\Channel\Sticker as DiscordSticker;

use Discord\Builders\MessageBuilder;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Option;
use Discord\Builders\Components\SelectMenu;

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
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestGuildTransfer;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestGuildAuditLog;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestSearchMembers;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateButton;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestModifyButton;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateSelectMenu;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestModifySelectMenu;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestModifyInteraction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestCreateInteraction;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDelayReply;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestDelayDelete;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStickerUpdate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStageCreate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStageUpdate;
use JaxkDev\DiscordBot\Communication\Packets\Plugin\RequestStageDelete;

use JaxkDev\DiscordBot\Communication\Packets\Resolution;
use JaxkDev\DiscordBot\Communication\Packets\Heartbeat;
use JaxkDev\DiscordBot\Plugin\Storage;
use JaxkDev\DiscordBot\Communication\Packets\Packet;
use JaxkDev\DiscordBot\Models\Channels\CategoryChannel;
use JaxkDev\DiscordBot\Models\Channels\TextChannel;
use JaxkDev\DiscordBot\Models\Channels\VoiceChannel;
use JaxkDev\DiscordBot\Models\Member;
use JaxkDev\DiscordBot\Models\Messages\Reply;

use JaxkDev\DiscordBot\Models\Role;
use JaxkDev\DiscordBot\Plugin\ApiRejection;
use Monolog\Logger;
use JaxkDev\DiscordBot\Plugin\Utils;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use function React\Promise\reject;

use Discord\Builders\Components\Button;
use JaxkDev\DiscordBot\Models\Messages\Webhook as WebhookMessage;
use JaxkDev\DiscordBot\Models\Webhook;

class CommunicationHandler
{

    /** @var Client */
    private $client;

    /** @var float|null */
    private $lastHeartbeat = null;

    /** @var Logger */
    private $logger;

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
        elseif ($pk instanceof RequestGuildTransfer) $this->handleGuildTransfer($pk);
        elseif ($pk instanceof RequestGuildAuditLog) $this->handleAuditLog($pk);
        elseif ($pk instanceof RequestSearchMembers) $this->handleSearchMembers($pk);
        elseif ($pk instanceof RequestCreateButton) $this->handleButtonCreate($pk);
        elseif ($pk instanceof RequestModifyButton) $this->handleButtonModify($pk);
        elseif ($pk instanceof RequestCreateSelectMenu) $this->handleSelectCreateMenu($pk);
        elseif ($pk instanceof RequestModifySelectMenu) $this->handleSelectModifyMenu($pk);
        elseif ($pk instanceof RequestModifyInteraction) $this->handleModifyInteraction($pk);
        elseif ($pk instanceof RequestCreateInteraction) $this->handleCreateInteraction($pk);
        elseif ($pk instanceof RequestDelayReply) $this->handleDelayReply($pk);
        elseif ($pk instanceof RequestDelayDelete) $this->handleDelayDelete($pk);
        elseif($pk instanceof RequestStageCreate) $this->handleStageCreate($pk);
        elseif($pk instanceof RequestStageUpdate) $this->handleStageUpdate($pk);
        elseif($pk instanceof RequestStageDelete) $this->handleStageDelete($pk);
    }
    private function handleDelayReply(RequestDelayReply $pk): void
    {
        $message = $pk->getMessage();
        if ($message->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Message Id must be present!");
            return;
        }
        $this->getMessage($pk, $message->getChannelId(), $message->getId(), function (DiscordMessage $msg) use ($message, $pk) {
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
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), function (DiscordMessage $msg) use ($pk) {

            $msg->delayedDelete(1000 * $pk->getDelay())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Delayed delete with success!");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to delay delete.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }


    private function handleDeleteWebhook(RequestDeleteWebhook $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
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
        $this->getChannel($pk, $pk->getWebhook()->getChannelId(), function (DiscordChannel $channel) use ($pk) {
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
        $this->getChannel($pk, $pk->getWebhook()->getChannelId(), function (DiscordChannel $channel) use ($pk) {
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

    private function handleFetchWebhooks(RequestFetchWebhooks $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
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
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
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
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), function (DiscordMessage $message) use ($pk) {
            $this->resolveRequest($pk->getUID(), true, "Fetched message.", [ModelConverter::genModelMessage($message)]);
        });
    }

    private function handleUnpinMessage(RequestUnpinMessage $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
            $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), function (DiscordMessage $message) use ($channel, $pk) {
                $channel->unpinMessage($message)->then(function () use ($pk) {
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
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
            $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), function (DiscordMessage $message) use ($channel, $pk) {
                $channel->pinMessage($message)->then(function () use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully pinned the message.");
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
                $this->logger->debug("Failed to leave server? ({$pk->getUID()}) - {$e->getMessage()}");
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
            $guild->updateRolePositions($data)->then(function (DiscordGuild $guild) use ($promise) {
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
            $dMember->removeRole($pk->getRoleId())->done(function () use ($pk) {
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
            $dMember->addRole($pk->getRoleId())->done(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Added role.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to add role.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to add role ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleRemoveReaction(RequestRemoveReaction $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), function (DiscordMessage $msg) use ($pk) {
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
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), function (DiscordMessage $msg) use ($pk) {
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
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), function (DiscordMessage $msg) use ($pk) {
            $msg->react($pk->getEmoji())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Reaction added.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to react to message.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to react to message ({$pk->getUID()}) - {$e->getMessage()}");
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
    private function handleStageCreate(RequestStageCreate $pk){
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
                $dc->discoverable_disabled = $c->isDisabled();
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
     $this->getStage($pk, $pk->getServerId(), $pk->getStageId(), function (DiscordGuild $guild, DiscordStage $stage) use ($pk){
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
        $this->getChannel($pk, $pk->getChannelID(), function (DiscordChannel $channel) use ($pk) {
            $channel->limitDelete($pk->getValue())->done(function () use ($pk) {
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

            $dc->startThread($pk->getChannel()->getName(), $pk->isPrivate(), $pk->getDuration())->then(function () use ($pk) {
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
        $id = $pk->getChannel()->getID();
        if ($id === null) {
            return;
        }

        $this->getServer($pk, $pk->getChannel()->getServerID(), function (DiscordGuild $guild) use ($id, $pk) {
            $guild->channels->fetch($id)->then(function (DiscordChannel $discord) use ($id, $pk) {
                $discord->threads->fetch($id)->then(function (DiscordThread $thread) use ($id, $discord, $pk) {
                    $thread->id = $id;
                    $thread->guild_id = $pk->getChannel()->getServerID();
                    $thread->name = $pk->getChannel()->getName();
                    $thread->owner_id = $pk->getChannel()->getOwner();
                    $thread->auto_archive_duration = $pk->getChannel()->getDuration();
                    $thread->archiver_id = $pk->getChannel()->getUserID();


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
    private function handleMessageStartThread(RequestThreadMessageCreate $pk): void
    {
        $this->getMessage($pk, $pk->getChannelID(), $pk->getMessageID(), function (DiscordMessage $message) use ($pk) {
            //     $guild->channels->fetch($pk->getChannel()->getID())->then(function (DiscordChannel $discord) use ($pk){
            $message->startThread($pk->getName(), $pk->getDuration())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID());
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to bulk delete messages..", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
        //  });
    }
    private function handleCrossPost(RequestCrossPostMessage $pk): void
    {
        if ($pk->getChannelID() === null) {
            $this->resolveRequest($pk->getUID(), false, "Failed to create cross post.", ["Channel ID must be present!"]);
            return;
        }
        if ($pk->getMessageID() === null) {
            $this->resolveRequest($pk->getUID(), false, "Failed to create cross post.", ["Message ID must be present!"]);
            return;
        }
        //$this->getServer($pk, $pk->getChannel()->getServerID(), function (DiscordGuild $guild) use ($pk){
        // $guild->messages->fetch($pk->getChannel()->getID())->then(function (DiscordMessage $discord) use ($pk){
        //    $discord->startThread($pk->getChannel()->getName(), $pk->isPrivate(), $pk->getDuration())->then(function () use ($pk){
        $this->getMessage($pk, $pk->getChannelID(), $pk->getMessageID(), function (DiscordMessage $discord) use ($pk) {
            $discord->crosspost()->done(function (DiscordMessage $message) use ($pk) {
                $this->resolveRequest($pk->getUID());
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to bulk delete messages..", [$e->getMessage(), $e->getTraceAsString()]);
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
            $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($guild, $pk) {
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
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
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
            $dMember->setNickname($pk->getNickname())->done(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Updated nickname.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to update nickname.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to update nickname ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleGuildTransfer(RequestGuildTransfer $pk)
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->transferOwnership($pk->getUserId())->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Transferred guild.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to transfer guild.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleStickerUpdate(RequestStickerUpdate $pk)
    {
        $serverId = $pk->getSticker()->getServerId();
        if($serverId === null){
            $this->resolveRequest($pk->getUID(), false, "Server ID must not be null.");
            return;
        }
        $this->getServer($pk, $serverId, function (DiscordGuild $guild) use ($pk) {
            $guild->stickers->fetch($pk->getSticker()->getId())->then(function (DiscordSticker $sticker) use ($guild, $pk) {
                $sticker->name = $pk->getSticker()->getName();
                $sticker->description = $pk->getSticker()->getDescription();
                $guild->stickers->save($sticker)->then(function (DiscordSticker $sticker) use ($pk) {
                    $this->resolveRequest($pk->getUID(), true, "Successfully updated Guild Sticker.", [ModelConverter::genModelStickers($sticker)]);
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
    private function handleAuditLog(RequestGuildAuditLog $pk)
    {
        $this->getServer($pk, $pk->getServerId(), function (DiscordGuild $guild) use ($pk) {
            $guild->getAuditLog([
                "user_id" => $pk->getUserId(),
                "action_type" => $pk->getActionType(),
                "before" => $pk->getBefore(),
                "limit" => $pk->getLimit()
            ])->then(function () use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Searched AuditLog with success!.");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to search audit log.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }
    private function handleSearchMembers(RequestSearchMembers $pk)
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
        $voiceChannel = new DiscordChannel($this->client->getDiscordClient(), [
            'name' => $channel->getName(),
            'id' => $channel->getID()
        ]);
        try {
            $this->client->getDiscordClient()->joinVoiceChannel($voiceChannel, $pk->isMuted(), $pk->isDeafend(), null, true);
            $this->resolveRequest($pk->getUID());
        } catch (\Throwable $e) {
            $this->resolveRequest($pk->getUID(), false, $e->getMessage());
        }
    }
    private function handleVoiceChannelLeave(RequestLeaveVoiceChannel $pk): void
    {
        $channel = $pk->getChannel();
        try {
            $voiceClient = $this->client->getDiscordClient()->getVoiceClient($channel->getServerID());
            if ($voiceClient === null) {
                $this->resolveRequest($pk->getUID(), false, "Bot isn't in a voice channel.");
                return;
            }
            $voiceClient->close();
        } catch (\Throwable $e) {
            $this->resolveRequest($pk->getUID(), false, $e->getMessage());
        }
    }
    private function handleVoiceChannelMove(RequestMoveVoiceChannel $pk): void
    {
        $channel = $pk->getChannel();
        $voiceChannel = new DiscordChannel($this->client->getDiscordClient(), [
            'name' => $channel->getName(),
            'id' => $channel->getID()
        ]);
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
        $this->getChannel($pk, $channel->getID(), function (DiscordChannel $discordChannel) use ($channel, $pk) {

            $discordChannel->moveMember($pk->getUserId())->then(function () use ($pk, $channel) {
                $this->resolveRequest($pk->getUID(), true, "Succcessfully moved member to {$channel->getID()}!");
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
        $this->getChannel($pk, $channel->getID(), function (DiscordChannel $discordChannel) use ($channel, $pk) {

            $discordChannel->muteMember($pk->getUserId())->then(function () use ($pk, $channel) {
                $this->resolveRequest($pk->getUID(), true, "Succcessfully moved member to {$channel->getID()}!");
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
        $this->getChannel($pk, $channel->getID(), function (DiscordChannel $discordChannel) use ($channel, $pk) {

            $discordChannel->unmuteMember($pk->getUserId())->then(function () use ($pk, $channel) {
                $this->resolveRequest($pk->getUID(), true, "Succcessfully unmuted member in {$channel->getID()}!");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to unmute member.", [$e->getMessage(), $e->getTraceAsString()]);
            });
        });
    }


    private function handleSendFile(RequestSendFile $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
            if (!$channel->allowText()) {
                $this->resolveRequest($pk->getUID(), false, "Failed to send file, Invalid channel - text is not allowed.");
                $this->logger->debug("Failed to send file ({$pk->getUID()}) - Channel does not allow text.");
                return;
            }
            $builder = MessageBuilder::new();
            $builder->addFile($pk->getFilePath(), $pk->getFileName());
            $builder->setContent($pk->getMessage());
            $channel->sendMessage($builder)->then(function (DiscordMessage $message) use ($pk) {

                $this->resolveRequest($pk->getUID(), true, "Successfully sent file.", [ModelConverter::genModelMessage($message)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to send file.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to send file ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }
    private function handleModifyInteraction(RequestModifyInteraction $pk)
    {
        $m = $pk->getMessage();
        if ($m->getId() === null) {
            $this->resolveRequest($pk->getUID(), false, "Message ID must be present.");
            return;
        }
        $this->getMessage($pk, $m->getChannelId(), $m->getId(), function (DiscordMessage $message) use ($m, $pk) {
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
                    $builder->setEmbeds($embeds);
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
            if ($m instanceof Reply) {
                if ($m->getReferencedMessageId() === null) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to modify interaction.", ["Reply message has no referenced message ID."]);
                    $this->logger->debug("Failed to modify interaction ({$pk->getUID()}) - Reply message has no referenced message ID.");
                    return;
                }
                $this->getMessage($pk, $m->getChannelId(), $m->getReferencedMessageId(), function (DiscordMessage $msg) use ($builder, $pk, $de) {
                    $builder = $builder->setReplyTo($msg);
                    $msg->edit($builder)->done(function (DiscordMessage $msg) use ($pk) {

                        $interaction = $msg->interaction;
                        print_r($interaction);
                        if ($interaction === null) {
                            $this->resolveRequest($pk->getUID(), false, "Interaction was not found in message.");
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
                    print_r($interaction);
                    if ($interaction === null) {
                        $this->resolveRequest($pk->getUID(), false, "Interaction was not found in message.");
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
            $message->edit($builder)->done(function (DiscordMessage $message) use ($pk) {
                $interaction = $message->interaction;
                print_r($interaction);
                if ($interaction === null) {
                    $this->resolveRequest($pk->getUID(), false, "Interaction was not found in message.");
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
    }
    private function handleCreateInteraction(RequestCreateInteraction $pk)
    {
        $this->getChannel($pk, $pk->getMessage()->getChannelId(), function (DiscordChannel $channel) use ($pk) {
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
                    $builder->setEmbeds($embeds);
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
                    $builder->setEmbeds([$de]);
                }
            }
            if ($m instanceof Reply) {
                if ($m->getReferencedMessageId() === null) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to send interaction.", ["Reply message has no referenced message ID."]);
                    $this->logger->debug("Failed to send interaction ({$pk->getUID()}) - Reply message has no referenced message ID.");
                    return;
                }
                $this->getMessage($pk, $m->getChannelId(), $m->getReferencedMessageId(), function (DiscordMessage $msg) use ($channel, $builder, $pk, $de) {
                    $builder = $builder->setReplyTo($msg);
                    if ($pk->isEphemeral()) {
                        $builder->_setFlags(64);
                    }
                    $channel->sendMessage($builder)->done(function (DiscordMessage $msg) use ($builder, $pk) {
                        $interaction = $msg->interaction;
                        print_r($interaction);
                        if ($interaction === null) {
                            $this->resolveRequest($pk->getUID(), false, "Interaction was not found in message.");
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
                    print_r($interaction);
                    if ($interaction === null) {
                        $this->resolveRequest($pk->getUID(), false, "Interaction was not found in message.");
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
    /** 
     * Handles button creation.
     * @param RequestCreateButton $pk
     * 
     * @return void
     */
    private function handleButtonCreate(RequestCreateButton $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
            if (!$channel->allowText()) {
                $this->resolveRequest($pk->getUID(), false, "Failed to send interaction, Invalid channel - text is not allowed.");
                $this->logger->debug("Failed to send interaction ({$pk->getUID()}) - Channel does not allow text.");
                return;
            }
            $button = $pk->getButton();
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

            $button->setListener(function (DiscordInteraction $interaction) use ($channel, $builder, $pk) {
                ///if(!$interaction->hasResponded()){
                try {
                    $interaction->acknowledge()->then(function () use ($interaction, $pk) {

                        $this->resolveRequest($pk->getUID(), true, "Button created.", [ModelConverter::genModelInteraction($interaction)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to create Button.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to create Button ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                } catch (\Throwable $e) {

                    $interaction->sendFollowUpMessage($builder, $pk->isEphemeral())->then(function () use ($builder, $interaction, $pk) {
                        $this->resolveRequest($pk->getUID(), true, "Successfully sent interaction.", [ModelConverter::genModelInteraction($interaction)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to sent interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                }
            }, $this->client->getDiscordClient(), false);
            $this->resolveRequest($pk->getUID(), true, "Successfully sent Interaction.");
        }, function (\Throwable $e) use ($pk) {
            $this->resolveRequest($pk->getUID(), false, "Failed to send Interaction.", [$e->getMessage(), $e->getTraceAsString()]);
            $this->logger->debug("Failed to send Interaction ({$pk->getUID()}) - {$e->getMessage()}");
        });
    }
    /** 
     * Handles selection menu creation.
     * @param RequestCreateSelectMenu $pk
     * 
     * 
     * @return void
     */
    private function handleSelectCreateMenu(RequestCreateSelectMenu $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
            if (!$channel->allowText()) {
                $this->resolveRequest($pk->getUID(), false, "Failed to send interaction, Invalid channel - text is not allowed.");
                $this->logger->debug("Failed to send interaction ({$pk->getUID()}) - Channel does not allow text.");
                return;
            }
            $select = $pk->getSelectMenu();
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
                    $builde = $builder->setEmbeds($embeds);
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
            $select->setListener(function (DiscordInteraction $interaction, Collection $options) use ($channel, $builder, $pk) {
                foreach ($options as $option) {
                    print_r($option->getValue() . PHP_EOL);
                }

                try {
                    $interaction->acknowledge()->then(function () use ($interaction, $pk) {

                        $this->resolveRequest($pk->getUID(), true, "Select menu created.", [ModelConverter::genModelInteraction($interaction)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to create Select Menu.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to create Select Menu ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                } catch (\Throwable $e) {
                    $interaction->sendFollowupMessage($builder, $pk->isEphemeral())->then(function () use ($builder, $interaction, $pk) {
                        $this->resolveRequest($pk->getUID(), true, "Successfully edit interaction.", [ModelConverter::genModelInteraction($interaction)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to edit interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                }
            }, $this->client->getDiscordClient(), false);
            $this->resolveRequest($pk->getUID(), true, "Successfully sent Interaction.");
        }, function (\Throwable $e) use ($pk) {
            $this->resolveRequest($pk->getUID(), false, "Failed to send Interaction.", [$e->getMessage(), $e->getTraceAsString()]);
            $this->logger->debug("Failed to send Interaction ({$pk->getUID()}) - {$e->getMessage()}");
        });
    }

    /** 
     * Handles button creation.
     * @param RequestModifyButton $pk
     * 
     * @return void
     */
    private function handleButtonModify(RequestModifyButton $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
            if (!$channel->allowText()) {
                $this->resolveRequest($pk->getUID(), false, "Failed to modify interaction, Invalid channel - text is not allowed.");
                $this->logger->debug("Failed to modify button ({$pk->getUID()}) - Channel does not allow text.");
                return;
            }
            $button = $pk->getButton();
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

            $button->setListener(function (DiscordInteraction $interaction) use ($m, $channel, $builder, $pk) {
                if ($pk->doNothing()) {
                    return;
                }
                if ($interaction->channel_id !== $channel->id) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update interaction.", ["Interacted Channel ID: {$interaction->channel_id} is not the same as {$channel->id}"]);
                    return;
                }

                try {
                    $interaction->updateMessage($builder)->then(function () use ($interaction, $pk) {

                        $this->resolveRequest($pk->getUID(), true, "Button modified.", [ModelConverter::genModelInteraction($interaction)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to modify Button.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to modify Button ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                } catch (\Throwable $e) {
                    $interaction->editFollowUpMessage($builder)->then(function () use ($builder, $interaction, $pk) {
                        $this->resolveRequest($pk->getUID(), true, "Successfully edited interaction.", [ModelConverter::genModelInteraction($interaction)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to edit interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                }
            }, $this->client->getDiscordClient(), false);
            $this->resolveRequest($pk->getUID(), true, "Successfully modified Interaction.");
        }, function (\Throwable $e) use ($pk) {
            $this->resolveRequest($pk->getUID(), false, "Failed to modify Interaction.", [$e->getMessage(), $e->getTraceAsString()]);
            $this->logger->debug("Failed to modify Interaction ({$pk->getUID()}) - {$e->getMessage()}");
        });
    }
    /** 
     * Handles selection menu creation.
     * @param RequestModifySelectMenu $pk
     * 
     * 
     * @return void
     */
    private function handleSelectModifyMenu(RequestModifySelectMenu $pk): void
    {
        $this->getChannel($pk, $pk->getChannelId(), function (DiscordChannel $channel) use ($pk) {
            if (!$channel->allowText()) {
                $this->resolveRequest($pk->getUID(), false, "Failed to modify interaction, Invalid channel - text is not allowed.");
                $this->logger->debug("Failed to modify Select Menu ({$pk->getUID()}) - Channel does not allow text.");
                return;
            }
            $select = $pk->getSelectMenu();
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
                    $builde = $builder->setEmbeds($embeds);
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
            $select->setListener(function (DiscordInteraction $interaction, Collection $options) use ($m, $channel, $builder, $pk) {
                foreach ($options as $option) {
                    print_r($option->getValue() . PHP_EOL);
                }
                if ($pk->doNothing()) {
                    return;
                }
                if ($interaction->channel_id !== $channel->id) {
                    $this->resolveRequest($pk->getUID(), false, "Failed to update interaction.", ["Interacted Channel ID: {$interaction->channel_id} is not the same as {$channel->id}"]);
                    return;
                }

                try {
                    $interaction->updateMessage($builder)->then(function () use ($interaction, $pk) {

                        $this->resolveRequest($pk->getUID(), true, "Select Menu modified.", [ModelConverter::genModelInteraction($interaction)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to Modify Select Menu.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to modify Select Menu ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                } catch (\Throwable $e) {
                    $interaction->editFollowUpMessage($builder)->then(function () use ($builder, $interaction, $pk) {
                        $this->resolveRequest($pk->getUID(), true, "Successfully modified interaction.", [ModelConverter::genModelInteraction($interaction)]);
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to modify interaction.", [$e->getMessage(), $e->getTraceAsString()]);
                    });
                }
            }, $this->client->getDiscordClient(), false);
            $this->resolveRequest($pk->getUID(), true, "Successfully modified Interaction.");
        }, function (\Throwable $e) use ($pk) {
            $this->resolveRequest($pk->getUID(), false, "Failed to modify Interaction.", [$e->getMessage(), $e->getTraceAsString()]);
            $this->logger->debug("Failed to modify Interaction ({$pk->getUID()}) - {$e->getMessage()}");
        });
    }




    private function handleSendMessage(RequestSendMessage $pk): void
    {
        $this->getChannel($pk, $pk->getMessage()->getChannelId(), function (DiscordChannel $channel) use ($pk) {
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
                $this->getMessage($pk, $m->getChannelId(), $m->getReferencedMessageId(), function (DiscordMessage $msg) use ($channel, $pk, $de) {
                    $channel->sendMessage($pk->getMessage()->getContent(), false, $de, null, $msg)->done(function (DiscordMessage $msg) use ($pk) {
                        $this->resolveRequest($pk->getUID(), true, "Message sent.", [ModelConverter::genModelMessage($msg)]);
                        $this->logger->debug("Sent message ({$pk->getUID()})");
                    }, function (\Throwable $e) use ($pk) {
                        $this->resolveRequest($pk->getUID(), false, "Failed to send.", [$e->getMessage(), $e->getTraceAsString()]);
                        $this->logger->debug("Failed to send message ({$pk->getUID()}) - {$e->getMessage()}");
                    });
                });
            } else {
                $channel->sendMessage($m->getContent(), false, $de)->done(function (DiscordMessage $msg) use ($pk) {
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
        $this->getMessage($pk, $message->getChannelId(), $message->getId(), function (DiscordMessage $dMessage) use ($pk, $message) {
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
            $dMessage->content = $message->getContent();
            if ($de !== null) {
                $dMessage->embeds->clear();
                $dMessage->addEmbed($de);
            }
            $channel = $dMessage->channel;
            if ($channel === null) {
                return;
            }
            $channel->messages->save($dMessage)->done(function (DiscordMessage $dMessage) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Message edited.", [ModelConverter::genModelMessage($dMessage)]);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to edit message.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to edit message ({$pk->getUID()}) - {$e->getMessage()}");
            });
        });
    }

    private function handleDeleteMessage(RequestDeleteMessage $pk): void
    {
        $this->getMessage($pk, $pk->getChannelId(), $pk->getMessageId(), function (DiscordMessage $dMessage) use ($pk) {
            $dMessage->delete()->done(function () use ($pk) {
                $this->resolveRequest($pk->getUID());
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
        $this->getChannel($pk, $invite->getChannelId(), function (DiscordChannel $channel) use ($pk, $invite) {
            /** @phpstan-ignore-next-line Poorly documented function on discord.php's side. */
            $channel->createInvite([
                "max_age" => $invite->getMaxAge(), "max_uses" => $invite->getMaxUses(), "temporary" => $invite->isTemporary(), "unique" => true
            ])->done(function (DiscordInvite $dInvite) use ($pk) {
                $this->resolveRequest($pk->getUID(), true, "Invite initialised.", [ModelConverter::genModelInvite($dInvite)]);
                $this->logger->debug("Invite initialised ({$pk->getUID()})");
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to initialise.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to initialise invite ({$pk->getUID()}) - {$e->getMessage()}");
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
                    $this->resolveRequest($pk->getUID(), true, "Invite revoked.", [ModelConverter::genModelInvite($dInvite)]);
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
    private function getStage(Packet $pk, string $server_id, string $stage_id, callable $cb): void
    {
        $this->getServer($pk, $server_id, function(DiscordGuild $guild) use ($cb, $pk, $stage_id){
            $guild->stage_instances->fetch($stage_id)->done(function (DiscordStage $stage) use ($guild, $cb) {
                $cb($guild, $stage);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch Stage.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to process request (" . get_class($pk) . "|{$pk->getUID()}) - message error: {$e->getMessage()}");
            });
        });
    }

    //Includes DM Channels.
    private function getChannel(Packet $pk, string $channel_id, callable $cb): void
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
            $cb($c);
        }
    }

    private function getMessage(Packet $pk, string $channel_id, string $message_id, callable $cb): void
    {
        $this->getChannel($pk, $channel_id, function (DiscordChannel $channel) use ($pk, $message_id, $cb) {
            $channel->messages->fetch($message_id)->done(function (DiscordMessage $dMessage) use ($cb) {
                $cb($dMessage);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch message.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to process request (" . get_class($pk) . "|{$pk->getUID()}) - message error: {$e->getMessage()}");
            });
        });
    }
    private function getMessageChannelId(Packet $pk, string $channel_id, string $message_id, callable $cb): void
    {
        $this->getChannel($pk, $channel_id, function (DiscordChannel $channel) use ($pk, $message_id, $cb) {
            $channel->messages->fetch($message_id)->done(function (DiscordMessage $dMessage) use ($channel, $cb) {
                $cb($dMessage, $channel);
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
    private function getThread(Packet $pk, string $channel_id, string $thread_id, callable $cb): void
    {
        $this->getChannel($pk, $channel_id, function (DiscordChannel $channel) use ($pk, $thread_id, $cb) {
            $channel->threads->fetch($thread_id)->then(function (DiscordThread $thread) use ($cb) {
                $cb($thread);
            }, function (\Throwable $e) use ($pk) {
                $this->resolveRequest($pk->getUID(), false, "Failed to fetch thread.", [$e->getMessage(), $e->getTraceAsString()]);
                $this->logger->debug("Failed to process request (" . get_class($pk) . "|{$pk->getUID()}) - Thead Error: {$e->getMessage()}");
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
