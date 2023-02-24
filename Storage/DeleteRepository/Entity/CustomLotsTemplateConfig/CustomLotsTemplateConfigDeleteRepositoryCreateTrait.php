<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CustomLotsTemplateConfig;

trait CustomLotsTemplateConfigDeleteRepositoryCreateTrait
{
    protected ?CustomLotsTemplateConfigDeleteRepository $customLotsTemplateConfigDeleteRepository = null;

    protected function createCustomLotsTemplateConfigDeleteRepository(): CustomLotsTemplateConfigDeleteRepository
    {
        return $this->customLotsTemplateConfigDeleteRepository ?: CustomLotsTemplateConfigDeleteRepository::new();
    }

    /**
     * @param CustomLotsTemplateConfigDeleteRepository $customLotsTemplateConfigDeleteRepository
     * @return static
     * @internal
     */
    public function setCustomLotsTemplateConfigDeleteRepository(CustomLotsTemplateConfigDeleteRepository $customLotsTemplateConfigDeleteRepository): static
    {
        $this->customLotsTemplateConfigDeleteRepository = $customLotsTemplateConfigDeleteRepository;
        return $this;
    }
}
