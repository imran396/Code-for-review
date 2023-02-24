<?php
/**
 * SAM-10589: Supply uniqueness of lot item fields: lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\CustomField\Internal\Detect;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemUniqueCustomFieldLockRequirementCheckingResult
 * @package Sam\EntityMaker\LotItem\Lock\CustomField\Internal\Detect
 */
class LotItemUniqueCustomFieldLockRequirementCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_DO_NOT_LOCK_BECAUSE_UNIQUE_CUSTOM_FIELDS_ABSENT_IN_INPUT = 1;
    public const INFO_DO_NOT_LOCK_BECAUSE_UNIQUE_CUSTOM_FIELDS_VALUE_EQUAL_TO_EXISTING = 2;

    public const OK_LOCK_BECAUSE_UNIQUE_CUSTOM_FIELDS_VALUE_CHANGED = 1;

    protected const INFO_MESSAGES = [
        self::INFO_DO_NOT_LOCK_BECAUSE_UNIQUE_CUSTOM_FIELDS_ABSENT_IN_INPUT => 'Do not lock for unique custom fields constraint, because unique custom fields absent in input',
        self::INFO_DO_NOT_LOCK_BECAUSE_UNIQUE_CUSTOM_FIELDS_VALUE_EQUAL_TO_EXISTING => 'Do not lock for unique custom fields constraint, because "%s" custom field value equal to existing',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_LOCK_BECAUSE_UNIQUE_CUSTOM_FIELDS_VALUE_CHANGED => 'Lock for unique custom fields constraint, because "%s" custom field value will be changed',
    ];

    protected array $logData;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(?int $lotItemId, array $customFieldsInput): static
    {
        $this->getResultStatusCollector()->construct([], self::SUCCESS_MESSAGES, [], self::INFO_MESSAGES);
        $this->logData = [
            'li' => $lotItemId,
            'customFieldsInput' => $customFieldsInput
        ];
        return $this;
    }

    // --- Mutate ---

    public function addSuccess(int $code, string $fieldName): static
    {
        $this->getResultStatusCollector()->addSuccess($code, sprintf(self::SUCCESS_MESSAGES[$code], $fieldName));
        return $this;
    }

    public function addInfo(int $code, ?string $fieldName = null): static
    {
        $message = $fieldName ? sprintf(self::INFO_MESSAGES[$code], $fieldName) : null;
        $this->getResultStatusCollector()->addInfo($code, $message);
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
