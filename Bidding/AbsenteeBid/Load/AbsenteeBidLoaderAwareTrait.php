<?php

namespace Sam\Bidding\AbsenteeBid\Load;

/**
 * Trait AbsenteeBidLoaderAwareTrait
 * @package Sam\Bidding\AbsenteeBid\Load
 */
trait AbsenteeBidLoaderAwareTrait
{
    protected ?AbsenteeBidLoader $absenteeBidLoader = null;

    /**
     * @return AbsenteeBidLoader
     */
    protected function getAbsenteeBidLoader(): AbsenteeBidLoader
    {
        if ($this->absenteeBidLoader === null) {
            $this->absenteeBidLoader = AbsenteeBidLoader::new();
        }
        return $this->absenteeBidLoader;
    }

    /**
     * @param AbsenteeBidLoader $absenteeBidLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAbsenteeBidLoader(AbsenteeBidLoader $absenteeBidLoader): static
    {
        $this->absenteeBidLoader = $absenteeBidLoader;
        return $this;
    }
}
