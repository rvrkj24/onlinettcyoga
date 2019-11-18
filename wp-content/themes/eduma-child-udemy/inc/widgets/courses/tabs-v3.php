<?php
global $post, $wpdb;
$random     = rand( 1, 99 );
$limit_tab  = $instance['tabs-options']['limit_tab'] ? $instance['tabs-options']['limit_tab'] : 4;
$cat_id_tab = $instance['tabs-options']['cat_id_tab'] ? $instance['tabs-options']['cat_id_tab'] : array();
$limit      = $instance['limit'];
$featured   = ! empty( $instance['featured'] ) ? true : false;
$sort       = $instance['order'];
$thumb_w    = ( ! empty( $instance['thumbnail_width'] ) && '' != $instance['thumbnail_width'] ) ? $instance['thumbnail_width'] : apply_filters( 'thim_course_thumbnail_width', 450 );
$thumb_h    = ( ! empty( $instance['thumbnail_height'] ) && '' != $instance['thumbnail_height'] ) ? $instance['thumbnail_height'] : apply_filters( 'thim_course_thumbnail_height', 400 );

if ( ! empty( $cat_id_tab ) ) {
	foreach ( $cat_id_tab as $value ) {
		$array[ $value ] = 1;
		$html[ $value ]  = '';

		$condition[ $value ]              = array(
			'post_type'           => 'lp_course',
			'posts_per_page'      => $limit_tab,
			'ignore_sticky_posts' => true,
		);
		$condition[ $value ]['tax_query'] = array(
			array(
				'taxonomy' => 'course_category',
				'field'    => 'term_id',
				'terms'    => $value
			),
		);

		if ( $featured ) {
			$condition[ $value ]['meta_query'] = array(
				array(
					'key'   => '_lp_featured',
					'value' => 'yes',
				)
			);
		}

		if ( $sort == 'popular' ) {

			$query = $wpdb->prepare( "
	  SELECT ID, a+IF(b IS NULL, 0, b) AS students FROM(
		SELECT p.ID as ID, IF(pm.meta_value, pm.meta_value, 0) as a, (
	SELECT COUNT(*)
  FROM (SELECT COUNT(item_id), item_id, user_id FROM {$wpdb->prefix}learnpress_user_items GROUP BY item_id, user_id) AS Y
  GROUP BY item_id
  HAVING item_id = p.ID
) AS b
FROM {$wpdb->posts} p
LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id  AND pm.meta_key = %s
WHERE p.post_type = %s AND p.post_status = %s
GROUP BY ID
) AS Z
ORDER BY students DESC
 ", '_lp_students', 'lp_course', 'publish' );

			$post_in = $wpdb->get_col( $query );

			$condition[ $value ]['post__in'] = $post_in;
			$condition[ $value ]['orderby']  = 'post__in';
		}

		$the_query[ $value ] = new WP_Query( $condition[ $value ] );

		if ( $the_query[ $value ]->have_posts() ) :
			?>
			<?php
			ob_start();
			while ( $the_query[ $value ]->have_posts() ) : $the_query[ $value ]->the_post();
				$course = LP_Course::get_course( $post->ID );
				$course_duration = get_post_meta( $course->get_id(), 'thim_course_duration', true );
				if( thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
					$num_ratings = learn_press_get_course_rate_total($course->get_id()) ? learn_press_get_course_rate_total($course->get_id()) : 0;
				}

				$class_course = '';
				if($course-> has_sale_price() || $course->is_free()){
					$class_course = 'color-1';
				}else{
					$class_course = 'color-2';
				}
				?>
				<div class="course-item lpr_course <?php echo 'course-grid-' . $limit_tab; ?> <?php echo $class_course; ?>">
					<div class="course-item-wrapper">
						<div class="course-thumbnail">
							<a href="<?php echo esc_url( get_the_permalink() ); ?>" >
								<?php echo thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', apply_filters( 'thim_course_thumbnail_width', 500 ), apply_filters( 'thim_course_thumbnail_height', 387 ), get_the_title() ); ?>
							</a>
							<div class="price<?php if($course->is_free()) echo ' free';?>">
								<?php if ( $course->has_sale_price() ) { ?>
									<span class="old-price"> <?php echo esc_html($course->get_origin_price_html()); ?></span>
								<?php } ?>
								<?php echo esc_html($course->get_price_html()); ?>
							</div>
						</div>
						<div class="thim-course-content">
							<?php
							learn_press_course_instructor();
							?>
							<h2 class="course-title">
								<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
							</h2>

							<div class="course-meta">
								<span>
	                                <i class="ion ion-android-person"></i>
									<?php echo intval($course->count_students());?>
								</span>

								<?php
								$courses_tag = get_the_terms($course->get_id(),'course_category');
								if($courses_tag) {
									?>
									<span>
									<i class="ion ion-ios-pricetags-outline"></i>
									<a href="<?php echo get_term_link($courses_tag[0]->term_id) ?>">
									<?php
									echo esc_html($courses_tag[0]-> name);
									?>
								</a>
								</span>
								<?php }?>

								<?php if( thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {?>
									<span class="star">
                                    <i class="ion ion-android-star"></i>
										<?php echo intval($num_ratings); ?>
                                </span>
								<?php }?>

							</div>

							<div class="thim-course-content-hover">
								<div class="thim-course-content-hover-box">
									<span class="course-date"><?php echo esc_html__('Last updated: ','eduma') . get_the_date('n/Y');?></span>
									<h2 class="course-title">
										<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
									</h2>
									<div class="course-feature">
										<span class="course-lectures">
											<i class="ion-document"></i>
											<?php echo $course->get_curriculum_items( 'lp_lesson' ) ? count( $course->get_curriculum_items( 'lp_lesson' ) ) . esc_html__(' lectures','eduma') : 0 . esc_html__(' lecture','eduma'); ?>
										</span>
										<?php if ( ! empty( $course_duration ) ): ?>
											<span class="course-duration">
												<i class="ion-android-time"></i>
												<?php echo $course_duration; ?>
											</span>
										<?php endif; ?>
									</div>
									<div class="course-excerpt">
										<?php echo thim_excerpt(50); ?>
									</div>
									<?php
									if( is_admin() ){
										LP_Global::set_user(learn_press_get_current_user());
									}

									do_action('thim-udemy-course-buttons');

									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
			endwhile;
			$html[ $value ] .= ob_get_contents();
			ob_end_clean();
			?>

		<?php
		endif;
		wp_reset_postdata();
	}
} else {
	return;
}

if ( $instance['title'] ) {
	echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
}

$list_tab = $content_tab = '';
if ( ! empty( $cat_id_tab ) ) {
	foreach ( $cat_id_tab as $k => $tab ) {
		$term = get_term_by( 'id', $tab, 'course_category' );
		if ( $k == 0 ) {
			$list_tab    .= '<li class="active"><a href="#tab-course-' . $random . '-' . $tab . '" data-toggle="tab">' . $term->name . '</a></li>';
			$content_tab .= '<div role="tabpanel" class="tab-pane fade in active" id="tab-course-' . $random . '-' . $tab . '">';
			$content_tab .= $html[ $tab ];
			$content_tab .= '</div>';
		} else {
			$list_tab    .= '<li><a href="#tab-course-' . $random . '-' . $tab . '" data-toggle="tab">' . $term->name . '</a></li>';
			$content_tab .= '<div role="tabpanel" class="tab-pane fade" id="tab-course-' . $random . '-' . $tab . '">';
			$content_tab .= $html[ $tab ];
			$content_tab .= '</div>';
		}
	}
}
?>
<div class="thim-category-tabs thim-course-grid">
	<ul class="nav nav-tabs">
		<?php echo ent2ncr( $list_tab ); ?>
	</ul>
	<div class="clearfix"></div>
	<div class="tab-content thim-list-event">
		<?php echo ent2ncr( $content_tab ); ?>
	</div>
</div>
