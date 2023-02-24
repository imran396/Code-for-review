<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch;

use Sam\Bidding\BuyNow\BuyNowValidationInput;
use Sam\Core\Service\CustomizableClass;
use Sam\Bidding\BuyNow\BuyNowValidator;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\AuctionCheckerCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\UserFlagCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class BuyNowChecker
 */
class BuyNowChecker extends CustomizableClass
{
    use AuctionCheckerCacherAwareTrait;
    use EditorUserAwareTrait;
    use UserFlagCacherAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check Buy Now function availability for lot
     * @param AdvancedSearchLotDto $dto
     * @param bool $isUserChecking
     * @return bool
     */
    public function isAvailable(AdvancedSearchLotDto $dto, bool $isUserChecking = false): bool
    {
        $isAuctionApproved = $this->getAuctionCheckerCacher()->isApproved($dto->auctionId);
        $userFlag = $this->getUserFlagCacher()->getFlag($this->getEditorUserId(), $dto->lotAccountId);
        $input = BuyNowValidationInput::new()->fromAdvancedSearchLotDto($dto, $isAuctionApproved, $userFlag);
        $isAvailable = BuyNowValidator::new()
            ->enableUserChecking($isUserChecking)
            ->isAvailableByDataArray($input);
        return $isAvailable;
    }
}
