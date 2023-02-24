<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\Consignor\Internal\Detect;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\EntityMaker\LotItem
 */
class LotItemUniqueConsignorLockRequirementCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_DO_NOT_LOCK_BECAUSE_AUTO_CREATE_CONSIGNOR_DISABLED = 1;
    public const INFO_DO_NOT_LOCK_BECAUSE_CONSIGNOR_ALREADY_EXISTS = 2;
    public const INFO_DO_NOT_LOCK_BECAUSE_CONSIGNOR_NAME_IS_ABSENT = 3;

    public const OK_LOCK_BECAUSE_CONSIGNOR_WILL_BE_CREATED = 11;

    protected const INFO_MESSAGES = [
        self::INFO_DO_NOT_LOCK_BECAUSE_AUTO_CREATE_CONSIGNOR_DISABLED => 'Do not lock for unique consignor constraint because auto creating is disabled',
        self::INFO_DO_NOT_LOCK_BECAUSE_CONSIGNOR_ALREADY_EXISTS => 'Do not lock for unique consignor constraint because consignor already exists',
        self::INFO_DO_NOT_LOCK_BECAUSE_CONSIGNOR_NAME_IS_ABSENT => 'Do not lock for unique consignor constraint because consignor name is absent in input',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_LOCK_BECAUSE_CONSIGNOR_WILL_BE_CREATED => 'Lock for unique consignor constraint because consignor will be created'
    ];

    protected readonly array $logData;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $modeName, ?string $consignorName): static
    {
        $this->logData = compact('modeName', 'consignorName');
        $this->getResultStatusCollector()->construct([], self::SUCCESS_MESSAGES, [], self::INFO_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    public function addInfo(int $code): static
    {
        $this->getResultStatusCollector()->addInfo($code);
        return $this;
    }

    // --- Query ---

    public function mustLock(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function message(): string
    {
        if ($this->getResultStatusCollector()->hasSuccess()) {
            return $this->getResultStatusCollector()->getConcatenatedSuccessMessage() . composeSuffix($this->logData);
        }

        if ($this->getResultStatusCollector()->hasInfo()) {
            return $this->getResultStatusCollector()->getConcatenatedInfoMessage() . composeSuffix($this->logData);
        }

        return '';
    }
}
