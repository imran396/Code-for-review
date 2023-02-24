<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Timezone;

trait TimezoneReadRepositoryCreateTrait
{
    protected ?TimezoneReadRepository $timezoneReadRepository = null;

    protected function createTimezoneReadRepository(): TimezoneReadRepository
    {
        return $this->timezoneReadRepository ?: TimezoneReadRepository::new();
    }

    /**
     * @param TimezoneReadRepository $timezoneReadRepository
     * @return static
     * @internal
     */
    public function setTimezoneReadRepository(TimezoneReadRepository $timezoneReadRepository): static
    {
        $this->timezoneReadRepository = $timezoneReadRepository;
        return $this;
    }
}
