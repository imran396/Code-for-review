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

namespace Sam\Tax\SamTaxCountryState\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SamTaxCountryStates\SamTaxCountryStatesReadRepositoryCreateTrait;

/**
 * Class SamTaxCountryStateExistenceChecker
 * @package Sam\Tax\SamTaxCountryState
 */
class SamTaxCountryStateExistenceChecker extends CustomizableClass
{
    use SamTaxCountryStatesReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if country state exist
     *
     * @param string $country
     * @param string $state
     * @param int|null $accountId null- it means account id is not filtered in results.
     * @param int|null $auctionId null- it means auction id is not filtered in results.
     * @param int|null $lotItemId null- it means lot item id is not filtered in results.
     * @return bool
     */
    public function exist(
        string $country,
        string $state,
        ?int $accountId = null,
        ?int $auctionId = null,
        ?int $lotItemId = null
    ): bool {
        $samTaxCountryStatesRepository = $this->createSamTaxCountryStatesReadRepository()
            ->filterCountry($country)
            ->filterState($state)
            ->filterActive(true);
        if ($accountId) {
            $samTaxCountryStatesRepository->filterAccountId($accountId);
        } elseif ($auctionId) {
            $samTaxCountryStatesRepository->filterAuctionId($auctionId);
        } elseif ($lotItemId) {
            $samTaxCountryStatesRepository->filterLotItemId($lotItemId);
        }
        $isFound = $samTaxCountryStatesRepository->exist();
        return $isFound;
    }

}
