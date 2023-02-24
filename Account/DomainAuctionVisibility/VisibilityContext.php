<?php
/**
 * Context
 *
 * @see https://bidpath.atlassian.net/browse/SAM-3051
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\DomainAuctionVisibility;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class VisibilityContext
 */
class VisibilityContext
{
    use AccountAwareTrait;
    use AuctionAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * @var bool
     */
    private bool $isSamPortal = false;
    /**
     * @var string|null
     */
    private ?string $portalDomainAuctionVisibility = Constants\AccountVisibility::SEPARATE;
    /**
     * @var string|null
     */
    private ?string $portalUrlHandling = null;

    //<editor-fold desc="Getter\Setter">

    /**
     * @return bool
     */
    public function isSamPortal(): bool
    {
        return $this->isSamPortal;
    }

    /**
     * @param bool $isSamPortal
     * @return static
     */
    public function enableSamPortal(bool $isSamPortal): static
    {
        $this->isSamPortal = $isSamPortal;
        return $this;
    }

    /**
     * @return string
     */
    public function getPortalDomainAuctionVisibility(): string
    {
        return $this->portalDomainAuctionVisibility;
    }

    /**
     * @param string $portalDomainAuctionVisibility
     * @return static
     */
    public function setPortalDomainAuctionVisibility(string $portalDomainAuctionVisibility): static
    {
        $this->portalDomainAuctionVisibility = Cast::toString($portalDomainAuctionVisibility, Constants\AccountVisibility::$types);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPortalUrlHandling(): ?string
    {
        return $this->portalUrlHandling;
    }

    /**
     * @param string $portalUrlHandling
     * @return static
     */
    public function setPortalUrlHandling(string $portalUrlHandling): static
    {
        $this->portalUrlHandling = trim($portalUrlHandling);
        return $this;
    }

    //</editor-fold>
}
