<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ResetPassword;

class ResetPasswordDeleteRepository extends AbstractResetPasswordDeleteRepository
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Define filtering by more or equal rp.modified_on
     * @param string $date
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $date): static
    {
        $this->filterInequality('rp.modified_on', $date, '>=');
        return $this;
    }
}
