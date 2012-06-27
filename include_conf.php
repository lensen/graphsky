<?php
$base_dir = dirname(__FILE__);

require_once $base_dir . "/conf_default.php";

# Include user-defined overrides if they exist.
if( file_exists( $base_dir . "/conf.php" ) ) {
    include_once $base_dir . "/conf.php";
}
?>
