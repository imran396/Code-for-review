<?php
/**
 * SAM-9355: Refactor Domain Detector and Domain Redirector for unit testing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\RouteType;

use Sam\Core\Service\CustomizableClass;

/**
 * Class RouteType
 * @package Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\RouteType
 */
class RouteType extends CustomizableClass
{
    protected const AUCTION = 1;
    protected const INVOICE = 2;
    protected const SETTLEMENT = 3;
    protected const NONE = 4;

    protected int $type;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $type
     * @return $this
     */
    public function construct(int $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function constructAuction(): static
    {
        return $this->construct(self::AUCTION);
    }

    public function constructInvoice(): static
    {
        return $this->construct(self::INVOICE);
    }

    public function constructSettlement(): static
    {
        return $this->construct(self::SETTLEMENT);
    }

    public function constructNone(): static
    {
        return $this->construct(self::NONE);
    }

    public function isAuction(): bool
    {
        return $this->type === self::AUCTION;
    }

    public function isInvoice(): bool
    {
        return $this->type === self::INVOICE;
    }

    public function isSettlement(): bool
    {
        return $this->type === self::SETTLEMENT;
    }

    public function isNone(): bool
    {
        return $this->type === self::NONE;
    }
}
