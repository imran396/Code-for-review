<?php
/**
 * Trait for GeneralChecker
 *
 * SAM-7956: Create a basic health check endpoint /health
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 2, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Health\Internal\Validate;

/**
 * Trait GeneralCheckerAwareTrait
 * @package Sam\Infrastructure\Health
 */
trait GeneralCheckerCreateTrait
{
    /**
     * @var GeneralChecker|null
     */
    protected ?GeneralChecker $generalChecker = null;

    /**
     * @return GeneralChecker
     */
    protected function createGeneralChecker(): GeneralChecker
    {
        return $this->generalChecker ?: GeneralChecker::new();
    }

    /**
     * @param GeneralChecker $generalChecker
     * @return static
     * @internal
     */
    public function setGeneralChecker(GeneralChecker $generalChecker): static
    {
        $this->generalChecker = $generalChecker;
        return $this;
    }
}
