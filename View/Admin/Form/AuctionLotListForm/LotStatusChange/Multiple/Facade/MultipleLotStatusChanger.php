<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Facade;

use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Load\LotDtoLoaderCreateTrait;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Translate\MultipleLotStatusChangeTranslatorCreateTrait;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Update\MultipleLotStatusChangeUpdaterCreateTrait;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Validate\MultipleLotStatusChangeValidatorCreateTrait;

/**
 * Class MultipleLotStatusChanger
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Facade
 */
class MultipleLotStatusChanger extends CustomizableClass
{
    use LotDtoLoaderCreateTrait;
    use MultipleLotStatusChangeTranslatorCreateTrait;
    use MultipleLotStatusChangeUpdaterCreateTrait;
    use MultipleLotStatusChangeValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function change(
        int $targetLotStatus,
        array $lotItemIds,
        int $auctionId,
        int $editorUserId,
        string $translationLanguage
    ): MultipleLotStatusChangeResult {
        $result = MultipleLotStatusChangeResult::new();
        $translator = $this->createMultipleLotStatusChangeTranslator();
        if (!$lotItemIds) {
            return $result->setErrorMessage($translator->makeNoLotsProcessedMessage($translationLanguage));
        }

        $lotDtos = $this->createLotDtoLoader()->loadDtos($lotItemIds, $auctionId);
        $validationResult = $this->createMultipleLotStatusChangeValidator()->validate($targetLotStatus, $lotDtos);
        if ($validationResult->hasError()) {
            $result->setErrorMessage($translator->makeErrorMessage($validationResult, $auctionId, $translationLanguage));
        }

        $validLotItemIds = $validationResult->collectValidLotItemIds();
        if ($validLotItemIds) {
            $this->createMultipleLotStatusChangeUpdater()->updateStatus($targetLotStatus, $validLotItemIds, $auctionId, $editorUserId);
            $result->setSuccessMessage($translator->makeSuccessMessage($validationResult, $auctionId, $translationLanguage));
        }
        return $result;
    }
}
