<?php

namespace Sam\Transform\Number;

/**
 * Trait NumberFormatterAwareTrait
 * @package Sam\Transform
 */
trait NumberFormatterAwareTrait
{
    protected ?NumberFormatterInterface $numberFormatter = null;

    /**
     * @param NumberFormatterInterface $numberFormatter
     * @return static
     */
    public function setNumberFormatter(NumberFormatterInterface $numberFormatter): static
    {
        $this->numberFormatter = $numberFormatter;
        return $this;
    }

    /**
     * @return NumberFormatterInterface
     */
    protected function getNumberFormatter(): NumberFormatterInterface
    {
        if ($this->numberFormatter === null) {
            $this->numberFormatter = NumberFormatter::new();
        }
        return $this->numberFormatter;
    }
}
