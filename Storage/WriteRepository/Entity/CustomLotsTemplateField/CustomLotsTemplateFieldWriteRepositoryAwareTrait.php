<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CustomLotsTemplateField;

trait CustomLotsTemplateFieldWriteRepositoryAwareTrait
{
    protected ?CustomLotsTemplateFieldWriteRepository $customLotsTemplateFieldWriteRepository = null;

    protected function getCustomLotsTemplateFieldWriteRepository(): CustomLotsTemplateFieldWriteRepository
    {
        if ($this->customLotsTemplateFieldWriteRepository === null) {
            $this->customLotsTemplateFieldWriteRepository = CustomLotsTemplateFieldWriteRepository::new();
        }
        return $this->customLotsTemplateFieldWriteRepository;
    }

    /**
     * @param CustomLotsTemplateFieldWriteRepository $customLotsTemplateFieldWriteRepository
     * @return static
     * @internal
     */
    public function setCustomLotsTemplateFieldWriteRepository(CustomLotsTemplateFieldWriteRepository $customLotsTemplateFieldWriteRepository): static
    {
        $this->customLotsTemplateFieldWriteRepository = $customLotsTemplateFieldWriteRepository;
        return $this;
    }
}
