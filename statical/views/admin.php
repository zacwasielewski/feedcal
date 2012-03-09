<div class="wrapper">
    <fieldset>
        
        <p class="option">
            <label for="title">
                <?php _e('Title', PLUGIN_LOCALE); ?>:
            </label>
            <input
                type="text"
                id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>"
                value="<?php echo $instance['title']; ?>" class=""
                placeholder="Optional">
        </p>
        
        <p class="option">
            <label for="year">
                <?php _e('Year', PLUGIN_LOCALE); ?>:
            </label>
            <input
                type="text"
                id="<?php echo $this->get_field_id('year'); ?>"
                name="<?php echo $this->get_field_name('year'); ?>"
                value="<?php echo $instance['year']; ?>"
                size="4"
                class="">
        </p>
        
        <p class="option">
            <label for="month">
                <?php _e('Month', PLUGIN_LOCALE); ?>:
            </label>
			<select
			    name="<?php echo $this->get_field_name('month') ?>"
			    id="<?php echo $this->get_field_id('month') ?>"
			    class="">
			    <option value="">select</option>
                <?php
                
                    $i=0;
                    while ($i++ < 12)
					{
 					    $selected = ( $i == $instance['month'] ) ? 'selected' : '';
 					    $label = date("F",mktime(0,0,0,$i,1,2000));
                        ?>
                        <option
                            value="<?php echo $i ?>"
                            <?php echo $selected ?>
                            >
                            <?php echo $label ?>
                        </option>
                        <?php
					}
					
                ?>
			    </select>
        </p>
        
        <p class="option">
            <span for="days">
                <?php _e('Highlight Days', PLUGIN_LOCALE); ?>:
            </label>
            
            <table class="statical-admin-days">
                <tr>
                <?php
    
                $i=0;
                while ($i++ < 31)
                {
                    
                    // start and end the row
                    if ((($i-1) % 7) == 0) {
                        ?></tr><tr><?php
                    }
                    
                    if (is_array($instance['days'])) {
                        $checked = ( in_array($i,$instance['days'] )) ? 'checked' : '';
                    }
                                    
                    ?>
                    <td>
                    <label for="<?php echo $this->get_field_id('days') . '-' . $i ?>">
                        <input type="checkbox"
                            id="<?php echo $this->get_field_id('days') . '-' . $i ?>"
                            name="<?php echo $this->get_field_name('days') ?>[]"
                            value="<?php echo $i ?>"
                            <?php echo $checked ?>
                            ><?php echo $i ?>
                    </label>
                    </td>
                    <?php
                }
                
                ?>
                </tr>
            </table>
            
        </p>

    </fieldset>
</div>

<!-- /wrapper -->
  