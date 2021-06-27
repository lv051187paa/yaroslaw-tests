<div class="wrap">
    <h1>My plugin page title Required</h1>
    <?php
    settings_errors();
    ?>
    <form method="post" action="options.php">
        <?php
        settings_fields( 'my_test_plugin_group' );
        do_settings_sections( 'my_plugin_slug' );
        submit_button();
        ?>
    </form>
</div>