<?php
/**
 *
 * * SAM-4558: Settlement Deleter module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-02
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Delete;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;

/**
 * Class MultipleSettlementDeleter
 * @package Sam\Settlement\Delete
 */
class MultipleSettlementDeleter extends CustomizableClass
{
    use SettlementDeleterAwareTrait;
    use SettlementLoaderAwareTrait;

    /** @var int[] */
    protected array $settlementIds = [];
    protected ?string $successMessage = null;
    protected ?string $errorMessage = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Settlements
     * @param int $editorUserId
     */
    public function delete(int $editorUserId): void
    {
        $deletedSettlementNos = [];
        $settlements = $this->getSettlementLoader()->loadEntities($this->getSettlementIds(), true);
        foreach ($settlements as $settlement) {
            $this->getSettlementDeleter()->delete($settlement, $editorUserId);
            $deletedSettlementNos[] = $settlement->SettlementNo;
        }
        $count = count($deletedSettlementNos);
        $deletedSettlementNoList = implode(', ', $deletedSettlementNos);
        $message = null;
        if ($count > 1) {
            $message = "Settlements [" . $deletedSettlementNoList . "] have been deleted";
        } elseif ($count === 1) {
            $message = "Settlement [" . $deletedSettlementNoList . "] has been deleted";
        }
        $this->successMessage = $message;
    }

    /**
     * Get Error Message
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * Get Success Message
     * @return string|null
     */
    public function getSuccessMessage(): ?string
    {
        return $this->successMessage;
    }

    /**
     * Set selected settlement Ids
     * @param int[] $settlementIds
     * @return static
     */
    public function setSettlementIds(array $settlementIds): static
    {
        $this->settlementIds = ArrayCast::castInt($settlementIds);
        return $this;
    }

    /**
     * @return int[]
     */
    protected function getSettlementIds(): array
    {
        return $this->settlementIds;
    }

    /**
     * validate
     * @return bool
     */
    public function validate(): bool
    {
        $this->errorMessage = null;
        $count = count($this->getSettlementIds());
        if ($count === 0) {
            $this->errorMessage = 'No settlements selected to delete';
            return false;
        }
        return true;
    }
}
