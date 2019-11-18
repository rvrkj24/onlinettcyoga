<?php

$link           = $regency = '';
$link_to_single = ! empty( $instance['link_to_single'] ) ? true : false;
$limit          = ( $instance['limit'] && '' <> $instance['limit'] ) ? (int) $instance['limit'] : 10;
$item_visible   = ( $instance['item_visible'] && '' <> $instance['item_visible'] ) ? (int) $instance['item_visible'] : 5;
$item_time      = ( $instance['pause_time'] && '' <> $instance['pause_time'] ) ? (int) $instance['pause_time'] : 5000;
$autoplay       = $instance['autoplay'] ? 1 : 0;
$mousewheel     = $instance['mousewheel'] ? 1 : 0;

$testomonial_args = array(
	'post_type'           => 'testimonials',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true
);

$testimonial = new WP_Query( $testomonial_args );

if ( $testimonial->have_posts() ) {
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}

//	$html = '<div class="thim-testimonial-slick thim-js-call-slick" data-verticalswipe = "0" data-verticalslide ="1" data-centermode="1" data-numofslide="3" data-numofscroll="1" data-loopslide="1" data-autoscroll="0" data-speedauto="6000" data-respon="[3, 1], [3, 1], [3, 1], [3, 1], [3, 1]">';
//	$html .= '<div class="slide-slick">';
	$html = '<div class="swiper-container">';
    $html .= '<div class="swiper-wrapper">';
	while ( $testimonial->have_posts() ) : $testimonial->the_post();
		$link    = get_post_meta( get_the_ID(), 'website_url', true );
		$regency = get_post_meta( get_the_ID(), 'regency', true );

		$html .= '<div class="swiper-slide">';
		$html .= '<div class="item-wrapper">';
		$html .= '<div class="content">' . apply_filters( 'the_content', get_the_content() ) . '</div>';

		$html .= '<div class="author">';
		if ( has_post_thumbnail() ) {
			$html .= '<div class="image">';
			$html .= thim_get_feature_image( get_post_thumbnail_id(), 'full', apply_filters( 'thim_testimonial_thumbnail_width', 100 ), apply_filters( 'thim_testimonial_thumbnail_height', 100 ) );
			$html .= '</div>';

			$html .= '<div class="info">';
			if ( $link_to_single ) {
				$html .= '<h3 class="title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
			} else if ( $link <> '' ) {
				$html .= '<h3 class="title"><a href="' . $link . '">' . get_the_title() . '</a></h3>';
			} else {
				$html .= '<h3 class="title">' . get_the_title() . '</h3>';
			}
			$html .= '<div class="regency">' . esc_html( $regency ) . '</div>';
		}
		$html .= '</div></div>';
		$html .= '<div class="swiper-navigation">';
		$html .= '<div class="swiper-button-next"></div>';
		$html .= '<div class="swiper-button-prev"></div>';
		$html .= '</div>';
		$html .= '</div></div>';

	endwhile;
	$html .= '</div>';
	$html .= '</div>';

	wp_reset_postdata();
	echo ent2ncr( $html );
}
?>


