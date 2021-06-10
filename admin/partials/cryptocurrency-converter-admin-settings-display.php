<?php

?>
<div class="wrap">
    <h2><?php _e("Plugin Configuration", CCC_PLUGIN_SLUG)?></h2>
    <?php settings_errors(); ?>
    <form method="POST" action="options.php">
        <?php
            settings_fields( 'cryptocurrency_converter_general_settings' );
            do_settings_sections( 'cryptocurrency_converter_general_settings' );
        ?>
        <?php submit_button(); ?>
    </form>
</div>
