<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale\Internal\Load\DataProviderCreateTrait;

/**
 * Class QuantityScaleDetector
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Scale
 */
class QuantityScaleDetector extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detect(QuantityScaleDetectorInput $input): int
    {
        if (is_numeric($input->quantityDigits)) {
            return max((int)$input->quantityDigits, 0);
        }

        // If category names are set it means that we are editing auction lot together with lot item
        if ($input->categoryNames !== null) {
            return $this->detectByEditedLotItem($input);
        }

        if (
            $input->quantityDigits === null
            && $input->auctionLotId
        ) {
            return $this->createDataProvider()->detectQuantityScaleForAuctionLot($input->auctionLotId);
        }

        if ($input->lotItemId) {
            return $this->createDataProvider()->detectQuantityScaleForLotItem($input->lotItemId);
        }

        return $this->createDataProvider()->detectQuantityScaleForAccount($input->entityAccountId);
    }

    protected function detectByEditedLotItem(QuantityScaleDetectorInput $input): int
    {
        $scale = null;
        if ($input->categoryNames) {
            $primaryCategoryName = $input->categoryNames[array_key_first($input->categoryNames)];
            $scale = $this->createDataProvider()->detectQuantityScaleForCategory(trim($primaryCategoryName));
        }
        if ($scale === null) {
            $scale = $this->createDataProvider()->detectQuantityScaleForAccount($input->entityAccountId);
        }
        return $scale;
    }
}
