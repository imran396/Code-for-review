<?php
/**
 * SAM-4071: Simplify url building cases
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Url;

/**
 * Trait UrlParserAwareTrait
 * @package Sam\Core\Url
 */
trait UrlParserAwareTrait
{
    /**
     * @var UrlParser|null
     */
    protected ?UrlParser $urlParser = null;

    /**
     * @return UrlParser
     */
    protected function getUrlParser(): UrlParser
    {
        if ($this->urlParser === null) {
            $this->urlParser = UrlParser::new();
        }
        return $this->urlParser;
    }

    /**
     * @param UrlParser $urlParser
     * @return static
     * @internal
     */
    public function setUrlParser(UrlParser $urlParser): static
    {
        $this->urlParser = $urlParser;
        return $this;
    }
}
