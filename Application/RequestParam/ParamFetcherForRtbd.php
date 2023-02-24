<?php
/**
 * SAM-6413: Avoid rtbd crash because of wrong inputs
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\RequestParam;

/**
 * Class ParamFetcherForRtbd
 * @package Sam\Application\RequestParam
 */
class ParamFetcherForRtbd extends RequestParamFetcher
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param object $data
     * @return $this
     */
    public function construct(object $data): static
    {
        $this->setParameters((array)$data);
        return $this;
    }
}
