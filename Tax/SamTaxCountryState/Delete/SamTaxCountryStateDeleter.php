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

namespace Sam\Tax\SamTaxCountryState\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SamTaxCountryStates\SamTaxCountryStatesReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\SamTaxCountryStates\SamTaxCountryStatesWriteRepositoryAwareTrait;

/**
 * Class SamTaxCountryStateDeleter
 * @package Sam\Tax\SamTaxCountryState
 */
class SamTaxCountryStateDeleter extends CustomizableClass
{
    use SamTaxCountryStatesReadRepositoryCreateTrait;
    use SamTaxCountryStatesWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $countryStateIds
     * @param int $editorUserId
     * @param int|null $accountId null- it does not consider before delete, means deleting for all accounts
     * @param int|null $auctionId null- it does not consider before delete, means deleting for all auctions
     * @param int|null $lotItemId null- it does not consider before delete, means deleting for all lot items
     */
    public function delete(
        array $countryStateIds,
        int $editorUserId,
        ?int $accountId = null,
        ?int $auctionId = null,
        ?int $lotItemId = null
    ): void {
        if (
            !$accountId
            && !$auctionId
            && !$lotItemId
        ) {
            log_errorBackTrace('No filtering conditions supplied in SamTaxCountryStates deleting function. Skip delete operation.');
            return;
        }

        $repo = $this->createSamTaxCountryStatesReadRepository()
            ->filterActive(true);
        if (count($countryStateIds) > 0) {
            $repo->skipId($countryStateIds);
        }

        if ($accountId) {
            $repo = $repo->filterAccountId($accountId);
        } elseif ($auctionId) {
            $repo = $repo->filterAuctionId($auctionId);
        } elseif ($lotItemId) {
            $repo = $repo->filterLotItemId($lotItemId);
        }

        $taxes = $repo->loadEntities();

        foreach ($taxes as $tax) {
            $tax->Active = false;
            $this->getSamTaxCountryStatesWriteRepository()->saveWithModifier($tax, $editorUserId);
        }
    }
}
