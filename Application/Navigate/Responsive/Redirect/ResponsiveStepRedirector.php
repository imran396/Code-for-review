<?php
/**
 * SAM-5546: Auction registration step detection and redirect
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           12-01, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Navigate\Responsive\Redirect;

use Sam\Application\Navigate\Responsive\AuctionRegistration\AuctionRegistrationStepDetector;
use Sam\Application\Navigate\Responsive\AuctionRegistration\AuctionRegistrationStepDetectorCreateTrait;
use Sam\Application\Navigate\Responsive\Login\LoginStepDetector;
use Sam\Application\Navigate\Responsive\Login\LoginStepDetectorCreateTrait;
use Sam\Application\Navigate\Responsive\StepUrlBuilder\StepUrlBuilder;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class AuctionRegistrationStepRedirector
 * @package Sam\Application\Navigate\Responsive\Redirect
 */
class ResponsiveStepRedirector extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use AuctionAwareTrait;
    use AuctionRegistrationStepDetectorCreateTrait;
    use EditorUserAwareTrait;
    use LoginStepDetectorCreateTrait;

    protected array $urlParams = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Process redirecting to correct url.
     * @param bool $useToAuctionRegistration
     */
    public function redirect(bool $useToAuctionRegistration = true): void
    {
        $redirectStep = $this->findRedirectStep($useToAuctionRegistration);
        $redirectUrl = $this->findRedirectUrlByStep($useToAuctionRegistration, $redirectStep);
        $this->createApplicationRedirector()->redirect($redirectUrl);
    }

    /**
     * Get url query parameters
     * @return array
     */
    public function getUrlParams(): array
    {
        return $this->urlParams;
    }

    /**
     * Set url query parameters
     * @param array $urlParams
     * @return static
     */
    public function setUrlParams(array $urlParams): static
    {
        $this->urlParams = $urlParams;
        return $this;
    }

    /**
     * Find correct redirect step.
     * @param bool $useToAuctionRegistration
     * @return int|null
     */
    protected function findRedirectStep(bool $useToAuctionRegistration): ?int
    {
        $stepStatus = $this->getProperStepDetectorService($useToAuctionRegistration)
            ->detect();
        return $stepStatus;
    }

    /**
     * Find correct redirect url for step.
     * @param bool $useToAuctionRegistration
     * @param int|null $redirectStep
     * @return string
     */
    protected function findRedirectUrlByStep(bool $useToAuctionRegistration, ?int $redirectStep): string
    {
        $auctionId = $this->getAuctionId();
        $urlParams = $this->getUrlParams();
        $redirectUrl = StepUrlBuilder::new()
            ->setAuctionId($auctionId)
            ->withParams($urlParams)
            ->build($useToAuctionRegistration, $redirectStep)
            ->getRedirectUrl();
        return $redirectUrl;
    }

    /**
     * @param bool $useToAuctionRegistration
     * @return AuctionRegistrationStepDetector|LoginStepDetector
     */
    protected function getProperStepDetectorService(bool $useToAuctionRegistration): AuctionRegistrationStepDetector|LoginStepDetector
    {
        if ($useToAuctionRegistration) {
            return $this->createAuctionRegistrationStepDetector()
                ->setAuctionId($this->getAuctionId())
                ->setEditorUserId($this->getEditorUserId());
        }
        return $this->createLoginStepDetector()
            ->setAuctionId($this->getAuctionId())
            ->setEditorUserId($this->getEditorUserId());
    }
}
