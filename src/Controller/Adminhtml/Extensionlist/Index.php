<?php

namespace Extdn\ExtensionDashboard\Controller\Adminhtml\Extensionlist;

use Magento\Backend\App\Action;

class Index extends Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Fooman_Connect::xero_order');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Extension Dashboard'));
        $this->_addBreadcrumb(__('Extension Dashboard'), __('Extension Dashboard'));
        $this->_view->renderLayout();
    }
}
