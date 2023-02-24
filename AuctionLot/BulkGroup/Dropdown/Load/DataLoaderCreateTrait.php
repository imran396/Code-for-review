<?php

namespace Sam\AuctionLot\BulkGroup\Dropdown\Load;

/**
 * Trait DataLoaderCreateTrait
 * @package Sam\AuctionLot\BulkGroup\Dropdown\Load
 */
trait DataLoaderCreateTrait
{
    /**
     * @var DataLoader|null
     */
    protected ?DataLoader $dataLoader = null;

    /**
     * @return DataLoader
     */
    protected function createDataLoader(): DataLoader
    {
        return $this->dataLoader ?: DataLoader::new();
    }

    /**
     * @param DataLoader $dataLoader
     * @return $this
     * @internal
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }
}
