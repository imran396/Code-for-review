<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Printing;

use DateTime;
use Sam\Application\Url\Build\Config\Settlement\SettlementCheckDownloadUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settlement\Check\Load\SettlementCheckLoaderCreateTrait;
use Sam\Settlement\Check\Printing\Internal\Render\CheckFieldRendererFactoryCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class SettlementCheckPrintingContentRenderer
 * @package Sam\Settlement\Check
 */
class SettlementCheckPrintingContentRenderer extends CustomizableClass
{
    use CheckFieldRendererFactoryCreateTrait;
    use DateHelperAwareTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use SettlementCheckLoaderCreateTrait;
    use UrlBuilderAwareTrait;

    // HTML layout template constants
    protected const BACKGROUND_IMG_HTML_TPL = <<<HTML
<img class="stlm-check-image" src="%imageUrl%" alt="">
HTML;

    protected const PAGEBREAK_HTML_TPL = <<<HTML
<div class="pagebreak"></div>
HTML;

    protected const BODY_HTML_TPL = <<<HTML
<div class="stlm-check-container" style="min-height:%checkHeight%px">
    %printedOnDate%
    %payee%
    %amountFormatted%
    %amountSpelling%
    %memo%
    %addressFormatted%
</div>
HTML;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(array $settlementCheckIds, int $accountId): string
    {
        $output = $this->renderCheckBackground($accountId);

        $checksContent = $this->renderChecks($settlementCheckIds, $accountId);
        $perPage = (int)$this->getSettingsManager()->get(Constants\Setting::STLM_CHECK_PER_PAGE, $accountId);
        $lastIndex = array_key_last($checksContent);
        foreach ($checksContent as $checkIndex => $checkContent) {
            $output .= $checkContent;
            $output .= $this->renderPageBreak($checkIndex, $lastIndex, $perPage);
        }
        return $output;
    }

    protected function renderCheckBackground(int $accountId): string
    {
        $checkFile = $this->getSettingsManager()->get(Constants\Setting::STLM_CHECK_FILE, $accountId);
        if ($checkFile) {
            $imageUrl = $this->getUrlBuilder()->build(
                SettlementCheckDownloadUrlConfig::new()->forWeb($accountId)
            );
            return str_replace('%imageUrl%', $imageUrl, self::BACKGROUND_IMG_HTML_TPL);
        }
        return '';
    }

    protected function renderPageBreak(int $checkIndex, int $lastIndex, int $perPage): string
    {
        if (
            ($checkIndex + 1) % $perPage === 0
            && $checkIndex !== $lastIndex
        ) {
            return self::PAGEBREAK_HTML_TPL;
        }
        return '';
    }

    protected function renderChecks(array $settlementCheckIds, int $accountId): array
    {
        $nameFieldRenderer = $this->createCheckFieldRendererFactory()->create(
            'name',
            Constants\Setting::STLM_CHECK_NAME_COORD_X,
            Constants\Setting::STLM_CHECK_NAME_COORD_Y,
            $accountId
        );
        $dateFieldRender = $this->createCheckFieldRendererFactory()->create(
            'date',
            Constants\Setting::STLM_CHECK_DATE_COORD_X,
            Constants\Setting::STLM_CHECK_DATE_COORD_Y,
            $accountId
        );
        $amountFieldRenderer = $this->createCheckFieldRendererFactory()->create(
            'amount',
            Constants\Setting::STLM_CHECK_AMOUNT_COORD_X,
            Constants\Setting::STLM_CHECK_AMOUNT_COORD_Y,
            $accountId
        );
        $amountSpellingFieldRenderer = $this->createCheckFieldRendererFactory()->create(
            'amount-spelling',
            Constants\Setting::STLM_CHECK_AMOUNT_SPELLING_COORD_X,
            Constants\Setting::STLM_CHECK_AMOUNT_SPELLING_COORD_Y,
            $accountId
        );
        $memoFieldRenderer = $this->createCheckFieldRendererFactory()->create(
            'memo',
            Constants\Setting::STLM_CHECK_MEMO_COORD_X,
            Constants\Setting::STLM_CHECK_MEMO_COORD_Y,
            $accountId
        );
        $addressFieldRenderer = $this->createCheckFieldRendererFactory()->create(
            'address',
            Constants\Setting::STLM_CHECK_ADDRESS_COORD_X,
            Constants\Setting::STLM_CHECK_ADDRESS_COORD_Y,
            $accountId
        );

        $sm = $this->getSettingsManager();
        $settlementChecks = $this->createSettlementCheckLoader()->yieldByIds($settlementCheckIds, true);
        $checkHeight = (int)$sm->get(Constants\Setting::STLM_CHECK_HEIGHT, $accountId);
        $repeatCount = (int)$sm->get(Constants\Setting::STLM_CHECK_REPEAT_COUNT, $accountId);

        $checksContent = [];
        foreach ($settlementChecks as $checkIndex => $check) {
            $printedOnDateFormatted = $this->formatPrintedOnDate($check->PrintedOn, $accountId);
            $amountFormatted = $this->formatAmount($check->Amount, $accountId);
            $addressFormatted = $this->formatAddress($check->Address);
            $content = '';
            for ($repeatIndex = 0; $repeatIndex < $repeatCount; $repeatIndex++) {
                $positionIndex = $checkIndex * $repeatIndex;
                $content .= strtr(
                    self::BODY_HTML_TPL,
                    [
                        '%checkHeight%' => $checkHeight,
                        '%printedOnDate%' => $dateFieldRender->render($positionIndex, $printedOnDateFormatted),
                        '%payee%' => $nameFieldRenderer->render($positionIndex, $check->Payee),
                        '%amountFormatted%' => $amountFieldRenderer->render($positionIndex, $amountFormatted),
                        '%amountSpelling%' => $amountSpellingFieldRenderer->render($positionIndex, $check->AmountSpelling),
                        '%memo%' => $memoFieldRenderer->render($positionIndex, $check->Memo),
                        '%addressFormatted%' => $addressFieldRenderer->render($positionIndex, $addressFormatted),
                    ],
                );
            }
            $checksContent[$checkIndex] = $content;
        }
        return $checksContent;
    }

    protected function formatAmount(?float $amount, int $accountId): string
    {
        return $this->getNumberFormatter()
            ->constructForSettlement($accountId)
            ->formatMoney($amount);
    }

    protected function formatPrintedOnDate(?DateTime $printedOn, int $accountId): string
    {
        $printedOn = $this->getDateHelper()->convertUtcToSys($printedOn, $accountId);
        return $printedOn ? $printedOn->format(Constants\Date::US_DATE) : '';
    }

    protected function formatAddress(string $address): string
    {
        return nl2br($address);
    }
}
