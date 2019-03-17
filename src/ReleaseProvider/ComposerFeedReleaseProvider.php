<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\ReleaseProvider;

use Exception;
use Extdn\ExtensionDashboard\Api\ReleaseProviderInterface;
use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionRelease;
use Extdn\ExtensionDashboard\ExtensionRelease\ExtensionReleaseFactory;
use GuzzleHttp\Client;

/**
 * Class ComposerFeedReleaseProvider
 * @package Extdn\ExtensionDashboard\ReleaseProvider
 */
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
        $data = [
            'module_name' => $moduleName,
            'version' => $version,
            'date' => date('Y-m-d', strtotime($package['time'])),
            'security_release' => 0,
            'content' => '',
        ];

        return $data;
    }

    /**
     * @param string $url
     * @return array
     * @throws Exception
     */
    protected function getDataFromUrl(string $url): array
    {
        $response = $this->client->get($url);
        if (!$response) {
            throw new Exception('Invalid response from URL ' . $url);
        }

        $contents = $response->getBody()->getContents();
        if (!$contents) {
            throw new Exception('Empty response from URL ' . $url);
        }

        $data = json_decode($contents, true);
        if (!$data) {
            throw new Exception('Empty data from URL ' . $url);
        }

        return $data;
    }
}
