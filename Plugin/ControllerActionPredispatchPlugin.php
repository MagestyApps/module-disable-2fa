<?php

namespace MagestyApps\Disable2FA\Plugin;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Event\Observer;
use Magento\TwoFactorAuth\Api\TfaSessionInterface;
use Magento\User\Model\User;
use Magento\User\Model\UserFactory;
use Magento\TwoFactorAuth\Observer\ControllerActionPredispatch;
use Psr\Log\LoggerInterface;

class ControllerActionPredispatchPlugin
{
    /**
     * @var UserContextInterface
     */
    private $userContext;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var TfaSessionInterface
     */
    private $tfaSession;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param UserFactory $userFactory
     * @param TfaSessionInterface $tfaSession
     * @param UserContextInterface $userContext
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserFactory $userFactory,
        TfaSessionInterface $tfaSession,
        UserContextInterface $userContext,
        LoggerInterface $logger
    ) {
        $this->userFactory = $userFactory;
        $this->tfaSession = $tfaSession;
        $this->userContext = $userContext;
        $this->logger = $logger;
    }

    /**
     * Disable 2FA for the user if the corresponding setting is enabled
     *
     * @param ControllerActionPredispatch $subject
     * @param callable $proceed
     * @param Observer $observer
     * @return mixed
     */
    public function aroundExecute(ControllerActionPredispatch $subject, callable $proceed, Observer $observer)
    {
        try {
            $userId = $this->userContext->getUserId();

            if ($userId && !$this->tfaSession->isGranted()) {
                /** @var User $userConfig */
                $user = $this->userFactory->create()->load($userId);
                if ($user && $user->getDisableTfa()) {
                    $this->tfaSession->grantAccess();
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Error when disabling 2FA for a user', ['message' => $e->getMessage()]);
        }

        return $proceed($observer);
    }
}
