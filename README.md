# Extension Dashboard
Adds a dashboard to review all installed extensions in the Magento admin.
[Screenshot Dashboard](/docs/extension_dashboard.png?raw=true "Extension Dashboard")

[Screenshot Admin > System > Extension Dashboard](/docs/admin_menu_entry.png?raw=true "Admin Menu")

## Installation

```
bin/magento deploy:mode:set developer (if in production mode)
composer config repositories.extdndash git https://github.com/extdn/extension-dashboard-m2.git
composer require extdn/extension-dashboard-m2
bin/magento module:enable Extdn_ExtensionDashboard
bin/magento setup:upgrade  
your usual sequence of commands to enable production mode if you started out in production mode
(for example bin/magento deploy:mode:set production)
```