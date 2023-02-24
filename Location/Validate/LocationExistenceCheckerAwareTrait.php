<?php
/**
 * Created by PhpStorm.
 * User: imran
 * Date: 1/15/18
 * Time: 2:55 AM
 */

namespace Sam\Location\Validate;

/**
 * Trait LocationExistenceCheckerAwareTrait
 * @package Sam\Location\Validate
 */
trait LocationExistenceCheckerAwareTrait
{
    /**
     * @var LocationExistenceChecker|null
     */
    protected ?LocationExistenceChecker $locationExistenceChecker = null;

    /**
     * @param LocationExistenceChecker $locationExistenceChecker
     * @return static
     * @internal
     */
    public function setLocationExistenceChecker(LocationExistenceChecker $locationExistenceChecker): static
    {
        $this->locationExistenceChecker = $locationExistenceChecker;
        return $this;
    }

    /**
     * @return LocationExistenceChecker
     */
    protected function getLocationExistenceChecker(): LocationExistenceChecker
    {
        if ($this->locationExistenceChecker === null) {
            $this->locationExistenceChecker = LocationExistenceChecker::new();
        }
        return $this->locationExistenceChecker;
    }

}
