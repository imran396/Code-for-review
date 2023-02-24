<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/5/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Render\Logo;

use Sam\Application\Url\Build\Config\Image\InvoiceImageUrlConfig;
use Sam\Application\Url\Build\Config\Image\LocationImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Invoice;
use Sam\Invoice\Common\Render\Logo\Internal\Load\DataLoaderCreateTrait;

/**
 * Class InvoiceLogoPathResolver
 * @package
 */
class InvoiceLogoPathResolver extends CustomizableClass
{
    use DataLoaderCreateTrait;
    use PathResolverCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Builds image url exclusively by invoice logo defined per account
     * @param int $accountId
     * @return string
     */
    public function buildInvoiceLogoUrl(int $accountId): string
    {
        if (!$this->createDataLoader()->hasInvoiceLogo($accountId)) {
            return '';
        }

        return $this->getUrlBuilder()->build(
            InvoiceImageUrlConfig::new()->construct($accountId)
        );
    }

    /**
     * Build logo absolute url to render on invoice.
     * It may be defined by location or general invoice logo of account.
     * @param Invoice $invoice
     * @return string
     */
    public function buildUrlByInvoice(Invoice $invoice): string
    {
        $locationImageUrl = $this->buildLocationImageUrlByInvoiceId($invoice->Id);
        if ($locationImageUrl) {
            return $locationImageUrl;
        }

        $invoiceImageUrl = $this->buildInvoiceLogoUrl($invoice->AccountId);
        return $invoiceImageUrl;
    }

    /**
     * @param Invoice $invoice
     * @return string
     */
    public function resolveFileRootPath(Invoice $invoice): string
    {
        $basePath = $this->resolveFileBasePath($invoice);
        $fileRootPath = $this->path()->sysRoot() . $basePath;
        return $fileRootPath;
    }

    /**
     * Check, if invoice logo is registered in system parameters
     * @param array $row
     * @return bool
     */
    protected function isLocationLogoForInvoiceByInvoiceHeaderRow(array $row): bool
    {
        if (
            $row
            && (string)$row['logo'] !== ''
        ) {
            return true;
        }
        return false;
    }

    /**
     * Build url for image defined by location of invoiced auction
     * @param int $invoiceId
     * @return string empty string when absent
     */
    protected function buildLocationImageUrlByInvoiceId(int $invoiceId): string
    {
        $row = $this->createDataLoader()->loadInvoiceHeader($invoiceId);
        if (!$this->isLocationLogoForInvoiceByInvoiceHeaderRow($row)) {
            return '';
        }

        $accountId = (int)$row['account_id'];
        $locationId = (int)$row['id'];
        $locationImageUrl = $this->getUrlBuilder()->build(
            LocationImageUrlConfig::new()->construct($locationId, $accountId)
        );
        return $locationImageUrl;
    }

    // --- Invoice logo file base path resolving logic ---

    /**
     * Search for invoice logo relative base path (is it location's or general invoice's logo) and return it
     * @param Invoice $invoice
     * @return string empty string when no one image found
     */
    protected function resolveFileBasePath(Invoice $invoice): string
    {
        $path = $this->resolveLocationImageFileBasePathByInvoiceId($invoice->Id);
        if ($path) {
            return $path;
        }

        $path = $this->resolveInvoiceImageFileBasePath($invoice->AccountId);
        return $path;
    }

    /**
     * @param int $invoiceId
     * @return string empty string when location image not found
     */
    protected function resolveLocationImageFileBasePathByInvoiceId(int $invoiceId): string
    {
        $row = $this->createDataLoader()->loadInvoiceHeader($invoiceId);
        if (!$this->isLocationLogoForInvoiceByInvoiceHeaderRow($row)) {
            return '';
        }

        $accountId = (int)$row['account_id'];
        $locationId = (int)$row['id'];
        $fileBasePath = LocationImageUrlConfig::new()
            ->construct($locationId, $accountId)
            ->fileBasePath();
        return $fileBasePath;
    }

    /**
     * @param int $accountId
     * @return string empty string when invoice image not found
     */
    protected function resolveInvoiceImageFileBasePath(int $accountId): string
    {
        if (!$this->createDataLoader()->hasInvoiceLogo($accountId)) {
            return '';
        }

        $fileBasePath = InvoiceImageUrlConfig::new()
            ->construct($accountId)
            ->fileBasePath();
        return $fileBasePath;
    }
}
