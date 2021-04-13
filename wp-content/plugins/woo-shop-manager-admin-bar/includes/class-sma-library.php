<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//six functions
//tab1
function get_sma_general_settings( $key, $default='yes' ){
	$sma_general_settings_option = get_option( 'sma_general_settings_option', array() );
	return isset( $sma_general_settings_option[$key] ) ? stripslashes($sma_general_settings_option[$key]): stripslashes($default) ;
}
function update_general_settings_widget( $key, $val ){
	$sma_general_settings_option = get_option( 'sma_general_settings_option', array() );
	$sma_general_settings_option[$key] = $val;
	update_option( 'sma_general_settings_option', $sma_general_settings_option );
}

//tab2
function get_sma_dashboard_widget( $key ){
	$sma_dashboard_widget_option = get_option( 'sma_dashboard_widget_option', array() );
	return isset( $sma_dashboard_widget_option[$key] ) ? $sma_dashboard_widget_option[$key] : 'yes';
}
function update_sma_dashboard_widget( $key, $val ){
	$sma_dashboard_widget_option = get_option( 'sma_dashboard_widget_option', array() );
	$sma_dashboard_widget_option[$key] = $val;
	update_option( 'sma_dashboard_widget_option', $sma_dashboard_widget_option );
}

//tab3
function get_sma_adminbar( $key ){
	$sma_admin_menu_option = get_option( 'sma_adminbar_option', array() );
	return isset( $sma_admin_menu_option[$key] ) ? $sma_admin_menu_option[$key] : 'yes';
}
function update_sma_adminbar( $key, $val ){
	$sma_admin_menu_option = get_option( 'sma_adminbar_option', array() );
	$sma_admin_menu_option[$key] = $val;
	update_option( 'sma_adminbar_option', $sma_admin_menu_option );
}

//toggle function
function sma_toggle( $name, $key, $val, $parent ){
	?>
	<input type="hidden" name="<?php echo $name?>" value="no"/>
	<input class="tgl tgl-flat <?php echo $key ; if (!empty($parent)){ echo ' ' . $parent;}?>" id="<?php echo $key;?>" name="<?php echo $name?>" type="checkbox" <?php echo $val == 'yes' ? 'checked' : '' ?> value="yes"/>
	<label id="<?php echo $key;?>-checkbox" class="tgl-btn" for="<?php echo $key;?>"></label>
    <?php
}
