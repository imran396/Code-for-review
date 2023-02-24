<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/30/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Strategy\Manual;

use Sam\Core\Service\CustomizableClass;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;
use Sam\Rtb\Pool\Instance\RtbdDescriptorsAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class RtbdInstanceManualAdviser
 * @package Sam\Rtb\Pool\Discovery\Strategy\Manual
 */
class ManualRtbdListProducer extends CustomizableClass
{
    use AuctionAwareTrait;
    use RtbdDescriptorsAwareTrait;
    use SupportLoggerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Produce array of available rtbd instances for web/soap/cli usage.
     * We build prioritized list of rtbd instances at Auction Settings page, we place to top `includeAccount` matched descriptors, and then all other excluding `excludeAccount` matched descriptors.
     * They all have filled 'name' attribute, it is mandatory, when we want to register concrete rtbd instance per auction.
     * @return RtbdDescriptor[]
     */
    public function makePrioritizedList(): array
    {
        $descriptors = $this->getRtbdDescriptors();
        $accountId = $this->getAuction()->AccountId;
        $resultDescriptors = [];
        foreach ($descriptors as $index => $descriptor) {
            if (
                $descriptor->isValid()
                && $descriptor->amongIncludeAccountIds($accountId)
            ) {
                $resultDescriptors[] = $descriptor;
                unset($descriptors[$index]);
            }
        }

        foreach ($descriptors as $descriptor) {
            if (
                $descriptor->isValid()
                && !$descriptor->amongExcludeAccountIds($accountId)
                && empty($descriptor->getIncludeAccountIds())
            ) {
                $resultDescriptors[] = $descriptor;
            }
        }

        return $resultDescriptors;
    }
}
