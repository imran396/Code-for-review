<?php
/**
 * SAM-5664: Extract update action from Auction Sms page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSmsForm\Save;

/**
 * Trait AuctionSmsEditorCreateTrait
 * @package Sam\View\Admin\Form\AuctionSmsForm\Save
 */
trait AuctionSmsEditorCreateTrait
{
    protected ?AuctionSmsEditor $auctionSmsEditor = null;

    /**
     * @return AuctionSmsEditor
     */
    protected function createAuctionSmsEditor(): AuctionSmsEditor
    {
        return $this->auctionSmsEditor ?: AuctionSmsEditor::new();
    }

    /**
     * @param AuctionSmsEditor $auctionSmsEditor
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionSmsEditor(AuctionSmsEditor $auctionSmsEditor): static
    {
        $this->auctionSmsEditor = $auctionSmsEditor;
        return $this;
    }
}
