<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2014/06/30
 * Time: 9:46 AM
 */
set_time_limit( 0 );
//error_reporting( 0 );
//ini_set( 'display_errors', 0 );
//ini_set( 'log_errors', 0 );

require_once( 'mk-heatmap-db.php' );

$variance = 5;
if ( isset( $_POST['variance'] ) ) {
	$variance = $_POST['variance'];
}

$min_point_count = 1;
if ( isset( $_POST['min_point_count'] ) ) {
	$min_point_count = $_POST['min_point_count'];
}

function requireToVar( $file, $var = array() ) {
	ob_start();
	extract( $var );
	require( $file );

	return ob_get_clean();
}

$spots    = array();
$counts   = array();
$height   = 0;
$width    = 0;
$radius   = 10;
$grd_step = 50;
$exArr    = array();

foreach ( $clickArr as $key => $value ) {
	$valueArr = explode( " ", $value );
	$exArr[]  = $valueArr;
	if(isset($valueArr[2])) {
		$width = ( $width < $valueArr[2] ) ? $valueArr[2] : $width;
		if ( isset( $valueArr[3] ) ) {
			if ( $height < $valueArr[3] ) {
				$height = $valueArr[3];
			}
		} elseif ( $height < $valueArr[1] ) {
			$height = $valueArr[1];
		}
	} elseif ( $height < $valueArr[1] ) {
		$height = $valueArr[1];
	}
}

if ( $_POST['map'] != "scroll" ) {
	foreach ( $exArr as $key => $value ) {
		switch ( $_POST['layout'] ) {
			case 'left':
				$_x = $value[0];
				$_y = $value[1];
				break;
			case 'center':
				$delta = (int) ( $width / 2 - $value[2] / 2 );
				$_x    = $value[0] + $delta;
				$_y    = $value[1];
				break;
			case 'right':
				$delta = $width - $value[2];
				$_x    = $value[0] + $delta;
				$_y    = $value[1];
				break;
		}

		if ( $variance > 0 ) {
			// Aggregate the data points
			$_x = ( $_x / $variance >> 0 ) * $variance;
			$_y = ( $_y / $variance >> 0 ) * $variance;
		}

		if ( isset( $counts[ $_x . "_" . $_y ] ) ) {
			$counts[ $_x . "_" . $_y ] += 1;
		} else {
			$counts[ $_x . "_" . $_y ] = 1;
			$spots[]                   = array( $_x, $_y );
		}
	}


	$data = array();
	foreach ( $spots as $key => $value ) {
		if ( $counts[ $value[0] . "_" . $value[1] ] >= $min_point_count ) {
			$obj        = new stdClass();
			$obj->x     = (int) $value[0];
			$obj->y     = (int) $value[1];
			$obj->value = $counts[ $value[0] . "_" . $value[1] ];
			$data[]     = $obj;
		}
	}

//  $width += $radius;
//	$height += $radius;

	$count  = count( $data );
	$return = array(
		"width"  => $width,
		"height" => $height > 1000 ? $height : 3000,
		"max"    => ! empty( $counts ) ? max( $counts ) : 0,
		"count"  => $count,
		"data"   => $data
	);

} else {
	//build grid
	$color_map = array(
		/*0%*/
		"#16A099",
		/*10%*/
		"#166ba3",
		/*20%*/
		"#53907a",
		/*30%*/
		"#8db353",
		/*40%*/
		"#c6da29",
		/*50%*/
		"#e9f50a",
		/*60%*/
		"#eaff00",
		/*70%*/
		"#c8ff00",
		/*80%*/
		"#9aff00",
		/*90%*/
		"#6fff00",
		/*100%*/
		"#37ff00"
	);

	$height  = 0;
	$newArr = array();
	foreach ( $clickArr as $arr ) {
		$a = explode( " ", $arr );
		if ( isset( $a[1] ) ) {
			if ( $height < $a[1] ) {
				$height = $a[1];
			}
		} elseif ( $a[0] > $height ) {
			$height = $a[0];
		}
		$newArr[] = $a[0];
	}

	$count = count( $clickArr );
	unset( $clickArr );

	$grd_step          = $_POST['grid_step'];
	$grid_levels_count = (int) ceil( $height / $grd_step );

	$points   = array();
	$percents = array();
	$colors   = array();
	$map      = array();
	// Default the arrays
	for ( $i = 0; $i < $grid_levels_count; $i ++ ) {
		$key            = $i * $grd_step + $grd_step;
		$points[ $i ]   = 0;
		$percents[ $i ] = 0;
		$colors[ $i ]   = $color_map[0];
		$map[ $i ]      = $key;
	}

	sort( $newArr );
	foreach ( $newArr as $value ) {
		for ( $i = 0; $i < $grid_levels_count; $i ++ ) {
			$hPt = $map[ $i ];
			if ( $hPt > $value ) {
				break;
			}
			$points[ $i ] += 1;
			$percents[ $i ] = (int) floor( $points[ $i ] * 100 / $points[0] );
			$colors[ $i ]   = $color_map[ (int) floor( $percents[ $i ] / 10 ) ];
		}
	}


	$return = array(
		"max_h"      => $height,
		"percents"   => $percents,
		"colors"     => $colors,
		"grid_count" => $grid_levels_count,
		"grid_step"  => $_POST['grid_step']
	);

}

$args                    = array();
$args['count']           = $count;
$args['home_url']        = $_POST['home_url'];
$args['url']             = str_replace( ".", "~", $_POST['url'] );
$args['map']             = $_POST['map'];
$args['brandLogo']       = isset( $_POST['brandLogo'] ) ? $_POST['brandLogo'] : '';
$args['from']            = $_POST['from'];
$args['to']              = $_POST['to'];
$args['grid_step']       = $_POST['grid_step'];
$args['width']           = $width;
$args['height']          = $height;
$args['variance']        = $variance;
$args['min_point_count'] = $min_point_count;

echo json_encode( array_merge( $return, array( "view" => requireToVar( "views/mk-heatmap-view.php", $args ) ) ) );

die();