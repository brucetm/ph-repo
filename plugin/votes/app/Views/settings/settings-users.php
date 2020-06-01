<?php settings_fields( 'simple-votes-users' ); ?>

<h3><?php _e('User Settings', 'votes'); ?></h3>
<div class="simple-votes-display-settings">
	<div class="row">
		<div class="description">
			<h5><?php _e('Anonymous Users', 'votes'); ?></h5>
			<p><?php _e('Enable voting functionality for unauthenticated users.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><input type="checkbox" name="simplevotes_users[anonymous][display]" value="true" <?php if ( $this->settings_repo->anonymous('display') ) echo ' checked'; ?> data-votes-anonymous-checkbox /><?php _e('Enable Anonymous Users', 'votes'); ?>
			</label>
			<label class="block" data-votes-anonymous-count><input type="checkbox" name="simplevotes_users[anonymous][save]" value="true" <?php if ( $this->settings_repo->anonymous('save') ) echo ' checked'; ?> /><?php _e('Include in Post Vote Count', 'votes'); ?>
			</label>
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('User Cookie Consent', 'votes'); ?></h5>
			<p><?php _e('Require user consent for saving cookies before allowing votes to be saved.', 'votes'); ?></p><p><strong><?php _e('Important:', 'votes'); ?></strong> <?php _e('If using this option for GDPR compliance, please consult an attorney for appropriate legal terms to display in the modal consent.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><input type="checkbox" name="simplevotes_users[consent][require]" value="true" <?php if ( $this->settings_repo->consent('require') ) echo ' checked'; ?> data-votes-require-consent-checkbox /><?php _e('Require User Consent', 'votes'); ?>
			</label>
			<div class="require-consent-modal-content" data-votes-require-consent-modal-content>
				<h3><?php _e('Content to Display in Modal Agreement', 'votes'); ?></h3>
				<?php
					wp_editor($this->settings_repo->consent('modal'), 'simplevotes_users_authentication_modal',
					array(
						'textarea_name' => 'simplevotes_users[consent][modal]',
						'tabindex' => 1,
						'wpautop' => true
						)
					);
				?>
				<p>
					<label class="block"><?php _e('Consent Button Text', 'votes'); ?></label>
					<input type="text" name="simplevotes_users[consent][consent_button_text]" value="<?php echo $this->settings_repo->consent('consent_button_text'); ?>" />
				</p>
				<p>
					<label class="block"><?php _e('Deny Button Text', 'votes'); ?></label>
					<input type="text" name="simplevotes_users[consent][deny_button_text]" value="<?php echo $this->settings_repo->consent('deny_button_text'); ?>" />
				</p>
			</div>
		</div>
	</div><!-- .row -->
	<div class="row" data-votes-require-login>
		<div class="description">
			<h5><?php _e('Anonymous Voting Behavior', 'votes'); ?></h5>
			<p><?php _e('By default, vote buttons are hidden from unauthenticated users if anonymous users are disabled.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><input type="checkbox" name="simplevotes_users[require_login]" value="true" <?php if ( $this->settings_repo->requireLogin() ) echo ' checked'; ?> data-votes-require-login-checkbox data-votes-anonymous-settings="modal" /><?php _e('Show Buttons and Display Modal for Anonymous Users', 'votes'); ?>
			</label>
			<label class="block"><input type="checkbox" name="simplevotes_users[redirect_anonymous]" value="true" <?php if ( $this->settings_repo->redirectAnonymous() ) echo ' checked'; ?> data-votes-redirect-anonymous-checkbox data-votes-anonymous-settings="redirect" /><?php _e('Redirect Anonymous Users to a Page', 'votes'); ?>
			</label>
			<div class="authentication-modal-content" data-votes-authentication-modal-content>
				<h3><?php _e('Edit the Modal Content Below', 'votes'); ?></h3>
				<p><strong><?php _e('Important: ', 'votes'); ?></strong> <?php _e('If plugin css or javascript has been disabled, the modal window will not display correctly.', 'votes'); ?></p>
				<p><?php _e('To add "close" button or link, give it a data attribute of "data-votes-modal-close".', 'votes'); ?></p>
				<?php
					wp_editor($this->settings_repo->authenticationModalContent(true), 'simplevotes_users_authentication_modal',
					array(
						'textarea_name' => 'simplevotes_users[authentication_modal]',
						'tabindex' => 1,
						'wpautop' => true
						)
					);
				?>
			</div>
			<div class="anonymous-redirect-content" data-votes-anonymous-redirect-content>
				<label><?php _e('Enter the Page/Post ID to redirect to (defaults to the site url)', 'sscblog'); ?></label>
				<input type="text" name="simplevotes_users[anonymous_redirect_id]" value="<?php echo $this->settings_repo->redirectAnonymousId(); ?>" />
			</div>
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('Save Unauthenticated Votes as', 'votes'); ?></h5>
			<p><?php _e('Unauthenticated users\' votes may be saved in either cookies or session. Authenticated users\' votes are saved as user meta.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><input type="radio" name="simplevotes_users[anonymous][saveas]" value="cookie" <?php if ( $this->settings_repo->saveType() == 'cookie' ) echo 'checked'; ?>/><?php _e('Cookie', 'votes'); ?>
			</label>
			<label>
				<input type="radio" name="simplevotes_users[anonymous][saveas]" value="session" <?php if ( $this->settings_repo->saveType() == 'session' ) echo 'checked'; ?>/><?php _e('Session', 'votes'); ?>
			</label>
		</div>
	</div><!-- .row -->
</div><!-- .votes-display-settings -->