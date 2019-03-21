<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\Api;

use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionRelease;

/**
 * Class ReleaseProviderInterface
 * @package Extdn\ExtensionDashboard\Api
 */
interface ReleaseProviderInterface
{
    /**
     * @return ExtensionRelease[]
     */
    public function getExtensionReleases(): array;
}
