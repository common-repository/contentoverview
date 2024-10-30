<?php
/*
Plugin Name: contentOVERVIEW
Plugin URI: http://fehertamas.com/contentoverview/
Description: This plugin creates an hierarchical overview about all used headers in your posting.
Version: 0.4.9
Author: Feher Tamas
Author URI: http://fehertamas.com/

Copyright: Feher Tamas
License: GPL2+
*/

if ( !defined( 'ABSPATH' ) ) exit;

class cow_contentoverview {
	var $cow_tpl = 0;
	var $cow_depth = 0;
	var $cow_last = 0;
	var $cow_hiar = array(0);
	var $cow_num = array();
	var $cow_hist = array();
	var $options;
	var $meta;

	function init() {
    load_plugin_textdomain( 'cow_contentoverview', false, dirname( plugin_basename( __FILE__ ) ) );
		$this->options = get_option( 'cow_contentoverview' );
		add_filter( 'the_content', array( &$this, 'cow_search' ) );
		add_filter( 'contentoverview', array( &$this, 'cow_toc' ) );
	}

	function cow_toc( $content ) {
  	global $post;
		$this->options = get_option( 'cow_contentoverview' );
		$this->cow_search( $post->post_content );

	  if ( is_array( $this->cow_num ) && sizeof( $this->cow_num ) > 0 )
  		return join ( '', $this->cow_num );
  		
    return;
	}

	function cow_search( $content ) {
  	global $post;
  	$this->meta = get_post_custom( $post->ID );
  	
  	$this->cow_depth = 0;
  	$this->cow_last = 0;
  	$this->cow_hiar = array(0);
  	$this->cow_num = array();
  	$this->cow_hist = array();

  	if ( $this->meta['_contentoverview_style_value'][0] == 'cow_contentoverview_style_none' || $this->meta['_contentoverview_style_value'][0] == '' ) {
		  return $content;
		}
		   
		if ( $this->options['method'] == 'cow_contentoverview_no' ) {
		  return $content;
		}
  	
  	if ( $this->meta['_contentoverview_style_value'][0] != '' ) {
		  $this->options['style'] = $this->meta['_contentoverview_style_value'][0];
		}
  	
  	if ( $this->meta['_contentoverview_allowjumps_state'][0] == 1 ) {
		  $this->options['allowjumps'] = $this->meta['_contentoverview_allowjumps_state'][0];
		}
  	
  	$this->options['start'] =  1;
  	
  	if ( $this->meta['_contentoverview_start_value'][0] != '' ) {
		  $this->options['start'] = $this->meta['_contentoverview_start_value'][0];
		  
		  if ( sizeof( $this->cow_hiar ) == 0 && preg_match( '/([0-9\.]*?)/', $this->options['start'] ) ) {
		    $this->cow_hiar = explode( ".", $this->options['start'] );
		    $this->cow_hiar[sizeof( $this->cow_hiar ) - 1]--;
		  }
		    
		}
  	
  	if ( $this->meta['_contentoverview_position_value'][0] != '' ) {
		  $this->options['method'] = $this->meta['_contentoverview_position_value'][0];
		}
	
    if ( !is_search() && !is_tag() && !is_author() && !is_day() && !is_month() && !is_year() && !is_category() ) {	
  		$content = preg_replace_callback( "/<h([0-6])[^>]*>(.*?)<\/h\\1>/i", array( &$this, 'cow_listing' ), $content );
    	  
    	/* BEFORE TEXT */  
  		if ( $this->options['method'] == 'cow_contentoverview_abgn' ) {
    	  $content = join ( '', $this->cow_num ).$content;
    	}
    	/* AFTER FIRST PARAGRAPH */  
  		elseif ( $this->options['method'] == 'cow_contentoverview_afpg' ) {
    	  $content = preg_replace( '/<span id=\"more/', join ( '', $this->cow_num ).'<span id="more', $content );
    	}
	    
	    /*echo '<pre>';
	    print_r($this->hist);
	    echo '</pre>';*/
    	  
      return $content;
    }
    
    //if ( is_search() || is_tag() || is_author() || is_day() || is_month() || is_year() || is_category() )
      //$content = preg_replace( '/\$contentoverview/', '', $content );

		return $content;
	}

	function cow_listing( $prcmatch ) {
	  $cntpwrd = 0;
	  $mmtnmrtn = 0;
	
	  if ( $this->cow_depth == 0 && ( $prcmatch[1] <= $this->cow_last || empty( $this->cow_last ) ) ) {
	    $this->cow_last = $prcmatch[1];
	  }
	  
	  /* COUNT THE NUMBER UPWARD */
	  if ( $this->options['allowjumps'] && preg_match( "/(\\\$\+)([\d]*)/", $prcmatch[2], $sm ) ) {
	    //$prcmatch[2] = preg_replace( '/'.preg_quote($sm[0]).'/', '', $prcmatch[2] );
	    $cntpwrd = $sm[2];
	  }

	  /* ENUMERATE */
	  if ( !preg_match( "/(\\\$\-\-)/", $prcmatch[2], $sm ) && !preg_match( "/(\\\$\&\#8211\;)/", $prcmatch[2], $sm ) || !$this->options['allowjumps'] ) {

	    if ( $prcmatch[1] > $this->cow_last ) {
	      $this->cow_depth++;
	      $this->cow_hiar[sizeof( $this->cow_hiar )] = 0;
	    } elseif ( $prcmatch[1] < $this->cow_last ) {

	      if ( $this->cow_last - $prcmatch[1] > 1 ) {
	      
	        for ( $i = 0; $i < $this->cow_depth; $i++ )
	          unset( $this->cow_hiar[sizeof( $this->cow_hiar ) - 1] );
	      
	      } else {
	        unset( $this->cow_hiar[sizeof( $this->cow_hiar ) - 1] );
	      }
	      
	      if ( $this->cow_depth > 0 ) {
  	      $this->cow_depth -= $this->cow_last - $prcmatch[1];
	      }
	      
	      /* DEBUG - IF NEXT TAG SMALLER THEN SMALLEST BEFORE */
	      if ( $this->cow_depth < 0 ) 
	        $this->cow_depth = 0;
	    }
	    
	    //$adnu += $cntpwrd;
	    
	    //$this->cow_hist[$adus] = $adus;//.$adnu;
	    $this->cow_hiar[sizeof( $this->cow_hiar ) - 1] = ( !isset( $this->cow_hiar[$this->cow_depth] ) ) ? 1 + $cntpwrd : $this->cow_hiar[sizeof( $this->cow_hiar ) - 1] + 1 + $cntpwrd;
	    //$this->cow_hiar[$this->cow_depth] = ( !isset( $this->cow_hiar[$this->cow_depth] ) ) ? 1 + $cntpwrd : $this->cow_hiar[$this->cow_depth] + 1 + $cntpwrd;
	    $cow_hiar = array();
	    $cow_ljoiner = '.';
	    $cow_joiner = '.';
	    
	    /* LIST STYLING */
	    if ( $this->options['style'] != 'cow_contentoverview_style_decimal' ) {

	      foreach ( $this->cow_hiar as $key => $val ) {
    
          switch ( $this->options['style'] ) {
            case 'cow_contentoverview_style_loroman' :
        	    $cow_hiar[$key] = $this->decimal2roman( $val );
        	    break;
            case 'cow_contentoverview_style_uproman' :
        	    $cow_hiar[$key] = $this->decimal2roman( $val, 'high');
        	    break;
            case 'cow_contentoverview_style_indent' :
        	    $cow_hiar = array();
        	    $cow_ljoiner = '';
        	    $cow_joiner = '';
        	    break;
            case 'cow_contentoverview_style_disc' :
        	    $cow_hiar = array();
        	    $cow_ljoiner = '&bull;';
        	    $cow_joiner = '';
        	    break;
            case 'cow_contentoverview_style_circle' :
        	    $cow_hiar = array();
        	    $cow_ljoiner = '&omicron;';
        	    $cow_joiner = '';
        	    break;
            case 'cow_contentoverview_style_loalpha' || 'cow_contentoverview_style_upalpha' :
              $alext = 'low';
      
              if ( $this->options['style'] == 'cow_contentoverview_style_upalpha' )
                $alext = 'high';
      
              if ( $key == 0 )
        	      $cow_hiar[$key] = '<strong>'.$this->decimal2alpha( $val-1, $alext ).')</strong> ';
        	    else
        	      $cow_hiar[$key] = $val.'.';
        	    
        	    if ( $key > 0 && !isset ( $cow_hiar[$key+1] ) )
        	      $cow_hiar[0] = '<small>'.$this->decimal2alpha( $this->cow_hiar[0] - 1, $alext ).'</small> ';

        	    $cow_ljoiner = '';
        	    $cow_joiner = '';
        	    break;
            default :
        	    $cow_hiar[$key] = $val;
        	    break;
        	}
      	
      	}
    	
    	} else {
    	  $cow_hiar = $this->cow_hiar;
    	}
	    
	    $this->cow_depth = ( $this->cow_depth < 0 ) ? 0 : $this->cow_depth;
	  
	  }
	  /* OMMIT THE ENUMERATION */
	  else {
	    //$prcmatch[2] = preg_replace( '/'.preg_quote('\$--').'/', '', $prcmatch[2] );
	    $cow_hiar = array();
	    $cow_ljoiner = $cow_joiner = '';
	  }
	  
	  $prcmatch[2] = preg_replace( array( '/\$\-\-/', '/\$\&\#8211\;/', '/(\$\+)([\d]*)/' ), array( '', '', '' ), $prcmatch[2] );
	  
	  /* GENERATE UNIQUE TAG-ID*/
	  $adus = preg_replace( '/([^_a-z0-9])/i', '', strtolower($prcmatch[2]) );
	  
	  if ( !isset( $this->hist[$adus] ) ) {
	    $this->hist[$adus] = 0;
	  }
	  else {
	    $this->hist[$adus]++;
	  }
	    
	  //$adnu = ( isset( $this->hist[$adus] ) && $this->hist[$adus] > 0 ) ? '_'.$this->hist[$adus] : '';
	  //echo $adnu.' ('.$adus.')<br/>';
	  
	  $this->cow_num[] = '<p>'.str_repeat( '&nbsp;', 1 + ( $this->cow_depth * 5 ) ).join( $cow_hiar, $cow_ljoiner ).$cow_ljoiner.' <a href="#'.$adus.$adnu.'">'.$prcmatch[2].'</a></p>'."\r\n";
	  $this->cow_last = $prcmatch[1];
	  
		return '<h'.$prcmatch[1].' id="'.$adus.$adnu.'">'.join( $cow_hiar, $cow_joiner ).$cow_joiner.' '.$prcmatch[2].'</h'.$prcmatch[1].'>'."\r\n";
	}

  function decimal2roman ( $dec, $ori = 'low' ) {
    $ret = ''; 
  
    if ( !is_numeric( $dec ) || $dec <= 0 || $dec > 3999)
      return false; 

    if ( $ori == 'low' )
      $rom = array( 'm' => 1000, 'd' => 500, 'c' => 100, 'l' => 50, 'x' => 10, 'v' => 5, 'i' => 1 ); 
    elseif ( $ori == 'high' )
      $rom = array( 'M' => 1000, 'D' => 500, 'C' => 100, 'L' => 50, 'X' => 10, 'V' => 5, 'I' => 1 ); 

    foreach ( $rom as $key => $val ) {
    
      if ( ( $num[$key] = floor( $dec / $val ) ) > 0 )
        $dec -= $num[$key] * $val; 
        
    }
    
    foreach ( $num as $key => $val ) { 
      $ret .= ( $val > 3 ) ? $key . $old_key : str_repeat ( $key, $val ); 
      $old_key = $key;                 
    }

    if ( $ori == 'low' )
      return str_replace ( array ( 'dcd', 'lxl', 'viv' ), array ( 'cm', 'xc', 'ix' ), $ret ); 
    elseif ( $ori == 'high' )
      return str_replace ( array ( 'DCD', 'LXL', 'VIV' ), array ( 'CM', 'XC', 'IX' ), $ret ); 
  }

  function decimal2alpha ( $dec, $ori = 'low' ) {
    $ret = ''; 
  
    if ( !is_numeric( $dec ) )
      return false; 

    if ( $ori == 'low' )
      $rom = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z' ); 
    elseif ( $ori == 'high' )
      $rom = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' ); 
      
    if ( $dec > floor( $dec / count( $rom ) ) )
      $dec -= floor( $dec / count( $rom ) ) * count ( $rom );
      
    return $rom[$dec];  
  }
	
}

if ( !is_admin() ) {
	$cow_contentoverview = new cow_contentoverview;
} else {
  define ('WPLANG', 'de_DE');
  load_plugin_textdomain( 'cow_contentoverview' );
	require( dirname( __FILE__ ) . '/contentoverview-admin.php' );
	$cow_contentoverview = new cow_contentoverview_admin;
	register_activation_hook( __FILE__, array( &$cow_contentoverview, 'activation_hook' ) );
}

function contentoverview( $prefix = '', $suffix = '' ) {
  $toc = apply_filters( 'contentoverview', 'contentoverview' );
  
  if ( $toc ) {
    echo $prefix.$toc.$suffix;
  }
    
  return;
}

add_action( 'init', array( &$cow_contentoverview, 'init' ) );
