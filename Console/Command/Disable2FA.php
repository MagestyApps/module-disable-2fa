<?php

namespace MagestyApps\Disable2FA\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\User\Model\ResourceModel\User as UserResource;
use Magento\User\Model\UserFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Disable2FA extends Command
{
    /**
     * Command argument name for username
     */
    const ARGUMENT_USERNAME = 'username';

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var UserResource
     */
    private $userResource;

    /**
     * @param UserFactory $userFactory
     * @param UserResource $userResource
     * @param string|null $name
     */
    public function __construct(
        UserFactory $userFactory,
        UserResource $userResource,
        ?string $name = null
    ) {
        $this->userFactory = $userFactory;
        $this->userResource = $userResource;
        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('security:tfa:disable')
            ->setDescription('Disable Two-Factor Authentication for an admin user')
            ->addArgument(
                self::ARGUMENT_USERNAME,
                InputArgument::REQUIRED,
                'Admin username'
            );

        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $username = $input->getArgument(self::ARGUMENT_USERNAME);
            $user = $this->userFactory->create();
            $user->loadByUsername($username);

            if (!$user->getId()) {
                $output->writeln('<error>Admin user with username "' . $username . '" not found.</error>');
                return Cli::RETURN_FAILURE;
            }

            $user->setDisableTfa(1);
            $this->userResource->save($user);

            $output->writeln('<info>Two-Factor Authentication has been disabled for user "' . $username . '".</info>');
            return Cli::RETURN_SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Cli::RETURN_FAILURE;
        }
    }
}
