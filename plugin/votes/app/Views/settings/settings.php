<div class="wrap">
	<h1><?php echo $this->plugin_name . ' '; _e('Settings', 'votes'); ?></h1>

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if ( $tab == 'general' ) echo 'nav-tab-active'; ?>" href="options-general.php?page=simple-votes">
			<?php _e('General', 'votes'); ?>
		</a>
		<a class="nav-tab <?php if ( $tab == 'users' ) echo 'nav-tab-active'; ?>" href="options-general.php?page=simple-votes&tab=users">
			<?php _e('Users', 'votes'); ?>
		</a>
		<a class="nav-tab <?php if ( $tab == 'display' ) echo 'nav-tab-active'; ?>" href="options-general.php?page=simple-votes&tab=display">
			<?php _e('Display & Post Types', 'votes'); ?>
		</a>
	</h2>

	<form method="post" enctype="multipart/form-data" action="options.php">
		<?php include(Votes\Helpers::view('settings/settings-' . $tab)); ?>
		<?php submit_button(); ?>
	</form>
</div><!-- .wrap -->