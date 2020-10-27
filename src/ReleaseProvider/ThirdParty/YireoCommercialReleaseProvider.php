<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\ReleaseProvider\ThirdParty;

use Exception;
use RuntimeException;
use Extdn\ExtensionDashboard\Api\ReleaseProviderInterface;
use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionReleaseFactory;
use GuzzleHttp\Client;

class YireoCommercialReleaseProvider implements ReleaseProviderInterface
{
    /**
     * @var ExtensionReleaseFactory
     */
    private $extensionReleaseFactory;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string[]
     */
    private $urls = [];

    /**
     * YireoCommercialReleaseProvider constructor.
     * @param ExtensionReleaseFactory $extensionReleaseFactory
     */
    public function __construct(
        ExtensionReleaseFactory $extensionReleaseFactory,
        Client $client
    ) {
        // @todo: Solve this in a separate module instead
        // @todo: Use a DI VirtualType for this instead
        $this->urls = [
            'Yireo_TaxRatesManager2' => 'https://api.yireo.com/resource,packages/request,Yireo_TaxRatesManager2/',
            'Yireo_EmailTester2' => 'https://api.yireo.com/resource,packages/request,Yireo_EmailTester2/',
        ];

        $this->extensionReleaseFactory = $extensionReleaseFactory;
        $this->client = $client;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getExtensionReleases(): array
    {
        $items = [];
        foreach ($this->urls as $moduleName => $url) {
            try {
                $data = $this->getDataFromUrl($url);
            } catch (Exception $e) {
                continue;
            }

            foreach ($data['packages'] as $package) {
                $data = $this->getDataFromPackage($moduleName, $package);
                $items[] = $this->extensionReleaseFactory->create(['data' => $data]);
            }
        }

        return $items;
    }

    /**
     * @param string $moduleName
     * @param array $package
     * @return array
     */
    private function getDataFromPackage(string $moduleName, array $package): array
    {
        return [
            'module_name' => $moduleName,
            'version' => $package['version'],
            'date' => $package['version'] ?? '',
            'security_release' => $package['security_release'] ?? 0,
            'content' => $package['content'] ?? '',
        ];
    }

    /**
     * @param string $url
     * @return array
     * @throws RuntimeException
     */
    private function getDataFromUrl(string $url): array
    {
        $response = $this->client->get($url);
        if (!$response) {
            throw new RuntimeException('Invalid response from URL ' . $url);
        }

        $contents = $response->getBody()->getContents();
        if (!$contents) {
            throw new RuntimeException('Empty response from URL ' . $url);
        }

        $data = json_decode($contents, true);
        if (!$data) {
            throw new RuntimeException('Empty data from URL ' . $url);
        }

        return $data;
    }
}
