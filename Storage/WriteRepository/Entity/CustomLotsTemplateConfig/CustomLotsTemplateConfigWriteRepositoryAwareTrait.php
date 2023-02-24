<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CustomLotsTemplateConfig;

trait CustomLotsTemplateConfigWriteRepositoryAwareTrait
{
    protected ?CustomLotsTemplateConfigWriteRepository $customLotsTemplateConfigWriteRepository = null;

    protected function getCustomLotsTemplateConfigWriteRepository(): CustomLotsTemplateConfigWriteRepository
    {
        if ($this->customLotsTemplateConfigWriteRepository === null) {
            $this->customLotsTemplateConfigWriteRepository = CustomLotsTemplateConfigWriteRepository::new();
        }
        return $this->customLotsTemplateConfigWriteRepository;
    }

    /**
     * @param CustomLotsTemplateConfigWriteRepository $customLotsTemplateConfigWriteRepository
     * @return static
     * @internal
     */
    public function setCustomLotsTemplateConfigWriteRepository(CustomLotsTemplateConfigWriteRepository $customLotsTemplateConfigWriteRepository): static
    {
        $this->customLotsTemplateConfigWriteRepository = $customLotsTemplateConfigWriteRepository;
        return $this;
    }
}
