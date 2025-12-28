<?php

namespace MagestyApps\Disable2FA\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Magento\User\Block\User\Edit\Tab\Main;

class AddUserFormField implements ObserverInterface
{
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @param Registry $coreRegistry
     */
    public function __construct(
        Registry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Add the field to the user edit form
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Main) {
            /** @var $model \Magento\User\Model\User */
            $model = $this->coreRegistry->registry('permissions_user');

            $form = $block->getForm();
            $baseFieldset = $form->getElement('base_fieldset');

            // Note that the values are reverted because the db field name is called 'disable_tfa'
            // while the form field is called 'Enable 2FA'
            $baseFieldset->addField(
                'disable_tfa',
                'select',
                [
                    'name' => 'disable_tfa',
                    'label' => __('Enable 2FA'),
                    'id' => 'disable_tfa',
                    'title' => __('Enable 2FA'),
                    'class' => 'input-select',
                    'options' => ['0' => __('Yes'), '1' => __('No')],
                    'value' => $model->getData('disable_tfa')
                ],
                'email'
            );
        }
    }
}
