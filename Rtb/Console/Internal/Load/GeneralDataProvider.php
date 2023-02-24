<?php
/**
 * SAM-6758: Rtb console output builders
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Console\Internal\Load;

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Rtb\Control\LotTitle\Load\RunningLotTitleDataLoaderCreateTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Timezone\Load\TimezoneLoader;
use Timezone;

/**
 * Class DataProvider
 */
class GeneralDataProvider extends CustomizableClass
{
    use AuctionAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DateHelperAwareTrait;
    use EditorUserAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RunningLotTitleDataLoaderCreateTrait;
    use OptionalsTrait;

    public const OP_AUCTION_TIMEZONE = 'auctionTimezone';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int|null $editorUserId null for anonymous visitor
     * @param array $optionals
     * @return $this
     */
    public function construct(int $auctionId, ?int $editorUserId, array $optionals = []): static
    {
        $this->setAuctionId($auctionId);
        $this->setEditorUserId($editorUserId);
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Returns auction.currency if set, setting_system.primary_currency_id otherwise
     * @return string
     */
    public function detectDefaultCurrencySign(): string
    {
        return $this->getCurrencyLoader()->detectDefaultSign($this->getAuctionId());
    }

    /**
     * Get the highest lot number of items on its auction
     * @return int
     */
    public function detectHighestLotNum(): int
    {
        return $this->createRunningLotTitleDataLoader()
            ->detectHighestLotNum($this->getAuctionId(), true);
    }

    /**
     * @return string
     */
    public function detectAuctionStartTimezoneCode(): string
    {
        /** @var Timezone|null $auctionTimezone */
        $auctionTimezone = $this->fetchOptional(self::OP_AUCTION_TIMEZONE);
        $code = $auctionTimezone
            ? $this->getDateHelper()->getTimezoneCodeByLocation(
                $auctionTimezone->Location,
                $this->getAuction()->StartClosingDate
            )
            : '';
        return $code;
    }

    /**
     * @return string
     */
    public function detectAuctionTimezoneLocation(): string
    {
        /** @var Timezone|null $auctionTimezone */
        $auctionTimezone = $this->fetchOptional(self::OP_AUCTION_TIMEZONE);
        $location = $auctionTimezone->Location ?? '';
        return $location;
    }

    /**
     * @return string
     */
    public function detectAuctionTimezoneOffset(): string
    {
        /** @var Timezone|null $auctionTimezone */
        $auctionTimezone = $this->fetchOptional(self::OP_AUCTION_TIMEZONE);
        $offset = $auctionTimezone
            ? $this->getDateHelper()->getTimezoneOffsetByLocation(
                $auctionTimezone->Location,
                $this->getAuction()->StartClosingDate
            )
            : '';
        return $offset;
    }

    protected function initOptionals(array $optionals): void
    {
        $auction = $this->getAuction();
        $auctionTimezone = $optionals[self::OP_AUCTION_TIMEZONE] ?? null;
        $optionals[self::OP_AUCTION_TIMEZONE] = $auctionTimezone instanceof Timezone
            ? $auctionTimezone
            : static function () use ($auction): ?Timezone {
                return TimezoneLoader::new()->load($auction->TimezoneId, true);
            };
        $this->setOptionals($optionals);
    }
}
