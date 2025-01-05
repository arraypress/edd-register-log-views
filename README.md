# EDD Log Views Registration Library

A comprehensive PHP library for registering custom log views in Easy Digital Downloads. This library provides a robust solution for programmatically creating and managing log views with custom capabilities and file organization.

## Features

- 🚀 Easy registration of custom log views
- 🔒 Capability-based access control
- 📁 Automatic file path management
- 🎯 Flexible view organization
- 🛠️ Helper functions for quick implementation
- 🔄 Singleton pattern for consistent state
- ✅ Type safety with strict typing

## Requirements

- PHP 7.4 or higher
- WordPress 6.7.1 or higher
- Easy Digital Downloads 3.0 or higher

## Installation

You can install the package via composer:

```bash
composer require arraypress/edd-register-log-views
```

## Basic Usage

Here's a simple example of how to register a custom log view:

```php
use function ArrayPress\EDD\Register\register_log_view;

register_log_view( [
    'id'         => 'guest_verifications',
    'title'      => 'Guest Verifications',
    'class_name' => 'Guest_Verifications_Log_Table',
    'file'       => 'admin/logs/class-guest-verifications-log-table.php',
    'capability' => 'view_shop_reports'
] );
```

## Multiple Views Registration

For registering multiple views at once:

```php
use function ArrayPress\EDD\Register\register_log_views;

$views = [
    [
        'id'         => 'guest_verifications',
        'title'      => 'Guest Verifications',
        'class_name' => 'Guest_Verifications_Log_Table',
        'file'       => 'admin/logs/class-guest-verifications-log-table.php'
    ],
    [
        'id'         => 'download_logs',
        'title'      => 'Download Logs',
        'class_name' => 'Download_Logs_Table',
        'file'       => 'admin/logs/class-download-logs-table.php',
        'capability' => 'manage_shop_settings'
    ]
];

// Base path defaults to plugin directory
register_log_views( $views );

// Or specify a custom base path
register_log_views( $views, dirname(__FILE__) . '/includes' );
```

## Configuration Options

Each log view can be configured with the following options:

| Option | Type | Required | Description |
|--------|------|----------|-------------|
| id | string | Yes | Unique identifier for the log view |
| title | string | Yes | Display text in the log views menu |
| class_name | string | Yes | Fully qualified class name for the list table |
| file | string | Yes | Path to the class file (relative to base path) |
| capability | string | No | Required capability (defaults to 'view_shop_reports') |
| base_path | string | No | Custom base path for file location |

## Direct Class Usage

For more control, you can use the class directly:

```php
use ArrayPress\EDD\Register\LogViewsManager;

// Get the manager instance
$manager = LogViewsManager::instance();

// Register a view
$manager->register( [
    'id'         => 'custom_logs',
    'title'      => 'Custom Logs',
    'class_name' => 'Custom_Logs_Table',
    'file'       => 'class-custom-logs-table.php'
] );
```

## File Organization

By default, the library looks for log table classes relative to your plugin's root directory:

```
your-plugin/
├── admin/
│   └── logs/
│       └── class-guest-verifications-log-table.php
├── includes/
└── your-plugin.php
```

You can customize this by:
1. Providing a custom base_path in the view configuration
2. Passing a base_path to register_log_views()

## Error Handling

The library uses type declarations and strict typing for error prevention:

```php
try {
    register_log_view( [
        'id'    => 'example',
        'title' => 'Example Logs'
    ] );
} catch ( TypeError $e ) {
    // Handle type error
    error_log( $e->getMessage()) ;
}
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the GPL2+ License. See the LICENSE file for details.

## Support

For support, please use the [issue tracker](https://github.com/arraypress/edd-register-log-views/issues).