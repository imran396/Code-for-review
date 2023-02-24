<?php
/**
 * SAM-10464: Separate BP manager to services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Clone;

use BuyersPremium;
use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\BuyersPremium\Load\BuyersPremiumRangeLoaderCreateTrait;
use Sam\BuyersPremium\Save\BuyersPremiumRangeProducerCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\BuyersPremium\BuyersPremiumWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeWriteRepositoryAwareTrait;

/**
 * Class BuyersPremiumCloner
 * @package Sam\BuyersPremium\Clone
 */
class BuyersPremiumCloner extends CustomizableClass
{
    use BuyersPremiumLoaderCreateTrait;
    use BuyersPremiumRangeLoaderCreateTrait;
    use BuyersPremiumRangeProducerCreateTrait;
    use BuyersPremiumRangeWriteRepositoryAwareTrait;
    use BuyersPremiumWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param int $sourceAuctionId
     * @param int $targetAuctionId
     * @param int $creatorUserId
     */
    public function cloneAuctionBpRange(int $sourceAuctionId, int $targetAuctionId, int $creatorUserId): void
    {
        $origBpRangeArray = $this->createBuyersPremiumRangeLoader()->loadBpRangeByAuctionId($sourceAuctionId);
        foreach ($origBpRangeArray as $origBpRange) {
            $this->createBuyersPremiumRangeProducer()->addInAuctionBp(
                $origBpRange->Amount,
                $origBpRange->Fixed,
                $origBpRange->Percent,
                $origBpRange->Mode,
                $creatorUserId,
                $targetAuctionId
            );
        }
    }

    /**
     * Clone buyersPremiums from provided account
     * @param int $sourceAccountId
     * @param int $targetAccountId
     * @param int $editorUserId
     */
    public function cloneFromAccount(int $sourceAccountId, int $targetAccountId, int $editorUserId): void
    {
        foreach ($this->createBuyersPremiumLoader()->loadAuctionTypeBpByAccountId($sourceAccountId) as $buyersPremium) {
            $this->copyAndSaveBp($buyersPremium, $targetAccountId, $editorUserId);
        }
    }

    /**
     * @param BuyersPremium $bp
     * @param int $accountId account.id
     * @param int $editorUserId user.id
     */
    protected function copyAndSaveBp(BuyersPremium $bp, int $accountId, int $editorUserId): void
    {
        $newBp = $this->createEntityFactory()->buyersPremium();
        $newBp->AccountId = $accountId;
        $newBp->Name = $bp->Name;
        $newBp->ShortName = $bp->ShortName;
        $newBp->RangeCalculation = $bp->RangeCalculation;
        $newBp->AdditionalBpInternet = $bp->AdditionalBpInternet;
        $newBp->Active = $bp->Active;
        $this->getBuyersPremiumWriteRepository()->saveWithModifier($newBp, $editorUserId);

        $bpRanges = $this->createBuyersPremiumRangeLoader()->loadBpRangeByBpId($bp->Id);
        foreach ($bpRanges as $bpRange) {
            $newBpRange = $this->createEntityFactory()->buyersPremiumRange();
            $newBpRange->BuyersPremiumId = $newBp->Id;
            $newBpRange->Amount = $bpRange->Amount;
            $newBpRange->Fixed = $bpRange->Fixed;
            $newBpRange->Percent = $bpRange->Percent;
            $newBpRange->Mode = $bpRange->Mode;
            $this->getBuyersPremiumRangeWriteRepository()->saveWithModifier($newBpRange, $editorUserId);
        }
    }
}
