<?php
/**
 * BuddyBoss - Members Settings ( Notifications )
 *
 * @since BuddyPress 3.0.0
 * @version 3.0.0
 */

bp_nouveau_member_hook( 'before', 'settings_template' ); ?>

<h2 class="screen-heading email-settings-screen">
	<?php _e( 'Email Preferences', 'buddyboss' ); ?>
</h2>

<p class="bp-help-text email-notifications-info">
	<?php _e( 'Choose your email notification preferences.', 'buddyboss' ); ?>
</p>

<form action="<?php echo esc_url( bp_displayed_user_domain() . bp_get_settings_slug() . '/notifications' ); ?>" method="post" class="standard-form" id="settings-form">
	<!----------Newsletter---------------->
     <?php
        $profileUserID = bp_displayed_user_id();
        $ac_subscribe = get_user_meta($profileUserID, 'ac_newsletter_subscribe', true);
        if($ac_subscribe==""){
        	$ac_subscribe='subscribed';
        }
     ?>
     <table class="notification-settings" id="newsletter-notification-settings">
		<thead>
			<tr>
				<th class="icon"></th>
				<th class="title"><?php _e( 'Newsletter', 'buddyboss' ); ?></th>
				<th class="yes"><?php _e( 'Yes', 'buddyboss' ); ?></th>
				<th class="no"><?php _e( 'No', 'buddyboss' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr id="newsletter-notification-settings-request">
				<td></td>
				<td><?php _e( 'Receive our always interesting, not-too-frequent, new-articles newsletter', 'buddyboss' ); ?></td>
				<td class="yes">
					<div class="bp-radio-wrap">
						<input type="radio" name="newsletter-subscribe" id="newsletter-subscribe-yes" class="bs-styled-radio" value="subscribed" <?php if($ac_subscribe=='subscribed'){echo 'checked';}?>>
						<label for="newsletter-subscribe-yes"></label>
					</div>
				</td>
				<td class="no">
					<div class="bp-radio-wrap">
						<input type="radio" name="newsletter-subscribe"  id="newsletter-subscribe-no" class="bs-styled-radio" value="unsubscribed" <?php if($ac_subscribe=='unsubscribed'){echo 'checked';}?>>
						<label for="newsletter-subscribe-no"></label>
					</div>
				</td>
			</tr>

		</tbody>
	</table>
	<!----------Newsletter---------------->
	<?php bp_nouveau_member_email_notice_settings(); ?>

	<?php bp_nouveau_submit_button( 'member-notifications-settings' ); ?>

</form>

<?php
bp_nouveau_member_hook( 'after', 'settings_template' );
