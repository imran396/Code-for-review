<?php
/**
 * SAM-4075: Helper methods for server request parameters filtering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-17, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Application\RequestParam;


/**
 * Class ParamFetcherForPost
 * @package Sam\Application\RequestParam
 */
class ParamFetcherForPost extends RequestParamFetcher
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $params = $this->getServerRequest()->getParsedBody();
        $this->setParameters($params);
        return $this;
    }
}
