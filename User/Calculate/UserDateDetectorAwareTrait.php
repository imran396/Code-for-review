<?php
/**
 * SAM-4664: User dates detector class
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 25, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Calculate;

/**
 * Trait UserDateDetectorAwareTrait
 */
trait UserDateDetectorAwareTrait
{
    protected ?UserDateDetector $userDateDetector = null;

    /**
     * @return UserDateDetector
     */
    protected function getUserDateDetector(): UserDateDetector
    {
        if ($this->userDateDetector === null) {
            $this->userDateDetector = UserDateDetector::new();
        }
        return $this->userDateDetector;
    }

    /**
     * @param UserDateDetector $userDateDetector
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserDateDetector(UserDateDetector $userDateDetector): static
    {
        $this->userDateDetector = $userDateDetector;
        return $this;
    }
}
