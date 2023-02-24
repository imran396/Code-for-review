<?php

namespace Sam\Lot\Category\Renderer;

/**
 * Trait LotCategoryRendererAwareTrait
 * @package Sam\Lot\Category\Renderer
 */
trait LotCategoryRendererAwareTrait
{
    /**
     * @var LotCategoryRenderer|null
     */
    protected ?LotCategoryRenderer $lotCategoryRenderer = null;

    /**
     * @return LotCategoryRenderer
     */
    protected function getLotCategoryRenderer(): LotCategoryRenderer
    {
        if ($this->lotCategoryRenderer === null) {
            $this->lotCategoryRenderer = LotCategoryRenderer::new();
        }
        return $this->lotCategoryRenderer;
    }

    /**
     * @param LotCategoryRenderer $lotCategoryRenderer
     * @return static
     * @internal
     */
    public function setLotCategoryRenderer(LotCategoryRenderer $lotCategoryRenderer): static
    {
        $this->lotCategoryRenderer = $lotCategoryRenderer;
        return $this;
    }
}
