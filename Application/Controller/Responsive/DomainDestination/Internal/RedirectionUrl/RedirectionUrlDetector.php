<?php
/**
 * SAM-9355: Refactor Domain Detector and Domain Redirector for unit testing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl;

use Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\Load\DataProviderCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class RedirectionUrlDetector
 * @package Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl
 */
class RedirectionUrlDetector extends CustomizableClass
{
    use DataProviderCreateTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Redirecting to url based on domain mode settings.
     * @param int $systemAccountId
     * @return string
     */
    public function detect(int $systemAccountId): string
    {
        $dataProvider = $this->createDataProvider();
        $serverRequestReader = $this->getServerRequestReader();

        $account = $dataProvider->detectAccount($systemAccountId);
        if (!$account) {
            return '';
        }

        $host = $dataProvider->detectDomainDestinationHost($account);
        if (
            !$host
            || $host === $serverRequestReader->httpHost()
        ) {
            return '';
        }

        return sprintf(
            '%s://%s%s',
            $serverRequestReader->scheme(),
            $host,
            $serverRequestReader->requestUri()
        );
    }

}
