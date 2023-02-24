<?php
/**
 * SAM-6413: Avoid rtbd crash because of wrong inputs
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\RequestParam;

use RuntimeException;

/**
 * Trait ParamFetcherForRtbdAwareTrait
 * @package Sam\Application\RequestParam
 */
trait ParamFetcherForRtbdAwareTrait
{
    protected ?ParamFetcherForRtbd $paramFetcherForRtbd = null;

    /**
     * @return ParamFetcherForRtbd
     */
    protected function getParamFetcherForRtbd(): ParamFetcherForRtbd
    {
        if ($this->paramFetcherForRtbd === null) {
            throw new RuntimeException("ParamFetcherForRtbd undefined");
        }
        return $this->paramFetcherForRtbd;
    }

    /**
     * @param ParamFetcherForRtbd $paramFetcherForRtbd
     * @return $this
     * @internal
     */
    public function setParamFetcherForRtbd(ParamFetcherForRtbd $paramFetcherForRtbd): static
    {
        $this->paramFetcherForRtbd = $paramFetcherForRtbd;
        return $this;
    }
}
