<?php
/**
 * Manage static messages of bidding process
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 1, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\LocalFileManager;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Messenger
 * @package Sam\Rtb
 */
class Messenger extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use LocalFileManagerCreateTrait;
    use SettingsManagerAwareTrait;

    public const MESSAGE_TYPE_ADMIN_HISTORY = "history_back";
    public const MESSAGE_TYPE_FRONT_HISTORY = "history_front";
    public const MESSAGE_TYPE_ADMIN_CHAT = "chat_back";
    public const MESSAGE_TYPE_FRONT_CHAT = "chat_front";

    protected ?string $rootPath = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add history message to a file
     * @param int $auctionId
     * @param string $message
     * @param bool $isAdmin
     * @param bool $isSync
     * @return bool
     */
    public function createStaticHistoryMessage(int $auctionId, string $message, bool $isAdmin = false, bool $isSync = false): bool
    {
        $messageType = $isAdmin ? '/' . self::MESSAGE_TYPE_ADMIN_HISTORY : '/' . self::MESSAGE_TYPE_FRONT_HISTORY;
        $fileRootPath = $this->getRootPath() . '/' . $messageType . '_' . $auctionId . '.html';
        return $this->saveStaticMessage($fileRootPath, $auctionId, $message, $isSync);
    }

    /**
     * Add message from chat to a file
     * @param int $auctionId
     * @param string $message
     * @param bool $isAdmin
     * @param bool $isSync
     * @return bool
     */
    public function createStaticChatMessage(int $auctionId, string $message, bool $isAdmin = false, bool $isSync = false): bool
    {
        $messageType = $isAdmin ? '/' . self::MESSAGE_TYPE_ADMIN_CHAT : '/' . self::MESSAGE_TYPE_FRONT_CHAT;
        $fileRootPath = $this->getRootPath() . '/' . $messageType . '_' . $auctionId . '.html';
        return $this->saveStaticMessage($fileRootPath, $auctionId, $message, $isSync);
    }

    /**
     * Save message to a file
     * @param string $fileRootPath
     * @param int $auctionId
     * @param string $message
     * @param bool $isSync
     * @return bool
     */
    protected function saveStaticMessage(string $fileRootPath, int $auctionId, string $message, bool $isSync = false): bool
    {
        try {
            LocalFileManager::new()->createDirPath($fileRootPath);
        } catch (FileException) {
            log_error('Error on Creating a temporary Directory');
            return false;
        }

        $content = $this->getCurrentContent($fileRootPath, $auctionId);
        if ($isSync) {
            if ($content === '') {
                $this->writeToFile($fileRootPath, $message);
            }
        } else {
            $this->writeToFile($fileRootPath, $message . $content);
        }
        $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);
        return true;
    }

    /**
     * @param string $fileRootPath
     * @param int $auctionId
     * @return string
     */
    protected function getCurrentContent(string $fileRootPath, int $auctionId): string
    {
        if (!file_exists($fileRootPath)) { // File has been created
            return '';
        }
        $auction = $this->getAuctionLoader()->load($auctionId, true);
        if (!$auction) {
            log_error("Available auction not found" . composeSuffix(['a' => $auctionId]));
            return '';
        }

        $twentyMsgMax = $this->getSettingsManager()->get(Constants\Setting::TWENTY_MESSAGES_MAX, $auction->AccountId);
        if (!$twentyMsgMax) {
            // suppress error to avoid halting when file suddenly not found
            return @file_get_contents($fileRootPath);
        }

        // Limit message
        $counter = 0;
        $limit = $this->cfg()->get('core->rtb->messageCenter->renderedMessageCount') - 1;
        $lines = file($fileRootPath, FILE_SKIP_EMPTY_LINES);
        $content = '';
        foreach ($lines as $line) {
            $content .= $line;
            $counter++;
            if ($counter === $limit) {
                break;
            }
        }
        return $content;
    }

    /**
     * @param string $fileRootPath
     * @param string $message
     */
    protected function writeToFile(string $fileRootPath, string $message): void
    {
        $fp = fopen($fileRootPath, 'wb');
        fwrite($fp, $message);
        fclose($fp);
    }

    /**
     * @param int $auctionId
     * @param bool|false $isAdmin
     * @return bool
     */
    public function clearStaticMessage(int $auctionId, bool $isAdmin = false): bool
    {
        $messageType = $isAdmin ? 'back' : 'front';
        $fileRootPath = $this->getRootPath() . '/' . $messageType . '_' . $auctionId . '.html';
        try {
            LocalFileManager::new()->createDirPath($fileRootPath);
        } catch (FileException) {
            log_error('Error on Creating a temporary Directory');
            return false;
        }

        $fp = fopen($fileRootPath, 'wb');
        fwrite($fp, '');
        fclose($fp);
        $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);
        $this->clearStaticChatMessage($auctionId, $isAdmin);
        $this->clearStaticHistoryMessage($auctionId, $isAdmin);
        return true;
    }

    /**
     * @param int $auctionId
     * @param bool $isAdmin
     * @return bool
     */
    public function clearStaticChatMessage(int $auctionId, bool $isAdmin = false): bool
    {
        $messageType = $isAdmin ? '/' . self::MESSAGE_TYPE_ADMIN_CHAT : '/' . self::MESSAGE_TYPE_FRONT_CHAT;
        return $this->emptyMessageFile($this->getRootPath() . '/' . $messageType . '_' . $auctionId . '.html');
    }

    /**
     * @param string $fileRootPath
     * @return bool
     */
    public function emptyMessageFile(string $fileRootPath): bool
    {
        try {
            LocalFileManager::new()->createDirPath($fileRootPath);
        } catch (FileException) {
            log_error('Error on Creating a temporary Directory');
            return false;
        }

        $fp = fopen($fileRootPath, 'wb');
        fwrite($fp, '');
        fclose($fp);
        $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);
        return true;
    }

    /**
     * @param int $auctionId
     * @param bool $isAdmin
     * @return bool
     */
    public function clearStaticHistoryMessage(int $auctionId, bool $isAdmin = false): bool
    {
        $messageType = $isAdmin ? '/' . self::MESSAGE_TYPE_ADMIN_HISTORY : '/' . self::MESSAGE_TYPE_FRONT_HISTORY;
        return $this->emptyMessageFile($this->getRootPath() . '/' . $messageType . '_' . $auctionId . '.html');
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        if ($this->rootPath === null) {
            $this->rootPath = path()->docRoot() . '/lot-info';
        }
        return $this->rootPath;
    }

    /**
     * @param string $rootPath
     * @return static
     */
    public function setRootPath(string $rootPath): static
    {
        $this->rootPath = trim($rootPath);
        return $this;
    }
}
