<?php
/**
 * SAM-4968: "Auction date cannot be in past" error displayed while editing auction.
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: StartDateCheckerLive: $
 * @since           06.04.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Qform\Auction;

use DateTime;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;

/**
 * Class StartClosingDateCheckerLive
 * @package Sam\Qform\Auction
 */
class StartClosingDateCheckerLive extends CustomizableClass
{
    use CurrentDateTrait;
    use FormStateLongevityAwareTrait;

    /**
     * @var DateTime|null
     */
    protected ?DateTime $dateOnFormLoad = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param DateTime|null $startClosingDateOnFormLoad
     * @param DateTime|null $endDateOnFormLoad
     * @param string $auctionType
     * @return static
     */
    public function initOnFormLoad(?DateTime $startClosingDateOnFormLoad, ?DateTime $endDateOnFormLoad, string $auctionType): static
    {
        $this->dateOnFormLoad = $this->detectApplicableDate($startClosingDateOnFormLoad, $endDateOnFormLoad, $auctionType);
        return $this;
    }

    /**
     * Check if auction end date was modified in the form.
     *
     * @param DateTime|null $startClosingDateOnFormLoad
     * @param DateTime|null $endDateOnFormLoad
     * @param string $auctionType
     * @return bool|null  true if auction changed to past. null - if it's date was not modified in the form.
     */
    public function isEndDateInThePast(?DateTime $startClosingDateOnFormLoad, ?DateTime $endDateOnFormLoad, string $auctionType): ?bool
    {
        $date = $this->detectApplicableDate($startClosingDateOnFormLoad, $endDateOnFormLoad, $auctionType);
        if ($this->isDateChangedManually($date)) {
            return $date < $this->getCurrentDateUtc();
        }
        return null;
    }

    /**
     * @param DateTime|null $startClosingDateOnFormLoad
     * @param DateTime|null $endDateOnFormLoad
     * @param string $auctionType
     * @return DateTime|null
     */
    protected function detectApplicableDate(
        ?DateTime $startClosingDateOnFormLoad,
        ?DateTime $endDateOnFormLoad,
        string $auctionType
    ): ?DateTime {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isHybrid($auctionType)) {
            return $startClosingDateOnFormLoad;
        }
        if ($auctionStatusPureChecker->isLive($auctionType)) {
            return $endDateOnFormLoad;
        }
        throw new \InvalidArgumentException('Unexpected auction type, expecting Live or Hybrid');
    }

    /**
     * Check whether date changed or not.
     *
     * @param DateTime|null $date
     * @return bool
     */
    protected function isDateChangedManually(?DateTime $date): bool
    {
        $isDateChanged = $date != $this->dateOnFormLoad;
        return $isDateChanged;
    }
}
