<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\ReleaseProvider;

use Extdn\ExtensionDashboard\Api\ReleaseProviderInterface;
use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionRelease;
use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionReleaseFactory;
use League\Csv\Reader;

class CsvReleaseProvider implements ReleaseProviderInterface
{
    /**
     * @var string
     */
    private $csvFile;

    /**
     * @var ExtensionReleaseFactory
     */
    private $extensionReleaseFactory;

    /**
     * CsvReleaseProvider constructor.
     * @param ExtensionReleaseFactory $extensionReleaseFactory
     * @param string $csvFile
     */
    public function __construct(
        ExtensionReleaseFactory $extensionReleaseFactory,
        string $csvFile = ''
    ) {
        $this->extensionReleaseFactory = $extensionReleaseFactory;
        $this->csvFile = $csvFile;
    }

    /**
     * @return ExtensionRelease[]
     */
    public function getExtensionReleases(): array
    {
        $csv = Reader::createFromPath($this->csvFile, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $items = [];
        foreach ($records as $record) {
            $record['module_name'] = $record['extension'];
            $record['security_release'] = $record['security'];
            $items[] = $this->extensionReleaseFactory->create(['data' => $record]);
        }

        return $items;
    }
}
