<?php
/**
 * SAM-4674: User Identification field transformation helper
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/5/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Identification;

/**
 * Trait UserIdentificationTransformerAwareTrait
 * @package Sam\User\Identification
 */
trait UserIdentificationTransformerAwareTrait
{
    protected ?UserIdentificationTransformer $userIdentificationTransformer = null;

    /**
     * @return UserIdentificationTransformer
     */
    public function getUserIdentificationTransformer(): UserIdentificationTransformer
    {
        if ($this->userIdentificationTransformer === null) {
            $this->userIdentificationTransformer = UserIdentificationTransformer::new();
        }
        return $this->userIdentificationTransformer;
    }

    /**
     * @param UserIdentificationTransformer $userIdentificationTransformer
     * @return static
     * @internal
     */
    public function setUserIdentificationTransformer(UserIdentificationTransformer $userIdentificationTransformer): static
    {
        $this->userIdentificationTransformer = $userIdentificationTransformer;
        return $this;
    }
}
