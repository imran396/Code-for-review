<?php
/**
 * Helper class for QCodo controllers (drafts) for rendering custom auction fields
 * Public/Admin side: auction list
 * Public side: auction detail page
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

namespace Sam\CustomField\Auction\Qform;

use AuctionCustData;
use AuctionCustField;
use DateTime;
use Sam\Application\Url\Build\Config\CustomField\AuctionCustomFieldFileUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Help\AuctionCustomFieldHelperAwareTrait;
use Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManagerAwareTrait;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Core\Constants;

/**
 * Class ViewControls
 */
class ViewControls extends CustomizableClass
{
    use AuctionCustomFieldHelperAwareTrait;
    use AuctionCustomFieldTranslationManagerAwareTrait;
    use BaseCustomFieldHelperAwareTrait;
    use NumberFormatterAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    // these options are externally defined
    protected bool $isPublic = true;               // Determine some rendering options, e.g. at public we translate labels, at private do not.

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
     * For simplifying initialization of class parameters
     *
     * @param bool $enable
     * @return static
     */
    public function enablePublic(bool $enable): static
    {
        $this->isPublic = $enable;
        return $this;
    }

    /**
     * Render custom field using data object
     *
     * @param AuctionCustField $auctionCustomField
     * @param AuctionCustData $auctionCustomData
     * @return string
     */
    public function renderByCustData(AuctionCustField $auctionCustomField, AuctionCustData $auctionCustomData): string
    {
        if ($auctionCustomField->isNumeric()) {
            $output = $this->renderByValue($auctionCustomField, $auctionCustomData->Numeric, $auctionCustomData->AuctionId);
        } else {
            $output = $this->renderByValue($auctionCustomField, $auctionCustomData->Text, $auctionCustomData->AuctionId);
        }
        return $output;
    }

    /**
     * Render custom field using value
     *
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @param int|null $auctionId
     * @return string
     */
    public function renderByValue(AuctionCustField $auctionCustomField, int|string|null $rawValue, ?int $auctionId): string
    {
        return match ($auctionCustomField->Type) {
            Constants\CustomField::TYPE_INTEGER => $this->renderInteger($auctionCustomField, $rawValue),
            Constants\CustomField::TYPE_DECIMAL => $this->renderDecimal($auctionCustomField, $rawValue),
            Constants\CustomField::TYPE_TEXT => $this->renderText($auctionCustomField, $rawValue),
            Constants\CustomField::TYPE_SELECT => $this->renderSelection($auctionCustomField, $rawValue),
            Constants\CustomField::TYPE_DATE => $this->renderDate($auctionCustomField, $rawValue),
            Constants\CustomField::TYPE_FULLTEXT => $this->renderFulltext($auctionCustomField, $rawValue),
            Constants\CustomField::TYPE_CHECKBOX => $this->renderCheckbox($auctionCustomField, $rawValue),
            Constants\CustomField::TYPE_PASSWORD => $this->renderPassword($auctionCustomField, $rawValue),
            Constants\CustomField::TYPE_FILE => $this->renderFile($auctionCustomField, $rawValue, $auctionId),
            Constants\CustomField::TYPE_LABEL => $this->renderLabel($auctionCustomField),
            Constants\CustomField::TYPE_RICHTEXT => $this->renderRichtext($auctionCustomField, $rawValue),
            default => '',
        };
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderInteger(AuctionCustField $auctionCustomField, int|string|null $rawValue): string
    {
        $rawValue = Cast::toInt($rawValue);
        if ($rawValue === null) {
            return '';
        }

        $value = $this->getNumberFormatter()->formatIntegerNto($rawValue);
        return $value;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @return string
     */
    public function renderDecimal(AuctionCustField $auctionCustomField, int|string|null $rawValue): string
    {
        $rawValue = Cast::toInt($rawValue);
        if ($rawValue === null) {
            return '';
        }

        $precision = (int)$auctionCustomField->Parameters;
        $realValue = CustomDataDecimalPureCalculator::new()->calcRealValue($rawValue, $precision);
        $output = $this->getNumberFormatter()->format($realValue, $precision);
        return $output;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderText(AuctionCustField $auctionCustomField, int|string|null $rawValue): string
    {
        return (string)$rawValue;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @return mixed
     */
    public function renderSelection(AuctionCustField $auctionCustomField, int|string|null $rawValue): mixed
    {
        if ((string)$rawValue === '') {
            return '';
        }
        $selections = $this->getSelections($auctionCustomField);
        $output = '';
        if (array_key_exists($rawValue, $selections)) {
            $output = $selections[$rawValue];
        }
        return $output;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @return string
     */
    public function renderDate(AuctionCustField $auctionCustomField, int|string|null $rawValue): string
    {
        if (
            (string)$rawValue === ''
            || (int)$rawValue <= 0
        ) {
            return '';
        }
        $dateFormat = $auctionCustomField->Parameters ?: 'm/d/Y g:i A e';
        $output = (new DateTime())->setTimestamp($rawValue)->format($dateFormat);
        return $output;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderFulltext(AuctionCustField $auctionCustomField, int|string|null $rawValue): string
    {
        return (string)$rawValue;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @return string
     */
    public function renderCheckbox(AuctionCustField $auctionCustomField, int|string|null $rawValue): string
    {
        if ((string)$rawValue === '') {
            return '';
        }
        $auctionTranslation = $this->getAuctionCustomFieldTranslationManager();
        if ($this->isPublic) {
            $isChecked = $rawValue ? true : false;
            $keyForCheckboxText = $auctionTranslation->makeKeyForCheckboxText($auctionCustomField->Name, $isChecked);
            $output = $this->getTranslator()->translate($keyForCheckboxText, 'auctioncustomfields');
        } else {
            $output = $rawValue ? Constants\CustomField::CHECKBOX_ON_TEXT
                : Constants\CustomField::CHECKBOX_OFF_TEXT;
        }
        return $output;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderPassword(AuctionCustField $auctionCustomField, int|string|null $rawValue): string
    {
        return (string)$rawValue;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @return string
     */
    public function renderLabel(AuctionCustField $auctionCustomField): string
    {
        if ($this->isPublic) {
            $output = $this->getAuctionCustomFieldTranslationManager()->translateParameters($auctionCustomField);
        } else {
            $output = $auctionCustomField->Parameters;
        }
        return $output;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @param int|null $auctionId
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderFile(AuctionCustField $auctionCustomField, int|string|null $rawValue, ?int $auctionId): string
    {
        if ((string)$rawValue === '') {
            return '';
        }
        $fileNames = explode('|', $rawValue);
        $output = '';
        foreach ($fileNames as $fileName) {
            $output .= $this->renderFileLink($fileName, $auctionId) . "\n";
        }
        $output = rtrim($output, "\n");
        return $output;
    }

    /**
     * @param string $fileName
     * @param int|null $auctionId
     * @return string
     */
    public function renderFileLink(string $fileName, ?int $auctionId): string
    {
        if (
            !$fileName
            || !$auctionId
        ) {
            return '';
        }

        $output = '';
        $segments = explode('.', $fileName);
        $extension = array_pop($segments);

        $url = $this->getUrlBuilder()->build(
            AuctionCustomFieldFileUrlConfig::new()->forWeb($auctionId, $fileName)
        );

        $output .= sprintf('<a class="custom_file custom_file_%s" href="%s" title="%s">', $extension, $url, $fileName);
        $output .= '<span class="custfile">';
        $output .= $fileName;
        $output .= '</span></a>';
        return $output;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param int|string|null $rawValue
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public function renderRichtext(AuctionCustField $auctionCustomField, int|string|null $rawValue): string
    {
        return (string)$rawValue;
    }

    /**
     * Return array for selection options
     *
     * @param AuctionCustField $auctionCustomField
     * @return array $value => $name
     */
    public function getSelections(AuctionCustField $auctionCustomField): array
    {
        if (!isset($this->selections[$auctionCustomField->Id])) {
            $nameList = $this->getAuctionCustomFieldTranslationManager()->translateParameters($auctionCustomField);
            $nameList = $nameList !== '' ? $nameList : $auctionCustomField->Parameters;
            $names = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($nameList);
            $values = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($auctionCustomField->Parameters);
            $options = [];
            foreach ($values as $i => $value) {
                $options[$value] = $names[$i] ?? $value;
            }
            $this->selections[$auctionCustomField->Id] = $options;
        }
        return $this->selections[$auctionCustomField->Id];
    }
}
