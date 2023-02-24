<?php

namespace Sam\Lot\Load;

/**
 * Trait LotItemLoaderAwareTrait
 * @package Sam\Lot\Load
 */
trait LotItemLoaderAwareTrait
{
    /**
     * @var LotItemLoader|null
     */
    protected ?LotItemLoader $lotItemLoader = null;

    /**
     * @return LotItemLoader
     */
    protected function getLotItemLoader(): LotItemLoader
    {
        if ($this->lotItemLoader === null) {
            $this->lotItemLoader = LotItemLoader::new();
        }
        return $this->lotItemLoader;
    }

    /**
     * @param LotItemLoader $lotItemLoader
     * @return static
     * @internal
     */
    public function setLotItemLoader(LotItemLoader $lotItemLoader): static
    {
        $this->lotItemLoader = $lotItemLoader;
        return $this;
    }
}
