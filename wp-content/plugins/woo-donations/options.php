<?php

if(!defined('ABSPATH')) exit;

$options= wdgk_get_wc_donation_setting();
	
	if(isset($_POST['wdgk_add_form'])){
		//print_r($_POST); 
		$product_name="";
		$cart_product="";
		$checkout_product="";
		$checkout_note="";
		$btncolor="";
		$textcolor="";
		$btntext="";
		$btntext="";
		$campaign="";
		if(isset($_POST['wdgk_product']))  $product_name=sanitize_text_field($_POST['wdgk_product']);
		if(isset($_POST['wdgk_cart'])) $cart_product=sanitize_text_field($_POST['wdgk_cart']);
		if(isset($_POST['wdgk_checkout']))  $checkout_product=sanitize_text_field($_POST['wdgk_checkout']);
		if(isset($_POST['wdgk_note'])) $checkout_note=sanitize_text_field($_POST['wdgk_note']);
		if(isset($_POST['wdgk_btncolor'])) $btncolor=sanitize_text_field($_POST['wdgk_btncolor']);
		if(isset($_POST['wdgk_textcolor'])) $textcolor=sanitize_text_field($_POST['wdgk_textcolor']);
		if(isset($_POST['wdgk_btntext'])) $btntext=sanitize_text_field($_POST['wdgk_btntext']);
		if(isset($_POST['wdgk_campaign'])) $campaign=sanitize_text_field($_POST['wdgk_campaign']);
		$options['Product']=$product_name;
		$options['Cart']=$cart_product;
		$options['Checkout']=$checkout_product;
		$options['Note']=$checkout_note;
		$options['Color']=$btncolor;
		$options['Text']=$btntext;
		$options['TextColor']=$textcolor;
		$options['Campaign']=$campaign;
		
		$nonce=$_POST['wdgk_wpnonce'];
		
		if(wp_verify_nonce( $nonce, 'wdgk_nonce' ))
		{
			if(!empty($product_name)){
				update_option('wdgk_donation_settings', $options);
				$successmsg= success_option_msg_wdgk('Settings Saved!');
			}
			else{
				
				$errormsg= failure_option_msg_wdgk('Please Select Donation Product from List.');
			}
		}
		else
		{
			$errormsg= failure_option_msg_wdgk('An error has occurred.');
			
		}

	}


	$product="";
	$cart="";
	$checkout="";
	$note="";
	$color="";
	$text="";
	$textcolor="";
	$campaign="";
	if(isset($options['Product'])){
		$product = $options['Product'];
	}
	if(isset($options['Cart'])){
		$cart = $options['Cart'];
	}
	if(isset($options['Checkout'])){
		$checkout = $options['Checkout'];
	}
	if(isset($options['Note'])){
		$note = $options['Note'];
	}
	if(isset($options['Color'])){
		$color = $options['Color'];
	}
	if(isset($options['Text'])){
		$text = $options['Text'];
	}
	if(isset($options['TextColor'])){
		$textcolor = $options['TextColor'];
	}
	if(isset($options['Campaign'])){
		$campaign = $options['Campaign'];
	}
	

?>

<div class="wdgk_wrap">

		<h2>Woocommerce Donation Settings</h2>
		
    <?php
    if ( isset( $successmsg ) ) 
	{
		echo $successmsg; 
    }
	
    if ( isset( $errormsg ) ) 
	{
        echo $errormsg;
    }
    ?>
	

  <div class='wdgk_inner'>
    	
    <form method="post">
        
		<table class="form-table">
		
		<tbody>
 
			<tr valign="top">

				<th scope="row">Select Donation Product</th>

				<td>							
					<select name="wdgk_product" id="wdgk-product"> 
						<option value="">--Select--</option>
						<?php
							
							$wdgk_get_page = get_posts(array(
								'post_type'     => 'product',
								'post_status' =>'publish',
								'posts_per_page'=>-1
								
							));
							
					
						foreach ( $wdgk_get_page as $wdgk_product ) {
							echo '<option value="'.$wdgk_product->ID.'"'.selected($wdgk_product->ID,$product,false).'>'.$wdgk_product->post_title.' ('.$wdgk_product->ID.')</option>';
						 }?>
					</select>	
                </td>
				
			</tr>
			<tr valign="top">

				<th scope="row">Add on Cart Page</th>
				
				<td>
					<label class="wdgk-switch wdgk-switch-wdgk_cart_status">							  
						<input type="checkbox" class="wdgk-cart" name="wdgk_cart" value="on" <?php if($cart=='on'){echo "checked";}?> >							  
						<span class="wdgk-slider wdgk-round"></span>					
					</label>
				</td>
			</tr>
			<tr valign="top">

				<th scope="row">Add on Checkout Page</th>
				
				<td>
					<label class="wdgk-switch wdgk-switch-wdgk_checkout_status">							  
						<input type="checkbox" class="wdgk-checkout"  name="wdgk_checkout" value="on" <?php if($checkout=='on'){echo "checked";}?> >							  
						<span class="wdgk-slider wdgk-round"></span>					
					</label>
				</td>
			</tr>
			<tr valign="top">

				<th scope="row">Add on Note</th>
				
				<td>
					<label class="wdgk-switch wdgk-switch-wdgk_note_status">							  
						<input type="checkbox" class="wdgk-note wdgk-checkout"  name="wdgk_note" value="on" <?php if($note=='on'){echo "checked";}?> >							  
						<span class="wdgk-slider wdgk-round"></span>					
					</label>
				</td>
			</tr>
			<tr valign="top">

				<th scope="row">Button Color</th>
				
				<td>
					<input type="text" name="wdgk_btncolor" class="wdgk_colorpicker" value="<?php echo $color; ?>">
				</td>
			</tr>
			<tr valign="top">
						<th scope="row">Button Text</th>
						<td>
							<input type="text" name="wdgk_btntext" value="<?php echo $text; ?>">
						</td>
			</tr>
			<tr valign="top">
						<th scope="row">Button Text Color</th>
						<td>
							<input type="text" name="wdgk_textcolor" class="wdgk_colorpicker" value="<?php echo $textcolor; ?>">
						</td>
			</tr>
			<!--<tr valign="top">

				<th scope="row">Enable Campaign</th>
				
				<td>
					<label class="wdgk-switch wdgk-switch-wdgk_cart_status">							  
						<input type="checkbox" class="wdgk-campaign" name="wdgk_campaign" value="on" <?php if($campaign=='on'){echo "checked";}?> >							  
						<span class="wdgk-slider wdgk-round"></span>					
					</label>
				</td>
			</tr>  -->
			
            </tbody>
		</table>
		
				<input type="hidden" name="wdgk_wpnonce" value="<?php echo $nonce= wp_create_nonce('wdgk_nonce'); ?>">
					
            	<input class="button button-primary button-large wdgk_submit" type="submit" name="wdgk_add_form" id="wdgk_submit" value="Save"/>
            	
          
    	</form>
    </div>
</div>
<script type='text/javascript'>
	(function($) {
		// Add Color Picker to all inputs that have 'color-field' class
		$(function() {
			$('.wdgk_colorpicker').wpColorPicker();
		});

	})(jQuery);
	    
</script>