<?php

namespace Sam\Auction\SaleNo\Parse;

/**
 * Trait SaleNoParserCreateTrait
 * @package Sam\Auction\SaleNo\Parse
 */
trait SaleNoParserCreateTrait
{
    /**
     * @var SaleNoParser|null
     */
    protected ?SaleNoParser $saleNoParser = null;

    /**
     * @return SaleNoParser
     */
    protected function createSaleNoParser(): SaleNoParser
    {
        return $this->saleNoParser ?: SaleNoParser::new();
    }

    /**
     * @param SaleNoParser|null $saleNoParser
     * @return $this
     * @internal
     */
    public function setSaleNoParser(?SaleNoParser $saleNoParser): static
    {
        $this->saleNoParser = $saleNoParser;
        return $this;
    }
}
