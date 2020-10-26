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

    public function testComposerNameSelfDisplays()
    {
        $this->dispatch($this->uri);
        self::assertStringContainsString('extdn/extension-dashboard-m2', $this->getResponse()->getBody());
    }

    public function testModuleNameSelfDisplays()
    {
        $this->dispatch($this->uri);
        self::assertStringContainsString('Extdn_ExtensionDashboard', $this->getResponse()->getBody());
    }
}
