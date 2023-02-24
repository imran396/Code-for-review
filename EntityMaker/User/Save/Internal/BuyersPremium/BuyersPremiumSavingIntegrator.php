<?php
/**
 * This mediator service integrate internal service of BuyersPremium saving with UserMakerProducer.
 *
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Save\Internal\BuyersPremium;

use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Save\Internal\BuyersPremium\Internal\Save\BuyersPremiumSaverCreateTrait;
use Sam\EntityMaker\User\Save\Internal\BuyersPremium\Internal\Save\BuyersPremiumSavingInput;
use Sam\EntityMaker\User\Save\UserMakerProducer;
use Sam\Core\Constants;

/**
 * Class BuyersPremiumSavingIntegrator
 * @package Sam\EntityMaker\User\Save\Internal\BuyersPremium
 */
class BuyersPremiumSavingIntegrator extends CustomizableClass
{
    use BuyersPremiumSaverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Integrate internal service of new BuyersPremium records creation into user entity-maker producer.
     * @param UserMakerProducer $userMakerProducer
     */
    public function create(UserMakerProducer $userMakerProducer): void
    {
        $this->save($userMakerProducer, true);
    }

    /**
     * Integrate internal service of updating BuyersPremium records into user entity-maker producer.
     * @param UserMakerProducer $userMakerProducer
     */
    public function update(UserMakerProducer $userMakerProducer): void
    {
        $this->save($userMakerProducer);
    }

    /**
     * @param UserMakerProducer $userMakerProducer
     * @param bool $isCreate
     * @return void
     */
    protected function save(UserMakerProducer $userMakerProducer, bool $isCreate = false): void
    {
        $configDto = $userMakerProducer->getConfigDto();
        $inputDto = $userMakerProducer->getInputDto();
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        foreach (Constants\Auction::AUCTION_TYPES as $auctionType) {
            if ($auctionStatusPureChecker->isHybrid($auctionType)) {
                $buyersPremiumString = $inputDto->buyersPremiumHybridString;
                $buyersPremiumDataRows = $inputDto->buyersPremiumHybridDataRows;
            } elseif ($auctionStatusPureChecker->isLive($auctionType)) {
                $buyersPremiumString = $inputDto->buyersPremiumLiveString;
                $buyersPremiumDataRows = $inputDto->buyersPremiumLiveDataRows;
            } else {
                $buyersPremiumString = $inputDto->buyersPremiumTimedString;
                $buyersPremiumDataRows = $inputDto->buyersPremiumTimedDataRows;
            }
            if (
                $buyersPremiumString === null
                && $buyersPremiumDataRows === null
            ) {
                continue;
            }

            $input = BuyersPremiumSavingInput::new()->construct(
                $configDto->mode,
                $buyersPremiumString,
                $buyersPremiumDataRows,
                $configDto->editorUserId,
                $userMakerProducer->getUserId(),
                $userMakerProducer->userDirectAccountId(),
                $auctionType
            );
            $this->createBuyersPremiumSaver()->save($input, $isCreate);
        }
    }

}
