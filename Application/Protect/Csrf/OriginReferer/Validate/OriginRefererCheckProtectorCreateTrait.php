<?php
/**
 * SAM-5676: Refactor Origin/Referer checking logic and implement unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\OriginReferer\Validate;

/**
 * Trait OriginRefererCheckProtectorCreateTrait
 * @package Sam\Application\Protect\Csrf\OriginReferer\Validate
 */
trait OriginRefererCheckProtectorCreateTrait
{
    /**
     * @var OriginRefererCheckProtector|null
     */
    protected ?OriginRefererCheckProtector $originRefererCheckProtector = null;

    /**
     * @return OriginRefererCheckProtector
     */
    protected function createOriginRefererCheckProtector(): OriginRefererCheckProtector
    {
        return $this->originRefererCheckProtector ?: OriginRefererCheckProtector::new();
    }

    /**
     * @param OriginRefererCheckProtector $originRefererCheckProtector
     * @return static
     * @internal
     */
    public function setOriginRefererCheckProtector(OriginRefererCheckProtector $originRefererCheckProtector): static
    {
        $this->originRefererCheckProtector = $originRefererCheckProtector;
        return $this;
    }
}
