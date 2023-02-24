<?php
/**
 * This is web application dependent wrapper for generating verification code and send email to user.
 * It accesses to web request context to get additional data.
 *
 * SAM-6801: Improve email verification for v3.5 - refactor service and add unit tests
 * SAM-5327: Refactor verification email module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\Verify\Prepare;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SignupVerificationWebPreparer
 * @package Sam\User\Signup\Verify\Prepare
 */
class SignupVerificationWebPreparer extends CustomizableClass
{
    use ParamFetcherForGetAwareTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Perform actions for asking user via email for verification code
     * @param int $targetUserId
     * @param int $systemAccountId
     * @param int $editorUserId
     */
    public function requireVerification(int $targetUserId, int $systemAccountId, int $editorUserId): void
    {
        $backUrl = $this->getParamFetcherForGet()->getBackUrl()
            ?: $this->getServerRequestReader()->currentUrl();
        $sale = $this->getParamFetcherForGet()->getIntPositive(Constants\UrlParam::SALE)
            ?: $this->getParamFetcherForGet()->getIntPositive(Constants\UrlParam::SALE_REG);
        $preparer = SignupVerificationPreparer::new()->construct(
            $targetUserId,
            $systemAccountId,
            [
                SignupVerificationPreparer::OP_BACK_PAGE_PARAM_URL => $backUrl,
                SignupVerificationPreparer::OP_SALE => $sale,
            ]
        );
        $preparer->requireVerification($editorUserId);
    }
}
