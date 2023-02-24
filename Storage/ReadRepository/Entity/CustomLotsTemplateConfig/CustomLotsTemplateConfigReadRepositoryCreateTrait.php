<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CustomLotsTemplateConfig;

trait CustomLotsTemplateConfigReadRepositoryCreateTrait
{
    protected ?CustomLotsTemplateConfigReadRepository $customLotsTemplateConfigReadRepository = null;

    protected function createCustomLotsTemplateConfigReadRepository(): CustomLotsTemplateConfigReadRepository
    {
        return $this->customLotsTemplateConfigReadRepository ?: CustomLotsTemplateConfigReadRepository::new();
    }

    /**
     * @param CustomLotsTemplateConfigReadRepository $customLotsTemplateConfigReadRepository
     * @return static
     * @internal
     */
    public function setCustomLotsTemplateConfigReadRepository(CustomLotsTemplateConfigReadRepository $customLotsTemplateConfigReadRepository): static
    {
        $this->customLotsTemplateConfigReadRepository = $customLotsTemplateConfigReadRepository;
        return $this;
    }
}
