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

namespace JaxkDev\DiscordBot\Models;

use JaxkDev\DiscordBot\Plugin\Utils;

use JaxkDev\DiscordBot\Models\ServerScheduledEvent;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Member;
use JaxkDev\DiscordBot\Models\Role;
use JaxkDev\DiscordBot\Models\ServerTemplate;

class Server implements \Serializable
{

    /** Verification level */
    public const LEVEL_OFF = 0;
    public const LEVEL_LOW = 1;
    public const LEVEL_MEDIUM = 2;
    public const LEVEL_TABLEFLIP = 3;
    public const LEVEL_DOUBLE_TABLEFLIP = 4;

    /** Explicit Content Filter */
    public const FILTER_DISABLED = 0;
    public const FILTER_MEMBERS_WITHOUT_ROLES = 1;
    public const FILTER_ALL_MEMBERS = 2;


    /** Default Notifications */
    public const ALL_MESSAGES = 0;
    public const ONLY_MENTIONS = 1;

    /** MFA Level */
    public const MFA_LEVEL_NONE = 0;
    public const MFA_LEVEL_ELEVATED = 1;

    /** Booster Level */
    public const TIER_NONE = 0;
    public const TIER_1 = 1;
    public const TIER_2 = 2;
    public const TIER_3 = 3;

    /** NSFW Level */
    public const NSFW_DEFAULT = 0;
    public const NSFW_EXPLICIT = 1;
    public const NSFW_SAFE = 2;
    public const NSFW_AGE_RESTRICTED = 3;

    /** Channel Flags */
    public const SUPPRESS_JOIN_NOTIFICATIONS = 1;
    public const SUPPRESS_PREMIUM_SUBSCRIPTION = 2;
    public const SUPPRESS_GUILD_REMINDER_NOTIFICATIONS = 4;
    public const SUPPRESS_JOIN_NOTIFICATION_REPLIES = 8;
    public const TOTAL_FLAGS =
    self::SUPPRESS_JOIN_NOTIFICATIONS +
        self::SUPPRESS_PREMIUM_SUBSCRIPTION +
        self::SUPPRESS_GUILD_REMINDER_NOTIFICATIONS +
        self::SUPPRESS_JOIN_NOTIFICATION_REPLIES;
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string|null */
    private $icon_url;

    /** @var string 
     * @deprecated
     */
    private $region;

    /** @var string */
    private $owner_id;

    /** @var bool */
    private $large;

    /** @var int */
    private $member_count;

    /** @var string|null */
    private $afk_channel_id; //null if not set.

    /** @var int|null */
    private $afk_timeout; //null when empty.

    /** @var string|null */
    private $splash; //null when creating.

    /** @var string|null */
    private $discovery_splash; //null if not discoverable.

    /** @var int */
    private $verification_level;

    /** @var int */
    private $default_notifications;

    /** @var int */
    private $content_filter;

    /** @var int */
    private $mfa;

    /** @var string|null */
    private $application_id; //null when server wasn't created by the bot.

    /** @var bool */
    private $widget_enabled;

    /** @var string|null */
    private $widget_channel_id; //null if not set.

    /** @var string|null */
    private $system_channel_id; //null if not set.

    /** @var int */
    private $flags;

    /** @var string|null */
    private $rules_channel_id; //null if not set.

    /** @var string|null */
    private $vanityCode; //null if vanity urls aren't unlocked yet.

    /** @var string|null */
    private $description; //null if community tab is disabled.

    /** @var string|null */
    private $banner; //null if not setting a banner.

    /** @var int */
    private $premium_tier;

    /** @var int */
    private $premium_sub_count;

    /** @var string */
    private $preferred_locale;

    /** @var string|null */
    private $public_updates_channel_id; //null if not set.

    /** @var int */
    private $nsfwLevel;

    /** @var bool */
    private $progressBar;

    /** @var ServerScheduledEvent[] */
    private $schedules = [];

    /** @var ServerTemplate[] */
    private $templates = [];

    /** @var Role[] */
    private $roles = [];

    /** @var ServerChannel[] */
    private $channels = [];

    /** @var Member[] */
    private $members = [];


    /** @var WelcomeScreen|null */
    private $welcomeScreen; //null when welcome screen is disabled.


    /** Server Constructor
     * 
     * @param string                            $id
     * @param string                            $name
     * @param string                            $region
     * @param string                            $owner_id
     * @param bool                              $large
     * @param int                               $member_count
     * @param string|null                       $icon_url
     * @param WelcomeScreen|null                $screen
     * @param string|null                       $afk_channel_id
     * @param int|null                          $afk_timeout
     * @param string|null                       $splash
     * @param string|null                       $discovery_splash
     * @param int                               $verification_level
     * @param int                               $default_notifications
     * @param int                               $content_filter
     * @param int                               $mfa_level
     * @param string|null                       $application_id
     * @param bool                              $widget_enabled
     * @param string|null                       $widget_channel_id
     * @param string|null                       $system_channel_id
     * @param int                               $system_channel_flags
     * @param string|null                       $rules_channel_id
     * @param string|null                       $vanity_url_code
     * @param string|null                       $description
     * @param string|null                       $banner
     * @param int                               $premiumm_tier
     * @param int                               $premium_subscription_count
     * @param string                            $preferred_locale
     * @param string|null                       $public_updates_channel_id
     * @param int                               $nsfw_level
     * @param bool                              $premium_progress_bar_enabled
     * @param ServerScheduledEvent[]            $schedules
     * @param ServerTemplate[]                  $templates
     * @param Role[]                            $roles
     * @param ServerChannel[]                   $channels
     * @param Member[]                          $members 
     * 
     */
    public function __construct(
        string $id,
        string $name,
        string $region,
        string $owner_id,
        bool $large,
        int $member_count,
        ?string $icon_url = null,
        ?WelcomeScreen $screen = null,
        ?string $afk_channel_id = null,
        ?int $afk_timeout = null,
        ?string $splash = null,
        ?string $discovery_splash = null,
        int $verification_level = self::LEVEL_OFF,
        int $default_notifications = self::ONLY_MENTIONS,
        int $content_filter = self::FILTER_ALL_MEMBERS,
        int $mfa_level = self::MFA_LEVEL_NONE,
        ?string $application_id = null,
        bool $widget_enabled = false,
        ?string $widget_channel_id = null,
        ?string $system_channel_id = null,
        int $system_channel_flags = 0,
        ?string $rules_channel_id = null,
        ?string $vanity_url_code = null,
        ?string $description = null,
        ?string $banner = null,
        int $premium_tier = self::TIER_NONE,
        int $premium_subscription_count = 0,
        string $preferred_locale = "en-US",
        ?string $public_updates_channel_id = null,
        int $nsfw_level = self::NSFW_DEFAULT,
        bool $premium_progress_bar_enabled = false,
        array $schedules = [],
        array $templates = [],
        array $roles = [],
        array $channels = [],
        array $members = []






    ) {
        $this->setId($id);
        $this->setName($name);
        $this->setRegion($region);
        $this->setOwnerId($owner_id);
        $this->setLarge($large);
        $this->setMemberCount($member_count);
        $this->setIconUrl($icon_url);
        $this->setWelcomeScreen($screen);
        $this->setAfkChannelId($afk_channel_id);
        $this->setAfkTimeout($afk_timeout);
        $this->setSplash($splash);
        $this->setDiscoverySplash($discovery_splash);
        $this->setVerificationLevel($verification_level);
        $this->setDefaultNotifications($default_notifications);
        $this->setContentFilter($content_filter);
        $this->setMFALevel($mfa_level);
        $this->setApplicationId($application_id);
        $this->setWidgetEnabled($widget_enabled);
        $this->setWidgetChannelId($widget_channel_id);
        $this->setSystemChannelId($system_channel_id);
        $this->setSystemChannelFlags($system_channel_flags);
        $this->setRulesChannelId($rules_channel_id);
        $this->setVanityUrlCode($vanity_url_code);
        $this->setDescription($description);
        $this->setBanner($banner);
        $this->setPremiumTier($premium_tier);
        $this->setPremiumSubCount($premium_subscription_count);
        $this->setPreferredLocale($preferred_locale);
        $this->setPublicUpdatesChannelID($public_updates_channel_id);
        $this->setNSFWLevel($nsfw_level);
        $this->setProgressBar($premium_progress_bar_enabled);
        $this->setSchedules($schedules);
        $this->setTemplates($templates);
        $this->setRoles($roles);
        $this->setChannels($channels);
        $this->setMembers($members);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        if (!Utils::validDiscordSnowflake($id)) {
            throw new \AssertionError("Server ID '$id' is invalid.");
        }
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIconUrl(): ?string
    {
        return $this->icon_url;
    }

    public function setIconUrl(?string $icon_url): void
    {
        $this->icon_url = $icon_url;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    public function getOwnerId(): string
    {
        return $this->owner_id;
    }

    public function setOwnerId(string $owner_id): void
    {
        if (!Utils::validDiscordSnowflake($owner_id)) {
            throw new \AssertionError("Owner ID '$owner_id' is invalid.");
        }
        $this->owner_id = $owner_id;
    }


    public function isLarge(): bool
    {
        return $this->large;
    }

    public function setLarge(bool $large): void
    {
        $this->large = $large;
    }

    public function getMemberCount(): int
    {
        return $this->member_count;
    }

    public function setMemberCount(int $member_count): void
    {
        $this->member_count = $member_count;
    }
    public function getWelcomeScreen(): ?WelcomeScreen
    {
        return $this->welcomeScreen;
    }
    public function setWelcomeScreen(?WelcomeScreen $screen)
    {
        $this->welcomeScreen = $screen;
    }
    public function getAfkChannelId(): ?string
    {
        return $this->afk_channel_id;
    }

    public function setAfkChannelId(?string $channel_id): void
    {
        if ($channel_id !== null) {
            if (!Utils::validDiscordSnowflake($channel_id)) {
                throw new \AssertionError("Channel ID '$channel_id' is invalid.");
            }
        }
        $this->afk_channel_id = $channel_id;
    }
    public function getAfkTimeout(): ?int
    {
        return $this->afk_timeout;
    }
    public function setAfkTimeout(?int $timeout): void
    {
        if ($timeout !== null) {
            if ($timeout <= 0) {
                throw new \AssertionError("Timeout: {$timeout} cannot be 0 or below.");
            }
        }
        $this->afk_timeout = $timeout;
    }
    public function getSplash(): ?string
    {
        return $this->splash;
    }
    public function setSplash(?string $splash): void
    {
        $this->splash = $splash;
    }
    public function getDiscoverySplash(): ?string
    {
        return $this->discovery_splash;
    }
    public function setDiscoverySplash(?string $splash): void
    {
        $this->discovery_splash = $splash;
    }
    public function getVerificationLevel(): int
    {
        return $this->verification_level;
    }
    public function setVerificationLevel(int $level): void
    {
        if ($level < self::LEVEL_OFF or $level > self::LEVEL_DOUBLE_TABLEFLIP) {
            throw new \AssertionError("{$level} is invalid. Must be betwen 0-4");
        }
        $this->verification_level = $level;
    }
    public function getDefaultNotifications(): int
    {
        return $this->default_notifications;
    }
    public function setDefaultNotifications(int $level): void
    {
        if ($level < 0 or $level > 1) {
            throw new \AssertionError("{$level} is invalid. Must be between 0-1");
        }
        $this->default_notifications = $level;
    }
    public function getContentFilter(): int
    {
        return $this->content_filter;
    }
    public function setContentFilter(int $level): void
    {
        if ($level < self::FILTER_DISABLED or $level > self::FILTER_ALL_MEMBERS) {
            throw new \AssertionError("{$level} is invalid. Must be between 0-2");
        }
        $this->content_filter = $level;
    }
    public function getMFALevel(): int
    {
        return $this->mfa;
    }
    public function setMFALevel(int $level): void
    {
        if ($level < self::MFA_LEVEL_NONE or $level > self::MFA_LEVEL_ELEVATED) {
            throw new \AssertionError("{$level} is invalid. Must be between 0-1");
        }
        $this->mfa = $level;
    }
    public function getApplicationId(): ?string
    {
        return $this->application_id;
    }

    public function setApplicationId(?string $id): void
    {
        if ($id !== null) {
            if (!Utils::validDiscordSnowflake($id)) {
                throw new \AssertionError("Channel ID '$id' is invalid.");
            }
        }
        $this->application_id = $id;
    }
    public function isWidgetEnabled(): booL
    {
        return $this->widget_enabled;
    }
    public function setWidgetEnabled(bool $widget)
    {
        $this->widget_enabled = $widget;
    }
    public function getWidgetChannelId(): ?string
    {
        return $this->widget_channel_id;
    }

    public function setWidgetChannelId(?string $channel_id): void
    {
        if ($channel_id !== null) {
            if (!Utils::validDiscordSnowflake($channel_id)) {
                throw new \AssertionError("Channel ID '$channel_id' is invalid.");
            }
        }
        $this->widget_channel_id = $channel_id;
    }
    public function getSystemChannelId(): ?string
    {
        return $this->system_channel_id;
    }

    public function setSystemChannelId(?string $channel_id): void
    {
        if ($channel_id !== null) {
            if (!Utils::validDiscordSnowflake($channel_id)) {
                throw new \AssertionError("Channel ID '$channel_id' is invalid.");
            }
        }
        $this->system_channel_id = $channel_id;
    }
    public function getSystemChannelFlags(): int
    {
        return $this->flags;
    }
    public function setSystemChannelFlags(int $flags): void
    {
        if ($flags > self::TOTAL_FLAGS) {
            throw new \AssertionError("Channel Flag: {$flags} is over the Total Flags limit: " . self::TOTAL_FLAGS);
        }
        if ($flags < 0) {
            throw new \AssertionError("Channel Flag: {$flags} is invalid.");
        }
        $this->flags = $flags;
    }
    public function getRulesChannelId(): ?string
    {
        return $this->rules_channel_id;
    }

    public function setRulesChannelId(?string $channel_id): void
    {
        if ($channel_id !== null) {
            if (!Utils::validDiscordSnowflake($channel_id)) {
                throw new \AssertionError("Channel ID '$channel_id' is invalid.");
            }
        }
        $this->rules_channel_id = $channel_id;
    }
    public function getVanityUrlCode(): ?string
    {
        return $this->vanityCode;
    }
    public function setVanityUrlCode(?string $url)
    {
        $this->vanityCode = $url;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): void
    {
        if ($description !== null) {
            if (strlen($description) > 120) {
                throw new \AssertionError("Description character limit has been reached. Please go below 120 characters.");
            }
        }
        $this->description = $description;
    }
    public function getBanner(): ?string
    {
        return $this->banner;
    }
    public function setBanner(?string $banner): void
    {
        $this->banner = $banner;
    }
    public function getPremiumTier(): int
    {
        return $this->premium_tier;
    }
    public function setPremiumTier(int $tier): void
    {
        if ($tier < self::TIER_NONE or $tier > self::TIER_3) {
            throw new \AssertionError("Tier {$tier} is invalid. Must be between 0-3");
        }
        $this->premium_tier = $tier;
    }
    public function getPremiumSubCount(): int
    {
        return $this->premium_sub_count;
    }
    public function setPremiumSubCount(int $sub): void
    {
        $this->premium_sub_count = $sub;
    }
    public function getPreferredLocale(): string
    {
        return $this->preferred_locale;
    }
    public function setPreferredLocale(string $locale): void
    {
        $this->preferred_locale = $locale;
    }
    public function getPublicUpdatesChannelId(): ?string
    {
        return $this->public_updates_channel_id;
    }
    public function setPublicUpdatesChannelId(?string $channel_id): void
    {
        if ($channel_id !== null) {
            if (!Utils::validDiscordSnowflake($channel_id)) {
                throw new \AssertionError("Channel ID '$channel_id' is invalid.");
            }
        }
        $this->public_updates_channel_id = $channel_id;
    }
    public function getNSFWLevel(): int
    {
        return $this->nsfwLevel;
    }
    public function setNSFWLevel(int $level)
    {
        if ($level < self::NSFW_DEFAULT or $level > self::NSFW_AGE_RESTRICTED) {
            throw new \AssertionError("NSFW Level: {$level} is invalid. Must be 0-3");
        }
        $this->nsfwLevel;
    }
    public function isProgressBar(): bool
    {
        return $this->progressBar;
    }
    public function setProgressBar(bool $progressBar)
    {
        $this->progressBar = $progressBar;
    }
    /** @return ServerScheduledEvent[] */
    public function getSchedules(): array
    {
        return $this->schedules;
    }

    /** @param ServerScheduledEvent[] $schedules*/
    public function setSchedules(array $schedules): void
    {
        $this->schedules = $schedules;
    }

    /** @return ServerTemplate[] */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /** @param ServerTemplate[] $templates */
    public function setTemplates(array $templates): void
    {
        $this->templates = $templates;
    }

    /** @return Role[] */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /** @param Role[] $roles */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /** @return ServerChannel[] */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /** @param ServerChannel[] $channels */
    public function setChannels(array $channels): void
    {
        $this->channels = $channels;
    }

    /** @return Member[] */
    public function getMembers(): array
    {
        return $this->members;
    }

    /** @param Member[] $members */
    public function setMembers(array $members): void
    {
        $this->members = $members;
    }
    public function getCreationTimestamp(): float
    {
        return Utils::getDiscordSnowflakeTimestamp($this->id);
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->name,
            $this->icon_url,
            $this->region,
            $this->owner_id,
            $this->large,
            $this->member_count,
            $this->welcomeScreen,
            $this->afk_channel_id,
            $this->afk_timeout,
            $this->splash,
            $this->discovery_splash,
            $this->verification_level,
            $this->default_notifications,
            $this->content_filter,
            $this->mfa,
            $this->application_id,
            $this->widget_enabled,
            $this->widget_channel_id,
            $this->system_channel_id,
            $this->flags,
            $this->rules_channel_id,
            $this->vanityCode,
            $this->description,
            $this->banner,
            $this->premium_tier,
            $this->premium_sub_count,
            $this->preferred_locale,
            $this->public_updates_channel_id,
            $this->nsfwLevel,
            $this->progressBar,
            $this->schedules,
            $this->templates,
            $this->roles,
            $this->channels,
            $this->members



        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->id,
            $this->name,
            $this->icon_url,
            $this->region,
            $this->owner_id,
            $this->large,
            $this->member_count,
            $this->welcomeScreen,
            $this->afk_channel_id,
            $this->afk_timeout,
            $this->splash,
            $this->discovery_splash,
            $this->verification_level,
            $this->default_notifications,
            $this->content_filter,
            $this->mfa,
            $this->application_id,
            $this->widget_enabled,
            $this->widget_channel_id,
            $this->system_channel_id,
            $this->flags,
            $this->rules_channel_id,
            $this->vanityCode,
            $this->description,
            $this->banner,
            $this->premium_tier,
            $this->premium_sub_count,
            $this->preferred_locale,
            $this->public_updates_channel_id,
            $this->nsfwLevel,
            $this->progressBar,
            $this->schedules,
            $this->templates,
            $this->roles,
            $this->channels,
            $this->members
        ] = unserialize($data);
    }
}
