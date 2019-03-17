<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\Controller\Adminhtml\Extensionlist;

use Magento\Backend\App\Action;

class Index extends Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Extdn_ExtensionDashboard::index');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Extension Dashboard'));
        $this->_addBreadcrumb(__('Extension Dashboard'), __('Extension Dashboard'));
        $this->_view->renderLayout();
    }
}
