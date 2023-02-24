<?php

namespace Sam\Reseller;

/**
 * Trait ResellerHelperAwareTrait
 * @package Sam\Reseller
 */
trait ResellerHelperAwareTrait
{
    /**
     * @var ResellerHelper|null
     */
    protected ?ResellerHelper $resellerHelper = null;

    /**
     * @return ResellerHelper
     */
    protected function getResellerHelper(): ResellerHelper
    {
        if ($this->resellerHelper === null) {
            $this->resellerHelper = ResellerHelper::new();
        }
        return $this->resellerHelper;
    }

    /**
     * @param ResellerHelper $resellerHelper
     * @return static
     * @internal
     */
    public function setResellerHelper(ResellerHelper $resellerHelper): static
    {
        $this->resellerHelper = $resellerHelper;
        return $this;
    }
}
