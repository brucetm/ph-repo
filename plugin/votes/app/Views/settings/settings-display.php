<?php
settings_fields( 'simple-votes-display' );
$preset_buttons = $this->settings_repo->presetButton();
$button_type_selected = $this->settings_repo->getButtonType();
?>

<h3><?php _e('Enabled Votes for:', 'votes'); ?></h3>
<div class="simple-votes-post-types">
	<?php
		foreach ( $this->post_type_repo->getAllPostTypes() as $posttype ) :
		$post_type_object = get_post_type_object($posttype);
		$display = $this->settings_repo->displayInPostType($posttype);
	?>
	<div class="post-type-row">
		<div class="post-type-checkbox">
			<input type="checkbox" name="simplevotes_display[posttypes][<?php echo $posttype; ?>][display]" value="true" <?php if ( $display ) echo ' checked'; ?> data-votes-posttype-checkbox />
		</div>
		<div class="post-type-name">
			<?php echo $post_type_object->labels->name; ?>
			<button class="button" data-votes-toggle-post-type-settings <?php if ( !$display ) echo 'style="display:none;"';?>><?php _e('Settings', 'votes'); ?></button>
		</div>
		<div class="post-type-settings">
			<div class="row">
				<div class="description">
					<h5><?php _e('Insert Vote button before content', 'votes') ?></h5>
					<p><?php _e('Vote buttons are automatically inserted before the content using the_content filter.', 'votes'); ?></p>
				</div>
				<div class="field">
					<input type="checkbox" name="simplevotes_display[posttypes][<?php echo $posttype; ?>][before_content]" value="true" <?php if ( isset($display['before_content']) ) echo ' checked'; ?>/> <?php _e('Include before content', 'votes'); ?>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Insert Vote button after content', 'votes') ?></h5>
					<p><?php _e('Vote buttons are automatically inserted after the content using the_content filter.', 'votes'); ?></p>
				</div>
				<div class="field">
					<input type="checkbox" name="simplevotes_display[posttypes][<?php echo $posttype; ?>][after_content]" value="true" <?php if ( isset($display['after_content']) ) echo ' checked'; ?>/> <?php _e('Include after content', 'votes'); ?>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Show vote count on post edit screen', 'votes') ?></h5>
					<p><?php _e('Adds a meta box with the total number of votes the post has received.', 'votes'); ?></p>
				</div>
				<div class="field">
					<input type="checkbox" name="simplevotes_display[posttypes][<?php echo $posttype; ?>][postmeta]" value="true" <?php if ( isset($display['postmeta']) ) echo ' checked'; ?>/> <?php _e('Add meta box', 'votes'); ?>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Show vote count in admin columns', 'votes') ?></h5>
					<p><?php _e('Adds a column to the admin listing with the total vote count.', 'votes'); ?></p>
				</div>
				<div class="field">
					<input type="checkbox" name="simplevotes_display[posttypes][<?php echo $posttype; ?>][admincolumns]" value="true" <?php if ( isset($display['admincolumns']) ) echo ' checked'; ?>/> <?php _e('Add admin column', 'votes'); ?>
				</div>
			</div><!-- .row -->
		</div><!-- .post-type-settings -->
	</div><!-- .post-type-row -->
	<?php endforeach; ?>
</div><!-- .simple-votes-post-types -->

<h3><?php _e('Vote Button Content & Appearance', 'votes'); ?></h3>
<div class="simple-votes-display-settings">
	<div class="row">
		<div class="description">
			<h5><?php _e('Button HTML Element', 'votes'); ?></h5>
			<p><?php _e('By default, the button is displayed in an HTML button element.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('Button HTML Element', 'votes'); ?></label>
			<select name="simplevotes_display[button_element_type]">
				<?php $button_type = $this->settings_repo->getButtonHtmlType(); ?>
				<option value="button" <?php if ( $button_type == 'button' ) echo 'selected';?>><?php _e('Button', 'votes'); ?></option>
				<option value="a" <?php if ( $button_type == 'a' ) echo 'selected';?>><?php _e('a (link)', 'votes'); ?></option>
				<option value="div" <?php if ( $button_type == 'div' ) echo 'selected';?>><?php _e('Div', 'votes'); ?></option>
				<option value="span" <?php if ( $button_type == 'span' ) echo 'selected';?>><?php _e('Span', 'votes'); ?></option>
			</select>
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('Button Type', 'votes'); ?></h5>
			<p><?php _e('Use a predefined button or add your own markup.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('Button Type', 'votes'); ?></label>
			<select name="simplevotes_display[buttontype]" data-votes-preset-button-select>
				<option value="custom"><?php _e('Custom Markup', 'votes'); ?></option>
				<?php
				foreach ( $preset_buttons as $button_name => $attrs ){
					$out = '<option value="' . $button_name . '"';
					if ( $button_name == $button_type_selected ) $out .= ' selected';
					$out .= '>' . $attrs['label'] . '</option>';
					echo $out;
				}
				?>
			</select>
			<div class="vote-button-previews" data-votes-preset-button-previews>
				<h4><?php _e('Preview', 'votes'); ?></h4>
				<?php
				foreach ( $preset_buttons as $button_name => $attrs ){
					$out = '<button class="simplevote-button preset '  . $button_name . '" data-votes-button-preview="' . $button_name . '" data-votes-button-active-content="' . $attrs['state_active'] . '" data-votes-button-default-content="' . $attrs['state_default'] . '" data-votes-button-icon="' . htmlentities($attrs['icon']) . '">' . $attrs['icon'] . ' ' . $attrs['state_default'] . ' <span class="simplevote-button-count" >2</span></button>';
					echo $out;
				}
				?>
			</div><!-- .vote-button-previews -->
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('Color Options', 'votes'); ?></h5>
			<p><?php _e('If colors are not specified, theme colors will apply. Note: theme styles will effect the appearance of the votes button. The button is displayed in a button element, with a css class of "simplevotes-button".', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><input type="checkbox" name="simplevotes_display[button_colors][custom]" value="true" data-votes-custom-colors-checkbox <?php if ( $this->settings_repo->buttonColors('custom') ) echo 'checked'; ?> /><?php _e('Specify custom colors', 'votes'); ?></label>
			<div class="color-options" data-votes-custom-colors-options>
				<div class="option-group">
					<h4><?php _e('Default Button State (Unvoted)', 'votes'); ?></h4>
					<?php
					$default_options = $this->settings_repo->colorOptions('default');
					foreach ( $default_options as $option => $label ){
						$out = '<div class="option" data-votes-color-option="' . $option . '">';
						$out .= '<label>' . $label . '</label>';
						$out .= '<input type="text" data-votes-color-picker="' . $option . '" name="simplevotes_display[button_colors][' . $option . ']"';
						$out .= ' value="';
						if ( $this->settings_repo->buttonColors($option) ) $out .= $this->settings_repo->buttonColors($option);
						$out .= '" />';
						$out .= '</div><!-- .option -->';
						echo $out;
					}
					?>
				</div><!-- .option-group -->
				<div class="option-group">
					<h4><?php _e('Active Button State (Voted)', 'votes'); ?></h4>
					<?php
					$default_options = $this->settings_repo->colorOptions('active');
					foreach ( $default_options as $option => $label ){
						$out = '<div class="option" data-votes-color-option="' . $option . '">';
						$out .= '<label>' . $label . '</label>';
						$out .= '<input type="text" data-votes-color-picker="' . $option . '" name="simplevotes_display[button_colors][' . $option . ']"';
						if ( $this->settings_repo->buttonColors($option) ) $out .= ' value="' . $this->settings_repo->buttonColors($option) . '"';
						$out .= '" />';
						$out .= '</div><!-- .option -->';
						echo $out;
					}
					?>
				</div><!-- .option-group -->
				<div class="option-group">
					<div class="option box-shadow">
						<label><input type="checkbox" name="simplevotes_display[button_colors][box_shadow]" value="true" <?php if ( $this->settings_repo->buttonColors('box_shadow') ) echo 'checked'; ?> data-votes-button-shadow /><?php _e('Include button shadow', 'votes'); ?>
					</div>
				</div>
			</div><!-- .color-options -->
		</div>
	</div><!-- .row -->
	<div class="row" data-votes-custom-button-option>
		<div class="description">
			<h5><?php _e('Button Markup: Unvoted', 'votes'); ?></h5>
			<p><?php _e('The button inner html, in an unvoted state.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('Text/HTML', 'votes'); ?></label>
			<input type="text" name="simplevotes_display[buttontext]" value="<?php echo $this->settings_repo->buttonText(); ?>" />
		</div>
	</div><!-- .row -->
	<div class="row" data-votes-custom-button-option>
		<div class="description">
			<h5><?php _e('Button Markup: Voted', 'votes'); ?></h5>
			<p><?php _e('The button inner html, in a voted state.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('Text/HTML', 'votes'); ?></label>
			<input type="text" name="simplevotes_display[buttontextvoted]" value="<?php echo $this->settings_repo->buttonTextVoted(); ?>" />
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('Include Total Vote Count', 'votes'); ?></h5>
			<p><?php _e('Adds the total number of times the post has been voted to the button.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label><input type="checkbox" name="simplevotes_display[buttoncount]" value="true" <?php if ( $this->settings_repo->includeCountInButton() ) echo 'checked'; ?> data-votes-include-count-checkbox /> <?php _e('Include count in button', 'votes'); ?></label>
		</div>
	</div><!-- .row -->
</div><!-- .votes-display-settings -->

<h3><?php _e('Vote Button Loading Indication', 'votes'); ?></h3>
<div class="simple-votes-post-types">
	<div class="post-type-row">
		<div class="post-type-checkbox">
			<input type="checkbox" name="simplevotes_display[loadingindicator][include]" value="true" <?php if ( $this->settings_repo->includeLoadingIndicator() ) echo 'checked'; ?> data-votes-posttype-checkbox />
		</div>
		<div class="post-type-name">
			<?php _e('Display loading indicator for buttons', 'votes'); ?> <em>(<?php _e('Helpful for slow sites with cache enabled', 'votes'); ?>)</em>
			<button class="button" data-votes-toggle-post-type-settings <?php if ( !$display ) echo 'style="display:none;"';?>><?php _e('Settings', 'votes'); ?></button>
		</div>
		<div class="post-type-settings">
			<div class="row">
				<div class="description">
					<h5><?php _e('Loading Text', 'votes') ?></h5>
					<p><?php _e('Replaces the unvoted/voted button text during the loading state.', 'votes'); ?></p>
				</div>
				<div class="field">
					<label class="block"><?php _e('Loading Text/HTML', 'votes'); ?></label>
					<input type="text" name="simplevotes_display[loadingindicator][text]" value="<?php echo $this->settings_repo->loadingText(); ?>" />
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Loading Spinner', 'votes') ?></h5>
					<p><?php _e('Adds a spinner to the button during loading state. See plugin documentation for filters available for theme customization.', 'votes'); ?></p>
				</div>
				<div class="field">
					<label class="block"><input type="checkbox" name="simplevotes_display[loadingindicator][include_html]" value="true" <?php if ( $this->settings_repo->loadingIndicatorType('include_html') ) echo 'checked'; ?> data-votes-spinner-type="html">					<?php _e('Use CSS/HTML Spinner', 'votes'); ?>
					</label>
					<label><input type="checkbox" name="simplevotes_display[loadingindicator][include_image]" value="true" <?php if ( $this->settings_repo->loadingIndicatorType('include_image') ) echo 'checked'; ?> data-votes-spinner-type="image">					<?php _e('Use Image/GIF Spinner', 'votes'); ?>
					</label>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Page Load', 'votes') ?></h5>
					<p><?php _e('Adds the loading state to votes buttons during page load. Helpful on sites with page cache enabled.', 'votes'); ?></p>
				</div>
				<div class="field">
					<label><input type="checkbox" name="simplevotes_display[loadingindicator][include_preload]" value="true" <?php if ( $this->settings_repo->includeLoadingIndicatorPreload() ) echo 'checked'; ?>><?php _e('Add loading state on page load', 'votes'); ?>
				</label>
				</div>
			</div><!-- .row -->
		</div><!-- .post-type-settings -->
	</div><!-- .post-type-row -->
</div><!-- .simple-votes-post-types -->

<h3><?php _e('Listing Display', 'votes'); ?></h3>
<div class="simple-votes-post-types">
	<div class="post-type-row">
		<div class="post-type-checkbox">
			<input type="checkbox" name="simplevotes_display[listing][customize]" value="true" <?php if ( $this->settings_repo->listCustomization() ) echo 'checked'; ?> data-votes-posttype-checkbox />
		</div>
		<div class="post-type-name">
			<?php _e('Customize the votes list HTML', 'votes'); ?>
			<button class="button" data-votes-toggle-post-type-settings <?php if ( !$this->settings_repo->listCustomization() ) echo 'style="display:none;"';?>><?php _e('Settings', 'votes'); ?></button>
		</div>
		<div class="post-type-settings">
			<div class="row">
				<div class="description">
					<h5><?php _e('List Wrapper Element', 'votes') ?></h5>
					<p><?php _e('The list wrapper html element. Defaults to an html ul list.', 'votes'); ?></p>
				</div>
				<div class="field">
					<label class="block"><?php _e('List Wrapper Type', 'votes'); ?></label>
					<select name="simplevotes_display[listing][wrapper_type]">
						<option value="ul" <?php if ( $this->settings_repo->listCustomization('wrapper_type') == 'ul' ) echo 'selected';?>><?php _e('Unordered List (ul)', 'votes'); ?></option>
						<option value="ol" <?php if ( $this->settings_repo->listCustomization('wrapper_type') == 'ol' ) echo 'selected';?>><?php _e('Ordered List (ol)', 'votes'); ?></option>
						<option value="div" <?php if ( $this->settings_repo->listCustomization('wrapper_type') == 'div' ) echo 'selected';?>><?php _e('Div', 'votes'); ?></option>
					</select>
					<p>
						<label class="block"><?php _e('Wrapper CSS Classes', 'votes'); ?></label>
						<input type="text" name="simplevotes_display[listing][wrapper_css]" value="<?php echo $this->settings_repo->listCustomization('wrapper_css'); ?>" />
					</p>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Single List Element', 'votes') ?></h5>
					<p><?php _e('The individual listing html element. Defaults to an html li item.', 'votes'); ?></p>
				</div>
				<div class="field">
					<label class="block"><?php _e('List Element Type', 'votes'); ?></label>
					<select name="simplevotes_display[listing][listing_type]">
						<option value="li" <?php if ( $this->settings_repo->listCustomization('listing_type') == 'ul' ) echo 'selected';?>><?php _e('List Item (li)', 'votes'); ?></option>
						<option value="p" <?php if ( $this->settings_repo->listCustomization('listing_type') == 'ol' ) echo 'selected';?>><?php _e('Paragraph (p)', 'votes'); ?></option>
						<option value="div" <?php if ( $this->settings_repo->listCustomization('listing_type') == 'div' ) echo 'selected';?>><?php _e('Div', 'votes'); ?></option>
					</select>
					<p>
						<label class="block"><?php _e('Listing CSS Classes', 'votes'); ?></label>
						<input type="text" name="simplevotes_display[listing][listing_css]" value="<?php echo $this->settings_repo->listCustomization('listing_css'); ?>" />
					</p>
				</div>
			</div><!-- .row -->
			<div class="row">
				<div class="description">
					<h5><?php _e('Single Listing Content Markup', 'votes') ?></h5>
					<p><?php _e('Optionally customize the single listing markup.', 'votes'); ?></p>
				</div>
				<div class="field">
					<p>
						<label class="block"><input type="checkbox" name="simplevotes_display[listing][customize_markup]" value="true" data-votes-listing-customizer-checkbox <?php if ( $this->settings_repo->listCustomization('customize_markup') ) echo 'checked'; ?>/><?php _e('Customize Content', 'votes'); ?></label>
					</p>
					<div class="simple-votes-listing-customizer" data-votes-listing-customizer>
						<div class="votes-alert">
							<p><strong><?php _e('Important:', 'votes'); ?></strong> <?php _e('By customizing the listing content, some shortcode options will not apply. These options include: include_links, include_buttons, include_thumbnails, and thumbnail_size', 'votes'); ?></p>
						</div>
						<div class="variable-tools">
							<h4><?php _e('Add Dynamic Fields', 'votes'); ?></h4>
							<p><?php _e('To add a custom meta field, use the format <code>[custom_field:custom_field_name]</code>', 'votes'); ?></p>
							<hr>
							<div class="variables">
								<label><?php _e('Post Fields', 'votes'); ?></label>
								<select>
									<option value="[post_title]"><?php _e('Post Title', 'votes'); ?></option>
									<option value="[post_excerpt]"><?php _e('Excerpt', 'votes'); ?></option>
									<option value="[post_permalink]"><?php _e('Permalink (raw link)', 'votes'); ?></option>
									<option value="[permalink][/permalink]"><?php _e('Permalink (as link)', 'votes'); ?></option>
									<?php
									$thumbnail_sizes = get_intermediate_image_sizes();
									foreach ( $thumbnail_sizes as $size ){
										echo '<option value="[post_thumbnail_' . $size . ']">' . __('Thumbnail: ', 'votes') . $size . '</option>';
									}
									?>
								</select>
								<button data-votes-listing-customizer-variable-button class="button"><?php _e('Add', 'votes'); ?></button>
							</div><!-- .variables -->
							<div class="variables right">
								<label><?php _e('Votes Fields', 'votes'); ?></label>
								<select>
									<option value="[votes_button]"><?php _e('Vote Button', 'votes'); ?></option>
									<option value="[votes_count]"><?php _e('Vote Count', 'votes'); ?></option>
								</select>
								<button data-votes-listing-customizer-variable-button class="button"><?php _e('Add', 'votes'); ?></button>
							</div><!-- .variables -->
						</div><!-- .variable-tools -->
						<?php
							wp_editor(
								$this->settings_repo->listCustomization('custom_markup_html'),
								'simplevotes_display_listing_custom_markup',
								array(
									'textarea_name' => 'simplevotes_display[listing][custom_markup_html]',
									'tabindex' => 1,
									'wpautop' => true
								)
							);
						?>
					</div>
				</div><!-- .field -->
			</div><!-- .row -->
		</div><!-- .post-type-settings -->
	</div><!-- .post-type-row -->
</div><!-- .simple-votes-post-types -->


<h3><?php _e('Additional Display Settings', 'votes'); ?></h3>
<div class="simple-votes-display-settings">
	<div class="row">
		<div class="description">
			<h5><?php _e('Clear Votes Button Text', 'votes'); ?></h5>
			<p><?php _e('The text that appears in the "Clear Votes" button by default. Note, the text may be overridden in the shortcode or function call.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('Clear Votes Button Text/HTML', 'votes'); ?></label>
			<input type="text" name="simplevotes_display[clearvotes]" value="<?php echo $this->settings_repo->clearVotesText(); ?>" />
		</div>
	</div><!-- .row -->
	<div class="row">
		<div class="description">
			<h5><?php _e('No Votes Text', 'votes'); ?></h5>
			<p><?php _e('Appears in user vote lists when they have not voted any posts.', 'votes'); ?></p>
		</div>
		<div class="field">
			<label class="block"><?php _e('No Votes Text/HTML', 'votes'); ?></label>
			<input type="text" name="simplevotes_display[novotes]" value="<?php echo $this->settings_repo->noVotesText(); ?>" />
		</div>
	</div><!-- .row -->
</div><!-- .votes-display-settings -->