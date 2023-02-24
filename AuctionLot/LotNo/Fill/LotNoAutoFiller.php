<?php
/**
 * Lot# auto-filling service
 * We are using auto-fill custom field data or lot# incrementing.
 *
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Fill;

use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\AuctionLot\LotNo\Fill\CustomField\Save\LotNoByCustomFieldProducerCreateTrait;
use Sam\AuctionLot\LotNo\Fill\Save\LotNoByAutoIncrementProducerCreateTrait;
use Sam\Core\Constants;

/**
 * Contains methods for making unique auction lot No.
 *
 * Class LotNoAutoFiller
 * @package Sam\AuctionLot\LotNo\Fill
 */
class LotNoAutoFiller extends CustomizableClass
{
    use LotNoByAutoIncrementProducerCreateTrait;
    use LotNoByCustomFieldProducerCreateTrait;

    /**
     * Settings for lot num auto incrementing
     * @var int|null
     */
    private ?int $autoIncrementMode = null;
    /**
     * Settings for lot num autofill by custom field
     * @var bool
     */
    private bool $isCustomFieldAutoFill = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init instance with defaults
     * @return static
     */
    public function initInstance(): static
    {
        $this->setAutoIncrementMode(Constants\Lot::LOT_NO_AUTO_INC_AUCTION_OPTION);
        $this->enableCustomFieldAutoFill(true);
        return $this;
    }

    /**
     * Check if lot# is empty and then fill lot# using one of the methods
     * We use auto-fill custom field data or lot number incrementing
     *
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    public function fill(AuctionLotItem $auctionLot): bool
    {
        return $this->fillByCustomField($auctionLot) || $this->fillByAutoincrement($auctionLot);
    }

    /**
     * Set isCustomFieldAutoFill property value and normalize boolean value
     * @param bool $isCustomFieldAutoFill
     * @return static
     */
    public function enableCustomFieldAutoFill(bool $isCustomFieldAutoFill): static
    {
        $this->isCustomFieldAutoFill = $isCustomFieldAutoFill;
        return $this;
    }

    /**
     * Set autoIncrementMode property value and normalize to integer positive value
     *
     * @param int $autoIncrementMode
     * @return static
     */
    public function setAutoIncrementMode(int $autoIncrementMode): static
    {
        $this->autoIncrementMode = $autoIncrementMode;
        return $this;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    private function fillByAutoincrement(AuctionLotItem $auctionLot): bool
    {
        if (
            (string)$auctionLot->LotNum !== ''
            || $this->isAutoincrementOff()
        ) {
            return false;
        }

        $lotNoParts = $this->createLotNoByAutoIncrementProducer()->produce($auctionLot, $this->shouldCheckAuctionAutoPopulateOption());
        return $this->populateAuctionLotNo($auctionLot, $lotNoParts);
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    private function fillByCustomField(AuctionLotItem $auctionLot): bool
    {
        if (!$this->isCustomFieldAutoFillEnabled() || !$this->isLotNoEmpty($auctionLot)) {
            return false;
        }

        $lotNoParts = $this->createLotNoByCustomFieldProducer()->produce($auctionLot);
        return $this->populateAuctionLotNo($auctionLot, $lotNoParts);
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param array $lotNoParts
     * @return bool
     */
    private function populateAuctionLotNo(AuctionLotItem $auctionLot, array $lotNoParts): bool
    {
        if (!$lotNoParts) {
            return false;
        }

        $auctionLot->LotNum = $lotNoParts['LotNum'];
        $auctionLot->LotNumExt = $lotNoParts['LotNumExt'];
        $auctionLot->LotNumPrefix = $lotNoParts['LotNumPrefix'];
        return true;
    }

    /**
     * Check if lot# is completely empty
     *
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    private function isLotNoEmpty(AuctionLotItem $auctionLot): bool
    {
        $lotNoConcat = trim($auctionLot->LotNum . $auctionLot->LotNumExt . $auctionLot->LotNumPrefix);
        return empty($lotNoConcat);
    }

    /**
     * @return bool
     */
    private function isAutoincrementOff(): bool
    {
        return $this->autoIncrementMode === Constants\Lot::LOT_NO_AUTO_INC_OFF;
    }

    /**
     * @return bool
     */
    private function shouldCheckAuctionAutoPopulateOption(): bool
    {
        return $this->autoIncrementMode === Constants\Lot::LOT_NO_AUTO_INC_AUCTION_OPTION;
    }

    /**
     * Return value of isCustomFieldAutoFill property
     *
     * @return bool
     */
    private function isCustomFieldAutoFillEnabled(): bool
    {
        return $this->isCustomFieldAutoFill;
    }
}
