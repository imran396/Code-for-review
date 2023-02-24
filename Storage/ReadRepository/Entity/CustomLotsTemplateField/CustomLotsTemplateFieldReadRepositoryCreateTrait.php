<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CustomLotsTemplateField;

trait CustomLotsTemplateFieldReadRepositoryCreateTrait
{
    protected ?CustomLotsTemplateFieldReadRepository $customLotsTemplateFieldReadRepository = null;

    protected function createCustomLotsTemplateFieldReadRepository(): CustomLotsTemplateFieldReadRepository
    {
        return $this->customLotsTemplateFieldReadRepository ?: CustomLotsTemplateFieldReadRepository::new();
    }

    /**
     * @param CustomLotsTemplateFieldReadRepository $customLotsTemplateFieldReadRepository
     * @return static
     * @internal
     */
    public function setCustomLotsTemplateFieldReadRepository(CustomLotsTemplateFieldReadRepository $customLotsTemplateFieldReadRepository): static
    {
        $this->customLotsTemplateFieldReadRepository = $customLotsTemplateFieldReadRepository;
        return $this;
    }
}
