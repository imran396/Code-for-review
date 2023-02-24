<?php
/**
 * SAM-6424 : Country tax services
 * https://bidpath.atlassian.net/browse/SAM-6424
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\SamTaxCountryState\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\WriteRepository\Entity\SamTaxCountryStates\SamTaxCountryStatesWriteRepositoryAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use SamTaxCountryStates;

/**
 * Class SamTaxCountryStateProducer
 * @package Sam\Tax\SamTaxCountryState
 */
class SamTaxCountryStateProducer extends CustomizableClass
{
    use AccountAwareTrait;
    use AuctionAwareTrait;
    use AuthIdentityManagerCreateTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use LotItemAwareTrait;
    use SamTaxCountryStatesWriteRepositoryAwareTrait;

    protected string $country;
    protected string $state;
    protected int $editorUserId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     *
     * @param string $country
     * @param string $state
     * @param int $editorUserId
     * @param int|null $accountId null- account id is not set
     * @param int|null $auctionId null- auction id is not set
     * @param int|null $lotItemId null- lot item id is not set
     * @return static
     */
    public function construct(
        string $country,
        string $state,
        int $editorUserId,
        ?int $accountId = null,
        ?int $auctionId = null,
        ?int $lotItemId = null
    ): static {
        $this->country = $country;
        $this->state = $state;
        $this->editorUserId = $editorUserId;
        $this->setAccountId($accountId);
        $this->setLotItemId($lotItemId);
        $this->setAuctionId($auctionId);
        return $this;
    }

    /**
     * Add country state
     * @return SamTaxCountryStates
     */
    public function add(): SamTaxCountryStates
    {
        $samTaxCountryStates = $this->getSamTaxCountryState();
        $this->getSamTaxCountryStatesWriteRepository()->saveWithModifier($samTaxCountryStates, $this->editorUserId);
        return $samTaxCountryStates;
    }

    /**
     * @return SamTaxCountryStates
     */
    public function getSamTaxCountryState(): SamTaxCountryStates
    {
        $samTaxCountryStates = $this->createEntityFactory()->samTaxCountryStates();
        $samTaxCountryStates->AccountId = $this->getAccountId();
        $samTaxCountryStates->AuctionId = $this->getAuctionId();
        $samTaxCountryStates->LotItemId = $this->getLotItemId();
        $samTaxCountryStates->Country = $this->country;
        $samTaxCountryStates->State = $this->state;
        $samTaxCountryStates->Active = true;
        return $samTaxCountryStates;
    }
}
