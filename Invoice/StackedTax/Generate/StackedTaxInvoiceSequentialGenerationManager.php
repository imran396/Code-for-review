<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 * SAM-5151: Invoice generation and reverse proxy timeout improvements
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate;

use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InvoiceListFormConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\StackedTax\Generate\Chunk\Internal\Limit\LimitConfig;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\InvoiceListForm\Report\Storage\InvoiceGenerationNativeSessionStorageCreateTrait;
use Sam\Invoice\StackedTax\Generate\Chunk\StackedTaxInvoiceChunkGeneratorCreateTrait;

/**
 * Class InvoiceSequentialGenerationManager
 * @package Sam\Invoice\StackedTax\Generate
 */
class StackedTaxInvoiceSequentialGenerationManager extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AdminTranslatorAwareTrait;
    use ApplicationAccessCheckerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use InvoiceGenerationNativeSessionStorageCreateTrait;
    use StackedTaxInvoiceChunkGeneratorCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * @var bool
     */
    private bool $hasRemaining = false;

    /**
     * @var int[]
     */
    private array $invoiceIds = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Used to clear report data
     *
     * @return StackedTaxInvoiceSequentialGenerationManager
     */
    public function clearReport(): StackedTaxInvoiceSequentialGenerationManager
    {
        $reportStorage = $this->createInvoiceGenerationNativeSessionStorage();
        $reportStorage->deleteReport();
        $reportStorage->deleteTotal();
        return $this;
    }

    /**
     * Generate report. Should be called once per manager instance
     *
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param int|null $filterAccountId
     * @param int|null $winningUserId
     * @param int|null $winningAuctionId
     * @param string $startDateTimeSysIso
     * @param string $endDateTimeSysIso
     * @param bool $isInit
     * @param string $language
     * @return StackedTaxInvoiceSequentialGenerationManager
     */
    public function generate(
        int $editorUserId,
        int $systemAccountId,
        ?int $filterAccountId,
        ?int $winningUserId,
        ?int $winningAuctionId,
        string $startDateTimeSysIso,
        string $endDateTimeSysIso,
        bool $isInit,
        string $language
    ): StackedTaxInvoiceSequentialGenerationManager {
        if ($isInit) {
            // On first call we should remove previous generation report (if any)
            $this->clearReport();
        }

        if (
            $startDateTimeSysIso === ''
            || $endDateTimeSysIso === ''
        ) {
            // Both dates should be set
            $startDateTimeSysIso = $endDateTimeSysIso = '';
        }

        $accountIds = [];
        $isAccountFiltering = $this->isAccountFiltering($editorUserId, $systemAccountId);
        if ($isAccountFiltering) {
            if ($filterAccountId) {
                $accountIds[] = $filterAccountId;
            } else {
                $accountIds = $this->getAccountLoader()->loadAllIds();
            }
        } else {
            $accountIds[] = $systemAccountId;
        }

        $invoiceChunkGenerator = $this->createStackedTaxInvoiceChunkGenerator();
        $limitConfig = LimitConfig::new()->construct(
            $this->cfg()->get('core->admin->invoice->generator->limitChunkGenerationTime'),
            $this->cfg()->get('core->admin->invoice->generator->memoryLimit')
        );

        $result = $invoiceChunkGenerator->generate(
            $accountIds,
            $editorUserId,
            $winningUserId,
            $startDateTimeSysIso,
            $endDateTimeSysIso,
            $winningAuctionId,
            $limitConfig,
            $language
        );

        // storing current call values:
        $this->hasRemaining = $result->hasRemaining;
        $this->invoiceIds = $result->invoiceIds;

        $lastCallReport = $invoiceChunkGenerator->getReport($isAccountFiltering, $result);
        $this->buildReportAndStoreIntoSession($lastCallReport);

        if ($result->hasErrorThatMustStopInvoiceGeneration()) {
            log_debug('Invoice generation stopped due to error');
            $this->hasRemaining = false; // Do not continue invoice generation requests
            return $this;
        }

        if (
            !$this->hasRemaining
            && !$this->getTotalGenerated()
            && !$result->hasError()
        ) {
            $this->clearReport();
        }

        return $this;
    }

    public function produceResponsePayload(string $sourcePage, ?int $winningAuctionId): array
    {
        $payload = [
            'hasRemaining' => $this->hasRemaining,
            'invoiceIds' => $this->invoiceIds,
            'report' => $this->createInvoiceGenerationNativeSessionStorage()->getReport(),
        ];

        if (!$this->hasRemaining) {
            // if generation finished - add redirect link
            if ($this->getTotalGenerated()) {
                if ($sourcePage === Constants\Invoice::AUCTION_INVOICE_PAGE) {
                    $redirectUrl = $this->getUrlBuilder()->build(
                        AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::A_AUCTIONS_STACKED_TAX_INVOICE, $winningAuctionId)
                    );
                } else {
                    $redirectUrl = $this->getUrlBuilder()->build(
                        ZeroParamUrlConfig::new()->forRedirect(Constants\Url::A_STACKED_TAX_INVOICE_LIST)
                    );
                }
                $payload['redirectUrl'] = UrlParser::new()->replaceParams(
                    $redirectUrl,
                    [Constants\UrlParam::STATUS => InvoiceListFormConstants::IS_ALL]
                );
            }
        }

        return $payload;
    }

    /**
     * Return total generated invoices in the current session
     *
     * @return int
     */
    protected function getTotalGenerated(): int
    {
        return $this->createInvoiceGenerationNativeSessionStorage()->getTotal();
    }

    /**
     * Add report string for invoices just generated
     * @param string $data
     * @return static
     */
    protected function buildReportAndStoreIntoSession(string $data): StackedTaxInvoiceSequentialGenerationManager
    {
        $sessionStorage = $this->createInvoiceGenerationNativeSessionStorage();
        $sessionStorage->addTotal(count($this->invoiceIds));

        if ($data) {
            $sessionStorage->addReport($data);
        }

        if (!$this->hasRemaining) {
            $totalCount = $this->getTotalGenerated();
            $langReportDone = $this->translate("invoice_generation.report.invoice_generated_in_total", ['invoiceCount' => $totalCount]);
            $sessionStorage->addReport(sprintf('<b>%s</b><br/>', $langReportDone));
        }
        return $this;
    }

    protected function isAccountFiltering(int $editorUserId, int $systemAccountId): bool
    {
        return $this->createApplicationAccessChecker()
            ->isCrossDomainAdminOnMainAccountForMultipleTenant($editorUserId, $systemAccountId, true);
    }

    protected function translate(string $key, array $params = [], string $domain = 'admin_stacked_tax_invoice_list'): string
    {
        return $this->getAdminTranslator()->trans($key, $params, $domain);
    }
}
