<?php
/**
 * SAM-9373: Refactor play sound to avoid client side caching of stale files
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-19, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sound\LiveSale\Path\Detect;

use Sam\Core\Constants;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Sound\LiveSale\Path\LiveSaleSoundFilePathResolverCreateTrait;

/**
 * Class LiveSaleSoundFilePathDetector
 * @package Sam\Sound\LiveSale\Path\Detect
 */
class LiveSaleSoundFilePathDetector extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LiveSaleSoundFilePathResolverCreateTrait;
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

    public function detect(int $soundId, int $accountId): string
    {
        $fileName = $this->detectFileName($soundId, $accountId);
        $fileRootPath = $this->detectFileRootPathForExistingFile($fileName, $accountId);
        return $fileRootPath;
    }

    /**
     * Detect sound file name by id for account.
     * @param int $soundId
     * @param int $accountId
     * @return string
     */
    protected function detectFileName(int $soundId, int $accountId): string
    {
        $sm = $this->getSettingsManager();
        switch ($soundId) {
            case Constants\SettingSound::PLACE_BID:
                $placeBidSound = $sm->get(Constants\Setting::PLACE_BID_SOUND, $accountId);
                return $placeBidSound !== ''
                    ? $placeBidSound
                    : $this->cfg()->get('core->rtb->sound->placeBid');

            case Constants\SettingSound::BID_ACCEPTED:
                $bidAcceptedSound = $sm->get(Constants\Setting::BID_ACCEPTED_SOUND, $accountId);
                return $bidAcceptedSound !== ''
                    ? $bidAcceptedSound
                    : $this->cfg()->get('core->rtb->sound->bidAccepted');

            case Constants\SettingSound::USER_OUTBID:
                $outBidSound = $sm->get(Constants\Setting::OUT_BID_SOUND, $accountId);
                return $outBidSound !== ''
                    ? $outBidSound
                    : $this->cfg()->get('core->rtb->sound->outBid');

            case Constants\SettingSound::LOT_SOLD_NOT_WON:
                $soldNotWonSound = $sm->get(Constants\Setting::SOLD_NOT_WON_SOUND, $accountId);
                return $soldNotWonSound !== ''
                    ? $soldNotWonSound
                    : $this->cfg()->get('core->rtb->sound->soldNotWon');

            case Constants\SettingSound::LOT_SOLD_WON:
                $soldWonSound = $sm->get(Constants\Setting::SOLD_WON_SOUND, $accountId);
                return $soldWonSound !== ''
                    ? $soldWonSound
                    : $this->cfg()->get('core->rtb->sound->soldWon');

            case Constants\SettingSound::LOT_PASSED:
                $passedSound = $sm->get(Constants\Setting::PASSED_SOUND, $accountId);
                return $passedSound !== ''
                    ? $passedSound
                    : $this->cfg()->get('core->rtb->sound->passed');

            case Constants\SettingSound::FAIR_WARNING:
                $fairWarningSound = $sm->get(Constants\Setting::FAIR_WARNING_SOUND, $accountId);
                return $fairWarningSound !== ''
                    ? $fairWarningSound
                    : $this->cfg()->get('core->rtb->sound->fairWarning');

            case Constants\SettingSound::ONLINE_BID_INCOMING_ON_ADMIN:
                $onlineBidIncomingOnAdminSound = $sm->get(Constants\Setting::ONLINE_BID_INCOMING_ON_ADMIN_SOUND, $accountId);
                return $onlineBidIncomingOnAdminSound !== ''
                    ? $onlineBidIncomingOnAdminSound
                    : $this->cfg()->get('core->rtb->sound->onlineBidIncomingOnAdmin');

            case Constants\SettingSound::ENABLE_PLAY:
                return $this->cfg()->get('core->rtb->sound->enablePlay');

            case Constants\SettingSound::BID:
                $bidSound = $sm->get(Constants\Setting::BID_SOUND, $accountId);
                return $bidSound ?: $this->cfg()->get('core->rtb->sound->bid');
        }

        return '';
    }

    /**
     * @param string $fileName
     * @param int $accountId
     * @return string
     */
    protected function detectFileRootPathForExistingFile(string $fileName, int $accountId): string
    {
        $resultFileRootPath = '';
        // Internal File
        if (!preg_match('/^(http|ftp):\/\//', $fileName)) {
            $liveSaleSoundFilePathResolver = $this->createLiveSaleSoundFilePathResolver();
            $resultFileRootPath = $liveSaleSoundFilePathResolver->exist($accountId, $fileName)
                ? $liveSaleSoundFilePathResolver->makeFileRootPath($accountId, $fileName)
                : $this->detectDefaultFileRootPath($fileName);
        }
        if (!$resultFileRootPath) {
            log_error("Failed trying to get sound {$fileName} does not exist");
        }
        return $resultFileRootPath;
    }

    /**
     * @param string $fileName
     * @return string
     */
    protected function detectDefaultFileRootPath(string $fileName): string
    {
        $liveSaleSoundFilePathResolver = $this->createLiveSaleSoundFilePathResolver();
        return $liveSaleSoundFilePathResolver->existDefault($fileName)
            ? $liveSaleSoundFilePathResolver->makeDefaultFileRootPath($fileName)
            : '';
    }
}
