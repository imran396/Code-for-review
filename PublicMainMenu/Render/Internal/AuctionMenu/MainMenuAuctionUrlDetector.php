<?php
/**
 * SAM-6632: Fix for Front-end main menu "Auctions" target setting
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Render\Internal\AuctionMenu;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Settings\SettingsManager;
use Sam\Storage\Entity\Aggregate\AccountAggregate;

/**
 * Class MainMenuAuctionUrlApplier
 */
class MainMenuAuctionUrlDetector extends CustomizableClass
{
    use OptionalsTrait;
    use UrlParserAwareTrait;

    public const MAIN_MENU_AUCTION_TARGET_FOR_SYSTEM = 'mainMenuAuctionTargetForSystem';
    public const MAIN_MENU_AUCTION_TARGET_FOR_MAIN = 'mainMenuAuctionTargetForMain';
    public const IS_PORTAL_SYSTEM_ACCOUNT = 'isPortalSystemAccount';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $systemAccountId
     * @param array $optionals = [
     *     self::MAIN_MENU_AUCTION_TARGET_FOR_SYSTEM => string,
     *     self::MAIN_MENU_AUCTION_TARGET_FOR_MAIN => string,
     *     self::IS_PORTAL_SYSTEM_ACCOUNT => bool,
     * ]
     * @return $this
     */
    public function construct(int $systemAccountId, array $optionals = []): static
    {
        $this->initOptionals($systemAccountId, $optionals);
        return $this;
    }

    /**
     * Detect url that should be used at responsive auction list page for referencing auction links
     * It depends on system parameters configuration of system or main account.
     * @return string
     */
    public function detectUrl(): string
    {
        $mainMenuAuctionTarget = $this->fetchOptional(self::MAIN_MENU_AUCTION_TARGET_FOR_SYSTEM);
        $mainMenuAuctionTarget = trim($mainMenuAuctionTarget);
        if ($mainMenuAuctionTarget) {
            return $this->getUrlParser()->addHttpScheme($mainMenuAuctionTarget);
        }

        $isPortalSystemAccount = $this->fetchOptional(self::IS_PORTAL_SYSTEM_ACCOUNT);
        if ($isPortalSystemAccount) {
            // One more try - fetch default from main account
            $mainMenuAuctionTarget = $this->fetchOptional(self::MAIN_MENU_AUCTION_TARGET_FOR_MAIN);
            $mainMenuAuctionTarget = trim($mainMenuAuctionTarget);
            if ($mainMenuAuctionTarget) {
                return $this->getUrlParser()->addHttpScheme($mainMenuAuctionTarget);
            }
        }

        return '';
    }

    /**
     * Init Optionals and define lazy initialization callbacks
     * @param int $systemAccountId
     * @param array $optionals
     */
    protected function initOptionals(int $systemAccountId, array $optionals): void
    {
        $optionals[self::MAIN_MENU_AUCTION_TARGET_FOR_SYSTEM] = $optionals[self::MAIN_MENU_AUCTION_TARGET_FOR_SYSTEM]
            ?? static function () use ($systemAccountId) {
                $targetUrl = SettingsManager::new()
                    ->get(Constants\Setting::MAIN_MENU_AUCTION_TARGET, $systemAccountId);
                return trim($targetUrl);
            };

        $optionals[self::MAIN_MENU_AUCTION_TARGET_FOR_MAIN] = $optionals[self::MAIN_MENU_AUCTION_TARGET_FOR_MAIN]
            ?? static function () {
                $targetUrl = SettingsManager::new()
                    ->getForMain(Constants\Setting::MAIN_MENU_AUCTION_TARGET);
                return trim($targetUrl);
            };

        $optionals[self::IS_PORTAL_SYSTEM_ACCOUNT] = $optionals[self::IS_PORTAL_SYSTEM_ACCOUNT]
            ?? static function () use ($systemAccountId) {
                return AccountAggregate::new()
                    ->setAccountId($systemAccountId)
                    ->isPortalAccount();
            };

        $this->setOptionals($optionals);
    }
}
