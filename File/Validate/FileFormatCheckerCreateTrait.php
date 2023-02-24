<?php

namespace Sam\File\Validate;

/**
 * Trait FileFormatCheckerTrait
 * @package Sam\File
 */
trait FileFormatCheckerCreateTrait
{
    protected ?FileFormatChecker $fileFormatChecker = null;

    /**
     * @param FileFormatChecker $fileFormatChecker
     * @return static
     * @internal
     */
    public function setFileFormatChecker(FileFormatChecker $fileFormatChecker): static
    {
        $this->fileFormatChecker = $fileFormatChecker;
        return $this;
    }

    /**
     * @return FileFormatChecker
     * @internal
     */
    protected function createFileFormatChecker(): FileFormatChecker
    {
        return $this->fileFormatChecker ?: FileFormatChecker::new();
    }
}
