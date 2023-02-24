<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Timezone;

trait TimezoneWriteRepositoryAwareTrait
{
    protected ?TimezoneWriteRepository $timezoneWriteRepository = null;

    protected function getTimezoneWriteRepository(): TimezoneWriteRepository
    {
        if ($this->timezoneWriteRepository === null) {
            $this->timezoneWriteRepository = TimezoneWriteRepository::new();
        }
        return $this->timezoneWriteRepository;
    }

    /**
     * @param TimezoneWriteRepository $timezoneWriteRepository
     * @return static
     * @internal
     */
    public function setTimezoneWriteRepository(TimezoneWriteRepository $timezoneWriteRepository): static
    {
        $this->timezoneWriteRepository = $timezoneWriteRepository;
        return $this;
    }
}
