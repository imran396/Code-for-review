<?php

namespace Sam\Lot\Category\CustomField;

/**
 * Trait DataLoaderAwareTrait
 * @package Sam\Lot\Category\CustomField
 */
trait LotCategoryCustomDataLoaderAwareTrait
{
    /**
     * @var LotCategoryCustomDataLoader|null
     */
    protected ?LotCategoryCustomDataLoader $lotCategoryCustomDataLoader = null;

    /**
     * @return LotCategoryCustomDataLoader
     */
    protected function getLotCategoryCustomDataLoader(): LotCategoryCustomDataLoader
    {
        if ($this->lotCategoryCustomDataLoader === null) {
            $this->lotCategoryCustomDataLoader = LotCategoryCustomDataLoader::new();
        }
        return $this->lotCategoryCustomDataLoader;
    }

    /**
     * @param LotCategoryCustomDataLoader $lotCategoryCustomDataLoader
     * @return static
     * @internal
     */
    public function setLotCategoryCustomDataLoader(LotCategoryCustomDataLoader $lotCategoryCustomDataLoader): static
    {
        $this->lotCategoryCustomDataLoader = $lotCategoryCustomDataLoader;
        return $this;
    }
}
