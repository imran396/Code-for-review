<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\BackPage;

trait BackPageUrlDetectorCreateTrait
{
    /**
     * @var BackPageUrlDetector|null
     */
    protected ?BackPageUrlDetector $backUrlParamDetector = null;

    /**
     * @return BackPageUrlDetector
     */
    protected function createBackPageUrlDetector(): BackPageUrlDetector
    {
        return $this->backUrlParamDetector ?: BackPageUrlDetector::new();
    }

    /**
     * @param BackPageUrlDetector $backPageUrlDetector
     * @return $this
     * @internal
     */
    public function setBackPageUrlDetector(BackPageUrlDetector $backPageUrlDetector): static
    {
        $this->backUrlParamDetector = $backPageUrlDetector;
        return $this;
    }
}
