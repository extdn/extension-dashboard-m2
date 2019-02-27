<?php

namespace Extdn\ExtensionDashboard\Model\Grid;

use Extdn\ExtensionDashboard\Model\ExtensionReleaseDb;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection as FrameworkDataCollection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;

class Collection extends FrameworkDataCollection implements SearchResultInterface
{
    const MAGENTO_VENDOR_NAME = 'Magento_';

    private $moduleList;
    private $moduleListActive;
    private $extensionDocumentFactory;
    private $packageInfo;
    private $extensionReleaseDb;

    private $extensions;

    public function __construct(
        EntityFactoryInterface $entityFactory,
        \Magento\Framework\Module\FullModuleList $moduleList,
        \Magento\Framework\Module\ModuleList $moduleListActive,
        \Extdn\ExtensionDashboard\Model\ExtensionDocumentFactory $extensionDocumentFactory,
        \Magento\Framework\Module\PackageInfo $packageInfo,
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
     * @return \Magento\Framework\Api\Search\DocumentInterface[]
     */
    public function getItems()
    {
        if (empty($this->extensions)) {
            $allModules = $this->moduleList->getAll();
            foreach ($allModules as $module) {
                if (!$this->isMagentoModule($module['name'])) {
                    $packageName = $this->packageInfo->getPackageName($module['name']);
                    $version = $this->packageInfo->getVersion($module['name']);
                    $active = $this->moduleListActive->has($module['name']);
                    $doc = $this->extensionDocumentFactory->create();
                    $doc->setCustomAttribute('module_name', $module['name']);
                    $doc->setCustomAttribute('setup_version', $module['setup_version']);
                    $doc->setCustomAttribute('package_name', $packageName);
                    $doc->setCustomAttribute('version', $version);
                    $doc->setCustomAttribute('latest_version', $this->getLatestVersion($module['name']));
                    $doc->setCustomAttribute('is_secure', $this->getIsSecure($module['name'], $version));
                    $doc->setCustomAttribute('active', $active ? __('Enabled') : __('Disabled'));

                    $this->extensions[] = $doc;
                }
            }
        }

        return $this->extensions;
    }

    private function getLatestVersion($moduleName)
    {
        return $this->extensionReleaseDb->getLatestReleaseForModule($moduleName);
    }

    private function getIsSecure($moduleName, $installedVersion)
    {
        return $this->extensionReleaseDb->getIsSecure($moduleName, $installedVersion);
    }

    private function isMagentoModule($moduleName)
    {
        return substr($moduleName, 0, strlen(self::MAGENTO_VENDOR_NAME)) === self::MAGENTO_VENDOR_NAME;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
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
     * @return \Magento\Framework\Api\SearchCriteriaInterface
     */
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
