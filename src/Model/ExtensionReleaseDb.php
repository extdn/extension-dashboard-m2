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
        if (!$this->isLoaded()) {
            //TODO: this should get updated from an online source
            $csv = Reader::createFromPath(__DIR__ . '/../data/all-releases.csv', 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();
            foreach ($records as $record) {
                $item = $this->objectFactory->create(['data' => $record]);
                $this->addItem($item);
            }
            $this->_setIsLoaded(true);
        }
    }

    public function getLatestReleaseForModule($name)
    {
        $latest = false;
        foreach ($this->getItems() as $item) {
            if ($item->getExtension() === $name) {
                if (!$latest) {
                    $latest = $item->getVersion();
                } elseif (version_compare($item->getVersion(), $latest, '>')) {
                    $latest = $item->getVersion();
                }
            }
        }
        if ($latest) {
            return $latest;
        }
        //TODO: Link this back to github repo to invite contributions
        return __('No release data found in DB');
    }

    public function getIsSecure($name, $installedVersion)
    {
        $found = false;
        $missing = [];
        foreach ($this->getItems() as $item) {
            if ($item->getExtension() === $name) {
                $found = true;
                if (version_compare($item->getVersion(), $installedVersion, '>')
                    && $item->getSecurity() == 1) {
                    $missing[] = $item->getVersion();
                }
            }
        }
        if (!$found) {
            return __('No release data found in DB');
        }
        //TODO: Make the display a bit nicer, warn with colour
        if (empty($missing)) {
            return __('All applied');
        }

        return __('WARNING missing') . ': ' . implode(',', $missing);
    }
}