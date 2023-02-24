<?php

namespace Sam\Lot\ItemNo\Parse;

/**
 * Trait LotItemNoParserCreateTrait
 * @package Sam\Lot\ItemNo\Parse
 */
trait LotItemNoParserCreateTrait
{
    protected ?LotItemNoParser $lotItemNoParser = null;

    /**
     * @return LotItemNoParser
     */
    protected function createLotItemNoParser(): LotItemNoParser
    {
        return $this->lotItemNoParser ?: LotItemNoParser::new();
    }

    /**
     * @param LotItemNoParser|null $lotItemNoParser
     * @return $this
     * @internal
     */
    public function setLotItemNoParser(?LotItemNoParser $lotItemNoParser): static
    {
        $this->lotItemNoParser = $lotItemNoParser;
        return $this;
    }
}
