<?php
/**
 * SAM-6721: Apply WriteRepository and unit tests to Add New Bidder registrator command services
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AddNewBidderForm\Save\NewUser;

/**
 * Trait AddNewBidderRegistratorForNewUserCreateTrait
 * @package Sam\View\Admin\Form\AddNewBidderForm\Save\NewUser
 */
trait AddNewBidderRegistratorForNewUserCreateTrait
{
    protected ?AddNewBidderRegistratorForNewUser $addNewBidderRegistratorForNewUser = null;

    /**
     * @return AddNewBidderRegistratorForNewUser
     */
    protected function createAddNewBidderRegistratorForNewUser(): AddNewBidderRegistratorForNewUser
    {
        return $this->addNewBidderRegistratorForNewUser ?: AddNewBidderRegistratorForNewUser::new();
    }

    /**
     * @param AddNewBidderRegistratorForNewUser $addNewBidderRegistratorForNewUser
     * @return $this
     * @internal
     */
    public function setAddNewBidderRegistratorForNewUser(AddNewBidderRegistratorForNewUser $addNewBidderRegistratorForNewUser): static
    {
        $this->addNewBidderRegistratorForNewUser = $addNewBidderRegistratorForNewUser;
        return $this;
    }
}
