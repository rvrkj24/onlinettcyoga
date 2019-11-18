<?php
/**
 * Template for displaying instructor of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/instructor.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
global $post;
$post_id = $post->ID;

//$course_teacher_name = get_post_meta($post_id,'course_teacher_name',true);
$course_teacher_name = get_field('teacher_name',$post_id);
//$course_teacher_img  = get_post_meta($post_id,'course_teacher_img',true);
$course_teacher_img = get_field('teacher_image',$post_id);
//$course_teacher_pageurl	 = get_post_meta($post_id,'course_teacher_pageurl',true);
$course_techer_pageurl= get_field('teacher_page_url',$post_id);
?>

<div class="course-author">

	<div class="course-author-content">
		<?php //echo $course->get_instructor()->get_profile_picture(); 
		if(!empty($course_teacher_img)){?>
		<img src="<?php echo $course_teacher_img; ?>" height="96" width="96">
		<?php } ?>
		<div class="author-contain">
			<label itemprop="jobTitle"><?php esc_html_e( 'Yoga Teacher', 'eduma' ); ?></label>
			<div class="value" itemprop="name">
				<a href="<?php echo $course_teacher_pageurl; ?>"><?php echo $course_teacher_name; //echo $course->get_instructor_html(); ?></a>
			</div>
		</div>
	</div>
</div>
