<?php

namespace Sam\Qform\Longevity;

use QBaseClass;
use Sam\Infrastructure\Storage\PhpSessionStorage;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * This will store the formstate in a pre-specified directory on the file system.
 * This offers significant speed advantage over PHP SESSION because EACH form state
 * is saved in its own file, and only the form state that is needed for loading will
 * be accessed (as opposed to with session, ALL the form states are loaded into memory
 * every time).
 *
 * The downside is that because it doesn't utilize PHP's session management subsystem,
 * this class must take care of its own garbage collection/deleting of old/outdated
 * formstate files.
 *
 * Because the index is randomy generated and MD5-hashed, there is no benefit from
 * encrypting it -- therefore, the QForm encryption preferences are ignored when using
 * QFileFormStateHandler.
 */
class FileFormStateHandler extends QBaseClass
{
    /**
     * The interval of hits before the garbage collection should kick in to delete
     * old FormState files, or 0 if it should never be run.  The higher the number,
     * the less often it runs (better aggregated-average performance, but requires more
     * hard drive space).  The lower the number, the more often it runs (slower aggregated-average
     * performance, but requires less hard drive space).
     */
    public static int $GarbageCollectInterval = 0;

    /**
     * The minimum age (in days) a formstate file has to be in order to be considered old enough
     * to be garbage collected.  So if set to "1.5", then all formstate files older than 1.5 days
     * will be deleted when the GC interval is kicked off.
     *
     * Obviously, if the GC Interval is set to 0, then this GC Days Old value will be never used.
     */
    public static int $GarbageCollectDaysOld = 2;

    /**
     * If PHP SESSION is enabled, then this method will delete all formstate files specifically
     * for this SESSION user (and no one else).  This can be used in lieu of or in addition to the
     * standard interval-based garbage collection mechanism.
     *
     * Also, for standard web applications with logins, it might be a good idea to call
     * this method whenever the user logs out.
     * @noinspection PhpUnused
     */
    public static function DeleteFormStateForSession(): void
    {
        // Figure Out Session Id (if applicable)
        $sessionId = PhpSessionStorage::new()->getSessionId();
        $prefix = self::fileNamePrefix() . $sessionId;
        // Go through all the files
        if ($sessionId !== '') {
            $directory = dir(path()->temporary());
            while (($file = $directory->read()) !== false) {
                $position = strpos($file, $prefix);
                if ($position === 0) {
                    @unlink(sprintf('%s/%s', path()->temporary(), $file));
                }
            }
        }
    }

    /**
     * This will delete all the formstate files that are older than $GarbageCollectDaysOld
     * days old.
     */
    public static function GarbageCollect(): void
    {
        $fileNamePrefix = self::fileNamePrefix();
        // Go through all the files
        $directory = dir(path()->temporary());
        while (($filePath = $directory->read()) !== false) {
            if ($fileNamePrefix === '') {
                $position = 0;
            } else {
                $position = strpos($filePath, $fileNamePrefix);
            }
            if ($position === 0) {
                $filePath = sprintf('%s/%s', path()->temporary(), $filePath);
                $timeInterval = time() - (60 * 60 * 24 * self::$GarbageCollectDaysOld);
                $modifiedTime = @filemtime($filePath);

                if ($modifiedTime < $timeInterval) {
                    @unlink($filePath);
                }
            }
        }
    }

    /**
     * @param string $formState
     * @param bool $isBackButtonFlag
     * @return string
     */
    public static function Save($formState, $isBackButtonFlag): string
    {
        // First see if we need to perform garbage collection
        if (self::$GarbageCollectInterval > 0) {
            // This is a crude interval-tester, but it works
            /** @noinspection IncorrectRandomRangeInspection */
            if (random_int(1, self::$GarbageCollectInterval) === 1) {
                self::GarbageCollect();
            }
        }

        // Compress (if available)
        if (function_exists('gzcompress')) {
            $formState = gzcompress($formState, 9);
        }

        // Figure Out Session Id (if applicable)
        $sessionId = PhpSessionStorage::new()->getSessionId();

        // Calculate a new unique Page Id
        $pageId = md5(microtime());

        // Figure Out FilePath
        $filePath = sprintf(
            '%s/%s%s_%s',
            path()->temporary(),
            self::fileNamePrefix(),
            $sessionId,
            $pageId
        );

        // Save THIS formstate to the file system
        // NOTE: if gzcompress is used, we are saving the *BINARY* data stream of the compressed formstate
        // In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
        // binary session streams, you can first base64_encode before saving to session (see note below).
        file_put_contents($filePath, $formState);

        // Return the Page Id
        // Because of the MD5-random nature of the Page ID, there is no need/reason to encrypt it
        return $pageId;
    }

    /**
     * @param string $postDataState
     * @return string|null
     */
    public static function Load($postDataState): ?string
    {
        // Pull Out strPageId
        $pageId = $postDataState;

        // Figure Out Session Id (if applicable)
        $sessionId = PhpSessionStorage::new()->getSessionId();

        $cfg = ConfigRepository::getInstance();
        $fileNamePrefix = $cfg->get('core->app->qform->fileFormStateHandler->fileNamePrefix');

        // Figure Out FilePath
        $filePath = sprintf(
            '%s/%s%s_%s',
            path()->temporary(),
            self::fileNamePrefix(),
            $sessionId,
            $pageId
        );

        if (file_exists($filePath)) {
            // Pull FormState from file system
            // NOTE: if gzcompress is used, we are restoring the *BINARY* data stream of the compressed formstate
            // In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
            // binary session streams, you can first base64_decode before restoring from session (see note above).
            $serializedForm = file_get_contents($filePath);

            // Uncompress (if available)
            if (function_exists('gzcompress')) {
                $serializedForm = gzuncompress($serializedForm);
            }

            return $serializedForm;
        }

        return null;
    }

    protected static function fileNamePrefix(): string
    {
        return ConfigRepository::getInstance()->get('core->app->qform->fileFormStateHandler->fileNamePrefix');
    }
}
