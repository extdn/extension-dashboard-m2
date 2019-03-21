# Extension Dashboard for Magento 2
This module adds a dashboard to review all installed extensions in the Magento admin (Magento 2.3.0+ for now only).

## Screenshots
![Screenshot Dashboard](docs/extension_dashboard.png?raw=true")

![Screenshot Admin > System > Extension Dashboard](docs/admin_menu_entry.png?raw=true)

## Installation
We strongly recommend to always make changes to a Magento 2 site through a development environment that runs in the Developer Mode:
```bash
bin/magento deploy:mode:set developer
```

Add this Git repository to composer and then install the composer package:
```bash
composer config repositories.extdndash git https://github.com/extdn/extension-dashboard-m2.git
composer require extdn/extension-dashboard-m2:dev-master
```

Next, enable the module:
```bash
bin/magento module:enable Extdn_ExtensionDashboard
bin/magento setup:upgrade
```

Next, follow the usual procedure to push changes from the development environment to production (for example with `bin/magento deploy:mode:set production`).

## Extension feeds
This dashboard is being fed through feeds: Either a CSV-file or a remote resource that allows you to define version-information. Currently, the following is supported:

- Define a new DI VirtualType (see `di.xml`) to use the `ComposerFeedProvider` to load information from Packagist.
- Use the `CsvFeedProvider` to load information from a local CSV file.
- Add a custom provider to the listing of providers.

## Todo
- Move extension feeds to different submodules?
- Automatically fetch information from Packagist on existing extensions, if available.