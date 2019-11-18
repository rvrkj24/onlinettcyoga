<?php
/**
 * Template for displaying title of section in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/section/title.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user        = learn_press_get_current_user();
$course      = learn_press_get_the_course();
$user_course = $user->get_course_data( get_the_ID() );

if ( ! isset( $section ) ) {
	return;
}

$title = $section->get_title();
?>

<?php if ( $title ) { 
     $course_count = $section->count_items( '', false );
     $description = $section->get_description();
     $items = $section->get_items();
     
     if(($course_count == 0) && (empty($description))){ 
        echo '<h4  class="title-without-content">'.$title.'</h4>';
     }else{
     ?>
    <h4 class="section-heading js-toggle-accordion <?php if($section->get_description()) echo 'has_description';?>">
        <span class="collapse"></span>
        <?php echo $title; ?>
        <span class="meta">
		<?php if ( $user->has_enrolled_course( $section->get_course_id() ) ) { ?>
			<span class="step"><?php printf( __( '%d/%d', 'eduma' ), $user_course->get_completed_items( '', false, $section->get_id() ), $section->count_items( '', false ) ); ?></span>
		<?php } else {             
            if($course_count){?>
			<span class="step"><?php echo $course_count; ?></span>
        <?php } 
            } ?>
            </span>
    </h4>
        <?php  } /* else{ */?>
                   <!--  <h4><?php echo $title; ?> </h4> -->
        <?php /* } */ ?>
  
<?php } ?>

