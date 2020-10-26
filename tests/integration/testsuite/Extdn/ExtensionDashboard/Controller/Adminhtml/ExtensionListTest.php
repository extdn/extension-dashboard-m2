<?php

namespace Extdn\ExtensionDashboard\Controller\Adminhtml;

use Fooman\PhpunitBridge\AbstractBackendController;
/**
 * @magentoAppArea adminhtml
 */
class ExtensionListTest extends AbstractBackendController
{
    public function setUp(): void
    {
        $this->resource = 'Extdn_ExtensionDashboard::list';
        $this->uri = 'backend/extdndashboard/extensionlist';
        parent::setUp();
    }
}
