<?php settings_fields( 'simple-votes-general' ); ?>

<h3><?php _e('Page Cache', 'votes'); ?></h3>
<div class="simple-votes-post-types">
	<div class="post-type-row">
		<div class="post-type-checkbox">
			<input type="checkbox" name="simplevotes_cache_enabled" value="true" <?php if ( $this->settings_repo->cacheEnabled() ) echo 'checked'; ?> />
		</div>
		<div class="post-type-name">
			<?php _e('Cache Enabled on Site (Votes content is injected on page load with AJAX request)', 'votes'); ?>
		</div>
	</div><!-- .post-type-row -->
</div><!-- .simple-votes-post-types -->

<h3><?php _e('Development Mode', 'votes'); ?></h3>
<div class="simple-votes-post-types">
	<div class="post-type-row">
		<div class="post-type-checkbox">
			<input type="checkbox" name="simplevotes_dev_mode" value="true" <?php if ( $this->settings_repo->devMode() ) echo 'checked'; ?> />
		</div>
		<div class="post-type-name">
			<?php _e('Enable Development Mode (logs JS responses in the console for debugging)'); ?>
		</div>
	</div><!-- .post-type-row -->
</div><!-- .simple-votes-post-types -->

<h3><?php _e('Dependencies', 'votes'); ?></h3>
<div class="simple-votes-display-settings">
	<div class="row">
		<div class="description">
			<h5><?php _e('Enqueue Plugin CSS', 'votes'); ?></h5>
		</div>
		<div class="field">
			<label class="block"><input type="checkbox" name="simplevotes_dependencies[css]" value="true" data-votes-dependency-checkbox <?php if ( $this->settings_repo->outputDependency('css') ) echo 'checked'; ?> /><?php _e('Output Plugin CSS', 'votes'); ?>
			</label>
			<div class="simplevotes-dependency-content" data-votes-dependency-content>
				<p><em><?php _e('If you are compiling your own minified CSS, include the CSS below:', 'votes'); ?></em></p>
				<textarea><?php echo Votes\Helpers::getFileContents('assets/css/styles-uncompressed.css'); ?></textarea>
			</div>
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('Enqueue Plugin Javascript', 'votes'); ?></h5>
			<p><?php _e('Important: The plugin JavaScript is required for core functions. If this is disabled, the plugin JS <strong>must</strong> be included with the theme along with the global JS variables.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block">
				<input type="checkbox" name="simplevotes_dependencies[js]" value="true" data-votes-dependency-checkbox <?php if ( $this->settings_repo->outputDependency('js') ) echo 'checked'; ?> /><?php _e('Output Plugin JavaScript', 'votes'); ?>
			</label>
			<div class="simplevotes-dependency-content" data-votes-dependency-content>
				<p><em><?php _e('If you are compiling your own minified Javascript, include the below (required for plugin functionality):', 'votes'); ?></em></p>
				<textarea><?php echo Votes\Helpers::getFileContents('assets/js/votes.js'); ?></textarea>
			</div>
		</div>
	</div><!-- .row -->
</div><!-- .votes-display-settings -->

<div class="votes-alert">
	<p><strong><?php _e('Votes Version', 'votes'); ?>:</strong> <?php echo Votes\Helpers::version(); ?></p>
</div>