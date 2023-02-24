<?php
/**
 * SAM-6632: Fix for Front-end main menu "Auctions" target setting
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Render\Internal\AuctionMenu;

/**
 * Trait MainMenuAuctionUrlDetectorCreateTrait
 */
trait MainMenuAuctionUrlDetectorCreateTrait
{
    protected ?MainMenuAuctionUrlDetector $mainMenuAuctionUrlDetector = null;

    /**
     * @return MainMenuAuctionUrlDetector
     */
    protected function createMainMenuAuctionUrlDetector(): MainMenuAuctionUrlDetector
    {
        return $this->mainMenuAuctionUrlDetector ?: MainMenuAuctionUrlDetector::new();
    }

    /**
     * @param MainMenuAuctionUrlDetector $mainMenuAuctionUrlDetector
     * @return $this
     * @internal
     */
    public function setMainMenuAuctionUrlDetector(MainMenuAuctionUrlDetector $mainMenuAuctionUrlDetector): static
    {
        $this->mainMenuAuctionUrlDetector = $mainMenuAuctionUrlDetector;
        return $this;
    }
}
