<?php

/**
 * Pay attention, we clear validator properties on get, so it could be used in multiple bid processing
 */

namespace Sam\Bidding\AbsenteeBid\Validate;

/**
 * Trait AbsenteeBidAmountValidatorAwareTrait
 * @package Sam\Bidding\AbsenteeBid\Validate
 */
trait AbsenteeBidAmountValidatorCreateTrait
{
    protected ?AbsenteeBidAmountValidator $absenteeBidAmountValidator = null;

    /**
     * @return AbsenteeBidAmountValidator
     */
    protected function createAbsenteeBidAmountValidator(): AbsenteeBidAmountValidator
    {
        return $this->absenteeBidAmountValidator ?: AbsenteeBidAmountValidator::new();
    }

    /**
     * @param AbsenteeBidAmountValidator $absenteeBidAmountValidator
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAbsenteeBidAmountValidator(AbsenteeBidAmountValidator $absenteeBidAmountValidator): static
    {
        $this->absenteeBidAmountValidator = $absenteeBidAmountValidator;
        return $this;
    }
}
