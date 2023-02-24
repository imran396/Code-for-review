<?php
/**
 * Trait for access to ParamFetcherForGet initialized by GET request
 *
 * SAM-4075: Apply Qform helpers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis, Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           May 09, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\RequestParam;

/**
 * Trait ParamFetcherForGetAwareTrait
 * @package Sam\Application\RequestParam
 */
trait ParamFetcherForGetAwareTrait
{
    /**
     * @var ParamFetcherForGet|null
     */
    protected ?ParamFetcherForGet $paramFetcherForGet = null;

    /**
     * @return ParamFetcherForGet
     */
    protected function getParamFetcherForGet(): ParamFetcherForGet
    {
        if ($this->paramFetcherForGet === null) {
            $this->paramFetcherForGet = ParamFetcherForGet::new()->construct();
        }
        return $this->paramFetcherForGet;
    }

    /**
     * @param ParamFetcherForGet $paramFetcherForGet
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setParamFetcherForGet(ParamFetcherForGet $paramFetcherForGet): static
    {
        $this->paramFetcherForGet = $paramFetcherForGet;
        return $this;
    }
}
