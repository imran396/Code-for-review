<?php
/**
 * SAM-5151: Invoice generation and reverse proxy timeout improvements
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           25.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate;

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
use Sam\Invoice\Legacy\Generate\Chunk\Internal\Limit\LimitConfig;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\InvoiceListForm\Report\Storage\InvoiceGenerationNativeSessionStorageCreateTrait;
use Sam\Invoice\Legacy\Generate\Chunk\LegacyInvoiceChunkGeneratorCreateTrait;

class LegacyInvoiceSequentialGenerationManager extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AdminTranslatorAwareTrait;
    use ApplicationAccessCheckerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use LegacyInvoiceChunkGeneratorCreateTrait;
    use InvoiceGenerationNativeSessionStorageCreateTrait;
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
     * @return LegacyInvoiceSequentialGenerationManager
     */
    public function clearReport(): LegacyInvoiceSequentialGenerationManager
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
     * @return LegacyInvoiceSequentialGenerationManager
     */
    public function generate(
        int $editorUserId,
        int $systemAccountId,
        ?int $filterAccountId,
        ?int $winningUserId,
        ?int $winningAuctionId,
        string $startDateTimeSysIso,
        string $endDateTimeSysIso,
        bool $isInit
    ): LegacyInvoiceSequentialGenerationManager {
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

        $invoiceChunkGenerator = $this->createLegacyInvoiceChunkGenerator();
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
            $limitConfig
        );

        // storing current call values:
        $this->hasRemaining = $result->hasRemaining;
        $this->invoiceIds = $result->invoiceIds;

        $lastCallReport = $invoiceChunkGenerator->getReport($isAccountFiltering, $result);
        $this->renderReportAndStoreIntoSession($lastCallReport);

        if (!$this->hasRemaining) {
            if (!$this->getTotalGenerated()) {
                $this->clearReport();
            }
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
                        AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::A_AUCTIONS_INVOICE, $winningAuctionId)
                    );
                } else {
                    $redirectUrl = $this->getUrlBuilder()->build(
                        ZeroParamUrlConfig::new()->forRedirect(Constants\Url::A_INVOICES_LIST)
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
    protected function renderReportAndStoreIntoSession(string $data): LegacyInvoiceSequentialGenerationManager
    {
        $sessionStorage = $this->createInvoiceGenerationNativeSessionStorage();
        $sessionStorage->addTotal(count($this->invoiceIds));

        if ($data) {
            $sessionStorage->addReport($data);
        }

        if (!$this->hasRemaining) {
            $totalCount = $this->getTotalGenerated();
            $langReportDone = $this->translate("invoice_generation.report.zero_invoice_generated_in_total");
            if ($totalCount === 1) {
                $langReportDone = $this->translate("invoice_generation.report.one_invoice_generated_in_total");
            } elseif ($totalCount > 1) {
                $langReportDone = $this->translate("invoice_generation.report.multiple_invoice_generated_in_total", ['count' => $totalCount]);
            }
            $sessionStorage->addReport(sprintf('<b>%s</b><br/>', $langReportDone));
        }
        return $this;
    }

    protected function isAccountFiltering(int $editorUserId, int $systemAccountId): bool
    {
        return $this->createApplicationAccessChecker()
            ->isCrossDomainAdminOnMainAccountForMultipleTenant($editorUserId, $systemAccountId, true);
    }

    protected function translate(string $key, array $params = [], string $domain = 'admin_invoice_list'): string
    {
        return $this->getAdminTranslator()->trans($key, $params, $domain);
    }
}
