# EDD Custom Log Views Registration Library

A comprehensive PHP library for registering custom log views and order log links in Easy Digital Downloads. This library provides a robust solution for programmatically creating and managing log views with custom capabilities and flexible organization.

## Features

- 🚀 Easy registration of custom log views
- 🔗 Order detail page log link integration
- 🔒 Capability-based access control
- 📁 Automatic file path management
- 🎯 Flexible view organization
- 🛠️ Simple helper functions
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

### Registering a Custom Log View

```php
edd_register_custom_log_view( [
    'id'         => 'audio_stats',
    'title'      => 'Audio Stats',
    'class_name' => 'Audio_Stats_Log_Table',
    'file'       => 'admin/logs/class-audio-stats-log-table.php',
    'capability' => 'view_shop_reports'
] );
```

### Adding an Order Log Link

```php
edd_register_log_order_link( [
    'id'    => 'audio-stats',
    'label' => __( 'View Audio Stats', 'your-plugin' ),
    'view'  => 'audio_stats'
] );
```

## Configuration Options

### Custom Log View Options

| Option | Type | Required | Description |
|--------|------|----------|-------------|
| id | string | Yes | Unique identifier for the log view |
| title | string | Yes | Display text in the log views menu |
| class_name | string | Yes | Fully qualified class name for the list table |
| file | string | Yes | Path to the class file (relative to base path) |
| capability | string | No | Required capability (defaults to 'view_shop_reports') |
| base_path | string | No | Custom base path for file location |

### Order Log Link Options

| Option | Type | Required | Description |
|--------|------|----------|-------------|
| id | string | Yes | Unique identifier for the log link |
| label | string | Yes | Text to display for the link |
| view | string | Yes | The log view parameter to link to |
| url_callback | callable | No | Custom callback to generate URL |
| display_callback | callable | No | Callback to conditionally display link |
| capability | string | No | Required capability (defaults to 'view_shop_reports') |

## Advanced Usage

### Custom URL Callback

```php
edd_register_log_order_link( [
    'id'           => 'custom-logs',
    'label'        => 'Custom Logs',
    'view'         => 'custom_view',
    'url_callback' => function( $order ) {
        return add_query_arg( [
            'page'     => 'custom-page',
            'order_id' => $order->id
        ], admin_url( 'admin.php' ) );
    }
] );
```

### Conditional Display

```php
edd_register_log_order_link( [
    'id'               => 'audio-stats',
    'label'            => 'Audio Stats',
    'view'             => 'audio_stats',
    'display_callback' => function( $order ) {
        // Only show if order has audio products
        foreach ( $order->items as $item ) {
            $download = edd_get_download( $item->product_id );
            if ( has_audio_files( $download ) ) {
                return true;
            }
        }
        return false;
    }
] );
```

## Direct Class Usage

For more control, you can use the classes directly:

```php
use ArrayPress\EDD\Register\LogViewsManager;
use ArrayPress\EDD\Register\LogOrderLink;

// Get manager instances
$log_views = edd_custom_log_views();
$order_links = edd_log_order_links();

// Register views
$log_views->register( [
    'id'         => 'custom_logs',
    'title'      => 'Custom Logs',
    'class_name' => 'Custom_Logs_Table',
    'file'       => 'class-custom-logs-table.php'
] );

// Register links
$order_links->register( [
    'id'    => 'custom-link',
    'label' => 'Custom Link',
    'view'  => 'custom_logs'
] );
```

## File Organization

By default, the library looks for log table classes relative to your plugin's root directory:

```
your-plugin/
├── admin/
│   └── logs/
│       └── class-audio-stats-log-table.php
├── includes/
└── your-plugin.php
```

You can customize this by providing a custom `base_path` in the view configuration.

## Complete Example

```php
<?php
/**
 * Plugin Name: Audio Stats for EDD
 * Description: Track audio preview statistics
 */

// Register the custom log view
edd_register_custom_log_view( [
    'id'         => 'audio_stats',
    'title'      => __( 'Audio Stats', 'audio-stats' ),
    'class_name' => '\\MyPlugin\\Admin\\Audio_Stats_Table',
    'file'       => 'admin/logs/class-audio-stats-table.php',
    'base_path'  => plugin_dir_path( __FILE__ ) . 'includes/',
    'capability' => 'view_shop_reports'
] );

// Add link to order details page
edd_register_log_order_link( [
    'id'    => 'audio-stats',
    'label' => __( 'View Audio Stats', 'audio-stats' ),
    'view'  => 'audio_stats'
] );
```

## How It Works

The library uses WordPress hooks to integrate with EDD's log system:

1. **Log Views**: Registers with `edd_log_views` filter to add custom views to the logs dropdown
2. **View Handler**: Creates `edd_logs_view_{$id}` action to render your custom table
3. **Order Links**: Hooks into `edd_view_order_details_logs_after` to display links on order pages

## Error Handling

The library uses type declarations and validation:

```php
// Returns false if required fields missing
$result = edd_register_custom_log_view( [
    'id'    => 'example',
    'title' => 'Example Logs'
    // Missing required fields
] );

if ( ! $result ) {
    error_log( 'Failed to register log view' );
}
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the GPL2+ License. See the LICENSE file for details.

## Support

For support, please use the [issue tracker](https://github.com/arraypress/edd-register-log-views/issues).