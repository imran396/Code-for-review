<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Download\ResellerCert\Internal;

/**
 * Trait AccessCheckerCreateTrait
 * @package Sam\Application\Controller\Responsive\Download\ResellerCert\Internal
 */
trait AccessCheckerCreateTrait
{
    protected ?AccessChecker $accessChecker = null;

    /**
     * @return AccessChecker
     */
    protected function createAccessChecker(): AccessChecker
    {
        return $this->accessChecker ?: AccessChecker::new();
    }

    /**
     * @param AccessChecker $accessChecker
     * @return static
     * @internal
     */
    public function setAccessChecker(AccessChecker $accessChecker): static
    {
        $this->accessChecker = $accessChecker;
        return $this;
    }
}
