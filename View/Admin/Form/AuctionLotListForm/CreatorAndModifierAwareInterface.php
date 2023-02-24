<?php
/**
 * SAM-6475: Apply DTO for assigned to auction lots and assign-ready lots used at Auction Lot List page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 05, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm;

/**
 * Interface CreatorAndModifierAwareInterface
 * @package Sam\View\Admin\Form\AuctionLotListForm
 */
interface CreatorAndModifierAwareInterface
{
    /**
     * @return string
     */
    public function getCreatorUsername(): string;

    /**
     * @return string
     */
    public function getModifierUsername(): string;
}
