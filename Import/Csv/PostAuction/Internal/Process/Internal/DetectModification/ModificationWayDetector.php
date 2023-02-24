<?php
/**
 * Pure decision-making logic that helps to determine what should we do with updated lot (sold, unsold, unassign, don't touch)
 *
 * SAM-9360: Refactor \Lot_PostCsvUpload
 * SAM-4832: Post auction import-Issue when no winning information in csv cell
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process\Internal\DetectModification;

use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\PostAuction\Internal\Process\Internal\DetectModification\ModificationWayDetectionResult as Result;

/**
 * Class ModificationWayDetector
 */
class ModificationWayDetector extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $emailFromInput
     * @param float|null $hammerPriceFromInput
     * @param int|null $winningBidderIdFromDb
     * @param float|null $hammerPriceFromDb
     * @param bool $isUnassignUnsold
     * @return Result
     * #[Pure]
     */
    public function detect(
        string $emailFromInput,
        ?float $hammerPriceFromInput,
        ?int $winningBidderIdFromDb,
        ?float $hammerPriceFromDb,
        bool $isUnassignUnsold
    ): Result {
        $result = Result::new()->construct();
        if ($emailFromInput) {
            if (!$this->isDefinedHammerPrice($hammerPriceFromInput)) {
                return $result->addError(Result::ERR_HP_ABSENT_IN_INPUT_AND_WB_PRESENT_IN_INPUT);
            }
            return $result->addSuccess(Result::OK_HP_PRESENT_IN_INPUT_AND_WB_PRESENT_IN_INPUT);
        }

        /**
         * The next cases are for undefined winning bidder in CSV input
         */

        if ($this->isDefinedHammerPrice($hammerPriceFromInput)) {
            // Enabled and disabled $isUnassignUnsold are considered as the same cases
            return $result->addSuccess(Result::OK_HP_PRESENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT);
        }

        if ($isUnassignUnsold) {
            return $result->addSuccess(Result::OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_ENABLED);
        }

        /**
         * The next cases are for disabled "Unassign unsold lots" option
         */

        if (
            $winningBidderIdFromDb
            || $this->isDefinedHammerPrice($hammerPriceFromDb)
        ) {
            return $result->addSuccess(Result::OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_DISABLED_BUT_HP_OR_WB_PRESENT_IN_DB);
        }

        return $result->addSuccess(Result::OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_DISABLED_BUT_HP_AND_WB_ABSENT_IN_DB);
    }

    /**
     * @param float|null $hammerPrice
     * @return bool
     * #[Pure]
     */
    protected function isDefinedHammerPrice(?float $hammerPrice): bool
    {
        return LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice);
    }
}
