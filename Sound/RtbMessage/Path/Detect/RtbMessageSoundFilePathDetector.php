<?php
/**
 * Refactor play sound to avoid client side caching of stale files: SAM-9373
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

namespace Sam\Sound\RtbMessage\Path\Detect;

use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Load\RtbMessageLoaderCreateTrait;
use Sam\Sound\RtbMessage\Path\RtbMessageSoundFilePathResolverCreateTrait;

/**
 * Class RtbMessageSoundFilePathDetector
 * @package Sam\Sound\RtbMessage\Path\Detect
 */
class RtbMessageSoundFilePathDetector extends CustomizableClass
{
    use RtbMessageLoaderCreateTrait;
    use RtbMessageSoundFilePathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $messageId
     * @param int $accountId
     * @return string
     */
    public function detect(int $messageId, int $accountId): string
    {
        $fileName = $this->findFileName($messageId, $accountId);
        if ($fileName) {
            return $this->makeFileRootPathForExistingFile($accountId, $fileName);
        }
        return '';
    }

    /**
     * @param int $messageId
     * @param int $accountId
     * @return string
     */
    protected function findFileName(int $messageId, int $accountId): string
    {
        $rtbMessage = $this->createRtbMessageLoader()->load($messageId);
        if (
            $rtbMessage
            && $rtbMessage->AccountId === $accountId
        ) {
            return $rtbMessage->SoundEffect;
        }
        return '';
    }

    /**
     * @param int $accountId
     * @param string $fileName
     * @return string
     */
    protected function makeFileRootPathForExistingFile(int $accountId, string $fileName): string
    {
        if (!preg_match('/^(http|ftp):\/\//', $fileName)) {
            $rtbMessageSoundFilePathResolver = $this->createRtbMessageSoundFilePathResolver();
            if ($rtbMessageSoundFilePathResolver->exist($accountId, $fileName)) {
                return $rtbMessageSoundFilePathResolver->makeFileRootPath($accountId, $fileName);
            }
        }

        log_error("Failed trying to get sound file for Rtb Message, \"{$fileName}\" does not exist");
        return '';
    }
}
