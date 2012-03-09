<?php
/*
Plugin Name: StatiCal
Plugin URI: http://wasielewski.org/projects/statical
Description: A simple static calendar.
Version: 0.1.0
Author: Zac Wasielewski
Author URI: http://wasielewski.org
License: GPL2

Copyright 2012 Zac Wasielewski (email: zac@wasielewski.org)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class StatiCal extends WP_Widget {
	
	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/
	
	/**
	 * The widget constructor. Specifies the classname and description, instantiates
	 * the widget, loads localization files, and includes necessary scripts and
	 * styles.
	 */
	public function __construct() {
	
		load_plugin_textdomain( 'statical', false, plugin_dir_path( __FILE__ ) . '/lang/' );
		
		parent::__construct(
			'statical',
			'StatiCal',
			array(
				'classname'		=>	'statical',
				'description'	=>	__( 'A simple static calendar. Select the year, month, and days to highlight.', 'statical-locale' )
			)
		);
		
		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();
	
	} // end constructor

	/*--------------------------------------------------*/
	/* API Functions
	/*--------------------------------------------------*/
	
	/**
	 * Outputs the content of the widget.
	 *
	 * @args			The array of form elements
	 * @instance		The current instance of the widget
	 */
	function widget( $args, $instance ) {
	
		extract( $args, EXTR_SKIP );
		
		echo $before_widget;
		
        $title = empty($instance['title'])
            ? '' : apply_filters('title', $instance['title']);

        $year = empty($instance['year'])
            ? '' : apply_filters('year', $instance['year']);
            
        $month = empty($instance['month'])
            ? '' : apply_filters('month', $instance['month']);

        $days = empty($instance['days'])
            ? '' : apply_filters('days', $instance['days']);
    
        $calendar = $this->draw_calendar($year,$month,$days);

        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
    
		include( plugin_dir_path(__FILE__) . '/views/widget.php' );
		
		echo $after_widget;
		
	} // end widget
	
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @new_instance	The previous instance of values before the update.
	 * @old_instance	The new instance of values to be generated via the update.
	 */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['year'] = strip_tags(stripslashes($new_instance['year']));
        $instance['month'] = strip_tags(stripslashes($new_instance['month']));
        
        if (!is_array($instance['days'])) {
            $instance['days'] = array();
        }
        foreach ($new_instance['days'] as $k=>$v) {
            $instance['days'][$k] = strip_tags(stripslashes($v));
        }
    
		return $instance;
		
	} // end widget
	
	/**
	 * Generates the administration form for the widget.
	 *
	 * @instance	The array of keys and values for the widget.
	 */
	function form( $instance ) {
	    
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'	=>	'',
				'year'	=>	date("Y"),
				'month'	=>	date("n"),
				'days'	=>	array(),
			)
		);
		
        $title = strip_tags(stripslashes($instance['title']));
        $year  = strip_tags(stripslashes($instance['year']));
        $month = strip_tags(stripslashes($instance['month']));
        
        if (is_array($instance['days'])) {
            $days = $instance['days'];
            foreach ($instance['days'] as $k=>$v) {
                $days[$k] = strip_tags(stripslashes($v));
            }
        } else {
            $days = strip_tags(stripslashes($instance['days']));
        }
		
		// Display the admin form
    	include( plugin_dir_path(__FILE__) . '/views/admin.php' );
		
	} // end form
	
	/*--------------------------------------------------*/
	/* Private Functions
	/*--------------------------------------------------*/
  
	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	private function register_scripts_and_styles() {
	
		if ( is_admin() ) {
		
			//$this->load_file( 'statical-admin-script', '/js/admin.js', true );
			$this->load_file( 'statical-admin-style', '/css/admin.css' );
			
		} else { 
		
			//$this->load_file( 'statical-script', '/js/widget.js', true );
			$this->load_file( 'statical-style', '/css/widget.css' );
			
		} // end if/else
		
	} // end register_scripts_and_styles

	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	        The ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file( $name, $file_path, $is_script = false ) {
		
		$url = plugins_url( $file_path, __FILE__ ) ;
		$file = plugin_dir_path( __FILE__ ) . $file_path;

		if( file_exists( $file ) ) {
		
			if( $is_script ) {
			
				wp_register_script( $name, $url, array( 'jquery' ) );
				wp_enqueue_script( $name );
				
			} else {
			
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
				
			} // end if
			
		} // end if
    
	} // end load_file
	
	/**
	 * Generates and returns the calendar markup
	 *
	 * @year        The calendar year to display
	 * @month   	The calendar month to display
	 * @days		Optional array of days to highlight on the calendar
	 */
	private function draw_calendar( $year, $month, $days = array() ) {
    
        /* draw table */
        $calendar = '';
        $calendar .= '<table cellpadding="0" cellspacing="0" class="statical-calendar">';
        $calendar .= '<caption>'. date('F Y',mktime(0,0,0,$month,1,$year)) .'</caption>';
        
        $show_headings = false;
        
        /* table headings */
        if ($show_headings==true) {
            $headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
            $calendar.= '<tr class="statical-row"><td class="statical-day-head">'.implode('</td><td class="statical-day-head">',$headings).'</td></tr>';
        }
        
        /* days and weeks vars now ... */
        $running_day = date('w',mktime(0,0,0,$month,1,$year));
        $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();
        
        /* row for week one */
        $calendar.= '<tr class="statical-row">';
        
        /* print "blank" days until the first of the current week */
        for($x = 0; $x < $running_day; $x++):
            $calendar.= '<td class="statical-day-np">&nbsp;</td>';
            $days_in_this_week++;
        endfor;
        
        /* keep going with days.... */
        for($list_day = 1; $list_day <= $days_in_month; $list_day++):
            
            if ( in_array( $list_day, $days )) {
                $calendar.= '<td class="statical-day statical-highlight">';
            } else {
                $calendar.= '<td class="statical-day">';
            }
        
            /* add in the day number */
            $calendar.= '<div class="day-number">'.$list_day.'</div>';
            
            /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
            //$calendar.= str_repeat('<p>&nbsp;</p>',2);
            
            $calendar.= '</td>';
            if($running_day == 6):
                $calendar.= '</tr>';
                if(($day_counter+1) != $days_in_month):
                    $calendar.= '<tr class="statical-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++; $running_day++; $day_counter++;
        endfor;
            
        /* finish the rest of the days in the week */
        if($days_in_this_week < 8):
            for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td class="statical-day-np">&nbsp;</td>';
            endfor;
        endif;
        
        /* final row */
        $calendar.= '</tr>';
        
        /* end the table */
        $calendar.= '</table>';
        
        /* all done, return result */
        return $calendar;

	}
	
} // end class

add_action( 'widgets_init', create_function( '', 'register_widget("StatiCal");' ) );

?>