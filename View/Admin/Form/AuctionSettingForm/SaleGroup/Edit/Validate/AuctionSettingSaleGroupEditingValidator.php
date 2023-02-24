<?php
/**
 * SAM-11530 : Auction Sales group : Groups are getting unlinked if user creates new group with same name
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Validate;

use Sam\Auction\SaleGroup\SaleGroupManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Common\AuctionSettingSaleGroupEditingInput;
use  Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Validate\AuctionSettingSaleGroupEditingValidationResult as Result;

/**
 * Class AuctionSettingSaleGroupEditingValidator
 * @package Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Validate
 */
class AuctionSettingSaleGroupEditingValidator extends CustomizableClass
{
    use SaleGroupManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(AuctionSettingSaleGroupEditingInput $input): Result
    {
        $result = Result::new()->construct();
        if (
            $input->saleGroupName
            && $input->saleGroupName !== $input->auction->SaleGroup
            && $this->getSaleGroupManager()->existBySaleGroup($input->saleGroupName, $input->auction->AccountId, true)
        ) {
            $result->addError(Result::ERR_FOUND);
        }
        $result->addSuccess(Result::OK_NOT_FOUND);
        return $result;
    }
}
