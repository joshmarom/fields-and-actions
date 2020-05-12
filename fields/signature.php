<?php
use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Fields\Field_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Signature extends Field_Base {

	public $depended_scripts = [
		'signature-pad',
	];

	public $depended_styles = [
		'signature-pad',
	];

	public function get_type() {
		return 'signature';
	}

	public function get_name() {
		return __( 'Signature', 'e-signature' );
	}

	public function render( $item, $item_index, $form ) {
		$form_id = $form->get_id();
		$unique_id = 'signaturePad_' . $form_id . $item_index;
		$form->add_render_attribute( 'input' . $item_index, [
			'type' => 'hidden',
			'for' => $unique_id,
		], null, true );
		$form->add_render_attribute( 'clear-button' . $item_index, [
			'type' => 'button',
			'for' => $unique_id,
		] );
		$form->add_render_attribute( 'canvas' . $item_index, [
			'id' => $unique_id,
			'class' => 'elementor-signature-field',
		] );
		?>
		<input <?php echo $form->get_render_attribute_string( 'input' . $item_index ) ?>>
		<canvas <?php echo $form->get_render_attribute_string( 'canvas' . $item_index ) ?>></canvas>
		<button <?php echo $form->get_render_attribute_string( 'clear-button' . $item_index ) ?>>
			<?php echo __( 'Clear', 'e-signature' ); ?>
		</button>
		<?php
		add_action( 'wp_footer', [ $this, 'front_end_inline_JS' ] );
	}

	public function front_end_inline_JS() {
		$action = __CLASS__ . 'front_end_inline_JS';
		if ( did_action( $action ) ) {
			return;
		}
		do_action( $action );
		?>
		<script>
			jQuery( document ).ready( function( $ ) {
				$canvas = $( 'canvas.elementor-signature-field' );
				$canvas.each( ( index, element ) => {
					const uid = element.id,
						$input = jQuery( 'input[for="' + uid + '"]' ),
						$clearButton = jQuery( 'button[for="' + uid + '"]' ),
						signaturePad = new SignaturePad( element );

					$clearButton.click( () => {
						signaturePad.clear();

						$input.val( '' );
					} );

					$( element ).mouseup( () => {
						const dataURL = signaturePad.toDataURL();

						$input.val( dataURL );
					} );

				} );
			} );
		</script>
		<?php
	}

	public function __construct() {
		parent::__construct();
		wp_register_script( 'signature-pad','//cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js', [], '2.3.2', true );
		wp_register_style( 'signature-pad', plugins_url( '/assets/signature_pad.css', __FILE__ ), [], '0.1' );
	}
}
