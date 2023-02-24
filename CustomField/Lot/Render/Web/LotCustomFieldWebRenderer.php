<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Render\Web;


use DateTime;
use Sam\Application\Url\Build\Config\CustomField\LotCustomFieldFileUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Translate\LotCustomFieldTranslationManagerAwareTrait;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Contains methods for rendering lot custom field value.
 * The result depends on the type and settings of the custom field
 *
 * Class LotCustomFieldWebRenderer
 * @package Sam\CustomField\Lot\Render\Web
 */
class LotCustomFieldWebRenderer extends CustomizableClass
{
    use LotCustomFieldTranslationManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render lot custom field value
     *
     * @param LotCustomFieldRenderValue $fieldValue
     * @param int|null $lotItemId
     * @return string
     * @throws \Exception
     */
    public function render(LotCustomFieldRenderValue $fieldValue, ?int $lotItemId): string
    {
        $output = '';
        switch ($fieldValue->type) {
            case Constants\CustomField::TYPE_INTEGER:
                if ((string)$fieldValue->numericValue !== '') {
                    $output = $fieldValue->numericValue;
                }
                break;

            case Constants\CustomField::TYPE_DECIMAL:
                if ($fieldValue->numericValue !== null) {
                    $precision = (int)$fieldValue->parameters;
                    $output = CustomDataDecimalPureCalculator::new()->calcRealValue($fieldValue->numericValue, $precision);
                }
                break;

            case Constants\CustomField::TYPE_TEXT:
            case Constants\CustomField::TYPE_FULLTEXT:
            case Constants\CustomField::TYPE_SELECT:
            case Constants\CustomField::TYPE_POSTALCODE:
            case Constants\CustomField::TYPE_YOUTUBELINK:
                $output = $fieldValue->textValue;
                break;

            case Constants\CustomField::TYPE_DATE:
                if ($fieldValue->numericValue > 0) {
                    $dateFormat = $fieldValue->parameters;
                    if ($dateFormat === '') {
                        $dateFormat = 'm/d/Y g:i A';
                    }
                    $output = (new DateTime())
                        ->setTimestamp($fieldValue->numericValue)
                        ->format($dateFormat);
                }
                break;

            case Constants\CustomField::TYPE_FILE:
                if ($lotItemId) {
                    $output = $this->renderFileLinks($fieldValue, $lotItemId);
                }
                break;
        }
        return (string)$output;
    }

    /**
     * Render the value of a lot custom field and translate it if the field is of select type
     *
     * @param LotCustomFieldRenderValue $fieldValue
     * @param int|null $lotItemId
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     * @throws \Exception
     */
    public function renderTranslated(
        LotCustomFieldRenderValue $fieldValue,
        ?int $lotItemId,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        if ($fieldValue->type === Constants\CustomField::TYPE_SELECT) {
            $translatedValue = $this->translateSelectValue($fieldValue, $accountId, $languageId);
            if ($translatedValue !== null) {
                $fieldValue = $fieldValue->withTextValue($translatedValue);
            }
        }
        return $this->render($fieldValue, $lotItemId);
    }

    /**
     * Return translation of custom field select value
     *
     * @param LotCustomFieldRenderValue $fieldValue
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    protected function translateSelectValue(
        LotCustomFieldRenderValue $fieldValue,
        ?int $accountId = null,
        ?int $languageId = null
    ): ?string {
        $custParamLangKey = $this->getLotCustomFieldTranslationManager()->makeKeyForParameters($fieldValue->name);
        $parameters = $this->getTranslator()->translate($custParamLangKey, 'customfields', $accountId, $languageId);
        $parameters = $parameters !== '' ? $parameters : $fieldValue->parameters;
        $keys = array_map('trim', explode(',', $fieldValue->parameters));
        $values = array_map('trim', explode(',', $parameters));
        if (count($keys) === count($values)) {
            $comb = array_combine($keys, $values);
        } else {
            $comb = array_combine($keys, $keys);
        }
        return $comb[$fieldValue->textValue] ?? null;
    }

    /**
     * @param LotCustomFieldRenderValue $fieldValue
     * @param int $lotItemId
     * @return string
     */
    protected function renderFileLinks(LotCustomFieldRenderValue $fieldValue, int $lotItemId): string
    {
        $output = '';
        $fileNames = array_filter(explode('|', $fieldValue->textValue));
        if (count($fileNames)) {
            foreach ($fileNames as $fileName) {
                $output .= $this->makeFileLink($fileName, $lotItemId, $fieldValue->fieldId) . "<br />";
            }
        }
        return $output;
    }

    /**
     * @param string $fileName
     * @param int $lotItemId
     * @param int $lotCustomFieldId
     * @return string
     */
    public function makeFileLink(string $fileName, int $lotItemId, int $lotCustomFieldId): string
    {
        if (
            !$fileName
            || !$lotItemId
            || !$lotCustomFieldId
        ) {
            return '';
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $url = $this->getUrlBuilder()->build(
            LotCustomFieldFileUrlConfig::new()->forWeb($lotItemId, $lotCustomFieldId, $fileName)
        );

        $output = "<a class=\"custom_file custom_file_{$extension}\" href=\"{$url}\" title=\"{$fileName}\">";
        $output .= '<span class="custfile">';
        $output .= $fileName;
        $output .= '</span></a>';
        return $output;
    }
}
