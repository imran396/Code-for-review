<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Download\Internal;

use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;

/**
 * Class Response
 * @package Sam\Application\Controller\Responsive\Download\Internal
 * @internal
 */
class Response extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use LocalFileManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function forbidden(): void
    {
        $this->createApplicationRedirector()->forbidden();
    }

    public function notFound(): void
    {
        $this->createApplicationRedirector()->pageNotFound();
    }

    public function outputFile(string $fileName, string $filePath): void
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $this->createLocalFileManager()->getSize($filePath));
        $this->createLocalFileManager()->output($filePath);
    }
}
