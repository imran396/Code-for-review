<?php
/**
 * SAM-4742: Http referrer host parser
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/26/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\HttpReferrer;

/**
 * Trait HttpReferrerParserAwareTrait
 * @package Sam\Application\HttpReferrer
 */
trait HttpReferrerParserAwareTrait
{
    /**
     * @var HttpReferrerParser|null
     */
    protected ?HttpReferrerParser $httpReferrerParser = null;

    /**
     * @return HttpReferrerParser
     */
    protected function getHttpReferrerParser(): HttpReferrerParser
    {
        if ($this->httpReferrerParser === null) {
            $this->httpReferrerParser = HttpReferrerParser::new();
        }
        return $this->httpReferrerParser;
    }

    /**
     * @param HttpReferrerParser $httpReferrerParser
     * @return static
     * @internal
     */
    public function setHttpReferrerParser(HttpReferrerParser $httpReferrerParser): static
    {
        $this->httpReferrerParser = $httpReferrerParser;
        return $this;
    }
}
