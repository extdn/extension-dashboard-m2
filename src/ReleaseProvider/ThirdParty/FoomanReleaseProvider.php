<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\ReleaseProvider\ThirdParty;

use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionReleaseFactory;
use Magento\Framework\Component\ComponentRegistrar;
use Extdn\ExtensionDashboard\ReleaseProvider\CsvReleaseProvider;

class FoomanReleaseProvider extends CsvReleaseProvider
{
    /**
     * FoomanReleaseProvider constructor.
     * @param ComponentRegistrar $componentRegistrar
     * @param ExtensionReleaseFactory $extensionReleaseFactory
     */
    public function __construct(
        ComponentRegistrar $componentRegistrar,
        ExtensionReleaseFactory $extensionReleaseFactory
    ) {
        $basePath = $componentRegistrar->getPath('module', 'Extdn_ExtensionDashboard');
        $csvFile = $basePath . '/data/all-releases.csv';

        parent::__construct($extensionReleaseFactory, $csvFile);
    }
}
