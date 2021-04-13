<?php
/**
 * Autoloading
 *
 * @package Swift_Control
 */

namespace SwiftControlPro;

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Require helper classes.
require __DIR__ . '/helpers/class-export.php';
require __DIR__ . '/helpers/class-import.php';
require __DIR__ . '/helpers.php';

// Require ajax classes.
require __DIR__ . '/ajax/class-change-widgets-order.php';
require __DIR__ . '/ajax/class-change-widget-settings.php';
require __DIR__ . '/ajax/class-save-general-settings.php';
require __DIR__ . '/ajax/class-delete-widget.php';
require __DIR__ . '/ajax/class-save-position.php';

// Require setup classes.
require __DIR__ . '/class-setup.php';
require __DIR__ . '/class-dynamic-widgets.php';

// Require integrations.
require __DIR__ . '/integrations/divi-integration.php';

// Init classes.
new Setup();
new Dynamic_Widgets();
