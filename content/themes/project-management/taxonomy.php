<?php
/**
 * Archive Template
 *
 * The archive template is the default template used for archives pages without a more specific template. 
 *
 * @package Marketing
 * @subpackage Template
 */

add_filter( 'sidebars_widgets', 'pm_task_sidebars' );

 /**
 * Disables the primary sidebar
 *
 * @since 0.1
 */
function pm_task_sidebars( $sidebars_widgets ) {
	$sidebars_widgets['primary'] = false;
	
	return $sidebars_widgets;
}

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // marketing_before_content ?>

	<section id="content" role="main" class="span9">

		<?php do_atomic( 'open_content' ); // marketing_open_content ?>

		<div class="hfeed">
			
			<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template. ?>
			
			<?php if ( is_post_type_archive( 'style_guide' ) ) : ?>
				
			<?php global $query_string;
			query_posts( $query_string . '&posts_per_page=40' ); ?>
				
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>
								
				<table class="table table-striped table-bordered">
					
					<thead>
						<tr>
							<th>User Story</th>
							<th>Status</th>
							<th>Priority</th>
							<th>Title</th>
							<th>Assigned To</th>
							<th>Created</th>
							<th>Last Updated</th>
						</tr>
					</thead>
					
					<tbody>
					
					<?php while ( have_posts() ) : the_post(); ?>
	
						<?php do_atomic( 'before_entry' ); // marketing_before_entry ?>
	
						<tr id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?><?php echo pm_task_classes(); ?>">
	
							<?php do_atomic( 'open_entry' ); // marketing_open_entry ?>
							
							<td>
							<?php 
							$user_story = get_post_meta( $post->ID, 'pm_user_story', true );
							$user_story_link = get_post_meta( $post->ID, 'pm_user_story_link', true );
							
							if( $user_story_link && $user_story ) echo '<a href="'. $user_story_link .'" class="user-story" target="_blank">';
							if( $user_story ) echo $user_story;
							if( $user_story_link && $user_story ) echo '</a>';
							?>
							</td>
							
							<td><?php echo get_the_term_list( $post->ID, 'pm_statuses', '<span class="label task-status">', ', ', '</span>' ); ?></td>
							
							<td><?php echo get_the_term_list( $post->ID, 'pm_priority', '<span class="task-priority">', ', ', '</span>' ); ?></td>
							
							<td><?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?></td>
	
							<td><?php echo get_the_term_list( $post->ID, 'pm_people', '', ', ', '' ); ?></td>
							
							<td><?php echo( '<abbr title="'. get_the_date("l, F jS, Y, g:i a", strtotime( $date )) . '">'. get_the_date("M jS @ g:i a", strtotime( $date )) . '</abbr>' ); ?></td>
							
							<td>
								<?php
								$args = array(
									'post_id' => $post->ID,
									'number' => '1'
								);
								$comments = get_comments($args);
								foreach($comments as $comment) :
									$date = $comment->comment_date;
									echo( '<abbr title="'. date("l, F jS, Y, g:i a", strtotime( $date )) . '">'. date("M jS @ g:i a", strtotime( $date )) . ' by '. $comment->comment_author .'</abbr>' );
								endforeach;
								?>
							</td>

						</tr>
	
						<?php do_atomic( 'after_entry' ); // marketing_after_entry ?>
	
					<?php endwhile; ?>
					
					</tbody>
					
				</table>

			<?php else : ?>

				<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

			<?php endif; ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // marketing_close_content ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

	</section><!-- #content -->
	
	<div class="span2">

		<div class="well" style="padding: 8px 0;">
			<ul class="nav nav-list">
				<li class="nav-header">Tasks</li>
				<li><a href="<?php echo bloginfo( 'home' ); ?>/tasks/">All tasks</a></li>
				<li><a href="<?php echo bloginfo( 'home' ); ?>/new-task/"><i class="icon-plus-sign"></i> New task</a></li>
			</ul>
		</div>

		<div class="well" style="padding: 8px 0;">
		<?php the_widget( 'Taxonomy_Drill_Down_Widget', array(
			'title' => '',
			'mode' => 'checkboxes',
			'taxonomies' => array( 'pm_people', 'pm_statuses', 'pm_priority' )
			) ); ?>
					
		</div>
				
	</div>

	<?php do_atomic( 'after_content' ); // marketing_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>