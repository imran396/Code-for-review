<?php
/**
 * Util to allow check an IP address against a list of CIDR (IP/ number of bits of mask "Classless Inter-Domain Routing")
 *
 * SAM-4026: Refactor IP CIDR checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 4, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Ip\Validate;

use OutOfRangeException;
use Sam\Core\Service\CustomizableClass;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

/**
 * Class CidrChecker
 * @package Sam\Core\Ip
 */
class CidrChecker extends CustomizableClass
{
    /**
     * @var string[]
     */
    protected array $subnets = [];

    /**
     * Get instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check IP against CIDR list
     *
     * @param string $ip IPv4 or IPv6
     * @return bool
     */
    public function isInCidrList(string $ip): bool
    {
        if (!$this->validateIp($ip)) {
            log_error("Invalid IP address" . composeSuffix(['ip' => $ip]));
            return false;
        }

        $address = \IPLib\Factory::addressFromString($ip);
        if ($address) {
            foreach ($this->subnets as $subnet) {
                $range = \IPLib\Factory::parseRangeString($subnet);
                $isMatched = $range && $address->matches($range);
                if ($isMatched) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Add a CIDR to the list of CIDRs to check against
     * @param string $subnet format x.x.x.x/x or IPv6/x
     * @return static
     */
    public function addSubnet(string $subnet): static
    {
        $subnetValidator = SubnetValidator::new();
        if ($subnetValidator->validate($subnet)) {
            if (!in_array($subnet, $this->subnets)) {
                $this->subnets[] = $subnet;
            }
        } else {
            throw new OutOfRangeException(implode('. ', $subnetValidator->errorMessages()));
        }
        return $this;
    }

    /**
     * @param array $subnets
     * @return static
     */
    public function setSubnets(array $subnets): static
    {
        foreach ($subnets as $subnet) {
            $this->addSubnet($subnet);
        }
        return $this;
    }

    /**
     * Validate ip for v4 and v6
     * @param string $ip
     * @return bool
     */
    protected function validateIp(string $ip): bool
    {
        $version = str_contains($ip, '.') ? Assert\Ip::V4 : Assert\Ip::V6;
        $constraint = new Assert\Ip(['version' => $version]);
        $validationErrors = Validation::createValidator()->validate($ip, $constraint);
        return count($validationErrors) === 0;
    }
}
