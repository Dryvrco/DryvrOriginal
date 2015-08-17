<?php
	/*
	 * HeatMapTracker
	 * (c) 2013. HeatMapTracker
	 * http://HeatMapTracker.com
	 */

	if( !defined( 'HMT_STARTED' ) || !isset( $this->PLUGIN_PATH ) ) {
		die( 'Can`t be called directly' );
	}

	global $wpdb, $loggedin_user;

	if( !is_user_logged_in( $loggedin_user ) && IS_KEY_VALID ) {
		header( 'location: '.admin_url().'?login' );
	}

	$_GET[ "name" ] = rawurlencode( $_GET[ "name" ] );

	$perpage = 30;
	$main_table = T_PREFIX.$this->OPTIONS[ 'dbtable_name' ];
	if( is_agency() ) {
		$user = current_user();
		$cur_status_id = detect_user_status( $user );
		$ui_enabled = validate_user_status( $cur_status_id );
		if( !$ui_enabled ) {
			die( "Subscription status issue" );
		}
		$main_table .= '_'.$loggedin_user[ 2 ];
	}

	function extract_pages( $data ) {
		$page_history = "";
		$arr_data = json_decode( $data );
		$inc = 0;
		if( is_array( $arr_data ) ) {
			foreach( $arr_data as $key => $value ) {
				$pg = "";
				foreach( $value as $kkey => $vvalue ) {
					$pg = $kkey;
				}
				foreach( $vvalue as $kkkey => $vvvalue ) {
					foreach( $vvvalue as $kkkkey => $vvvvalue ) {
						if( !isset( $time[ $key ] ) ) {
							$time[ $key ] = 0;
						}
						if( $time[ $key ] < $vvvvalue[ 0 ] ) {
							$time[ $key ] = ceil( $vvvvalue[ 0 ] );
						}
					}
				}
				if( $time[ $key ] == 0 ) {
					continue;
				}
				$str = explode( "/", $pg );
				if( $str[ count( $str ) - 1 ] != "" ) {
					$str = parse_url( $str[ count( $str ) - 1 ] );
				} else {
					$str = parse_url( $str[ count( $str ) - 2 ] );
				}
				$page_history .= '<a title="'.$pg.'" href="'.$pg.'" target="_blank">'.$str[ 'path' ].'</a>'.( ( count( $arr_data ) > ( $inc + 1 ) ) ? ' <b style="color:#f00">></b> ' : '' );
				$inc++;
			}
		}

		return $page_history;
	}

	if( isset( $this->PROJECTS[ $_GET[ 'name' ] ][ 'settings' ][ 'opt_record_tz' ] ) ) {
		date_default_timezone_set( $this->PROJECTS[ $_GET[ 'name' ] ][ 'settings' ][ 'opt_record_tz' ] );
	}
	//delete

	if( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'delete' ) {
		$entry_id = ( is_array( $_REQUEST[ 'session' ] ) ) ? $_REQUEST[ 'session' ] : array( $_REQUEST[ 'session' ] );
		foreach( $entry_id as $id ) {
			$wpdb->query( "DELETE FROM `".$main_table."` WHERE id = $id" );
		}
	}
	//delete batch
	if( isset( $_POST[ 'todel' ] ) && is_array( $_POST[ 'todel' ] ) && count( $_POST[ 'todel' ] ) > 0 ) {
		$entry_id = ( is_array( $_POST[ 'todel' ] ) ) ? $_POST[ 'todel' ] : array( $_POST[ 'todel' ] );
		foreach( $entry_id as $id ) {
			$wpdb->query( "DELETE FROM $main_table WHERE id = $id" );
		}
	}

	// if we have a result loop over the result

	$order_by = ( isset( $_GET[ 'order_by' ] ) ) ? ( ( $_GET[ 'order_by' ] == "session_time" ) ? "`session_end` - `session_start`" : "`".$_GET[ 'order_by' ]."`" ) : "`session_start`";

	$search = ( isset( $_GET[ 's' ] ) && $_GET[ 's' ] != "" ) ? " AND `session_spydata` like '%".$_GET[ 's' ]."%'" : "";

	$q = "SELECT * FROM `".$main_table."` WHERE `project` = '".$_GET[ "name" ]."' ".$search." ORDER BY ".$order_by." DESC";
	//pagination
	$perpage = ( isset( $_GET[ 'perpage' ] ) ) ? $_GET[ 'perpage' ] : $perpage;


	$totalitems = $wpdb->get_var( "SELECT COUNT(*) FROM `".$main_table."` WHERE `project` = '".$_GET[ "name" ]."' ".$search );
	$paged = !empty( $_GET[ "paged" ] ) ? $wpdb->escape_String( $_GET[ "paged" ] ) : '';


	if( !empty( $paged ) && !empty( $perpage ) ) {
		$offset = $paged;
		$q .= ' LIMIT '.(int) $offset.','.(int) $perpage;
	} else {
		$q .= ' LIMIT '.(int) $perpage;
	}

	$r = $wpdb->query( $q );
	$nr = $wpdb->numRows( $r );

	$objGeoIP = new hmtracker_GeoIP();

	include( $this->COMMON_MARKUP_PATH.'mk-header.php' );
?>
<div id="content" >
	<div class="analytics-block" >
		<h2 ><span >VIEW RECORDED SESSIONS</span > Analytics</h2 >

		<div class="table-holder" >
			<label class="pull-left" >
				<form method="get" action="<?php echo $this->PLUGIN_URL ?>" id="perpage" >
					<input type="hidden" name="analytics" value="" />
					<input type="hidden" name="name" value="<?php echo rawurldecode( $_GET[ "name" ] ) ?>" />
					<input type="hidden" name="order_by" value="<?php echo ( isset( $_GET[ "order_by" ] ) ) ? $_GET[ "order_by" ] : "session_start" ?>" />
					<input type="hidden" name="s" value="<?php echo ( isset( $_GET[ "s" ] ) ) ? $_GET[ "s" ] : "" ?>" />
					per page
					<select size="1" name="perpage" class="input-small perpage" style="margin-bottom: 0" >
						<?php
							for( $i = 10; $i < 510; $i += 10 ) {
								?>
								<option value="<?php echo $i ?>" <?php if( ( isset( $_GET[ 'perpage' ] ) && $i == $_GET[ 'perpage' ] ) || ( !isset( $_GET[ 'perpage' ] ) && $i == $perpage ) ): ?> selected="selected"<?php endif; ?>>
									<?php echo $i ?>
								</option >
							<?php } ?>
					</select >
				</form >
			</label >
			<label class="pull-left" >&nbsp;&rsaquo;&nbsp;</label >
			<label class="pull-left" >
				<form method="get" action="<?php echo $this->PLUGIN_URL ?>" id="orderby" >
					<input type="hidden" name="analytics" value="" />
					<input type="hidden" name="name" value="<?php echo rawurldecode( $_GET[ "name" ] ) ?>" />
					<input type="hidden" name="perpage" value="<?php echo ( isset( $_GET[ "perpage" ] ) ) ? $_GET[ "perpage" ] : $perpage ?>" />
					<input type="hidden" name="order_by" value="<?php echo ( isset( $_GET[ "order_by" ] ) ) ? $_GET[ "order_by" ] : "session_start" ?>" />
					<input type="hidden" name="s" value="<?php echo ( isset( $_GET[ "s" ] ) ) ? $_GET[ "s" ] : "" ?>" />
					order by
					<select size="1" name="order_by" class="input-medium perpage" style="margin-bottom: 0" >
						<option <?php echo ( isset( $_GET[ "order_by" ] ) && $_GET[ "order_by" ] == "session_start" ) ? 'selected' : "" ?> value="session_start" >
							Session Date
						</option >
						<option <?php echo ( isset( $_GET[ "order_by" ] ) && $_GET[ "order_by" ] == "session_time" ) ? 'selected' : "" ?> value="session_time" >
							Session Time
						</option >
					</select >
				</form >
			</label >
			<label class="pull-left" >&nbsp;&rsaquo;&nbsp;</label >
			<label class="pull-left" >
				<form method="get" action="<?php echo $this->PLUGIN_URL ?>" id="search" >
					<input type="hidden" name="analytics" value="" />
					<input type="hidden" name="name" value="<?php echo rawurldecode( $_GET[ "name" ] ) ?>" />
					<input type="hidden" name="perpage" value="<?php echo ( isset( $_GET[ "perpage" ] ) ) ? $_GET[ "perpage" ] : $perpage ?>" />
					<input type="hidden" name="order_by" value="<?php echo ( isset( $_GET[ "order_by" ] ) ) ? $_GET[ "order_by" ] : "session_start" ?>" />
					<input type='text' value="<?php echo ( isset( $_GET[ "s" ] ) ) ? $_GET[ "s" ] : "" ?>" style="margin: 0; height: 12px;" name="s" >
					<button type="submit" class="btn btn-mini btn-primary width-auto " > search</button >
				</form >
			</label >

			<div class="pull-right" >
				<button class="btn btn-mini btn-primary width-auto " id="deleteall" >Delete Selected</button >
			</div >


			<form id="to_del_form"
			      action="<?php echo admin_url().'?analytics&name='.$_GET[ "name" ].( isset( $_GET[ 'perpage' ] ) ? '&perpage='.$_GET[ 'perpage' ] : '' ).( isset( $_GET[ 'paged' ] ) ? '&paged='.$_GET[ 'paged' ] : '' ) ?>"
			      method="post" >

				<table id="gcheck" border="0" width="100%" cellpadding="0" cellspacing="0" class="table table-bordered table-striped bs-table" id="analytics_table" >
					<tr >
						<th style="width:8px;" ><input type="checkbox" class="group-checkable" data-set="#gcheck .checkboxes" /></th >
						<th ><span >Play</span ></th >
						<th ><span >Date - Time</span ></th >
						<th ><span >User IP</span ></th >
						<th ><span >Country</span ></th >
						<th ><span >OS, Browser</span ></th >
						<th ><span >Referrer</span ></th >
						<th ><span >Length</span ></th >
						<th class="table-header-options " ><span >Options</span ></th >
					</tr >
					<?php
						$wrap_col = 25;
						if( $nr > 0 ) {
							while( $a = $wpdb->fetchNextAssoc( $r ) ) {
								$referrer_url = preg_replace( "/'/", "", $a[ 'referrer' ] );
								$referrer_title = strlen( $referrer_url ) >= $wrap_col ? substr( $referrer_url, 0, $wrap_col )."..." : $referrer_url;
								//extract viewed pages
								if( '' == $page_history = extract_pages( $a[ 'session_spydata' ] ) ) {
									$page_history = "No tracked pages found!";
								}
								//split user id
								$usrData = explode( "~", $a[ 'user_id' ] );
								$play_button = '<a href="'.admin_url().'?hmtrackerview=&session='.$a[ "id" ].'" target="_blank" style="height: 11px;" class="btn btn-mini btn-info"><img src="'.$this->PLUGIN_URL.'images/play-btn.png" width="9" height="10" style="vertical-align:baseline;" /></a>';
								$delete_link = '<a href="'.admin_url().'?analytics&name='.$_GET[ "name" ].'&paged='.( isset( $_GET[ 'perpage' ] ) ? '&perpage='.$_GET[ 'perpage' ] : '' ).( isset( $_GET[ 'order_by' ] ) ? '&order_by='.$_GET[ 'order_by' ] : '' ).( isset( $_GET[ 's' ] ) ? '&s='.$_GET[ 's' ] : '' ).'&action=delete&session='.$a[ "id" ].'">delete</a>';
								//build table row
								?>
								<tr class="rows <?php echo $a[ "id" ]; ?>" >
									<td rowspan="2" ><input type="checkbox" class="checkboxes" name="todel[]" value="<?php echo $a[ "id" ]; ?>" /></td >
									<td rowspan="2" class="options-width" ><?php echo $play_button; ?></td >
									<td ><?php echo date( "M d, Y - H:i", $a[ "session_time" ] ) ?></td >
									<td ><?php echo $usrData[ 0 ] ?></td >
									<td ><i class="flag-<?php echo $a[ 'country_code' ]; ?>" ></i > <?php echo $a[ 'country' ]; ?></td >
									<td ><?php echo $usrData[ 1 ] ?></td >
									<td ><?php echo $a[ 'referrer' ] && $a[ 'referrer' ] != "''" ? "<a target='_blank' href='{$referrer_url}' title='{$referrer_url}'>{$referrer_title}</a>" : "Direct entry"; ?></td >
									<td ><?php echo HMTrackerFN::sec2hms( ( $a[ "session_end" ] - $a[ "session_start" ] ) ) ?></td >
									<td rowspan="2" class="options-width" ><?php echo $delete_link; ?></td >
								</tr >
								<tr >
									<td colspan="6" class="notop" ><?php echo $page_history ?></td >
								</tr >
							<?php
							}
						} else {
							?>
							<tr class="rows" >
								<td colspan="9" style="text-align: center;" >No Session Found.</td >
							</tr >
						<?php
						}
					?>

				</table >

				<?php
					if( $nr > 0 ) {
						echo pnp_pagination( $totalitems, $perpage, 5, $paged, admin_url().'?analytics&name='.$_GET[ "name" ].( isset( $_GET[ 'perpage' ] ) ? '&perpage='.$_GET[ 'perpage' ] : '' ).( isset( $_GET[ 'order_by' ] ) ? '&order_by='.$_GET[ 'order_by' ] : '' ).( isset( $_GET[ 's' ] ) ? '&s='.$_GET[ 's' ] : '' ) );
					}
				?>
			</form >

		</div >
	</div >
</div >
<?php include( $this->COMMON_MARKUP_PATH.'mk-footer.php' ); ?>
<!-- END FOOTER -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script >

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/app.js" type="text/javascript" ></script >
<!-- END PAGE LEVEL SCRIPTS -->
<script >
	jQuery( document ).ready( function() {
		App.init(); // initlayout and core plugins


		jQuery( '.perpage' ).change( function() {
			jQuery( this ).parent().submit();
		} );

		jQuery( '#gcheck .group-checkable' ).change( function() {
			var set = jQuery( this ).attr( "data-set" );
			var checked = jQuery( this ).is( ":checked" );
			jQuery( set ).each( function() {
				if( checked ) {
					jQuery( this ).attr( "checked", true );
				} else {
					jQuery( this ).attr( "checked", false );
				}
			} );
			jQuery.uniform.update( set );
		} );

		jQuery( '#deleteall' ).click( function() {
			jQuery( '#to_del_form' ).submit();
		} );


	} );
</script >
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
