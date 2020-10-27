<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\Model\Grid;

use Extdn\ExtensionDashboard\Model\ExtensionDocument;
use Extdn\ExtensionDashboard\Model\ExtensionDocumentFactory;
use Extdn\ExtensionDashboard\Model\ExtensionReleaseDb;
use Magento\Framework\Api\Search\DocumentInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection as FrameworkDataCollection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Module\FullModuleList;
use Magento\Framework\Module\ModuleList;
use Magento\Framework\Module\PackageInfo;

class Collection extends FrameworkDataCollection implements SearchResultInterface
{

    const MAGENTO_VENDOR_NAME = 'Magento_';

    /**
     * @var FullModuleList
     */
    private $moduleList;

    /**
     * @var ModuleList
     */
    private $moduleListActive;

    /**
     * @var ExtensionDocumentFactory
     */
    private $extensionDocumentFactory;

    /**
     * @var PackageInfo
     */
    private $packageInfo;

    /**
     * @var ExtensionReleaseDb
     */
    private $extensionReleaseDb;

    /**
     * @var DocumentInterface[]
     */
    private $extensions;

    /**
     * Collection constructor.
     *
     * @param EntityFactoryInterface $entityFactory
     * @param FullModuleList $moduleList
     * @param ModuleList $moduleListActive
     * @param ExtensionDocumentFactory $extensionDocumentFactory
     * @param PackageInfo $packageInfo
     * @param ExtensionReleaseDb $extensionReleaseDb
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        FullModuleList $moduleList,
        ModuleList $moduleListActive,
        ExtensionDocumentFactory $extensionDocumentFactory,
        PackageInfo $packageInfo,
        ExtensionReleaseDb $extensionReleaseDb
    ) {
        parent::__construct($entityFactory);
        $this->moduleList = $moduleList;
        $this->moduleListActive = $moduleListActive;
        $this->extensionDocumentFactory = $extensionDocumentFactory;
        $this->packageInfo = $packageInfo;
        $this->extensionReleaseDb = $extensionReleaseDb;
    }

    /**
     * @return DocumentInterface[]
     */
    public function getItems()
    {
        if (empty($this->extensions)) {
            $allModules = $this->moduleList->getAll();
            foreach ($allModules as $module) {
                if ($this->isMagentoModule($module['name'])) {
                    continue;
                }

                $this->extensions[] = $this->getDocFromModule($module);
            }
        }

        return $this->extensions;
    }

    /**
     * @param array $module
     * @return ExtensionDocument
     */
    private function getDocFromModule(array $module): ExtensionDocument
    {
        $packageName = $this->packageInfo->getPackageName($module['name']);
        $version = $this->packageInfo->getVersion($module['name']);
        $active = $this->moduleListActive->has($module['name']);

        $doc = $this->extensionDocumentFactory->create();
        $doc->setCustomAttribute('module_name', $module['name']);
        $doc->setCustomAttribute('setup_version', $module['setup_version']);
        $doc->setCustomAttribute('package_name', $packageName);
        $doc->setCustomAttribute('version', $version);
        $doc->setCustomAttribute('latest_version', $this->getLatestVersion((string)$module['name']));
        $doc->setCustomAttribute('is_secure', $this->isSecure($module['name'], $version));
        $doc->setCustomAttribute('active', $active ? __('Enabled') : __('Disabled'));

        return $doc;
    }

    /**
     * @param string $moduleName
     * @return bool|\Magento\Framework\Phrase
     */
    private function getLatestVersion(string $moduleName)
    {
        return $this->extensionReleaseDb->getLatestReleaseForModule($moduleName);
    }

    /**
     * @param string $moduleName
     * @param string $installedVersion
     * @return string
     */
    private function isSecure(string $moduleName, string $installedVersion)
    {
        // @todo: Refactor return value of underlying call
        return $this->extensionReleaseDb->getIsSecure($moduleName, $installedVersion);
    }

    /**
     * @param string $moduleName
     * @return bool
     */
    private function isMagentoModule(string $moduleName)
    {
        return substr($moduleName, 0, strlen(self::MAGENTO_VENDOR_NAME)) === self::MAGENTO_VENDOR_NAME;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return count($this->extensions);
    }

    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
     *
     * @return $this
     */
    public function setItems(?array $items = null)
    {
        $this->extensions = $items;
        return $this;
    }

    /**
     * Get search criteria.
     *
     * @return void
     */
    // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedFunction -- currently not used
    public function getSearchCriteria()
    {
        // TODO: Implement getSearchCriteria() method.
    }

    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return $this
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria)
    {
        // TODO: Implement setSearchCriteria() method.
        return $this;
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     *
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        // TODO: Implement setTotalCount() method.
        return $this;
    }

    /**
     * @return \Magento\Framework\Api\Search\AggregationInterface
     */
    // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedFunction -- currently not used
    public function getAggregations()
    {
        // TODO: Implement getAggregations() method.
    }

    /**
     * @param \Magento\Framework\Api\Search\AggregationInterface $aggregations
     *
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        // TODO: Implement setAggregations() method.
        return $this;
    }
}
