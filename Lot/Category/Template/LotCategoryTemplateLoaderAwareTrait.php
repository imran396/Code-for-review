<?php

namespace Sam\Lot\Category\Template;

/**
 * Trait LotCategoryTemplateLoaderAwareTrait
 * @package Sam\Lot\Category\Template
 */
trait LotCategoryTemplateLoaderAwareTrait
{
    /**
     * @var LotCategoryTemplateLoader|null
     */
    protected ?LotCategoryTemplateLoader $lotCategoryTemplateLoader = null;

    /**
     * @return LotCategoryTemplateLoader
     */
    protected function getLotCategoryTemplateLoader(): LotCategoryTemplateLoader
    {
        if ($this->lotCategoryTemplateLoader === null) {
            $this->lotCategoryTemplateLoader = LotCategoryTemplateLoader::new();
        }
        return $this->lotCategoryTemplateLoader;
    }

    /**
     * @param LotCategoryTemplateLoader $lotCategoryTemplateLoader
     * @return static
     * @internal
     */
    public function setLotCategoryTemplateLoader(LotCategoryTemplateLoader $lotCategoryTemplateLoader): static
    {
        $this->lotCategoryTemplateLoader = $lotCategoryTemplateLoader;
        return $this;
    }
}
