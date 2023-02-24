<?php
/**
 * SAM-5397: Token Link
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cli\Command\Create;

use Sam\Sso\TokenLink\Cli\Command\Base\CommandBase;
use Sam\Sso\TokenLink\Cli\ApplicationConstants;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateCommand
 * @package
 */
class CreateCommand extends CommandBase
{
    /**
     * @var string
     */
    protected static $defaultName = ApplicationConstants::C_CREATE;

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int 0 if everything went fine, or an error code
     * @throws InvalidOptionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = (string)$input->getOption(CreateConstants::O_USERNAME);
        $userId = (int)$input->getOption(CreateConstants::O_USER_ID);

        if (
            !$username
            && !$userId
        ) {
            $errorMessage = sprintf(
                'One of mandatory option missed "--%s" or "--%s"',
                CreateConstants::O_USERNAME,
                CreateConstants::O_USER_ID
            );
            throw new InvalidOptionException($errorMessage);
        }

        $createHandler = CreateHandler::new();
        if ($username) {
            $createHandler->setUsername($username);
        } else {
            $createHandler->setUserId($userId);
        }

        $createHandler
            ->setOutput($output)
            ->handle();
        $result = $createHandler->getResultCode();
        return $result;
    }
}
