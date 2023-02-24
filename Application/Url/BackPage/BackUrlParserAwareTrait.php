<?php
/**
 * SAM-5305: Back-page Url Parser
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\BackPage;

/**
 * Trait BackUrlParserAwareTrait
 * @package Sam\Core\Url
 */
trait BackUrlParserAwareTrait
{
    /**
     * @var BackUrlParser|null
     */
    protected ?BackUrlParser $backUrlParser = null;

    /**
     * @return BackUrlParser
     */
    protected function getBackUrlParser(): BackUrlParser
    {
        if ($this->backUrlParser === null) {
            $this->backUrlParser = BackUrlParser::new();
        }
        return $this->backUrlParser;
    }

    /**
     * @param BackUrlParser $backUrlParser
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBackUrlParser(BackUrlParser $backUrlParser): static
    {
        $this->backUrlParser = $backUrlParser;
        return $this;
    }
}
