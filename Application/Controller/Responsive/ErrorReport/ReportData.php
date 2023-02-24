<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\ErrorReport;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ReportData
 * @package Sam\Application\Controller\Responsive\ErrorReport
 */
class ReportData extends CustomizableClass
{
    public readonly ?string $errorReportFileName;
    public readonly ?int $javascript;
    public readonly ?string $flash;
    public readonly ?string $shortInfo;
    public readonly ?string $browser;
    public readonly ?string $browserVersion;
    public readonly ?string $os;
    public readonly ?string $contactName;
    public readonly ?string $contactEmail;
    public readonly ?string $contactPhone;
    public readonly ?string $userAgent;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?string $errorReportFileName,
        ?int $javascript,
        ?string $flash,
        ?string $shortInfo,
        ?string $browser,
        ?string $browserVersion,
        ?string $userAgent,
        ?string $os,
        ?string $contactName,
        ?string $contactEmail,
        ?string $contactPhone
    ): static {
        $this->errorReportFileName = $errorReportFileName;
        $this->javascript = $javascript;
        $this->flash = $flash;
        $this->shortInfo = $shortInfo;
        $this->browser = $browser;
        $this->browserVersion = $browserVersion;
        $this->os = $os;
        $this->contactName = $contactName;
        $this->contactEmail = $contactEmail;
        $this->contactPhone = $contactPhone;
        $this->userAgent = $userAgent;
        return $this;
    }
}
