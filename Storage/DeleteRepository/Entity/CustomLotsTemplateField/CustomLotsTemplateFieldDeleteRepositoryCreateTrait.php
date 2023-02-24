<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CustomLotsTemplateField;

trait CustomLotsTemplateFieldDeleteRepositoryCreateTrait
{
    protected ?CustomLotsTemplateFieldDeleteRepository $customLotsTemplateFieldDeleteRepository = null;

    protected function createCustomLotsTemplateFieldDeleteRepository(): CustomLotsTemplateFieldDeleteRepository
    {
        return $this->customLotsTemplateFieldDeleteRepository ?: CustomLotsTemplateFieldDeleteRepository::new();
    }

    /**
     * @param CustomLotsTemplateFieldDeleteRepository $customLotsTemplateFieldDeleteRepository
     * @return static
     * @internal
     */
    public function setCustomLotsTemplateFieldDeleteRepository(CustomLotsTemplateFieldDeleteRepository $customLotsTemplateFieldDeleteRepository): static
    {
        $this->customLotsTemplateFieldDeleteRepository = $customLotsTemplateFieldDeleteRepository;
        return $this;
    }
}
