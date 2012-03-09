<?php
/*
Plugin Name: StatiCal
Plugin URI: http://wasielewski.org/projects/statical
Description: A simple static calendar.
Version: 0.1.0
Author: Zac Wasielewski
Author URI: http://wasielewski.org
License: GPL2
*/

/*
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

/**
 * Protection 
 * 
 * This string of code will prevent hacks from accessing the file directly.
 */
defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Initializing 
 * 
 * The directory separator is different between linux and microsoft servers.
 * Thankfully php sets the DIRECTORY_SEPARATOR constant so that we know what
 * to use.
 */
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

/**
 * Actions and Filters
 * 
 * Register any and all actions here. Nothing should actually be called 
 * directly, the entire system will be based on these actions and hooks.
 */
add_action( 'widgets_init', create_function( '', 'register_widget("StatiCal");' ) );

/**
 * 
 * 
 */
class StatiCal extends WP_Widget
{
	/**
	 * Widget settings
	 * 
	 * Simply use the following field examples to create the WordPress Widget options that
	 * will display to administrators. These options can then be found in the $params 
	 * variable within the widget method.
	 * 
	 * 
		array(
			'name' => 'Title',
			'desc' => '',
			'id' => 'title',
			'type' => 'text',
			'std' => 'Your widgets title'
		),
		array(
			'name' => 'Textarea',
			'desc' => 'Enter big text here',
			'id' => 'textarea_id',
			'type' => 'textarea',
			'std' => 'Default value 2'
		),
		array(
		    'name'    => 'Select box',
			'desc' => '',
		    'id'      => 'select_id',
		    'type'    => 'select',
		    'options' => array( 'KEY1' => 'Value 1', 'KEY2' => 'Value 2', 'KEY3' => 'Value 3' )
		),
		array(
			'name' => 'Radio',
			'desc' => '',
			'id' => 'radio_id',
			'type' => 'radio',
			'options' => array(
				array('name' => 'Name 1', 'value' => 'Value 1'),
				array('name' => 'Name 2', 'value' => 'Value 2')
			)
		),
		array(
			'name' => 'Checkbox',
			'desc' => '',
			'id' => 'checkbox_id',
			'type' => 'checkbox'
		),
	 */
	protected $widget = array(
		// this description will display within the administrative widgets area
		// when a user is deciding which widget to use.
		'description' => 'A simple static calendar. Select the year, month, and days to highlight.',
		
		// determines whether or not to use the sidebar _before and _after html
		'do_wrapper' => true, 
		
		// string : if you set a filename here, it will be loaded as the view
		// when using a file the following array will be given to the file :
		// array('widget'=>array(),'params'=>array(),'sidebar'=>array(),
		// alternatively, you can return an html string here that will be used
		'view' => false,
		
		'fields' => array(
			// You should always offer a widget title
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'std' => 'Calendar Title'
			),
			array(
				'name' => 'Month',
				'desc' => '',
				'id' => 'month',
				'type' => 'select',
    		    'options' => array(
    		        '01' => 'January',
    		        '02' => 'February',
    		        '03' => 'March',
    		        '04' => 'April',
    		        '05' => 'May',
    		        '06' => 'June',
    		        '07' => 'July',
    		        '08' => 'August',
    		        '09' => 'September',
    		        '10' => 'October',
    		        '11' => 'November',
    		        '12' => 'December',
    		    )
			),
			array(
				'name' => 'Year',
				'desc' => '',
				'id' => 'year',
				'type' => 'text',
				'std' => 2012,
				'size' => 4
			),
			array(
				'name' => 'Highlight Days',
				'desc' => '',
				'id' => 'days',
				'type' => 'checkbox-multiple',
    		    'options' => array(
    		        '01' => '1',
    		        '02' => '2',
    		        '03' => '3',
    		        '04' => '4',
    		        '05' => '5',
    		        '06' => '6',
    		        '07' => '7',
    		        '08' => '8',
    		        '09' => '9',
    		        '10' => '10',
    		        '11' => '11',
    		        '12' => '12',
    		        '13' => '13',
    		        '14' => '14',
    		        '15' => '15',
    		        '16' => '16',
    		        '17' => '17',
    		        '18' => '18',
    		        '19' => '19',
    		        '20' => '20',
    		        '21' => '21',
    		        '22' => '22',
    		        '23' => '23',
    		        '24' => '24',
    		        '25' => '25',
    		        '26' => '26',
    		        '27' => '27',
    		        '28' => '28',
    		        '29' => '29',
    		        '30' => '30',
    		        '31' => '31',
    		    )
			),
		)
	);
	
	/**
	 * Widget HTML
	 * 
	 * If you want to have an all inclusive single widget file, you can do so by
	 * dumping your css styles with base_encoded images along with all of your 
	 * html string, right into this method.
	 *
	 * @param array $widget
	 * @param array $params
	 * @param array $sidebar
	 */
	function html($widget, $params, $sidebar)
	{
	
        ?><h3 class="widget-title"><?php echo $params['title'] ?></h3><?php
        echo $this->draw_calendar($params['year'],$params['month'],$params['days']);
        
	}
	
    /* draws a calendar */
    function draw_calendar($year,$month,$days=array()){
        
        ?>
        <style type="text/css">
        /* calendar widget */
        
        #wp-calendar { width: 100%; }
        #wp-calendar caption { text-align: right; color: #333; font-size: 12px; margin-top: 10px; margin-bottom: 15px; }
        #wp-calendar thead { font-size: 10px; }
        #wp-calendar thead th { padding-bottom: 10px; }
        #wp-calendar tbody { color: #aaa; }
        #wp-calendar tbody td { background: #f5f5f5; border: 1px solid #fff; text-align: center; padding:8px;}
        #wp-calendar tbody td.statical-highlight { background-color: #ff9; }
        #wp-calendar tbody .pad { background: none; }
        #wp-calendar tfoot #next { font-size: 10px; text-transform: uppercase; text-align: right; }
        #wp-calendar tfoot #prev { font-size: 10px; text-transform: uppercase; padding-top: 10px; }
        
        </style>
        <?php
        
        /* draw table */
        $calendar = '<div class="widget_calendar">';
        $calendar .= '<table cellpadding="0" cellspacing="0" class="statical-calendar" id="wp-calendar">';
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
        $calendar.= '</table></div>';
        
        /* all done, return result */
        return $calendar;
        
    }
	
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class, based off of the options
	 * that were defined within the widget property. This method does not need to be
	 * changed.
	 */
	function StatiCal()
	{
		//Initializing
		$classname = str_replace('_',' ', get_class($this));
		
		// widget actual processes
		parent::WP_Widget( 
			$id = $classname, 
			$name = (isset($this->widget['name'])?$this->widget['name']:$classname), 
			$options = array( 'description'=>$this->widget['description'] )
		);
	}
	
	/**
	 * Widget View
	 * 
	 * This method determines what view method is being used and gives that view
	 * method the proper parameters to operate. This method does not need to be
	 * changed.
	 *
	 * @param array $sidebar
	 * @param array $params
	 */
	function widget($sidebar, $params)
	{
		//initializing variables
		$this->widget['number'] = $this->number;
		$title = apply_filters( 'StatiCal_title', $params['title'] );
		$do_wrapper = (!isset($this->widget['do_wrapper']) || $this->widget['do_wrapper']);
		
		if ( $do_wrapper ) 
			echo $sidebar['before_widget'];
		
		//loading a file that is isolated from other variables
		if (file_exists($this->widget['view']))
			$this->getViewFile($widget, $params, $sidebar);
			
		if ($this->widget['view'])
			echo $this->widget['view'];
			
		else $this->html($this->widget, $params, $sidebar);
			
		if ( $do_wrapper ) 
			echo $sidebar['after_widget'];
	}
	
	/**
	 * Get the View file
	 * 
	 * Isolates the view file from the other variables and loads the view file,
	 * giving it the three parameters that are needed. This method does not
	 * need to be changed.
	 *
	 * @param array $widget
	 * @param array $params
	 * @param array $sidebar
	 */
	function getViewFile($widget, $params, $sidebar) {
		require $this->widget['view'];
	}

	/**
	 * Administration Form
	 * 
	 * This method is called from within the wp-admin/widgets area when this
	 * widget is placed into a sidebar. The resulting is a widget options form
	 * that allows the administration to modify how the widget operates.
	 * 
	 * You do not need to adjust this method what-so-ever, it will parse the array
	 * parameters given to it from the protected widget property of this class.
	 *
	 * @param array $instance
	 * @return boolean
	 */
	function form($instance)
	{
		//reasons to fail
		if (empty($this->widget['fields'])) return false;
		
		$defaults = array(
			'id' => '',
			'name' => '',
			'desc' => '',
			'type' => '',
			'options' => '',
			'std' => '',
			'size' => '',
		);
		
		do_action('StatiCal_before');
		foreach ($this->widget['fields'] as $field)
		{
			//making sure we don't throw strict errors
			$field = wp_parse_args($field, $defaults);

			$meta = false;
			if (isset($field['id']) && array_key_exists($field['id'], $instance))
				@$meta = attribute_escape($instance[$field['id']]);

			if ($field['type'] != 'custom' && $field['type'] != 'metabox') 
			{
				echo '<p><label for="',$this->get_field_id($field['id']),'">';
			}
			if (isset($field['name']) && $field['name']) echo $field['name'],':';

			switch ($field['type'])
			{
				case 'text':
					echo '<input type="text" size="', @$field['size'] ,'" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" value="', ($meta ? $meta : @$field['std']), '" class="vibe_text" />', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'textarea':
					echo '<textarea class="vibe_textarea" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" cols="60" rows="4" style="width:97%">', $meta ? $meta : @$field['std'], '</textarea>', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'select':
					echo '<select class="vibe_select" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '">';

					foreach ($field['options'] as $value => $option)
					{
 					   $selected_option = ( $value ) ? $value : $option;
					    echo '<option', ($value ? ' value="' . $value . '"' : ''), ($meta == $selected_option ? ' selected="selected"' : ''), '>', $option, '</option>';
					}

					echo '</select>', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'radio':
					foreach ($field['options'] as $option)
					{
						echo '<input class="vibe_radio" type="radio" name="', $this->get_field_name($field['id']), '" value="', $option['value'], '"', ($meta == $option['value'] ? ' checked="checked"' : ''), ' />', 
						$option['name'];
					}
					echo '<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'checkbox':
					echo '<input type="hidden" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" /> ', 
						 '<input class="vibe_checkbox" type="checkbox" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '"', $meta ? ' checked="checked"' : '', ' /> ', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'checkbox-multiple':
                    
					foreach ($field['options'] as $value => $option)
					{
						echo '<br /><input class="vibe_checkbox" type="checkbox" ',
						    'name="', $this->get_field_name($field['id']), '[]" ',
						    'value="',$value,'" ',
						    'id="', $this->get_field_id($field['id']), '-',$value,'" ',
						    (in_array( $value, $instance[$field['id']] ))
						        ? ' checked="checked"'
						        : '',
						    ' /> ',$option;
					}

					echo '<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'custom':
					echo $field['std'];
					break;
			}

			if ($field['type'] != 'custom' && $field['type'] != 'metabox') 
			{
				echo '</label></p>';
			}
		}
		do_action('StatiCal_after');
		return true;
	}

	/**
	 * Update the Administrative parameters
	 * 
	 * This function will merge any posted paramters with that of the saved
	 * parameters. This ensures that the widget options never get lost. This
	 * method does not need to be changed.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	function update($new_instance, $old_instance)
	{
		// processes widget options to be saved
		$instance = wp_parse_args($new_instance, $old_instance);
		return $instance;
	}

}