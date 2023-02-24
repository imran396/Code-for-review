<?php
/**
 * Helper class for QCodo controllers (drafts) for rendering custom user fields
 * Public/Admin side: user list
 * Public side: user detail page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Jul 11, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Qform;

use DateTime;
use Sam\Application\Url\Build\Config\CustomField\UserCustomFieldFileUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManagerAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use UserCustData;
use UserCustField;

/**
 * Class ViewControls
 * @package Sam\CustomField\User\Qform
 */
class ViewControls extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use NumberFormatterAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UserCustomFieldHelperAwareTrait;
    use UserCustomFieldTranslationManagerAwareTrait;

    // these options are externally defined
    /**
     * at public we translate labels, at private do not.
     */
    protected bool $isTranslating = true;

    // these properties not need to define externally, they are dependent on the options above
    protected array $selections = [];

    /**
     * Class instantiation method
     * @return static or customized class extending ViewControls
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render custom field using data object
     *
     * @param UserCustField $userCustomField
     * @param UserCustData $userCustomData
     * @return string
     */
    public function renderByCustData(UserCustField $userCustomField, UserCustData $userCustomData): string
    {
        if ($userCustomField->isNumeric()) {
            $output = $this->renderByValue($userCustomField, $userCustomData->Numeric, $userCustomData->UserId);
        } else {
            $output = $this->renderByValue($userCustomField, $userCustomData->Text, $userCustomData->UserId);
        }
        return $output;
    }

    /**
     * Render custom field using value
     *
     * @param UserCustField $userCustomField
     * @param int|string|null $value
     * @param int $userId
     * @return string
     */
    public function renderByValue(UserCustField $userCustomField, int|string|null $value, int $userId): string
    {
        return match ($userCustomField->Type) {
            Constants\CustomField::TYPE_INTEGER => $this->renderInteger($userCustomField, Cast::toInt($value)),
            Constants\CustomField::TYPE_DECIMAL => $this->renderDecimal($userCustomField, Cast::toInt($value)),
            Constants\CustomField::TYPE_TEXT => $this->renderText($userCustomField, (string)$value),
            Constants\CustomField::TYPE_SELECT => $this->renderSelection($userCustomField, $value),
            Constants\CustomField::TYPE_DATE => $this->renderDate($userCustomField, Cast::toInt($value)),
            Constants\CustomField::TYPE_FULLTEXT => $this->renderFulltext($userCustomField, (string)$value),
            Constants\CustomField::TYPE_CHECKBOX => $this->renderCheckbox($userCustomField, Cast::toInt($value)),
            Constants\CustomField::TYPE_PASSWORD => $this->renderPassword($userCustomField, (string)$value),
            Constants\CustomField::TYPE_LABEL => $this->renderLabel($userCustomField),
            Constants\CustomField::TYPE_FILE => $this->renderFile($userCustomField, (string)$value, $userId),
            default => '',
        };
    }

    /**
     * @param UserCustField $userCustomField
     * @param int|null $value
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderInteger(UserCustField $userCustomField, ?int $value): string
    {
        if ($value === null) {
            return '';
        }
        $output = $this->getNumberFormatter()->formatIntegerNto($value);
        return $output;
    }

    /**
     * @param UserCustField $userCustomField
     * @param int|null $value
     * @return string
     */
    public function renderDecimal(UserCustField $userCustomField, ?int $value): string
    {
        if ($value === null) {
            return '';
        }
        $decimals = (int)$userCustomField->Parameters;
        $decimalValue = substr((string)$value, 0, $decimals * -1) .
            '.' . substr((string)$value, $decimals * -1);
        $output = $this->getNumberFormatter()->format($decimalValue, $decimals);
        return $output;
    }

    /**
     * @param UserCustField $userCustomField
     * @param string $value
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderText(UserCustField $userCustomField, string $value): string
    {
        return $value;
    }

    /**
     * @param UserCustField $userCustomField
     * @param string $value
     * @return string
     */
    public function renderSelection(UserCustField $userCustomField, string $value): string
    {
        if ($value === '') {
            return '';
        }
        $selections = $this->getSelections($userCustomField);
        if (array_key_exists($value, $selections)) {
            $value = $selections[$value];
        }
        return $value;
    }

    /**
     * @param UserCustField $userCustomField
     * @param int|null $value
     * @return string
     */
    public function renderDate(UserCustField $userCustomField, ?int $value): string
    {
        if ($value === null || $value <= 0) {
            return '';
        }
        $dateFormat = $userCustomField->Parameters ?: 'm/d/Y g:i A e';
        $output = (new DateTime())
            ->setTimestamp($value)
            ->format($dateFormat);
        return $output;
    }

    /**
     * @param UserCustField $userCustomField
     * @param string $value
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderFulltext(UserCustField $userCustomField, string $value): string
    {
        return $value;
    }

    /**
     * @param UserCustField $userCustomField
     * @param int|null $value
     * @return string
     */
    public function renderCheckbox(UserCustField $userCustomField, ?int $value): string
    {
        if ((string)$value === '') {
            return '';
        }
        if ($this->isTranslating()) {
            $isChecked = $value ? true : false;
            $keyForCheckboxText = $this->getUserCustomFieldTranslationManager()->makeKeyForCheckboxText($userCustomField->Name, $isChecked);
            $output = $this->getTranslator()->translate($keyForCheckboxText, 'usercustomfields');
        } else {
            $output = $value ? Constants\CustomField::CHECKBOX_ON_TEXT
                : Constants\CustomField::CHECKBOX_OFF_TEXT;
        }
        return $output;
    }

    /**
     * @param UserCustField $userCustomField
     * @param string $value
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderPassword(UserCustField $userCustomField, string $value): string
    {
        return $value;
    }

    /**
     * @param UserCustField $userCustomField
     * @return string
     */
    public function renderLabel(UserCustField $userCustomField): string
    {
        if ($this->isTranslating()) {
            $output = $this->getUserCustomFieldTranslationManager()->translateParameters($userCustomField);
        } else {
            $output = $userCustomField->Parameters;
        }
        return $output;
    }

    /**
     * @param UserCustField $userCustomField
     * @param string $value
     * @param int $userId
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderFile(UserCustField $userCustomField, string $value, int $userId): string
    {
        if (empty($value)) {
            return '';
        }
        $fileNames = explode('|', $value);
        $output = '';
        foreach ($fileNames as $fileName) {
            $output .= $this->renderFileLink($fileName, $userId) . "\n";
        }
        $output = rtrim($output, "\n");
        return $output;
    }

    /**
     * @param string $fileName
     * @param int|null $userId null when user is unknown yet, e.g. on creating new
     * @return string
     */
    public function renderFileLink(string $fileName, ?int $userId): string
    {
        if (
            !$fileName
            || !$userId
        ) {
            return '';
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $urlConfig = UserCustomFieldFileUrlConfig::new()->forWeb($userId, $fileName);
        $url = $this->getUrlBuilder()->build($urlConfig);
        $output = sprintf(
            '<a class="custom_file custom_file_%s" href="%s" title="%s"><span class="custfile">%s</span></a>',
            $extension,
            $url,
            $fileName,
            $fileName
        );
        return $output;
    }

    /**
     * Return array for selection options
     *
     * @param UserCustField $userCustomField
     * @return array $value => $name
     */
    public function getSelections(UserCustField $userCustomField): array
    {
        if (!isset($this->selections[$userCustomField->Id])) {
            $nameList = $this->getUserCustomFieldTranslationManager()->translateParameters($userCustomField);
            $nameList = $nameList !== '' ? $nameList : $userCustomField->Parameters;
            $names = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($nameList);
            $values = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($userCustomField->Parameters);
            $options = [];
            foreach ($values as $i => $value) {
                $options[$value] = $names[$i] ?? $value;
            }
            $this->selections[$userCustomField->Id] = $options;
        }
        return $this->selections[$userCustomField->Id];
    }

    /**
     * @return bool
     */
    public function isTranslating(): bool
    {
        return $this->isTranslating;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableTranslating(bool $enable): static
    {
        $this->isTranslating = $enable;
        return $this;
    }
}
