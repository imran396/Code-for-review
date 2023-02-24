<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\LotInfo;

/**
 * Trait LotInfoServiceAwareTrait
 * @package Sam\Rtb\LotInfo
 */
trait LotInfoServiceAwareTrait
{
    /**
     * @var Service|null
     */
    protected ?Service $lotInfoService = null;

    /**
     * @return Service
     */
    protected function getLotInfoService(): Service
    {
        if ($this->lotInfoService === null) {
            $this->lotInfoService = Service::new();
        }
        return $this->lotInfoService;
    }

    /**
     * @param Service $lotInfoService
     * @return static
     * @internal
     */
    public function setLotInfoService(Service $lotInfoService): static
    {
        $this->lotInfoService = $lotInfoService;
        return $this;
    }
}
