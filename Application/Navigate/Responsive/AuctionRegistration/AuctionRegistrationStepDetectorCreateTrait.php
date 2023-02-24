<?php
/**
 * SAM-5546: Auction registration step detection and redirect
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           12-12, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Navigate\Responsive\AuctionRegistration;

/**
 * Trait AuctionRegistrationStepDetectorCreateTrait
 * @package Sam\Application\Navigate\Responsive\AuctionRegistration
 */
trait AuctionRegistrationStepDetectorCreateTrait
{
    /**
     * @var AuctionRegistrationStepDetector|null
     */
    protected ?AuctionRegistrationStepDetector $auctionRegistrationStepDetector = null;

    /**
     * @return AuctionRegistrationStepDetector
     */
    protected function createAuctionRegistrationStepDetector(): AuctionRegistrationStepDetector
    {
        return $this->auctionRegistrationStepDetector
            ?: AuctionRegistrationStepDetector::new();
    }

    /**
     * @param AuctionRegistrationStepDetector $auctionRegistrationStepDetector
     * @return static
     * @internal
     */
    public function setAuctionRegistrationStepDetector(AuctionRegistrationStepDetector $auctionRegistrationStepDetector): static
    {
        $this->auctionRegistrationStepDetector = $auctionRegistrationStepDetector;
        return $this;
    }
}
