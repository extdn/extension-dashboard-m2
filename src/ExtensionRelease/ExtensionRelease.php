<?php
declare(strict_types=1);

namespace Extdn\ExtensionDashboard\ExtensionRelease;

use Magento\Framework\DataObject;

class ExtensionRelease extends DataObject
{
    /**
     * ExtensionRelease constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        // @todo: This does not make sense if we are going to override this anyway
        parent::__construct($data);

        $this->setModuleName((string)$data['module_name']);
        $this->setVersion((string)$data['version']);
        $this->setDate((string)$data['date']);
        $this->setSecurityRelease((bool)$data['security_release']);
        $this->setContent((string)$data['content']);
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return (string) $this->getData('module_name');
    }

    /**
     * @param string $moduleName
     */
    private function setModuleName(string $moduleName): void
    {
        $this->setData('module_name', $moduleName);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return (string) $this->getData('version');
    }

    /**
     * @param string $version
     */
    private function setVersion(string $version): void
    {
        $this->setData('version', $version);
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return (string) $this->getData('date');
    }

    /**
     * @param string $date
     */
    private function setDate(string $date): void
    {
        $this->setData('date', $date);
    }

    /**
     * @return bool
     */
    public function isSecurityRelease(): bool
    {
        return (bool) $this->getData('security');
    }

    /**
     * @param bool $securityRelease
     */
    private function setSecurityRelease(bool $securityRelease): void
    {
        $this->setData('security', $securityRelease);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return (string) $this->getData('content');
    }

    /**
     * @param string $content
     */
    private function setContent(string $content): void
    {
        $this->setData('content', $content);
    }
}
