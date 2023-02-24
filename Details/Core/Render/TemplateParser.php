<?php
/**
 * Template parser base
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core\Render;

use Currency;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Details\Core\ConfigManagerAwareTrait;
use Sam\Details\Core\Placeholder\Placeholder;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\User\Access\LotAccessCheckerAwareTrait;

/**
 * Class TemplateParser
 * @package Sam\Details
 */
abstract class TemplateParser extends CustomizableClass implements TemplateParserInterface
{
    use ConfigManagerAwareTrait;
    use CurrencyLoaderAwareTrait;
    use LotAccessCheckerAwareTrait;
    use SystemAccountAwareTrait;
    use UserAwareTrait;

    /**
     * @var Currency|null
     */
    protected ?Currency $conversionCurrency = null;
    /**
     * @var int|null
     */
    protected ?int $conversionCurrencyId = null;
    /**
     * @var EscapingToolInterface|null
     */
    protected ?EscapingToolInterface $escapingTool = null;
    /**
     * @var Filterer|null
     */
    protected ?Filterer $filterer = null;
    /**
     * @var int|null
     */
    protected ?int $languageId = null;
    /**
     * @var Renderer|null
     */
    protected ?Renderer $renderer = null;
    /**
     * @var bool
     */
    protected bool $shouldHideEmptyFields = true;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Replace placeholders with data in template
     * @param Placeholder[] $placeholders
     */
    public function parse(string $template, ?array $placeholders, array $row): string
    {
        /** @var Placeholder[] $beginEndPlaceholders */
        $beginEndPlaceholders = [];
        foreach ($placeholders ?: [] as $placeholder) {
            if ($placeholder->getType() === Constants\Placeholder::BEGIN_END) {
                $beginEndPlaceholders[] = $placeholder;
                continue;
            }

            if ($placeholder->getType() === Constants\Placeholder::COMPOSITE) {
                [$value, $isAvailable] = $this->produceCompositeValue($placeholder, $row);
            } else {
                [$value, $isAvailable] = $this->produceValue($placeholder, $row);
            }
            $value = $isAvailable ? $value : $this->getRenderer()->translate('GENERAL_NOT_AVAILABLE', 'general');
            $value = $this->escape($value);
            $template = $this->replaceInTemplate($template, $placeholder, $value);
        }
        // BEGIN-END placeholders should be processed last
        foreach ($beginEndPlaceholders as $placeholder) {
            $subPlaceholders = $placeholder->getSubPlaceholders();
            [$value,] = $this->produceValue($subPlaceholders[0], $row);
            $template = $this->replaceInTemplateBeginEndPlaceholders($template, $placeholder, $value);
        }
        return $template;
    }

    /**
     * {@inheritDoc}
     */
    public function escape($value): string
    {
        $escapingTool = $this->getEscapingTool();
        if ($escapingTool) {
            $value = $escapingTool->escape($value);
        }
        return $value;
    }

    public function getEscapingTool(): ?EscapingToolInterface
    {
        return $this->escapingTool;
    }

    public function setEscapingTool(EscapingToolInterface $escapingTool): static
    {
        $this->escapingTool = $escapingTool;
        return $this;
    }

    /**
     * Produce value for composite placeholder
     * Should be first non-empty value among sub-placeholders of composite placeholder
     */
    protected function produceCompositeValue(Placeholder $placeholder, array $row): array
    {
        $value = '';
        $isAvailable = false;
        foreach ($placeholder->getSubPlaceholders() as $subPlaceholder) {
            [$valueProduced, $isAvailable] = $this->produceValue($subPlaceholder, $row);
            if ($valueProduced !== '') {
                $value = $valueProduced;
                break;
            }
        }
        return [$value, $isAvailable];
    }

    /**
     * Produce value for simple (not composite) placeholder
     * @return array [<value>, <is available>]
     */
    protected function produceValue(Placeholder $placeholder, array $row): array
    {
        $type = $placeholder->getType();
        if ($type === Constants\Placeholder::INLINE_TEXT) {
            $value = $this->produceValueForInlineTextPlaceholder($placeholder);
            return [$value, true];
        }

        $value = '';
        $isAvailable = $this->getConfigManager()->getOption($placeholder->getKey(), 'available');
        if ($isAvailable) {
            if ($type === Constants\Placeholder::DATE) {
                $value = $this->produceValueForDatePlaceholder($placeholder, $row);
            } elseif ($type === Constants\Placeholder::AUCTION_CUSTOM_FIELD) {
                $value = $this->produceValueForCustomFieldPlaceholder($placeholder, $row);
            } elseif ($type === Constants\Placeholder::LOT_CUSTOM_FIELD) {
                $isAvailable = $this->hasAccessToLotCustomField($placeholder, $row);
                if ($isAvailable) {
                    $value = $this->produceValueForCustomFieldPlaceholder($placeholder, $row);
                }
            } elseif ($type === Constants\Placeholder::LANG_LABEL) {
                $value = $this->produceValueForLangLabelPlaceholder($placeholder);
            } elseif ($type === Constants\Placeholder::MONEY) {
                $value = $this->produceValueForMoneyPlaceholder($placeholder, $row);
            } elseif ($type === Constants\Placeholder::DATE_ADDITIONAL) {
                $value = $this->produceValueForDateAdditionalPlaceholder($placeholder, $row);
            } elseif ($type === Constants\Placeholder::INDEXED_ARRAY) {
                $value = $this->produceValueForIndexedArrayPlaceholder($placeholder, $row);
            } elseif ($type === Constants\Placeholder::BOOLEAN) {
                $value = $this->produceValueForBooleanPlaceholder($placeholder, $row);
            } else { // if (in_array($type, [Constants\Placeholder::REGULAR, Constants\Placeholder::URL, Constants\Placeholder::INDEXED_ARRAY])) {
                $value = $this->produceValueForRegularPlaceholder($placeholder, $row);
            }
            $value = $this->getFilterer()->apply($value, $placeholder);
        }
        return [$value, $isAvailable];
    }

    protected function produceValueForBooleanPlaceholder(Placeholder $placeholder, array $row): string
    {
        return ''; // Not implemented
    }

    protected function produceValueForCustomFieldPlaceholder(Placeholder $placeholder, array $row): string
    {
        return $this->getRenderer()->renderAsIs($row, $placeholder->getKey());
    }

    /**
     * Check if current user has access permission to read lot custom field data
     */
    protected function hasAccessToLotCustomField(Placeholder $placeholder, array $row): bool
    {
        if (!$row) {
            log_errorBackTrace("Input data row is empty");
            return false;
        }

        $lotCustomField = $this->getConfigManager()->getLotCustomFieldByPlaceholderKey($placeholder->getKey());
        if (!$lotCustomField) {
            return false;
        }

        $accountId = (int)$row['account_id'];
        $auctionId = (int)$row['auction_id'];
        $consignorUserId = (int)$row['consignor_id'];
        return $this->getLotAccessChecker()->isAccess(
            $lotCustomField->Access,
            $this->getUserId(),
            $accountId,
            $auctionId,
            $consignorUserId
        );
    }

    protected function produceValueForDateAdditionalPlaceholder(Placeholder $placeholder, array $row): string
    {
        return $this->getRenderer()->renderDateAdditional($row, $placeholder->getKey());
    }

    protected function produceValueForDatePlaceholder(Placeholder $placeholder, array $row): string
    {
        $format = $placeholder->getOptionValue("fmt") ?? '';
        return $this->getRenderer()->renderDate($row, $placeholder->getKey(), $format);
    }

    protected function produceValueForInlineTextPlaceholder(Placeholder $placeholder): string
    {
        return $placeholder->getView();
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    protected function produceValueForIndexedArrayPlaceholder(Placeholder $placeholder, array $row): string
    {
        return $placeholder->getView();
    }

    protected function produceValueForLangLabelPlaceholder(Placeholder $placeholder): string
    {
        [$fieldKey, $section] = $this->getConfigManager()->getLangLabelInfo($placeholder->getKey());
        return $this->getRenderer()->translate($fieldKey, $section);
    }

    protected function produceValueForMoneyPlaceholder(Placeholder $placeholder, array $row): string
    {
        return $this->getRenderer()->renderMoneyValue($row, $placeholder->getKey(), $this->getConversionCurrency());
    }

    protected function produceValueForRegularPlaceholder(Placeholder $placeholder, array $row): string
    {
        return $this->getRenderer()->renderAsIs($row, $placeholder->getKey());
    }

    /**
     * Replace placeholder
     */
    protected function replaceInTemplate(string $template, Placeholder $placeholder, string $value): string
    {
        return str_replace($placeholder->getDecoratedView(), $value, $template);
    }

    /**
     * Replace BEGIN-END blocks decoration
     */
    protected function replaceInTemplateBeginEndPlaceholders(string $template, Placeholder $placeholder, string $value): string
    {
        $key = $placeholder->getKey();
        $isLogicalNot = $placeholder->getOptionValue(Constants\Placeholder::OPTION_BEGIN_END_LOGICAL_NOT) ?? false;
        $beginKey = $this->getConfigManager()->makeBeginKey($key, $isLogicalNot);
        $beginKeyDecorated = Placeholder::decorateView($beginKey);
        $endKey = $this->getConfigManager()->makeEndKey($key, $isLogicalNot);
        $endKeyDecorated = Placeholder::decorateView($endKey);
        if (
            ($value !== '' && !$isLogicalNot)
            || ($value === '' && $isLogicalNot)
            || !$this->shouldHideEmptyFields
        ) {
            $template = str_replace([$beginKeyDecorated, $endKeyDecorated], '', $template);
        } else {
            // $template = preg_replace("/\{" . $beginKey . "(.*)" . $endKey . "\}/s", '', $template);
            $beginKeyPos = strpos($template, $beginKeyDecorated);
            $endKeyPos = strpos($template, $endKeyDecorated);
            if (
                $beginKeyPos !== false
                && $endKeyPos !== false
                && $beginKeyPos < $endKeyPos
            ) {
                $before = substr($template, 0, $beginKeyPos);
                $after = substr($template, $endKeyPos + strlen($endKeyDecorated));
                $template = $before . $after;
            }
        }
        return $template;
    }

    public function getConversionCurrency(): ?Currency
    {
        if (
            !$this->conversionCurrency instanceof \Currency
            && $this->conversionCurrencyId
        ) {
            $this->conversionCurrency = $this->getCurrencyLoader()->load($this->conversionCurrencyId);
        }
        return $this->conversionCurrency;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setConversionCurrency(Currency $currency): static
    {
        $this->conversionCurrency = $currency;
        return $this;
    }

    public function setConversionCurrencyId(?int $currencyId): static
    {
        $this->conversionCurrencyId = $currencyId;
        return $this;
    }

    public function enableHideEmptyFields(bool $enabled): static
    {
        $this->shouldHideEmptyFields = $enabled;
        return $this;
    }

    public function getFilterer(): Filterer
    {
        if ($this->filterer === null) {
            $this->filterer = Filterer::new();
        }
        return $this->filterer;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setFilterer(Filterer $filterer): static
    {
        $this->filterer = $filterer;
        return $this;
    }

    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    public function setLanguageId(?int $languageId): static
    {
        $this->languageId = Cast::toInt($languageId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    public function getRenderer(): Renderer
    {
        if ($this->renderer === null) {
            throw new InvalidArgumentException('Renderer not defined');
        }
        return $this->renderer;
    }

    public function setRenderer(Renderer $renderer): static
    {
        $this->renderer = $renderer;
        return $this;
    }
}
