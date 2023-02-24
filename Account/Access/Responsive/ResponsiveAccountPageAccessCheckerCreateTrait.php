<?php
/**
 * SAM-6068: Issue related to "Show content from all account" on a portal account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Access\Responsive;

/**
 * Trait ResponsiveAccountPageAccessCheckerCreateTrait
 * @package Sam\Account\Access\Responsive
 */
trait ResponsiveAccountPageAccessCheckerCreateTrait
{
    /**
     * @var ResponsiveAccountPageAccessChecker|null
     */
    protected ?ResponsiveAccountPageAccessChecker $responsiveAccountPageAccessChecker = null;

    /**
     * @return ResponsiveAccountPageAccessChecker
     */
    protected function createResponsiveAccountPageAccessChecker(): ResponsiveAccountPageAccessChecker
    {
        return $this->responsiveAccountPageAccessChecker ?: ResponsiveAccountPageAccessChecker::new();
    }

    /**
     * @param ResponsiveAccountPageAccessChecker $responsiveAccountPageAccessChecker
     * @return $this
     * @internal
     */
    public function setResponsiveAccountPageAccessChecker(ResponsiveAccountPageAccessChecker $responsiveAccountPageAccessChecker): static
    {
        $this->responsiveAccountPageAccessChecker = $responsiveAccountPageAccessChecker;
        return $this;
    }
}
