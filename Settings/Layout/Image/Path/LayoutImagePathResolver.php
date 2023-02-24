<?php
/**
 * SAM-9458: Path resolving for logo images of pages layout
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Layout\Image\Path;

use Sam\Core\Constants;
use Sam\Core\Path\PathResolver;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class LayoutImagePathResolver
 * @package Sam\Settings\Layout\Image\Path
 */
class LayoutImagePathResolver extends CustomizableClass
{
    use PathResolverCreateTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Page Header logo ---

    /**
     * @param int $accountId
     * @return string
     * #[Pure]
     */
    public function makePageHeaderLogoPath(int $accountId): string
    {
        return sprintf('%s/%s', PathResolver::UPLOAD_SETTING, $accountId);
    }

    public function makePageHeaderLogoFilePath(int $accountId, string $fileName): string
    {
        return sprintf('%s/%s', $this->makePageHeaderLogoPath($accountId), $fileName);
    }

    public function makePageHeaderLogoRootPath(int $accountId): string
    {
        return sprintf('%s%s', $this->path()->sysRoot(), $this->makePageHeaderLogoPath($accountId));
    }

    public function makePageHeaderLogoFileRootPath(int $accountId, string $fileName): string
    {
        return sprintf('%s%s', $this->path()->sysRoot(), $this->makePageHeaderLogoFilePath($accountId, $fileName));
    }

    /**
     * Check, if page header logo is registered in system parameters for account
     * @param int $accountId
     * @return bool
     */
    public function hasPageHeaderLogo(int $accountId): bool
    {
        return $this->detectPageHeaderLogoFileName($accountId) !== '';
    }

    public function detectPageHeaderLogoFileName(int $accountId): string
    {
        return (string)$this->getSettingsManager()->get(Constants\Setting::PAGE_HEADER, $accountId);
    }

    public function detectPageHeaderLogoFilePath(int $accountId): string
    {
        return $this->makePageHeaderLogoFilePath($accountId, $this->detectPageHeaderLogoFileName($accountId));
    }

    public function detectPageHeaderLogoFileRootPath(int $accountId): string
    {
        return $this->makePageHeaderLogoFileRootPath($accountId, $this->detectPageHeaderLogoFileName($accountId));
    }

    // --- Invoice logo ---

    /**
     * @param int $accountId
     * @return string
     * #[Pure]
     */
    public function makeInvoiceLogoPath(int $accountId): string
    {
        return sprintf('%s/%s', PathResolver::UPLOAD_SETTING, $accountId);
    }

    public function makeInvoiceLogoFilePath(int $accountId, string $fileName): string
    {
        return sprintf('%s/%s', $this->makeInvoiceLogoPath($accountId), $fileName);
    }

    public function makeInvoiceLogoRootPath(int $accountId): string
    {
        return sprintf('%s%s', $this->path()->sysRoot(), $this->makeInvoiceLogoPath($accountId));
    }

    public function makeInvoiceLogoFileRootPath(int $accountId, string $fileName): string
    {
        return sprintf('%s%s', $this->path()->sysRoot(), $this->makeInvoiceLogoFilePath($accountId, $fileName));
    }

    /**
     * Check, if invoice logo is registered in system parameters for account
     * @param int $accountId
     * @return bool
     */
    public function hasInvoiceLogo(int $accountId): bool
    {
        return $this->detectInvoiceLogoFileName($accountId) !== '';
    }

    public function detectInvoiceLogoFileName(int $accountId): string
    {
        return (string)$this->getSettingsManager()->get(Constants\Setting::INVOICE_LOGO, $accountId);
    }

    public function detectInvoiceLogoFilePath(int $accountId): string
    {
        return $this->makeInvoiceLogoFilePath($accountId, $this->detectInvoiceLogoFileName($accountId));
    }

    public function detectInvoiceLogoFileRootPath(int $accountId): string
    {
        return $this->makeInvoiceLogoFileRootPath($accountId, $this->detectInvoiceLogoFileName($accountId));
    }

    // --- Settlement logo ---

    /**
     * @param int $accountId
     * @return string
     * #[Pure]
     */
    public function makeSettlementLogoPath(int $accountId): string
    {
        return sprintf('%s/%s', PathResolver::UPLOAD_SETTING, $accountId);
    }

    public function makeSettlementLogoFilePath(int $accountId, string $fileName): string
    {
        return sprintf('%s/%s', $this->makeSettlementLogoPath($accountId), $fileName);
    }

    public function makeSettlementLogoRootPath(int $accountId): string
    {
        return sprintf('%s%s', $this->path()->sysRoot(), $this->makeSettlementLogoPath($accountId));
    }

    public function makeSettlementLogoFileRootPath(int $accountId, string $fileName): string
    {
        return sprintf('%s%s', $this->path()->sysRoot(), $this->makeSettlementLogoFilePath($accountId, $fileName));
    }

    /**
     * Check, if settlement logo is registered in system parameters for account
     * @param int $accountId
     * @return bool
     */
    public function hasSettlementLogo(int $accountId): bool
    {
        return $this->detectSettlementLogoFileName($accountId) !== '';
    }

    public function detectSettlementLogoFileName(int $accountId): string
    {
        return (string)$this->getSettingsManager()->get(Constants\Setting::SETTLEMENT_LOGO, $accountId);
    }

    public function detectSettlementLogoFilePath(int $accountId): string
    {
        return $this->makeSettlementLogoFilePath($accountId, $this->detectSettlementLogoFileName($accountId));
    }

    public function detectSettlementLogoFileRootPath(int $accountId): string
    {
        return $this->makeSettlementLogoFileRootPath($accountId, $this->detectSettlementLogoFileName($accountId));
    }
}
