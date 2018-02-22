<?php
/*
Plugin Name: TK Notifications
Description: Adds subscription module for visitor emails
Plugin URI:
Author:      Tommi Kivimäki
Version:     0.1
*/


// process submitted form
function tk_notifications_process_email() {

	// get the nonce
	if ( isset( $_POST['tk_notifications_nonce_field'] ) ) {

		$nonce = $_POST['tk_notifications_nonce_field'];

	} else {

		$nonce = false;

	}

	// process form
	if ( isset( $_POST['tk_notifications_email'] ) ) {

		// verify nonce
		if ( ! wp_verify_nonce( $nonce, 'tk_notifications_form_action' ) ) {

			wp_die( 'Incorrect nonce!' );

		} else {

			$email = sanitize_text_field( $_POST[ 'tk_notifications_email' ] );

			if ( ! empty( $email ) ) {

				echo '<p>Your email is '. $email .'.</p>';

			} else {

				echo '<p>Please enter your email!</p>';

			}

		}

	}

}


// widget example: clean markup
class TK_Notifications_Widget extends WP_Widget {

//
// Constructor
//
	public function __construct() {

		$id = 'tk_notifications';

		$title = esc_html__('TK Notifications', 'custom-widget');

		$options = array(
			'classname' => 'tk_notifications_widget',
			'description' => esc_html__('Adds clean markup that is not modified by WordPress.', 'custom-widget')
		);

		parent::__construct( $id, $title, $options );

	}


//
// Outputs the widget content to a site
//
	public function widget( $args, $instance ) {

		// extract( $args );

		// $markup = '';
		//
		// if ( isset( $instance['markup'] ) ) {
		//
		// 	echo wp_kses_post( $instance['markup'] ); // Sanitize the user entered input in $instance


		// display form
			?>

			<form method="post">
				<p><label for="email">Enter your email:</label></p>
				<p><input id="email" type="text" name="tk_notifications_email"></p>
				<p><input type="submit" value="Submit Form"></p>

				<?php wp_nonce_field( 'tk_notifications_form_action', 'tk_notifications_nonce_field', false ); ?>

			</form>

		<?php

		}




//
// Process widget options
//
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		if ( isset( $new_instance['markup'] ) && ! empty( $new_instance['markup'] ) ) {

			$instance['markup'] = $new_instance['markup'];

		}

		return $instance;

	}

//
// Displays the form in the admin area
//
	public function form( $instance ) {

		$id = $this->get_field_id( 'markup' );

		$for = $this->get_field_id( 'markup' );

		$name = $this->get_field_name( 'markup' );

		$label = __( 'Markup/text:', 'custom-widget' );

		$markup = '<p>'. __( 'Clean markup.', 'custom-widget' ) .'</p>';

		if ( isset( $instance['markup'] ) && ! empty( $instance['markup'] ) ) {

			$markup = $instance['markup'];

		}

		?>

		<p>
			<label for="<?php echo esc_attr( $for ); ?>"><?php echo esc_html( $label ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $markup ); ?></textarea>
		</p>

<?php }

}

//
// Registers widget
//
function tk_notifications_register_widgets() {

	register_widget( 'TK_Notifications_Widget' );

}
add_action( 'widgets_init', 'tk_notifications_register_widgets' );
