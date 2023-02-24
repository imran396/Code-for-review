<?php
/**
 * SAM-4824: Encapsulate $_SERVER access
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           05-18, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Application\RequestParam\Route;


/**
 * Trait RequestRouteParserAwareTrait
 * @package Sam\Application\RequestParam\Route
 */
trait RequestRouteParserAwareTrait
{
    protected ?RequestRouteParser $requestRouteParser = null;

    /**
     * @return RequestRouteParser
     */
    protected function getRequestRouteParser(): RequestRouteParser
    {
        if ($this->requestRouteParser === null) {
            $this->requestRouteParser = RequestRouteParser::new();
        }
        return $this->requestRouteParser;
    }

    /**
     * @param RequestRouteParser $requestRouteParser
     * @return $this
     */
    public function setRequestRouteParser(RequestRouteParser $requestRouteParser): static
    {
        $this->requestRouteParser = $requestRouteParser;
        return $this;
    }
}
