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
class ManualRtbdAdviser extends CustomizableClass
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
     * @return RtbdDescriptor|null
     */
    public function suggest(): ?RtbdDescriptor
    {
        $descriptor = $this->findByName();
        if ($descriptor) {
            return $descriptor;
        }

        $descriptor = $this->findByIncludeAccount();
        if ($descriptor) {
            return $descriptor;
        }

        $descriptor = $this->findByIncludeAll();
        if ($descriptor) {
            return $descriptor;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function suggestName(): ?string
    {
        $descriptor = $this->suggest();
        $name = $descriptor?->getName();
        return $name;
    }

    /**
     * Find by name
     * @return RtbdDescriptor|null
     */
    protected function findByName(): ?RtbdDescriptor
    {
        $descriptors = $this->getRtbdDescriptors();
        $name = $this->getAuctionRtbdOrCreate()->RtbdName;
        if ($name !== '') {
            foreach ($descriptors as $descriptor) {
                if (
                    $descriptor->isValid()
                    && $descriptor->getName() === $name
                ) {
                    $message = 'Rtbd instance descriptor found by name'
                        . composeSuffix(['name' => $descriptor->getName()]);
                    $this->getSupportLogger()->trace($message);
                    return $descriptor;
                }
            }
        }
        return null;
    }

    /**
     * Find first available among included accounts
     * @return RtbdDescriptor|null
     */
    protected function findByIncludeAccount(): ?RtbdDescriptor
    {
        $descriptors = $this->getRtbdDescriptors();
        $accountId = $this->getAuction()->AccountId;
        if ($accountId) { // JIC
            foreach ($descriptors as $descriptor) {
                if (
                    $descriptor->isValid()
                    && $descriptor->amongIncludeAccountIds($accountId)
                ) {
                    $message = 'Rtbd instance descriptor found first available among included accounts'
                        . composeSuffix(
                            [
                                'name' => $descriptor->getName(),
                                'a.acc' => $accountId,
                            ]
                        );
                    $this->getSupportLogger()->trace($message);
                    return $descriptor;
                }
            }
        }
        return null;
    }

    /**
     * Find first available among instances with "Include All" access with consideration of excluded accounts
     * @return RtbdDescriptor|null
     */
    protected function findByIncludeAll(): ?RtbdDescriptor
    {
        $descriptors = $this->getRtbdDescriptors();
        $accountId = $this->getAuction()->AccountId;
        foreach ($descriptors as $descriptor) {
            if (
                $descriptor->isValid()
                && $descriptor->isIncludeAll()
                && !$descriptor->amongExcludeAccountIds($accountId)
            ) {
                $message = 'Rtbd instance descriptor found first available among instances with "Include All" access with consideration of excluded accounts'
                    . composeSuffix(
                        [
                            'name' => $descriptor->getName(),
                            'a.acc' => $accountId,
                        ]
                    );
                $this->getSupportLogger()->trace($message);
                return $descriptor;
            }
        }
        return null;
    }
}
