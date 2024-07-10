<?php
/*
Plugin Name: Recents Posts Shortcode
Description: Display last 10 posts by using shortcode
Version: 1.0.0
Author: Sergei Konovalov
Author URI: https://www.linkedin.com/in/sergei-konovalov-a901b8187/
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/PluginLogger.php';

class Recent_Posts_Shortcode {

	/**
	 * Property for LoggerInterface
	 */
	public $logger;

	/**
	 * Static property to hold our singleton instance
	 *
	 */
	static $instance = false;

	/**
	 * Field with posts quantity to display
	 *
	 * @var int
	 *
	 */
	private int $quantity;

	/**
	 * This is our constructor
	 *
	 * @return void
	 */
	public function __construct() {
		// back end
		$this->logger = new PluginLogger();
		$this->set_quantity( intval( get_option( 'posts_quantity' ) ) ?? 10 );
		add_action( 'admin_menu', array( $this, 'add_plugin_settings' ) );
		add_action( 'admin_init', array( $this, 'rps_plugin_settings' ) );
		add_shortcode( 'rps_recent_posts', array( $this, 'show_recent_posts' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ), 10 );
		$this->logger->info( "Plugin Constructed", [ "method" => 'construct' ] );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return Recent_Posts_Shortcode
	 */

	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Adding options page for the plugin
	 */
	public function add_plugin_settings() {
		try {
			add_options_page(
				'Recent Posts Shortcode Settings',
				'Recent Posts Shortcode Settings',
				'manage_options',
				'rps',
				[ $this, 'rps_options_page_output' ],
			);
		} catch ( Exception $ex ) {
			$this->logger->error( $ex->getMessage() );
		}
	}

	/**
	 * Settings page Output
	 *
	 * @return void
	 */
	public function rps_options_page_output() {
		?>
        <div class="wrap">
            <h2><?= get_admin_page_title() ?></h2>

            <form action="options.php" method="POST">
				<?php
				settings_fields( 'rps_option_group' );
				do_settings_sections( 'rps_page' );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	/**
	 * Add settings to settings page
	 * @return void
	 */
	public function rps_plugin_settings() {
		try {
			register_setting( 'rps_option_group', 'posts_quantity', [ $this, 'sanitize_callback' ] );
			add_settings_section( 'rps_section_id', 'RPS Settings', '', 'rps_page' );
			add_settings_field( 'rps_quantity_field', 'Posts Quantity to display', [
				$this,
				'fill_rps_quantity_field'
			], 'rps_page', 'rps_section_id' );
		} catch ( Exception $ex ) {
			$this->logger->error( $ex->getMessage() );
		}
	}

	/**
	 * Fill the settings
	 * @return void
	 */
	public function fill_rps_quantity_field() {
		$quantity = $this->get_quantity();
		?>
        <label><input type="number" name="posts_quantity" value="<?= $quantity ?>">Quantity of posts to display</label>
		<?php
	}

	/**
	 * Sanitize option
	 *
	 * @param $option
	 *
	 * @return int
	 */
	public function sanitize_callback( $option ) {
		return intval( $option );
	}

	public function prepare_items() {
		try {
			if ( $this->logger ) {
				error_log( "here" );
				$this->logger->info( 'Doing work' );
			}
			$posts = get_posts( [
				'numberposts' => $this->get_quantity(),
			] );
		} catch ( Exception $ex ) {
			$this->logger->error( $ex->getMessage() );
		}

		return $posts ?? null;
	}

	/**
	 * Get posts quantity to display
	 * @return int
	 */
	public function get_quantity() {
		return $this->quantity;
	}

	/**
	 * Set posts quantity
	 *
	 * @param int $quantity
	 *
	 * @return void
	 */
	public function set_quantity( int $quantity ) {
		$this->quantity = $quantity;
	}

	/**
	 * Callback function for shortcode
	 * @return string
	 */
	public function show_recent_posts() {

        $html  = '<div class="recent-posts-wrapper">';
        $posts = $this->prepare_items();
        if ( ! $posts ) {
            $html .= '<span>Nothing to display.</span>';
        } else {
            foreach ( $posts as $post ) {
                $html .= '<div class="recent-posts-item">';
                $html .= '<a href="' . get_permalink( $post ) . '" class="item-link">' . get_the_title( $post ) . '</a>';
                $html .= '</div>';
            }
        }
        $html .= '</div>';

		return $html;
	}

	/**
	 * call front-end CSS
	 *
	 * @return void
	 */

	public function front_scripts() {
		wp_enqueue_style( 'rps-notes', plugins_url( 'style.css', __FILE__ ), array(), 1.0, 'all' );
	}

}

// Instantiate our class
$Recent_Posts_Shortcode = Recent_Posts_Shortcode::getInstance();
