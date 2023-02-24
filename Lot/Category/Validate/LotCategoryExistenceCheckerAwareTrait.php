<?php

namespace Sam\Lot\Category\Validate;

/**
 * Trait LotCategoryExistenceCheckerAwareTrait
 * @package Sam\Lot\Category\Validate
 */
trait LotCategoryExistenceCheckerAwareTrait
{
    /**
     * @var LotCategoryExistenceChecker|null
     */
    protected ?LotCategoryExistenceChecker $lotCategoryExistenceChecker = null;

    /**
     * @return LotCategoryExistenceChecker
     */
    protected function getLotCategoryExistenceChecker(): LotCategoryExistenceChecker
    {
        if ($this->lotCategoryExistenceChecker === null) {
            $this->lotCategoryExistenceChecker = LotCategoryExistenceChecker::new();
        }
        return $this->lotCategoryExistenceChecker;
    }

    /**
     * @param LotCategoryExistenceChecker $lotCategoryExistenceChecker
     * @return static
     * @internal
     */
    public function setLotCategoryExistenceChecker(LotCategoryExistenceChecker $lotCategoryExistenceChecker): static
    {
        $this->lotCategoryExistenceChecker = $lotCategoryExistenceChecker;
        return $this;
    }
}
