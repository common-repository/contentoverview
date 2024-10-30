<?php

if ( !defined('ABSPATH') ) exit;

class cow_contentoverview_admin extends cow_contentoverview {
	var $errors;

	function init() {
		parent::init();
		$this->errors = new WP_Error;
		$this->options = get_option( 'cow_contentoverview' );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		
		if ( !isset( $this->options['method'] ) || !isset( $this->options['style'] ) )
		  $this->initialsetup();
		
	}

	function admin_menu() {
		$hook = add_options_page ( 'contentOVERVIEW', 'contentOVERVIEW', 'manage_options', 'cow_contentoverview', array( &$this, 'admin_page' ) );

		if ( !empty( $_POST['cow_contentoverview'] ) && $this->update( stripslashes_deep ( $_POST['cow_contentoverview'] ) ) ) {
			wp_redirect( add_query_arg( 'updated', '', wp_get_referer() ) );
			exit;
		}
		
	}
	
	function initialsetup () {
	  $_POST['cow_contentoverview'] = array( 'method'=>'cow_contentoverview_abgn', 'style'=>'cow_contentoverview_style_decimal' );
	  $this->update( stripslashes_deep ( $_POST['cow_contentoverview'] ) );
	  return;
	}
	
	function update ( $new ) {
	
		if ( !is_array( $this->options ) )
			$this->options = array();
			
		extract( $this->options, EXTR_SKIP );
	
		if ( isset( $new['method'] ) ) {
			$method = $new['method'];			
		}
	
		if ( isset( $new['style'] ) ) {
			$style = $new['style'];			
		}
	
		if ( isset( $new['method'] ) && $new['method'] == 'cow_contentoverview_no' ) {
			$method = '';			
			$style = '';			
		}
			
		$this->options = compact( 'method', 'style' );
		update_option( 'cow_contentoverview', $this->options );
		return !count( $this->errors->get_error_codes() );
	}

	function admin_page() {
	
		if ( !current_user_can( 'manage_options' ) )
			wp_die( __( 'Unsatisfying contentOVERVIEW', 'cow_contentoverview' ) );
		
		if ( !is_array( $this->options ) )
			$this->options = array(1);

		$values = $this->options;
		
?>
<style type="text/css">
/*  <![CDATA[  */

.cow_contentoverview_list_type {
	float:left;
	display:inline-block;
	padding:10px;
	width:20%;
	margin:0 3% 3% 0;
	border:1px #ddd solid;
	background:#fefefe;
}

.cow_contentoverview_list_type p {
  margin-left:25px;
  line-height:1.8em;
}

.cow_donation {
  vertical.align:top;
  width:250px;
}

.cow_donation div {
  border:1px #ddd solid;
  background:#fff;
  padding:10px;
  text-align:justify;
}

.cow_donation p {
  font-size:90%;
}

.cow_donation span {
  display:block;
  float:left;
  height:25px;
}

.cow_donation alignright {
  float:right;
}

/*  ]]>  */
</style>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
  <h2>contentOVERVIEW - Options</h2>
  
  <form action="<?php echo clean_url( remove_query_arg( 'updated' ) ); ?>" method="post">
	<table class="form-table">
	<tbody>
		<?php if ( empty( $errors ) ): ?>
		<tr>
			<th scope="row"><?php _e( 'Usage', 'cow_contentoverview' ); ?></th>
			<td class="syntax">
				<p><?php _e( 'Set up some default values for new posts or pages.', 'cow_contentoverview' ); ?>
  				<?php _e( 'Also you can use', 'cow_contentoverview' ); ?> <code>&lt;?php contentoverview( $prefix, $suffix ); ?&gt;</code> <?php _e( 'Template-Tag inside your loop.', 'cow_contentoverview' ); ?></p>
				<p><strong><code>$prefix</code></strong> - <em>(string) (optional)</em> - 
				  <?php _e( 'Set a string or HTML start-tag before the list.', 'cow_contentoverview' ); ?><br/>
				  <strong><code>$suffix</code></strong> - <em>(string) (optional)</em> - 
				  <?php _e( 'Set a string or HTML end-tag after the list.', 'cow_contentoverview' ); ?></p>
				<p><?php _e( 'See the FAQ for more information.', 'cow_contentoverview' ); ?> - <a href="http://wordpress.org/extend/plugins/contentoverview/faq/">FAQ</a></p>
			</td>
			<td rowspan="2" class="cow_donation"><div><p><strong>Please help me with your <a href="http://fehertamas.com/2010/contentoverview/#commentform" target="_blank">suggestions</a> and 
			      <a href="http://fehertamas.com/2010/contentoverview/#commentform" target="_blank">bug reports</a>!</strong> When you found this free plugin useful, you can send me a gift from my 
				    <a href="http://www.amazon.de/wishlist/26K33A78884CA" target="_blank">Amazon wishlist</a> or donate via 
				    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XNYGJ2WUQVH46" target="_blank">Paypal</a> to keep the active deveploment.</p>
				  <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XNYGJ2WUQVH46" target="_blank"><img alt="Make a donation via Paypal." border="0" src="<?php echo WP_PLUGIN_URL; ?>/contentoverview/cowdonate.png" height="45" width="90"></a>
				  <a href="http://www.amazon.de/wishlist/26K33A78884CA" target="_blank" class="alignright"><img alt="Send me a gift from Amazon wishlist." border="0" src="<?php echo WP_PLUGIN_URL; ?>/contentoverview/cowwishlist.png" height="45" width="90"></a>
				</div>
			</td>
		</tr>
		<?php endif; ?>
		<tr<?php if ( isset( $errors ) && in_array( 'method', $errors ) ) echo ' class="form-invalid"'; ?>>
			<th><?php _e( 'Display method', 'cow_contentoverview' ); ?></th>
			<td>
				<ul>
					<li><label for="cow_contentoverview_no">
					    <input type="radio" name="cow_contentoverview[method]" id="cow_contentoverview_no" value='cow_contentoverview_no'<?php checked( '', $values['method'] ); ?> /> 
					    <?php _e( 'Disabled as default, you can activate it whenever you edit a post or page.', 'cow_contentoverview' ); ?></label><br/><br/></li>
					<li><label for="cow_contentoverview_shcd">
					    <input type="radio" name="cow_contentoverview[method]" id="cow_contentoverview_shcd" value='cow_contentoverview_shcd'<?php checked( 'cow_contentoverview_shcd', $values['method'] ); ?> /> 
					    <?php _e( 'No specific position use the Template-Tag.', 'cow_contentoverview' ); ?></label></li>
					<li><label for="cow_contentoverview_abgn">
					    <input type="radio" name="cow_contentoverview[method]" id="cow_contentoverview_abgn" value='cow_contentoverview_abgn'<?php checked( 'cow_contentoverview_abgn', $values['method'] ); ?> /> 
					    <?php _e( 'Show the TOC before your post text', 'cow_contentoverview' ); ?></label></li>
					<li><label for="cow_contentoverview_afpg">
					    <input type="radio" name="cow_contentoverview[method]" id="cow_contentoverview_afpg" value='cow_contentoverview_afpg'<?php checked( 'cow_contentoverview_afpg', $values['method'] ); ?> /> 
					    <?php _e( 'Show the TOC after the first paragraph or (when used) after the more-tag', 'cow_contentoverview' ); ?></label></li>
				</ul>
			</td>
		</tr>
		<tr<?php if ( isset( $errors ) && in_array( 'style', $errors ) ) echo ' class="form-invalid"'; ?>>
			<th><?php _e( 'List style', 'cow_contentoverview' ); ?></th>
			<td colspan="2">
				<ul>
				  <li class="cow_contentoverview_list_type"><label for="cow_contentoverview_style_decimal">
		        <input type="radio" name="cow_contentoverview[style]" id="cow_contentoverview_style_decimal" value='cow_contentoverview_style_decimal'<?php checked( '', $values['method'] ); ?><?php checked( 'cow_contentoverview_style_decimal', $values['style'] ); ?> /> 
		        <?php _e( 'Decimal (recommended)', 'cow_contentoverview' ); ?></label><br />
		        <p><small>1. <a href="#"><?php _e( 'First header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.1. <a href="#"><?php _e( 'Second header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.2. <a href="#"><?php _e( 'Third header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.2.1. <a href="#"><?php _e( 'Other header', 'cow_contentoverview' ); ?></a><br />
		        2. <a href="#"><?php _e( 'Fourth header', 'cow_contentoverview' ); ?></a><br />
		        3. <a href="#"><?php _e( 'Last but one header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1. <a href="#"><?php _e( 'Last header', 'cow_contentoverview' ); ?></a></small><p></li>
		        
				  <li class="cow_contentoverview_list_type"><label for="cow_contentoverview_style_none">
		        <input type="radio" name="cow_contentoverview[style]" id="cow_contentoverview_style_none" value='cow_contentoverview_style_none'<?php checked( 'cow_contentoverview_style_none', $values['style'] ); ?> /> 
		        <?php _e( 'None only indent', 'cow_contentoverview' ); ?></label><br />
		        <p><small><a href="#"><?php _e( 'First header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#"><?php _e( 'Second header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#"><?php _e( 'Third header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#"><?php _e( 'Other header', 'cow_contentoverview' ); ?></a><br />
		        <a href="#"><?php _e( 'Fourth header', 'cow_contentoverview' ); ?></a><br />
		        <a href="#"><?php _e( 'Last but one header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#"><?php _e( 'Last header', 'cow_contentoverview' ); ?></a></small><p></li>
		        
				  <li class="cow_contentoverview_list_type"><label for="cow_contentoverview_style_disc">
		        <input type="radio" name="cow_contentoverview[style]" id="cow_contentoverview_style_disc" value='cow_contentoverview_style_disc'<?php checked( 'cow_contentoverview_style_disc', $values['style'] ); ?> /> 
		        <?php _e( 'Disc-Bullet', 'cow_contentoverview' ); ?></label><br />
		        <p><small>&bull; <a href="#"><?php _e( 'First header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <a href="#"><?php _e( 'Second header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <a href="#"><?php _e( 'Third header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <a href="#"><?php _e( 'Other header', 'cow_contentoverview' ); ?></a><br />
		        &bull; <a href="#"><?php _e( 'Fourth header', 'cow_contentoverview' ); ?></a><br />
		        &bull; <a href="#"><?php _e( 'Last but one header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <a href="#"><?php _e( 'Last header', 'cow_contentoverview' ); ?></a></small><p></li>
		        
				  <li class="cow_contentoverview_list_type"><label for="cow_contentoverview_style_circle">
		        <input type="radio" name="cow_contentoverview[style]" id="cow_contentoverview_style_circle" value='cow_contentoverview_style_circle'<?php checked( 'cow_contentoverview_style_circle', $values['style'] ); ?> /> 
		        <?php _e( 'Circle-Bullet', 'cow_contentoverview' ); ?></label><br />
		        <p><small>&omicron; <a href="#"><?php _e( 'First header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&omicron; <a href="#"><?php _e( 'Second header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&omicron; <a href="#"><?php _e( 'Third header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&omicron; <a href="#"><?php _e( 'Other header', 'cow_contentoverview' ); ?></a><br />
		        &omicron; <a href="#"><?php _e( 'Fourth header', 'cow_contentoverview' ); ?></a><br />
		        &omicron; <a href="#"><?php _e( 'Last but one header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&omicron; <a href="#"><?php _e( 'Last header', 'cow_contentoverview' ); ?></a></small><p></li>
				      
				  <li class="cow_contentoverview_list_type"><label for="cow_contentoverview_style_loroman">
		        <input type="radio" name="cow_contentoverview[style]" id="cow_contentoverview_style_loroman" value='cow_contentoverview_style_loroman'<?php checked( 'cow_contentoverview_style_loroman', $values['style'] ); ?> /> 
		        <?php _e( 'Lower Roman', 'cow_contentoverview' ); ?></label><br />
		        <p><small>i. <a href="#"><?php _e( 'First header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i.i. <a href="#"><?php _e( 'Second header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i.ii. <a href="#"><?php _e( 'Third header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i.ii.i. <a href="#"><?php _e( 'Other header', 'cow_contentoverview' ); ?></a><br />
		        ii. <a href="#"><?php _e( 'Fourth header', 'cow_contentoverview' ); ?></a><br />
		        iii. <a href="#"><?php _e( 'Last but one header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iii.i. <a href="#"><?php _e( 'Last header', 'cow_contentoverview' ); ?></a></small><p></li>
				      
				  <li class="cow_contentoverview_list_type"><label for="cow_contentoverview_style_uproman">
		        <input type="radio" name="cow_contentoverview[style]" id="cow_contentoverview_style_uproman" value='cow_contentoverview_style_uproman'<?php checked( 'cow_contentoverview_style_uproman', $values['style'] ); ?> /> 
		        <?php _e( 'Upper Roman', 'cow_contentoverview' ); ?></label><br />
		        <p><small>I. <a href="#"><?php _e( 'First header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I.I. <a href="#"><?php _e( 'Second header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I.II. <a href="#"><?php _e( 'Third header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I.II.I. <a href="#"><?php _e( 'Other header', 'cow_contentoverview' ); ?></a><br />
		        II. <a href="#"><?php _e( 'Fourth header', 'cow_contentoverview' ); ?></a><br />
		        III. <a href="#"><?php _e( 'Last but one header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;III.I. <a href="#"><?php _e( 'Last header', 'cow_contentoverview' ); ?></a></small><p></li>
				      
				  <li class="cow_contentoverview_list_type"><label for="cow_contentoverview_style_loalpha">
		        <input type="radio" name="cow_contentoverview[style]" id="cow_contentoverview_style_loalpha" value='cow_contentoverview_style_loalpha'<?php checked( 'cow_contentoverview_style_loalpha', $values['style'] ); ?> /> 
		        <?php _e( 'Lower Alphanumeric', 'cow_contentoverview' ); ?></label><br />
		        <p><small><strong>a)</strong> <a href="#"><?php _e( 'First header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>a</small> 1. <a href="#"><?php _e( 'Second header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>a</small> 2. <a href="#"><?php _e( 'Third header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>a</small> 2.1. <a href="#"><?php _e( 'Other header', 'cow_contentoverview' ); ?></a><br />
		        <strong>b)</strong> <a href="#"><?php _e( 'Fourth header', 'cow_contentoverview' ); ?></a><br />
		        <strong>c)</strong> <a href="#"><?php _e( 'Last but one header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>c</small> 1. <a href="#"><?php _e( 'Last header', 'cow_contentoverview' ); ?></a></small><p></li>
				      
				  <li class="cow_contentoverview_list_type"><label for="cow_contentoverview_style_upalpha">
		        <input type="radio" name="cow_contentoverview[style]" id="cow_contentoverview_style_upalpha" value='cow_contentoverview_style_upalpha'<?php checked( 'cow_contentoverview_style_upalpha', $values['style'] ); ?> /> 
		        <?php _e( 'Upper Alphanumeric', 'cow_contentoverview' ); ?></label><br />
		        <p><small><strong>A)</strong> <a href="#"><?php _e( 'First header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>A</small> 1. <a href="#"><?php _e( 'Second header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>A</small> 2. <a href="#"><?php _e( 'Third header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>A</small> 2.1. <a href="#"><?php _e( 'Other header', 'cow_contentoverview' ); ?></a><br />
		        <strong>B)</strong> <a href="#"><?php _e( 'Fourth header', 'cow_contentoverview' ); ?></a><br />
		        <strong>C)</strong> <a href="#"><?php _e( 'Last but one header', 'cow_contentoverview' ); ?></a><br />
		        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>A</small> 1. <a href="#"><?php _e( 'Last header', 'cow_contentoverview' ); ?></a></small><p></li>
				</ul>
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php echo attribute_escape( __( 'Save your options', 'cow_contentoverview' ) ); ?>" />
		<?php wp_nonce_field( 'cow_contentoverview' ); ?>
	</p>
	</form>
</div>
<?php		

	}
	
	//default options
	/*function activation_hook () {
		if ( is_array( $this->options ) )
			extract( $this->options, EXTR_SKIP );
			
	}   */
		
}

add_action( 'save_post', 'contentoverview_save_postdata' );
add_action( 'admin_menu', 'contentoverview_add_meta_box' );

function contentoverview_add_meta_box() {

	if ( function_exists( 'add_meta_box' ) ) {
		add_meta_box( 'contentoverview', __('contentOVERVIEW', 'contentoverview'), 'contentoverview_meta', 'post' );
		add_meta_box( 'contentoverview', __('contentOVERVIEW', 'contentoverview'), 'contentoverview_meta', 'page' );
	}
	
}

function contentoverview_save_postdata( $post_id ) {

  if ( !wp_verify_nonce( $_POST, plugin_basename( __FILE__ ) ) ) {
    //return $post_id;
  }
  
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

  if ( get_post_meta( $post_id, '_contentoverview_style_value' ) == '') {
    add_post_meta( $post_id, '_contentoverview_style_value', $_POST['_contentoverview_style'], true );
  } else {
    update_post_meta( $post_id, '_contentoverview_style_value', $_POST['_contentoverview_style'] );
  }

  if ( get_post_meta( $post_id, '_contentoverview_position_value' ) == '' ) {
    add_post_meta( $post_id, '_contentoverview_position_value', $_POST['_contentoverview_position'], true );
  } else {
    update_post_meta( $post_id, '_contentoverview_position_value', $_POST['_contentoverview_position'] );
  }

  if ( isset( $_POST['_contentoverview_allowjumps'] ) && $_POST['_contentoverview_allowjumps'] == 1 )
    $_POST['_contentoverview_allowjumps'] = 1;
  else
    $_POST['_contentoverview_allowjumps'] = 0;

  if ( get_post_meta( $post_id, '_contentoverview_allowjumps_state' ) == '' ) {
    add_post_meta( $post_id, '_contentoverview_allowjumps_state', $_POST['_contentoverview_allowjumps'], true );
  } else {
    update_post_meta( $post_id, '_contentoverview_allowjumps_state', $_POST['_contentoverview_allowjumps'] );
  }

  if ( isset( $_POST['_contentoverview_start'] ) && $_POST['_contentoverview_start'] == '' )
    $_POST['_contentoverview_start'] = 1;

  if ( get_post_meta( $post_id, '_contentoverview_start_value' ) == '' ) {
    add_post_meta( $post_id, '_contentoverview_start_value', $_POST['_contentoverview_start'], true );
  } else {
    update_post_meta( $post_id, '_contentoverview_start_value', $_POST['_contentoverview_start'] );
  }
  
  return $post_id;
}

function contentoverview_meta( $post ) {
	$options = get_option( 'cow_contentoverview' );

  if ( sizeof( get_post_meta( $post->ID, '_contentoverview_position_value' ) ) == 0 )
    $cow_position = $options['method'];
  else
    $cow_position = htmlspecialchars( stripcslashes( get_post_meta( $post->ID, '_contentoverview_position_value', true ) ) );

  if ( sizeof( get_post_meta( $post->ID, '_contentoverview_style_value' ) ) == 0 )
    $cow_style = $options['style'];
  else
    $cow_style = htmlspecialchars( stripcslashes( get_post_meta( $post->ID, '_contentoverview_style_value', true ) ) );
    
  $cow_allowjumps = get_post_meta( $post->ID, '_contentoverview_allowjumps_state' );
  $cow_allowjumps = $cow_allowjumps[0];    

  if ( sizeof( get_post_meta( $post->ID, '_contentoverview_start_value' ) ) == 0 )
    $cow_start = 1;
  else
    $cow_start = htmlspecialchars( stripcslashes( get_post_meta( $post->ID, '_contentoverview_start_value', true ) ) );

?>
<style type="text/css">
/*  <![CDATA[  */

.cow {
  line-height:1.8em;
  clear:both;
	text-aalign:right;
}

.cow strong {
	padding-right:5px;
}

.cow_box {
  width:78%;
  height:auto;
  line-height:1.8em;
  padding:1em 10% 1em 10%;
	border:1px #ddd solid;
	background:#fbfbef;
}

.cow_infobtn {
  display:block;
  width:1.4em;
	line-height:1.4em;
	margin-top:.4em;
	font-weight:bold;
	color:#fff;
	text-align:center;
	background:#0071bc;
	float:right;
	cursor:pointer;
}

/*  ]]>  */
</style>
<p class="cow"><strong><?php _e( 'Style', 'cow_contentoverview' ); ?>: </strong>
<select name="_contentoverview_style" id="contentoverview_style" onchange="exapleliststyle(this.selectedIndex)" onclick="exapleliststyle(this.selectedIndex)" onkeyup="exapleliststyle(this.selectedIndex)">
  <option value="cow_contentoverview_style_none"<?php if( $cow_style == 'cow_contentoverview_style_none' || $cow_style == '' ) {echo ' SELECTED';} ?>><?php _e( 'Don\'t use at this post...', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_style_decimal"<?php if( $cow_style == 'cow_contentoverview_style_decimal' ) {echo ' SELECTED';} ?>><?php _e( 'Decimal (recommended)', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_style_indent"<?php if( $cow_style == 'cow_contentoverview_style_indent' ) {echo ' SELECTED';} ?>><?php _e( 'None only indent', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_style_disc"<?php if( $cow_style == 'cow_contentoverview_style_disc' ) {echo ' SELECTED';} ?>><?php _e( 'Disc-Bullet', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_style_circle"<?php if( $cow_style == 'cow_contentoverview_style_circle' ) {echo ' SELECTED';} ?>><?php _e( 'Circle-Bullet', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_style_loroman"<?php if( $cow_style == 'cow_contentoverview_style_loroman' ) {echo ' SELECTED';} ?>><?php _e( 'Lower Roman', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_style_uproman"<?php if( $cow_style == 'cow_contentoverview_style_uproman' ) {echo ' SELECTED';} ?>><?php _e( 'Upper Roman', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_style_loalpha"<?php if( $cow_style == 'cow_contentoverview_style_loalpha' ) {echo ' SELECTED';} ?>><?php _e( 'Lower Alphanumeric', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_style_upalpha"<?php if( $cow_style == 'cow_contentoverview_style_upalpha' ) {echo ' SELECTED';} ?>><?php _e( 'Upper Alphanumeric', 'cow_contentoverview' ); ?></option>
</select></p>
<p id="cow_box" class="cow_box"><?php _e( 'If no style is selected, content OVERVIEW is disabled at this post!', 'cow_contentoverview' ); ?></p>
<p class="cow"><span class="cow_infobtn" onclick="var cow_gb=getElementById('cow_infobox_position').style;cow_gb.display=(cow_gb.display=='none')?'block':'none';">?</span>
  <strong><?php _e( 'Position', 'cow_contentoverview' ); ?>: </strong>
<select name="_contentoverview_position">
  <option value="cow_contentoverview_shcd"<?php if( $cow_position == 'cow_contentoverview_shcd' || $cow_position == '' ) {echo ' SELECTED';} ?>><?php _e( 'No specific position', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_abgn"<?php if( $cow_position == 'cow_contentoverview_abgn' ) {echo ' SELECTED';} ?>><?php _e( 'TOC before text', 'cow_contentoverview' ); ?></option>
  <option value="cow_contentoverview_afpg"<?php if( $cow_position == 'cow_contentoverview_afpg' ) {echo ' SELECTED';} ?>><?php _e( 'TOC after first paragraph', 'cow_contentoverview' ); ?></option>
</select></p>
<p id="cow_infobox_position" style="display:none;padding-left:15px;"><?php _e( 'If you use the <code><small>&lt;?php contentoverview(); ?&gt;</small></code> Template-Tag don\'t use other setting than <em>No specific position</em>. Otherwise you get two content lists.', 'cow_contentoverview' ); ?></p>
<p class="cow"><span class="cow_infobtn" onclick="var cow_gb=getElementById('cow_infobox_jump').style;cow_gb.display=(cow_gb.display=='none')?'block':'none';">?</span>
  <label for="_contentoverview_allowjumps"><strong><?php _e( 'Use jumps between headers', 'cow_contentoverview' ); ?>: </strong></label> 
  <input type="checkbox" id="_contentoverview_allowjumps" name="_contentoverview_allowjumps" value="1" <?php if( $cow_allowjumps ) echo 'CHECKED'; ?>/></p>
<p id="cow_infobox_jump" style="display:none;padding-left:15px;"><?php _e( 'To partially skip automatic numeration you must allow jumps. Write <code><small>$+<em>n</em></small></code> inside the header and the header numbering starts <em>n</em> counts higher (<em>n</em> is a number to count the header upward). Or write <code><small>$--</small></code> to ommit the enumeration on this heading. <strong>It works only with decimal enumeration.</strong>', 'cow_contentoverview' ); ?></p>
<p class="cow"><span class="cow_infobtn" onclick="var cow_gb=getElementById('cow_infobox_start').style;cow_gb.display=(cow_gb.display=='none')?'block':'none';">?</span>
  <label for="_contentoverview_start"><strong><?php _e( 'Set a start value', 'cow_contentoverview' ); ?>: </strong></label> 
  <input type="text" size="15" id="_contentoverview_start" name="_contentoverview_start" value="<?php echo $cow_start; ?>"/></p>
<p id="cow_infobox_start" style="display:none;padding-left:15px;"><?php _e( 'Set a start value like <em>15.9.3</em> and the numbering start with this number. <strong>Use only decimal numbers and dots.</strong>', 'cow_contentoverview' ); ?></p>
<script language="JavaScript">

var ls = [['1.','','&bull;','&omicron;','i.','I.','a','A'],
          ['1.1.','','&bull;','&omicron;','i.i.','I.I.','a 1.','A 1.'],
          ['1.2.','','&bull;','&omicron;','i.ii.','I.II.','a 2.','A 2.'],
          ['2.','','&bull;','&omicron;','ii.','II.','b','B'],
          ['2.1.','','&bull;','&omicron;','ii.i.','II.I.','b 1.','B 1.']];
    
function exapleliststyle(num) {

  if ( num == 0 ) {
    document.getElementById('cow_box').innerHTML = '<?php _e( 'If no style is selected, content OVERVIEW is disabled at this post!', 'cow_contentoverview' ); ?>';
  } else {
    document.getElementById('cow_box').innerHTML = ls[0][num-1]+' <a href=#><?php _e( 'First header', 'cow_contentoverview' ); ?></a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ls[1][num-1]+' <a href=#><?php _e( 'Second header', 'cow_contentoverview' ); ?></a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ls[2][num-1]+' <a href=#><?php _e( 'Third header', 'cow_contentoverview' ); ?></a><br />'+ls[3][num-1]+' <a href=#><?php _e( 'Fourth header', 'cow_contentoverview' ); ?></a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ls[4][num-1]+' <a href=#><?php _e( 'Last header', 'cow_contentoverview' ); ?></a>';
  }

}

exapleliststyle(document.getElementById('contentoverview_style').selectedIndex);

</script>

<?php
}
