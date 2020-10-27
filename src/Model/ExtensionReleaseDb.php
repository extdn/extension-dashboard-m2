<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\Model;

use Exception;
use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionRelease;
use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionReleaseFactory;
use Extdn\ExtensionDashboard\ReleaseProvider\ReleaseProviderListing;
use Magento\Framework\Data\Collection as FrameworkDataCollection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Phrase;

class ExtensionReleaseDb extends FrameworkDataCollection
{
    /**
     * @var ExtensionReleaseFactory
     */
    private $extensionReleaseFactory;

    /**
     * @var ReleaseProviderListing
     */
    private $releaseProviderListing;

    /**
     * ExtensionReleaseDb constructor.
     * @param EntityFactoryInterface $entityFactory
     * @param ExtensionReleaseFactory $extensionReleaseFactory
     * @param ReleaseProviderListing $releaseProviderListing
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        ExtensionReleaseFactory $extensionReleaseFactory,
        ReleaseProviderListing $releaseProviderListing
    ) {
        parent::__construct($entityFactory);
        $this->extensionReleaseFactory = $extensionReleaseFactory;
        $this->releaseProviderListing = $releaseProviderListing;
    }

    /**
     * @param bool $printQuery
     * @param bool $logQuery
     * @return FrameworkDataCollection|void
     * @throws Exception
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            $releaseProviders = $this->releaseProviderListing->getList();
            foreach ($releaseProviders as $releaseProvider) {
                foreach ($releaseProvider->getExtensionReleases() as $extensionRelease) {
                    $this->addItem($extensionRelease);
                }
            }

            $this->_setIsLoaded(true);
        }
    }

    /**
     * @param string $name
     * @return bool|Phrase
     */
    public function getLatestReleaseForModule(string $name)
    {
        $latest = false;
        foreach ($this->getItems() as $item) {
            /** @var ExtensionRelease $item */
            if ($item->getModuleName() === $name) {
                if (!$latest) {
                    $latest = $item->getVersion();
                } elseif (version_compare($item->getVersion(), $latest, '>')) {
                    $latest = $item->getVersion();
                }
            }
        }

        if ($latest) {
            return (string)$latest;
        }

        //TODO: Link this back to github repo to invite contributions
        return (string)__('No release data found in DB');
    }

    /**
     * @param string $name
     * @param string $installedVersion
     * @return string
     */
    public function getIsSecure(string $name, string $installedVersion): string
    {
        $found = false;
        $missing = [];
        foreach ($this->getItems() as $item) {
            /** @var ExtensionRelease $item */
            if ($item->getModuleName() === $name) {
                $found = true;
                if (version_compare($item->getVersion(), $installedVersion, '>')
                    && $item->isSecurityRelease()) {
                    $missing[] = $item->getVersion();
                }
            }
        }

        // @todo: Either return true or throw an Exception?
        if (!$found) {
            return (string)__('No release data found in DB');
        }

        // @todo: Make the display a bit nicer, warn with colour
        if (empty($missing)) {
            return (string)__('All applied');
        }

        return __('WARNING missing') . ': ' . implode(',', $missing);
    }
}
