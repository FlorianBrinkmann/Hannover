<?php
/**
 * Select control with all categories.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

/**
 * Class Hannover_Customize_Categories_Control
 */
class Hannover_Customize_Categories_Control extends WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'hannover_categories_control';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 */
	public function to_json() {
		// Call parent to_json() method to get the core defaults like "label", "description", etc.
		parent::to_json();

		// The setting value.
		$this->json['value'] = $this->value();

		// The control choices.
		$this->json['choices'] = $this->choices;

		// The data link.
		$this->json['link'] = $this->get_link();
	}

	/**
	 * Don't render the control's content - it uses a JS template instead.
	 *
	 * @since 4.9.0
	 */
	public function render_content() {
	}

	/**
	 * JS/Underscore template for the control UI.
	 *
	 * @since 4.9.0
	 */
	public function content_template() {
		?>
		<# if ( ! data.choices ) {
			return;
		} #>
		<# var elementId; #>
		<# elementId = _.uniqueId( 'hannover-customize-categories-control-' ); #>
		<ul>
			<li class="customize-control customize-control-hannover-categories-control">
				<label for="{{ elementId }}" class="customize-control-title">{{ data.label }}</label>
				<select id="{{ elementId }}" name="{{ elementId }}" data-customize-setting-key-link="default">
					<# for ( key in data.choices ) { #>
						<option value="{{ key }}"
						<# if ( key === data.value ) { #>
							checked="checked"
						<# } #>>{{ data.choices[ key ] }}</option>
					<# } #>
				</select>
			</li>
		</ul>
		<?php
	} // End if().
}
