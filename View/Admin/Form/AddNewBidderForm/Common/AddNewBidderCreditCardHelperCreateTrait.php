<?php
/**
 * SAM-5716: Extract auction bidder validation and building logic from "Add New Bidder" form
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AddNewBidderForm\Common;

/**
 * Trait AddNewBidderCreditCardHelperCreateTrait
 * @package Sam\View\Admin\Form\AddNewBidderForm
 */
trait AddNewBidderCreditCardHelperCreateTrait
{
    protected ?AddNewBidderCreditCardHelper $addNewBidderCreditCardHelper = null;

    /**
     * @return AddNewBidderCreditCardHelper
     */
    protected function createAddNewBidderCreditCardHelper(): AddNewBidderCreditCardHelper
    {
        return $this->addNewBidderCreditCardHelper ?: AddNewBidderCreditCardHelper::new();
    }

    /**
     * @param AddNewBidderCreditCardHelper $addNewBidderCreditCardHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAddNewBidderCreditCardHelper(AddNewBidderCreditCardHelper $addNewBidderCreditCardHelper): static
    {
        $this->addNewBidderCreditCardHelper = $addNewBidderCreditCardHelper;
        return $this;
    }
}
