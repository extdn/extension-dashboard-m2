<?php

namespace Extdn\ExtensionDashboard\Model;

use Magento\Framework\Data\Collection as FrameworkDataCollection;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use League\Csv\Reader;

class ExtensionReleaseDb extends FrameworkDataCollection
{

    private $objectFactory;

    public function __construct(
        EntityFactoryInterface $entityFactory,
        DataObjectFactory $objectFactory
    ) {
        $this->objectFactory = $objectFactory;
        parent::__construct($entityFactory);
    }

    public function loadData($printQuery = false, $logQuery = false)
    {
        //TODO: this should get updated from an online source
        $csv = Reader::createFromPath(__DIR__ . '/../data/all-releases.csv', 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        foreach ($records as $record) {
            $item = $this->objectFactory->create(['data' => $record]);
            $this->addItem($item);
        }
    }

    public function getLatestReleaseForModule($name)
    {
        $latest = false;
        foreach ($this->getItems() as $item) {
            if ($item->getData('Extension') === $name) {
                if (!$latest) {
                    $latest = $item->getData('Version');
                } elseif (version_compare($item->getData('Version'), $latest, '>')) {
                    $latest = $item->getData('Version');
                }
            }
        }
        if ($latest) {
            return $latest;
        }
        //TODO: Link this back to github repo to invite contributions
        return 'No release data found in DB';
    }
}
