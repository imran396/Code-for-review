<?php

namespace Sam\Lot\Category\Load;

/**
 * Trait LotCategoryLoaderAwareTrait
 * @package Sam\Lot\Category\Load
 */
trait LotCategoryLoaderAwareTrait
{
    /**
     * @var LotCategoryLoader|null
     */
    protected ?LotCategoryLoader $lotCategoryLoader = null;

    /**
     * @return LotCategoryLoader
     */
    protected function getLotCategoryLoader(): LotCategoryLoader
    {
        if ($this->lotCategoryLoader === null) {
            $this->lotCategoryLoader = LotCategoryLoader::new();
        }
        return $this->lotCategoryLoader;
    }

    /**
     * @param LotCategoryLoader $lotCategoryLoader
     * @return static
     * @internal
     */
    public function setLotCategoryLoader(LotCategoryLoader $lotCategoryLoader): static
    {
        $this->lotCategoryLoader = $lotCategoryLoader;
        return $this;
    }
}
