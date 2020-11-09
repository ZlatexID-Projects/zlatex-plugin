<?php
if (!defined('ABSPATH')) {
	die();
}

class zl_search_widget extends WP_Widget
{

	/**
	 * Register widget with WordPress.
	 */
	function __construct()
	{
		parent::__construct(
			'zl_search_widget', // Base ID
			esc_html__('Search Events', 'zl_search_widget'), // Name
			array('description' => esc_html__('Displays search', 'zl_search_widget')) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{
		echo $args['before_widget'];
?>
		
		<h2 class="text-primary text-center classes-header"><?php echo $args['before_title'] . esc_html($instance['title']) . $args['after_title']; ?></h2>
		<div class="search">
			<input type="text" class="serch-value">
			<p>
			<button class="serch-events-btn">search</button>
			</p>
			<p>
			1<input type="radio" checked name="importance" value="1" id="imp1">
			2<input type="radio" name="importance" value="2" id="imp2">
			3<input type="radio" name="importance" value="3" id="imp3">
			4<input type="radio" name="importance" value="4" id="imp4">
			5<input type="radio" name="importance" value="5" id="imp5">
			</p>
			<p>
				<label for="from_date">From Date</label>
				<input type="date" name="from_date" id="from_date">
			</p>
			<p>
				<label for="to_date">From Date</label>
				<input type="date" name="to_date" id="to_date">
			</p>
		</div>


	<?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'text_domain');

	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'text_domain'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		return $instance;
	}
} // class Foo_Widget

?>