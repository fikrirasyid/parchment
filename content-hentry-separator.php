<?php
	// Update the date in given condition
	if( isset( $GLOBALS['wp_query'] ) && is_sticky() ){
		$GLOBALS['wp_query']->has_sticky = true;
	}

	if( isset( $GLOBALS['wp_query'] ) && !is_sticky() ){	
		
		// Define date index
		if( !isset( $GLOBALS['wp_query']->manuscript_date ) ){		
			$date_index = 0;
		} else {
			$date_index = $GLOBALS['wp_query']->manuscript_date;
		}

		// Define timestamp
		$timestamp 	= strtotime( $post->post_date );

		// Define date, month and year
		$date 		= date( 'Y-m-d', $timestamp );

		// Print hentry-day
		if( $date_index != $date ){

			if( !isset( $GLOBALS['wp_query']->query['paged'] ) && !isset( $GLOBALS['wp_query']->has_sticky ) && $date_index == 0 ){
				echo '<h4 class="hentry-day on-page-cover" data-date="'. $date .'"><span class="label">' . date( __( 'l, j F Y', 'manuscript' ), $timestamp ) . '</span><span class="border"></span></h4>';
			} else if( isset( $GLOBALS['wp_query']->query['paged'] ) && !isset( $GLOBALS['wp_query']->has_sticky ) && $date_index == 0 && !isset( $GLOBALS['wp_query']->query['nopaging'] ) ){
				echo '<h4 class="hentry-day on-page-cover" data-date="'. $date .'"><span class="label">' . date( __( 'l, j F Y', 'manuscript' ), $timestamp ) . '</span><span class="border"></span></h4>';
			} else {
				echo '<h4 class="hentry-day" data-date="'. $date .'"><span class="label">' . date( __( 'l, j F Y', 'manuscript' ), $timestamp ) . '</span><span class="border"></span></h4>';				
			}

		}

		// Print marker
		$month_label = date( 'F', $timestamp );
		echo "<span style='display: none;' class='article-marker' data-date='$date' data-month='$month_label' data-year='$year'></span>";

		// Set globals
		$GLOBALS['wp_query']->manuscript_date 	= $date;
	
	}