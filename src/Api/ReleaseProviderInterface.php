<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\Api;

use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionRelease;

interface ReleaseProviderInterface
{
    /**
     * @return ExtensionRelease[]
     */
    public function getExtensionReleases(): array;
}
