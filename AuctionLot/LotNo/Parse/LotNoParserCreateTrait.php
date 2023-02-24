<?php

namespace Sam\AuctionLot\LotNo\Parse;

/**
 * Trait LotNoParserCreateTrait
 * @package Sam\AuctionLot\LotNo\Parse
 */
trait LotNoParserCreateTrait
{
    /**
     * @var LotNoParser|null
     */
    protected ?LotNoParser $lotNoParser = null;

    /**
     * @return LotNoParser
     */
    protected function createLotNoParser(): LotNoParser
    {
        return $this->lotNoParser ?: LotNoParser::new();
    }

    /**
     * @param LotNoParser|null $lotNoParser
     * @return $this
     * @internal
     */
    public function setLotNoParser(?LotNoParser $lotNoParser): static
    {
        $this->lotNoParser = $lotNoParser;
        return $this;
    }
}
