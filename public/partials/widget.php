<?php
if (!defined('ABSPATH')) {
	die();
}
/**
 * Adds Foo_Widget widget.
 */
class zlatex_widget extends WP_Widget
{

	/**
	 * Register widget with WordPress.
	 */
	function __construct()
	{
		parent::__construct(
			'zlatex_widget', // Base ID
			esc_html__('Calendar Old Events', 'text_domain'), // Name
			array('description' => esc_html__('Displays calendar', 'text_domain')) // Args
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
		<div class="calendar-old-events">
			<!-- <table>
				<caption>Таблица размеров обуви</caption>
				<tr>
					<th>Россия</th>
					<th>Великобритания</th>
					<th>Европа</th>
					<th>Длина ступни, см</th>
				</tr>
				<tr>
					<td>34,5</td>
					<td>3,5</td>
					<td>36</td>
					<td>23</td>
				</tr>
			</table> -->
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
		$instance          = array();
		$instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		return $instance;
	}
} // class Foo_Widget

?>