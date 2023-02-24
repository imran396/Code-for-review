<?php
/**
 * SAM-6612: Move password reset link generation logic to separate service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Password\Reset;

/**
 * Trait ResetLinkBuilderCreateTrait
 * @package Sam\User\Password\Reset
 */
trait ResetLinkBuilderCreateTrait
{
    protected ?ResetLinkBuilder $resetLinkBuilder = null;

    /**
     * @return ResetLinkBuilder
     */
    protected function createResetLinkBuilder(): ResetLinkBuilder
    {
        return $this->resetLinkBuilder ?: ResetLinkBuilder::new();
    }

    /**
     * @param ResetLinkBuilder $resetLinkBuilder
     * @return $this
     * @internal
     */
    public function setResetLinkBuilder(ResetLinkBuilder $resetLinkBuilder): static
    {
        $this->resetLinkBuilder = $resetLinkBuilder;
        return $this;
    }
}
