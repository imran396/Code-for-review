<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\UserCustData\Internal;

use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManagerAwareTrait;
use Sam\Observer\EntityObserverSubject;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Log\Observe\UserLoggerBaseHandler;
use UserCustData;
use UserCustField;

/**
 * Class UserCustDataLogger
 * @package Sam\Observer\UserCustData
 * @internal
 */
class UserCustDataLogger extends UserLoggerBaseHandler
{
    use BlockCipherProviderCreateTrait;
    use NumberFormatterAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserCustomFieldTranslationManagerAwareTrait;

    /**
     * @var UserCustField[]
     */
    protected array $userCustomFieldCache = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    protected function getEntityUserId(EntityObserverSubject $subject): int
    {
        /** @var UserCustData $userCustomFieldData */
        $userCustomFieldData = $subject->getEntity();
        return $userCustomFieldData->UserId;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFields(EntityObserverSubject $subject): array
    {
        $userCustomField = $this->loadUserCustomField($subject);
        return [
            'Numeric' => $userCustomField->Name ?? '',
            'Text' => $userCustomField->Name ?? '',
        ];
    }

    /**
     * Treat some values to be human readable (Country, State)
     *
     * @inheritDoc
     */
    protected function treat(EntityObserverSubject $subject, string $property, bool $isOld = false): string
    {
        /** @var UserCustData $userCustomData */
        $userCustomData = $subject->getEntity();
        $value = $isOld
            ? $subject->getOldPropertyValue($property)
            : $userCustomData->$property;
        $treatedValue = (string)$value;
        $userCustomField = $this->loadUserCustomField($subject);
        if (!$userCustomField) {
            log_error("Available user custom field not found" . composeSuffix(['ucf' => $subject->getEntity()->UserCustFieldId]));
            return $treatedValue;
        }

        if (
            $userCustomData->Encrypted
            && in_array($userCustomField->Type, Constants\CustomField::$encryptedTypes, true)
        ) {
            $treatedValue = $this->createBlockCipherProvider()->construct()->decrypt($treatedValue);
        } else {
            switch ($userCustomField->Type) {
                case Constants\CustomField::TYPE_DECIMAL:
                    if ($value !== null) {
                        $precision = (int)$userCustomField->Parameters;
                        $realValue = CustomDataDecimalPureCalculator::new()->calcRealValue((int)$value, $precision);
                        $treatedValue = $this->getNumberFormatter()->formatNto($realValue, $precision);
                    }
                    break;
                case Constants\CustomField::TYPE_DATE:
                    $treatedValue = $this->treatDate($value);
                    break;
                case Constants\CustomField::TYPE_CHECKBOX:
                    $treatedValue = $this->treatBoolean((bool)$value);
                    break;
            }
        }
        return $treatedValue;
    }

    /**
     * Check if object property is modified
     * We must consider situation, when new custom user field is created and initialized with default value,
     * that doesn't mean field's data change logging
     *
     * @inheritDoc
     */
    protected function isPropertyModified(string $property, EntityObserverSubject $subject): bool
    {
        /** @var UserCustData $userCustomData */
        $userCustomData = $subject->getEntity();
        $userCustomField = $this->loadUserCustomField($subject);
        if (!$userCustomField) {
            log_error("Available user custom field not found" . composeSuffix(['ucf' => $subject->getEntity()->UserCustFieldId]));
            return false;
        }
        if (parent::isPropertyModified($property, $subject)) {
            $isNew = $subject->isPropertyModified('UserCustField')
                && ($subject->getOldPropertyValue('UserCustField') === null);
            $isDefaultsPredefinedField = in_array(
                $userCustomField->Type,
                Constants\CustomField::$defaultsPredefinedTypes,
                true
            );
            if (
                $isNew
                && $isDefaultsPredefinedField
            ) {
                $defaultValue = $this->getUserCustomFieldTranslationManager()->translateParameters($userCustomField);
                $currentValue = $subject->$property;
                if (
                    $userCustomData->Encrypted
                    && in_array($userCustomField->Type, Constants\CustomField::$encryptedTypes, true)
                ) {
                    $currentValue = $this->createBlockCipherProvider()->construct()->decrypt($currentValue);
                }
                return $currentValue !== $defaultValue;
            }

            return true;
        }
        return false;
    }

    /**
     * @param EntityObserverSubject $subject
     * @return UserCustField|null
     */
    protected function loadUserCustomField(EntityObserverSubject $subject): ?UserCustField
    {
        $fieldId = $subject->getEntity()->UserCustFieldId;
        if (!array_key_exists($fieldId, $this->userCustomFieldCache)) {
            $this->userCustomFieldCache[$fieldId] = $this->getUserCustomFieldLoader()->load($fieldId);
        }
        return $this->userCustomFieldCache[$fieldId];
    }
}
