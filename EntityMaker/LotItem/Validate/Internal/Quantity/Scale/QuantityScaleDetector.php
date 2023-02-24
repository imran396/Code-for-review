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

namespace Sam\EntityMaker\LotItem\Validate\Internal\Quantity\Scale;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Validate\Internal\Quantity\Scale\Internal\Load\DataProviderCreateTrait;

/**
 * Class QuantityScaleDetector
 * @package Sam\EntityMaker\LotItem
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

        $lotItemQuantityDigits = $this->detectForLotItem($input);
        if ($lotItemQuantityDigits !== null) {
            return $lotItemQuantityDigits;
        }

        $mainCategoryQuantityDigits = $this->detectForMainCategory($input);
        if ($mainCategoryQuantityDigits !== null) {
            return $mainCategoryQuantityDigits;
        }

        return $this->createDataProvider()->detectQuantityScaleForAccount($input->serviceAccountId);
    }

    protected function detectForLotItem(QuantityScaleDetectorInput $input): ?int
    {
        if (
            $input->lotItemId
            && $input->quantityDigits === null
        ) {
            return $this->createDataProvider()->detectQuantityScaleForLotItem($input->lotItemId);
        }
        return null;
    }

    protected function detectForMainCategory(QuantityScaleDetectorInput $input): ?int
    {
        if ($input->categoryIds !== null) {
            if (!$input->categoryIds) {
                return null;
            }
            $primaryCategoryId = $input->categoryIds[array_key_first($input->categoryIds)];
            return $this->createDataProvider()->detectQuantityScaleForCategoryById((int)$primaryCategoryId);
        }

        if ($input->categoryNames !== null) {
            if (!$input->categoryNames) {
                return null;
            }
            $primaryCategoryName = $input->categoryNames[array_key_first($input->categoryNames)];
            return $this->createDataProvider()->detectQuantityScaleForCategoryByName($primaryCategoryName);
        }

        if ($input->lotItemId) {
            return $this->createDataProvider()->detectQuantityScaleForLotItemMainCategory($input->lotItemId);
        }

        return null;
    }
}
