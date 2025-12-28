# Magento 2 Disable Two-Factor Authentication Module

## Overview
The MagestyApps Disable2FA module allows store administrators to selectively disable Two-Factor Authentication (2FA) for specific admin users in Magento 2. This is particularly useful for development environments, automated testing, or for admin users who don't require the additional security layer.

## Features
- Selectively disable 2FA for specific admin users
- Command-line interface to disable 2FA for users
- Admin interface integration with user edit form
- Compatible with Magento's native Two-Factor Authentication module

## Requirements
- PHP 8.1 or higher
- Magento 2.4.x
- Magento Two-Factor Authentication module

## Installation

### Via Composer
```bash
composer require magestyapps/module-disable-2fa
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
```

### Manual Installation
1. Create the following directory structure in your Magento installation: `app/code/MagestyApps/Disable2FA`
2. Download the module and extract it to the directory you created
3. Enable the module by running the following commands:
```bash
bin/magento module:enable MagestyApps_Disable2FA
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
```

## Configuration

### Admin User Configuration
1. Log in to the Magento Admin Panel
2. Navigate to System > Permissions > All Users
3. Edit an existing user or create a new one
4. In the user edit form, you'll find a new option "Enable 2FA" with Yes/No options
5. Select "No" to disable 2FA for this specific user
6. Save the user

### Command Line Interface
The module provides a command-line interface to disable 2FA for a specific admin user:

```bash
bin/magento security:tfa:disable <username>
```

Replace `<username>` with the admin username for which you want to disable 2FA.

## How It Works
The module works by:
1. Adding a "disable_tfa" field to the admin user database table
2. Adding a corresponding field to the admin user edit form
3. Intercepting the 2FA authentication process and bypassing it for users with 2FA disabled

## Support
For issues, questions, or contributions, please create an issue on the GitHub repository or contact MagestyApps support.

## License
This module is licensed under the Open Software License (OSL 3.0) and the Academic Free License (AFL 3.0).
