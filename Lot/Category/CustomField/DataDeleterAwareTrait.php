<?php

namespace Sam\Lot\Category\CustomField;

/**
 * Trait DataDeleterAwareTrait
 * @package Sam\Lot\Category\CustomField
 */
trait DataDeleterAwareTrait
{
    /**
     * @var DataDeleter|null
     */
    protected ?DataDeleter $dataDeleter = null;

    /**
     * @return DataDeleter
     */
    protected function getDataDeleter(): DataDeleter
    {
        if ($this->dataDeleter === null) {
            $this->dataDeleter = DataDeleter::new();
        }
        return $this->dataDeleter;
    }

    /**
     * @param DataDeleter $dataDeleter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setDataDeleter(DataDeleter $dataDeleter): static
    {
        $this->dataDeleter = $dataDeleter;
        return $this;
    }
}
