<?php
/**
 * SAM-5207: Account filtering detecting helper in application context
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/27/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Filter;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class AccountFilterDetector
 * @package Sam\Account\Filter
 */
class AccountApplicationFilterDetector extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SystemAccountAwareTrait;

    /** @var int[] */
    protected array $selectedAccountId = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int[]
     */
    public function detect(): array
    {
        if (!$this->cfg()->get('core->portal->enabled')) {
            return [];
        }

        if ($this->shouldApplySystemAccountFilter()) {
            $accountIds[] = $this->getSystemAccountId();
        } else {
            $accountIds = $this->getSelectedAccountId();
        }
        return $accountIds;
    }

    /**
     * @return int|null
     */
    public function detectSingle(): ?int
    {
        $accountIds = $this->detect();
        $accountId = $accountIds ? array_shift($accountIds) : null;
        return $accountId;
    }

    /**
     * We allow to apply system account filtering only when cross-account transparent visibility is disabled at multiple tenant installation,
     * and system account should be main.
     * @return bool
     */
    public function shouldApplySystemAccountFilter(): bool
    {
        return $this->cfg()->get('core->portal->enabled')
            && !$this->isMainSystemAccount()
            && $this->cfg()->get('core->portal->domainAuctionVisibility') !== Constants\AccountVisibility::TRANSPARENT
            && !$this->getSystemAccount()->ShowAll; // SAM-6068
    }

    /**
     * @return int[]
     */
    public function getSelectedAccountId(): array
    {
        return $this->selectedAccountId;
    }

    /**
     * Define manual filtering by account
     * @param int[] $selectedAccountId
     * @return static
     */
    public function setSelectedAccountId(array $selectedAccountId): static
    {
        $this->selectedAccountId = $selectedAccountId;
        return $this;
    }
}
