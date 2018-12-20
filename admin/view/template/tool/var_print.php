<?php
    // echo '<hr>';

function var_log(&$varInput, $var_name='', $reference='', $method = '', $sub = false) {

    static $output ;
    static $depth ;

    if ( $sub == false ) {
        $output = '' ;
        $depth = 0 ;
        $reference = $var_name ;
        $var = serialize( $varInput ) ;
        $var = unserialize( $var ) ;
    } else {
        ++$depth ;
        $var =& $varInput ;
       
    }
       
    // constants
    $nl = "<br>" ;
    $block = 'a_big_recursion_protection_block';
   
	$c = $depth ;
    $indent = '' ;
    while( $c -- > 0 ) {
        // $indent .= '|  ' ;
    }

    // if this has been parsed before
    if ( is_array($var) && isset($var[$block])) {
   
        $real =& $var[ $block ] ;
        $name =& $var[ 'name' ] ;
        $type = gettype( $real ) ;
        $output .= $indent . $var_name . ' ' . $method . '& ' . ($type=='array'?'Array':get_class($real)) . ' ' . $name . $nl;
   
    // havent parsed this before
    } else {

        // insert recursion blocker
        $var = Array( $block => $var, 'name' => $reference );
        $theVar =& $var[ $block ] ;

        // print it out
        $type = gettype( $theVar ) ;
		
		switch( $type ) {
       
            case 'array' :
                //$output .= $indent . $var_name . ' ' . $method . ' Array (' . $nl;
				$output .= $indent . $var_name;
                $keys=array_keys($theVar);
                foreach($keys as $name) {
                    $value=&$theVar[$name];
                    // var_log($value, $name, $reference.'["'.$name.'"]', '=', true);
					var_log($value, $name, $reference.'["'.$name.'"]', '', true);
                }
                // $output .= $indent . ')' . $nl;
                break ;
           
            case 'boolean' :
				if ( strlen($theVar) == 0 ) {
					$output .= '<span class="left1">' . $var_name . '</span> = <span class="key1"> (' . $type . ') NULL or ZERO' . $nl;
				} else {
					$output .= '<span class="left1">' . $var_name . '</span> = <span class="key1"> (' . $type . ') <span class="right1">' . $theVar . '</span> ' . $nl;
				}
				break ;
			
			case 'object' :
                //$output .= $indent . $var_name . ' = ' . get_class($theVar) . ' {' . $nl;
				$output .= '<span class="left1">' . $var_name . '</span> = <span class="key1"> (' . $type . ') <span class="right1">' . $theVar . '</span>' . $nl;
				
                foreach($theVar as $name=>$value) {
                    var_log($value, $name, $reference.'->'.$name, '->', true);
                }
                $output .= $indent . '}' . $nl;
                break ;
           
            case 'string' :
                $output .= '<span class="left1">' . $var_name . '</span></span> = <span class="key1"> (' . $type . ')</span> <span class="right1">' .  $theVar. '</span>' . $nl;
                break ;
               
            default :
                $output .= '<span class="left1">' . $var_name . '</span> = <span class="key1"> (' . $type . ')</span> <span class="right1">' . $theVar . '</span>' . $nl;
                break ;
               
        }
    }
   
    -- $depth ;
   
    if( $sub == false )
        return $output ;
       
}
?>