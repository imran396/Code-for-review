<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Timezone;

trait TimezoneDeleteRepositoryCreateTrait
{
    protected ?TimezoneDeleteRepository $timezoneDeleteRepository = null;

    protected function createTimezoneDeleteRepository(): TimezoneDeleteRepository
    {
        return $this->timezoneDeleteRepository ?: TimezoneDeleteRepository::new();
    }

    /**
     * @param TimezoneDeleteRepository $timezoneDeleteRepository
     * @return static
     * @internal
     */
    public function setTimezoneDeleteRepository(TimezoneDeleteRepository $timezoneDeleteRepository): static
    {
        $this->timezoneDeleteRepository = $timezoneDeleteRepository;
        return $this;
    }
}
