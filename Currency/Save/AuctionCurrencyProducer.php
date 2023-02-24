<?php
/**
 * SAM-4682: Auction currency producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/6/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Save;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Delete\AuctionCurrencyDeleterCreateTrait;
use Sam\Currency\Load\AuctionCurrencyLoaderAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCurrency\AuctionCurrencyReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionCurrency\AuctionCurrencyWriteRepositoryAwareTrait;

/**
 * Class AuctionCurrencyProducer
 * @package Sam\Currency\Save
 */
class AuctionCurrencyProducer extends CustomizableClass
{
    use AuctionCurrencyDeleterCreateTrait;
    use AuctionCurrencyLoaderAwareTrait;
    use AuctionCurrencyReadRepositoryCreateTrait;
    use AuctionCurrencyWriteRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clone auction additional currencies
     *
     * @param int $sourceAuctionId Source auction id
     * @param int $targetAuctionId Target auction id
     * @param int $editorUserId
     */
    public function cloneAuctionCurrencies(int $sourceAuctionId, int $targetAuctionId, int $editorUserId): void
    {
        $rows = $this->createAuctionCurrencyReadRepository()
            ->select(['currency_id'])
            ->filterAuctionId($sourceAuctionId)
            ->loadRows();
        foreach ($rows as $row) {
            $clonedAuctionCurrency = $this->createEntityFactory()->auctionCurrency();
            $clonedAuctionCurrency->AuctionId = $targetAuctionId;
            $clonedAuctionCurrency->CurrencyId = (int)$row['currency_id'];
            $this->getAuctionCurrencyWriteRepository()->saveWithModifier($clonedAuctionCurrency, $editorUserId);
        }
    }

    /**
     * Create auction currencies
     * @param string[] $auctionCurrencies New auction currencies names
     * @param int $auctionId
     * @param int|null $auctionCurrency
     * @param int $editorUserId
     */
    public function create(array $auctionCurrencies, int $auctionId, ?int $auctionCurrency, int $editorUserId): void
    {
        if ($auctionCurrencies) {
            $currencyIds = $this->getCurrencyLoader()->loadCurrencyIdsByNames($auctionCurrencies);
            $this->save($currencyIds, $auctionId, $auctionCurrency, $editorUserId);
        }
    }

    /**
     * Update auction currencies
     * Works in 3 steps: update modified records, remove unused records, add new records.
     * It's faster than just 2 steps algorithm: remove old records, add new records.
     * @param string[] $newCurrencyNames New auction currencies names
     * @param int $auctionId
     * @param int|null $currencyId
     * @param int $editorUserId
     */
    public function update(array $newCurrencyNames, int $auctionId, ?int $currencyId, int $editorUserId): void
    {
        $newCurrencyIds = $this->getCurrencyLoader()->loadCurrencyIdsByNames($newCurrencyNames);
        $oldAuctionCurrencies = $this->getAuctionCurrencyLoader()->loadAuctionCurrencies($auctionId);

        // Exclude duplicate records
        foreach ($oldAuctionCurrencies as $oldKey => $oldAuctionCurrency) {
            foreach ($newCurrencyIds as $newKey => $newCurrencyId) {
                if (
                    $oldAuctionCurrency->AuctionId
                    && $oldAuctionCurrency->CurrencyId === $newCurrencyId
                ) {
                    unset($oldAuctionCurrencies[$oldKey], $newCurrencyIds[$newKey]);
                }
                if (!$newCurrencyId) {
                    unset($newCurrencyIds[$newKey]);
                }
            }
        }

        // Order keys ascending
        $oldAuctionCurrencies = array_values($oldAuctionCurrencies);

        // Update modified records
        $counter = 0;
        foreach ($newCurrencyIds as $key => $newCurrencyId) {
            if (isset($oldAuctionCurrencies[$counter])) {
                $oldAuctionCurrencies[$counter]->AuctionId = $auctionId;
                $oldAuctionCurrencies[$counter]->CurrencyId = $newCurrencyId;
                $this->getAuctionCurrencyWriteRepository()->saveWithModifier($oldAuctionCurrencies[$counter], $editorUserId);
                unset($newCurrencyIds[$key], $oldAuctionCurrencies[$counter]);
                $counter++;
            } else {
                break;
            }
        }

        // Remove unused records
        foreach ($oldAuctionCurrencies as $oldAuctionCurrency) {
            $this->getAuctionCurrencyWriteRepository()->deleteWithModifier($oldAuctionCurrency, $editorUserId);
        }

        $this->save($newCurrencyIds, $auctionId, $currencyId, $editorUserId);
    }

    /**
     * @param array $selectedCurrencyIds
     * @param int $auctionId
     * @param int|null $mainCurrencyId auction's main currency id
     * @param int $editorUserId
     */
    public function updateByIds(array $selectedCurrencyIds, int $auctionId, ?int $mainCurrencyId, int $editorUserId): void
    {
        $this->createAuctionCurrencyDeleter()->deleteAll($auctionId);
        $this->save($selectedCurrencyIds, $auctionId, $mainCurrencyId, $editorUserId);
    }

    /**
     * Save auction currencies
     * @param int[] $newCurrencyIds
     * @param int $auctionId
     * @param int|null $currencyId
     * @param int $editorUserId
     */
    protected function save(array $newCurrencyIds, int $auctionId, ?int $currencyId, int $editorUserId): void
    {
        $newCurrencyIds = ArrayCast::castInt($newCurrencyIds);
        $currencyId = (int)$currencyId;
        foreach ($newCurrencyIds as $newCurrencyId) {
            if ($newCurrencyId === $currencyId) {
                continue;
            }
            $auctionCurrency = $this->createEntityFactory()->auctionCurrency();
            $auctionCurrency->AuctionId = $auctionId;
            $auctionCurrency->CurrencyId = $newCurrencyId;
            $this->getAuctionCurrencyWriteRepository()->saveWithModifier($auctionCurrency, $editorUserId);
        }
    }
}
