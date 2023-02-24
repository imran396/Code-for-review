<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Base\Csv;

/**
 * Trait HtmlDecodeAwareTrait
 * @package Sam\Report\Base\Csv
 */
trait HtmlDecodeAwareTrait
{
    /** @var bool */
    protected bool $isHtmlDecode = false;

    /**
     * @param bool $isDecodeHtml
     * @return static
     */
    public function enableHtmlDecode(bool $isDecodeHtml): static
    {
        $this->isHtmlDecode = $isDecodeHtml;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHtmlDecode(): bool
    {
        return $this->isHtmlDecode;
    }
}
