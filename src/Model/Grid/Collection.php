<?php

namespace Extdn\ExtensionDashboard\Model\Grid;

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

    public function __construct(
        EntityFactoryInterface $entityFactory,
        \Magento\Framework\Module\FullModuleList $moduleList,
        \Magento\Framework\Module\ModuleList $moduleListActive,
        \Extdn\ExtensionDashboard\Model\ExtensionDocumentFactory $extensionDocumentFactory,
        \Magento\Framework\Module\PackageInfo $packageInfo
    ) {
        parent::__construct($entityFactory);
        $this->moduleList = $moduleList;
        $this->moduleListActive = $moduleListActive;
        $this->extensionDocumentFactory = $extensionDocumentFactory;
        $this->packageInfo = $packageInfo;
    }

    /**
     * @return \Magento\Framework\Api\Search\DocumentInterface[]
     */
    public function getItems()
    {
        $result = [];
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
                $doc->setCustomAttribute('active', $active ? __('Enabled') : __('Disabled'));

                $result[] = $doc;
            }
        }

        return $result;
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
        // TODO: Implement getTotalCount() method.
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
        // TODO: Implement setItems() method.
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
    }
}