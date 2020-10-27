<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\ReleaseProvider;

use Extdn\ExtensionDashboard\Api\ReleaseProviderInterface;

class ReleaseProviderListing
{
    /**
     * @var ReleaseProviderInterface[]
     */
    private $releaseProviders;

    /**
     * ReleaseProviders constructor.
     * @param ReleaseProviderInterface[] $releaseProviders
     */
    public function __construct(
        array $releaseProviders = []
    ) {
        $this->releaseProviders = $releaseProviders;
    }

    /**
     * @param ReleaseProviderInterface $releaseProvider
     */
    public function add(ReleaseProviderInterface $releaseProvider)
    {
        $this->releaseProviders[] = $releaseProvider;
    }

    /**
     * @return ReleaseProviderInterface[]
     */
    public function getList()
    {
        return $this->releaseProviders;
    }
}
