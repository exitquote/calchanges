<h2 class="ai1ec-calendar-title"><?php echo esc_html( $title ) ?></h2>
<span class="ai1ec-title-buttons">
	<a id="ai1ec-today" class="ai1ec-load-view ai1ec-button" href="#action=ai1ec_week&amp;ai1ec_post_ids=<?php echo $post_ids ?>">
		<?php _e( 'Today', AI1EC_PLUGIN_NAME ) ?>
	</a>
</span>
<ul class="ai1ec-pagination">
	<?php foreach( $pagination_links as $link ) : ?>
		<li>
			<a id="<?php echo $link['id'] ?>"
				class="ai1ec-load-view ai1ec-button"
				href="<?php echo esc_attr( $link['href'] ) ?>&amp;ai1ec_post_ids=<?php echo $post_ids ?>">
				<?php echo esc_html( $link['text'] ) ?>
			</a>
		</li>
	<?php endforeach ?>
</ul>
<table class="ai1ec-week-view-original">
	<thead>
		<tr>
			<?php foreach( $cell_array as $date => $day ) : ?>
				<th class="ai1ec-weekday <?php if( $day['today'] ) echo 'ai1ec-today' ?>">
					<span class="ai1ec-weekday-date"><?php echo date_i18n( 'j', $date, true ) ?></span>
					<span class="ai1ec-weekday-day"><?php echo date_i18n( 'D', $date, true ) ?></span>
				</th>
			<?php endforeach // weekday ?>
		</tr>
		<tr>
			<?php foreach( $cell_array as $day ) : ?>
				<td class="ai1ec-allday-events <?php if( $day['today'] ) echo 'ai1ec-today' ?>">

					<?php if( ! $done_allday_label ) : ?>
						<div class="ai1ec-allday-label"><?php _e( 'All-day', AI1EC_PLUGIN_NAME ) ?></div>
						<?php $done_allday_label = true ?>
					<?php endif ?>

					<?php foreach( $day['allday'] as $event ) : ?>
						<?php if (isset($event->ical_source_url) && !empty($event->ical_source_url)): ?>
							<a href="<?php echo $event->ical_source_url; ?>"
							class="ai1ec-event-container
								ai1ec-event-id-<?php echo $event->post_id ?>
								ai1ec-event-instance-id-<?php echo $event->instance_id ?>
								ai1ec-allday
								<?php if( $event->start_truncated ) echo 'ai1ec-start-truncated' ?>
								<?php if( $event->end_truncated ) echo 'ai1ec-end-truncated' ?>">
						<?php else: ?>
						<a href="<?php echo esc_attr( get_permalink( $event->post_id ) ) . $event->instance_id ?>"
							class="ai1ec-event-container
								ai1ec-event-id-<?php echo $event->post_id ?>
								ai1ec-event-instance-id-<?php echo $event->instance_id ?>
								ai1ec-allday
								<?php if( $event->start_truncated ) echo 'ai1ec-start-truncated' ?>
								<?php if( $event->end_truncated ) echo 'ai1ec-end-truncated' ?>">
						<?php endif; ?>

							<?php // Insert post ID for use by JavaScript filtering later ?>
							<input type="hidden" class="ai1ec-post-id" value="<?php echo $event->post_id ?>" />

							<div class="ai1ec-event-popup">
								<div class="ai1ec-event-summary">
									<?php if( $event->category_colors ): ?>
									  <div class="ai1ec-category-colors"><?php echo $event->category_colors ?></div>
									<?php endif ?>
									<?php if( $event->post_excerpt ): ?>
										<strong><?php _e( 'Summary:', AI1EC_PLUGIN_NAME ) ?></strong>
										<p><?php echo esc_html( $event->post_excerpt ) ?></p>
									<?php endif ?>
									<div class="ai1ec-read-more"><?php esc_html_e( 'click anywhere for details', AI1EC_PLUGIN_NAME ) ?></div>
								</div>
								<div class="ai1ec-event-popup-bg">
									<span class="ai1ec-event-title">
									  <?php if( function_exists( 'mb_strimwidth' ) ) : ?>
									    <?php echo esc_html( mb_strimwidth( apply_filters( 'the_title', $event->post->post_title ), 0, 35, '...' ) ) ?></span>
									  <?php else : ?>
									    <?php $read_more = strlen( apply_filters( 'the_title', $event->post->post_title ) ) > 35 ? '...' : '' ?>
                      <?php echo esc_html( substr( apply_filters( 'the_title', $event->post->post_title ), 0, 35 ) . $read_more );  ?>
									  <?php endif; ?>
									</span>
									<small><?php esc_html_e( '(all-day)', AI1EC_PLUGIN_NAME ) ?></small>
								</div>
							</div><!-- .event-popup -->

							<div class="ai1ec-event <?php if( $event->post_id == $active_event ) echo 'ai1ec-active-event' ?>" style="<?php echo $event->color_style ?>">
								<span class="ai1ec-event-title"><?php echo esc_html( apply_filters( 'the_title', $event->post->post_title ) ) ?></span>
							</div>

						</a>
					<?php endforeach // allday ?>

				</td>
			<?php endforeach // weekday ?>
		</tr>
	</thead>
	<tbody>
		<tr class="ai1ec-week">
			<?php foreach( $cell_array as $day ): ?>
				<td <?php if( $day['today'] ) echo 'class="ai1ec-today"' ?>>

					<?php if( ! $done_grid ) : ?>
						<div class="ai1ec-grid-container">
							<?php for( $hour = 0; $hour < 24; $hour++ ) : ?>
								<div class="ai1ec-hour-marker <?php if( $hour >= 8 && $hour < 18 ) echo 'ai1ec-business-hour' ?>" style="top: <?php echo $hour * 60 ?>px;">
									<div><?php echo esc_html( date_i18n( $time_format, gmmktime( $hour, 0 ), true ) ) ?></div>
								</div>
								<?php for( $quarter = 1; $quarter < 4; $quarter++ ) : ?>
									<div class="ai1ec-quarter-marker" style="top: <?php echo $hour * 60 + $quarter * 15 ?>px;"></div>
								<?php endfor ?>
							<?php endfor ?>
							<div class="ai1ec-now-marker" style="top: <?php echo $now_top ?>px;"></div>
						</div>
						<?php $done_grid = true ?>
					<?php endif ?>

					<div class="ai1ec-day">
						<?php foreach( $day['notallday'] as $notallday ): ?>
							<?php extract( $notallday ) ?>
							<?php if (isset($event->ical_source_url) && !empty($event->ical_source_url)): ?>
							<a href="<?php echo $event->ical_source_url; ?>"
								class="ai1ec-event-container
									ai1ec-event-id-<?php echo $event->post_id ?>
									ai1ec-event-instance-id-<?php echo $event->instance_id ?>
									<?php if( $event->start_truncated ) echo 'ai1ec-start-truncated' ?>
									<?php if( $event->end_truncated ) echo 'ai1ec-end-truncated' ?>"
								style="top: <?php echo $top ?>px; height: <?php echo max( $height, 31 ) ?>px; left: <?php echo $indent * 8 ?>px; <?php echo $event->color_style ?> <?php if( $event->faded_color ) echo "border: 2px solid $event->faded_color !important;" ?> background: linear-gradient( top, #fff, <?php echo $event->faded_color ?> ) !important; background: -o-linear-gradient( top, #fff, <?php echo $event->faded_color ?> ) !important; background: -moz-linear-gradient( top, #fff, <?php echo $event->faded_color ?> ) !important; background: -webkit-gradient( linear, left top, left bottom, color-stop( 0, # ), color-stop( 1, <?php echo $event->faded_color ?> ) ) !important; background: -webkit-linear-gradient( top, #fff, <?php echo $event->faded_color ?> ) !important;">
							<?php else: ?>
							<a href="<?php echo esc_attr( get_permalink( $event->post_id ) ) . $event->instance_id ?>"
								class="ai1ec-event-container
									ai1ec-event-id-<?php echo $event->post_id ?>
									ai1ec-event-instance-id-<?php echo $event->instance_id ?>
									<?php if( $event->start_truncated ) echo 'ai1ec-start-truncated' ?>
									<?php if( $event->end_truncated ) echo 'ai1ec-end-truncated' ?>"
								style="top: <?php echo $top ?>px; height: <?php echo max( $height, 31 ) ?>px; left: <?php echo $indent * 8 ?>px; <?php echo $event->color_style ?> <?php if( $event->faded_color ) echo "border: 2px solid $event->faded_color !important;" ?> background: linear-gradient( top, #fff, <?php echo $event->faded_color ?> ) !important; background: -o-linear-gradient( top, #fff, <?php echo $event->faded_color ?> ) !important; background: -moz-linear-gradient( top, #fff, <?php echo $event->faded_color ?> ) !important; background: -webkit-gradient( linear, left top, left bottom, color-stop( 0, # ), color-stop( 1, <?php echo $event->faded_color ?> ) ) !important; background: -webkit-linear-gradient( top, #fff, <?php echo $event->faded_color ?> ) !important;">
								<?php endif; ?>
								<?php if( $event->start_truncated ) : ?><div class="ai1ec-start-truncator">◤</div><?php endif ?>
								<?php if( $event->end_truncated ) : ?><div class="ai1ec-end-truncator">◢</div><?php endif ?>

								<?php // Insert post ID for use by JavaScript filtering later ?>
								<input type="hidden" class="ai1ec-post-id" value="<?php echo $event->post_id ?>" />

								<div class="ai1ec-event-popup">
									<div class="ai1ec-event-summary">
										<?php if( $event->category_colors ): ?>
										  <div class="ai1ec-category-colors"><?php echo $event->category_colors ?></div>
										<?php endif ?>
										<?php if( $event->post_excerpt ): ?>
											<strong><?php _e( 'Summary:', AI1EC_PLUGIN_NAME ) ?></strong>
											<p><?php echo esc_html( $event->post_excerpt ) ?></p>
										<?php endif ?>
										<div class="ai1ec-read-more"><?php esc_html_e( 'click anywhere for details', AI1EC_PLUGIN_NAME ) ?></div>
									</div>
									<div class="ai1ec-event-popup-bg">
										<span class="ai1ec-event-time"><?php echo esc_html( $event->short_start_time ) ?></span>
										<span class="ai1ec-event-title"><?php echo esc_html( apply_filters( 'the_title', $event->post->post_title ) ) ?></span>
										</span>
									</div>
								</div><!-- .event-popup -->

								<div class="ai1ec-event <?php if( $event->post_id == $active_event ) echo 'ai1ec-active-event' ?>">
									<span class="ai1ec-event-time"><?php echo esc_html( $event->short_start_time ) ?></span>
									<span class="ai1ec-event-title"><?php echo esc_html( apply_filters( 'the_title', $event->post->post_title ) ) ?></span>
								</div>

							</a>
						<?php endforeach // events ?>
					</div>
				</td>
			<?php endforeach // day ?>
		</tr>
	</tbody>
</table>
