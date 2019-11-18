<div class="course-info-top">
	<div class="container">
		<div class="row">
			<div class="course-info-left col-sm-8">
				<?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>
				<?php the_excerpt();?>
				<div class="course-meta">
					<?php do_action( 'thim_single_course_meta' );?>
				</div>
			</div>
<!--			<div id="sidebar" class="course-info-right col-sm-4 sticky-sidebar">-->
<!--				<div class="course-info-wrapper">-->
<!--					<div class="right-col__content">-->
<!--						<div class="right-col__wrapper">-->
<!--							--><?php
//							do_action('learn-press/course-info-right');
//							?>
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
		</div>
	</div>
</div>