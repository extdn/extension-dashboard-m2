<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\ReleaseProvider;

use Exception;
use RuntimeException;
use Extdn\ExtensionDashboard\Api\ReleaseProviderInterface;
use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionRelease;
use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionReleaseFactory;
use GuzzleHttp\Client;

class ComposerFeedReleaseProvider implements ReleaseProviderInterface
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
     * @var array
     */
    private $urls;

    /**
     * CsvReleaseProvider constructor.
     * @param ExtensionReleaseFactory $extensionReleaseFactory
     * @param Client $client
     * @param array $urls
     */
    public function __construct(
        ExtensionReleaseFactory $extensionReleaseFactory,
        Client $client,
        array $urls = []
    ) {
        $this->extensionReleaseFactory = $extensionReleaseFactory;
        $this->client = $client;
        $this->urls = $urls;
    }

    /**
     * @return ExtensionRelease[]
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

            foreach ($data['packages'] as $versions) {
                foreach ($versions as $version => $versionData) {
                    $data = $this->getDataFromPackage($moduleName, $version, $versionData);
                    $items[] = $this->extensionReleaseFactory->create(['data' => $data]);
                }
            }
        }

        return $items;
    }

    /**
     * @param string $moduleName
     * @param array $package
     * @return array
     */
    protected function getDataFromPackage(string $moduleName, string $version, array $package): array
    {
        return [
            'module_name' => $moduleName,
            'version' => $version,
            'date' => date('Y-m-d', strtotime($package['time'])),
            'security_release' => 0,
            'content' => '',
        ];
    }

    /**
     * @param string $url
     * @return array
     * @throws RuntimeException
     */
    protected function getDataFromUrl(string $url): array
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
