<?php
/**
 * SAM-5067: Improve security of lightly sensitive bidding information in timed and live auctions public communication
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 28, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\User;

/**
 * Trait UserHashGeneratorCreateTrait
 * @package Sam\Rtb\User
 */
trait UserHashGeneratorCreateTrait
{
    protected ?UserHashGenerator $userHashGenerator = null;

    /**
     * @return UserHashGenerator
     */
    protected function createUserHashGenerator(): UserHashGenerator
    {
        return $this->userHashGenerator ?: UserHashGenerator::new();
    }

    /**
     * @param UserHashGenerator $userHashGenerator
     * @return static
     * @internal
     */
    public function setUserHashGenerator(UserHashGenerator $userHashGenerator): static
    {
        $this->userHashGenerator = $userHashGenerator;
        return $this;
    }
}
