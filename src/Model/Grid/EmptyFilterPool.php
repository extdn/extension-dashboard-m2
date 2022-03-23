<?php declare(strict_types=1);

namespace Extdn\ExtensionDashboard\Model\Grid;

use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\View\Element\UiComponent\DataProvider\FilterPool;

class EmptyFilterPool extends FilterPool
{
    public function applyFilters(Collection $collection, SearchCriteriaInterface $criteria)
    {
    }
}
