<?php
/**
 * SAM-6280: Class for rendering result assembled from parts
 *
 * Class logic assembles rendering result from fractions and their additional extensions.
 * For instance time left, file size output. Possible result examples:
 * 5 days, 10 hours 30 minutes 45 seconds
 * 5 10:30:45
 * 5d 10h 30m 45s
 * 5Gb 10Mb 30Kb 45B
 *
 * It is configurable to trim zero values from left and right sides.
 * And perform final trimming of separation characters.
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Fraction;

use Sam\Core\Service\CustomizableClass;

/**
 * Class FractionComposer
 * @package Sam\Transform\Fraction
 * #[Pure]
 */
class FractionAssembler extends CustomizableClass
{
    protected array $fractions = [];
    protected array $extensions = [];
    protected bool $isTrimLeftZeros = false;
    protected bool $isTrimRightZeros = false;
    protected string $trimCharList = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @return string
     */
    public function assemble(): string
    {
        $pairs = [];
        foreach ($this->fractions as $i => $value) {
            $pairs[] = [$value, $this->extensions[$i]];
        }
        if ($this->isTrimLeftZeros) {
            $pairs = $this->trimLeftZeros($pairs);
        }
        if ($this->isTrimRightZeros) {
            $pairs = $this->trimRightZeros($pairs);
        }
        foreach ($pairs as $i => $pair) {
            $pairs[$i] = implode('', $pair);
        }
        $output = implode('', $pairs);
        if ($this->trimCharList) {
            $output = $this->trimSeparatorChars($output);
        }
        return $output;
    }

    /**
     * @param array $fractions
     * @return static
     */
    public function setFractions(array $fractions): static
    {
        $this->fractions = $fractions;
        return $this;
    }

    /**
     * @param array $extensions
     * @return static
     */
    public function setExtensions(array $extensions): static
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @param bool $isTrimLeftZeros
     * @return static
     */
    public function enableTrimLeftZeros(bool $isTrimLeftZeros): static
    {
        $this->isTrimLeftZeros = $isTrimLeftZeros;
        return $this;
    }

    /**
     * @param bool $isTrimRightZeros
     * @return static
     */
    public function enableTrimRightZeros(bool $isTrimRightZeros): static
    {
        $this->isTrimRightZeros = $isTrimRightZeros;
        return $this;
    }

    /**
     * @param string $trimCharList
     * @return static
     */
    public function setTrimCharList(string $trimCharList): static
    {
        $this->trimCharList = $trimCharList;
        return $this;
    }

    /**
     * @param array $pairs
     * @return array
     */
    protected function trimLeftZeros(array $pairs): array
    {
        foreach ($pairs as $i => $pair) {
            if ($pair[0] > 0) {
                break;
            }
            $pairs[$i] = null;
        }
        $pairs = array_filter($pairs);
        return $pairs;
    }

    /**
     * @param array $pairs
     * @return array
     */
    protected function trimRightZeros(array $pairs): array
    {
        $pairs = array_reverse($pairs);
        $pairs = $this->trimLeftZeros($pairs);
        $pairs = array_reverse($pairs);
        return $pairs;
    }

    /**
     * @param string $output
     * @return string
     */
    protected function trimSeparatorChars(string $output): string
    {
        if ($this->isTrimLeftZeros) {
            $output = ltrim($output, $this->trimCharList);
        }
        if ($this->isTrimRightZeros) {
            $output = rtrim($output, $this->trimCharList);
        }
        return $output;
    }
}
