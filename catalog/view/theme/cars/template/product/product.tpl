<?php echo $header; ?>

<div class="container">
   <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
   </ul>
   <div class="row">
      <div class="description">
         <div class="container mrg-top10 margin-bottom-30">
            <div class="row" id="content"> 
               
               <!--<div class="col-sm-2">
            <div class="rltd-imgz">
            <ul class="thumbnails prd-ulst">
               <?php if ($images) { ?>
            <?php foreach ($images as $image) { ?>
            <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
            <?php } ?>
            <?php } ?>
            </ul>
               </div>
                </div>-->
               <div class="col-sm-8"> 
                  <!-- <div class="light_box">
                     <div class="row">
                        <div class="col-sm-3">
                           <div class="frmtital">Pick Up Date *</div>
                           <div class="input-group date">
                              <input type="text" id="start_date" name="start_date" class="form-control fld-txt12 input-lg" data-date-format="MM/DD/YYYY" value="<?php echo $start_date; ?>">
                              <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                           </div>
                        </div>
                        <div class="col-sm-3">
                           <div class="frmtital">Pick Up Time *</div>
                           <div class="input-group time">
                              <input type="text" id="start_time" name="start_time" class="form-control fld-txt12 input-lg" data-date-format="hh:00 A" value="<?php echo $start_time; ?>">
                              <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                           </div>
                        </div>
                        <div class="col-sm-3">
                           <div class="frmtital">Drop Off Date *</div>
                           <div class="input-group date">
                              <input type="text" id="end_date" name="end_date" class="form-control fld-txt12 input-lg" data-date-format="MM/DD/YYYY" value="<?php echo $end_date; ?>">
                              <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                           </div>
                        </div>
                        <div class="col-sm-3">
                           <div class="frmtital">Drop Off Time *</div>
                           <div class="input-group time">
                              <input type="text" id="end_time" name="end_time" class="form-control fld-txt12 input-lg" data-date-format="hh:00 A" value="<?php echo $end_time; ?>">
                              <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                           </div>
                        </div>
                        <div class="col-sm-3">
                           <div class="frmtital">&nbsp;</div>
                           <a class="btn-block" onclick="kk()">Check Availability</a> </div>
                        <div class="col-sm-3 pull-right">
                           <div class="frmtital">Total Rent</div>
                           <div class="totprc"> <strong><?php echo $rate; ?></strong> </div>
                        </div>
                     </div>
                  </div>
                  <div class="clear">&nbsp;</div>-->
                  <div class="transparent">
                     <div class="desc">
                        <div class="row">
                           <div class="col-sm-<?php echo $classwidth; ?>">
                              <div class="img">
                                 <div class="desc_title"><i class="fa fa-car"></i> <?php echo $name; ?></div>
                                 <div class="desc_title"><i class="fa fa-male"></i> <?php echo $nickname; ?></div>
                                 <?php if ($this->session->data['customer']=='1'){ ?>
                                 <a class="des-wishbtan" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i></a>
                                 <?php } ?>
                                 <img src="<?php echo $photo_thumb; ?>" /> </div>
                           </div>
                           <?php if ($options) { ?>
                           <div class="col-sm-4">
                           <div class="col-sm-12 optnavl" id="product">
                              <?php if ($options) { ?>
                              <h3><?php echo $text_option; ?></h3>
                              <?php foreach ($options as $option) { ?>
                              <?php if ($option['type'] == 'select') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                                 <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
                                    <option value=""><?php echo $text_select; ?></option>
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                                    <?php if ($option_value['price']) { ?>
                                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                    <?php } ?>
                                    </option>
                                    <?php } ?>
                                 </select>
                              </div>
                              <?php } ?>
                              <?php if ($option['type'] == 'radio') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label"><?php echo $option['name']; ?></label>
                                 <div id="input-option<?php echo $option['product_option_id']; ?>">
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <div class="radio">
                                       <label>
                                          <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                          <?php echo $option_value['name']; ?>
                                          <?php if ($option_value['price']) { ?>
                                          (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                          <?php } ?>
                                       </label>
                                    </div>
                                    <?php } ?>
                                 </div>
                              </div>
                              <?php } ?>
                              <?php if ($option['type'] == 'checkbox') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label"><?php echo $option['name']; ?></label>
                                 <div id="input-option<?php echo $option['product_option_id']; ?>">
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <div class="checkbox">
                                       <label>
                                          <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                          <?php echo $option_value['name']; ?>
                                          <?php if ($option_value['price']) { ?>
                                          (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                          <?php } ?>
                                       </label>
                                    </div>
                                    <?php } ?>
                                 </div>
                              </div>
                              <?php } ?>
                              <?php if ($option['type'] == 'image') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label"><?php echo $option['name']; ?></label>
                                 <div id="input-option<?php echo $option['product_option_id']; ?>">
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <div class="radio">
                                       <label>
                                          <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                                          <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> <?php echo $option_value['name']; ?>
                                          <?php if ($option_value['price']) { ?>
                                          (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                                          <?php } ?>
                                       </label>
                                    </div>
                                    <?php } ?>
                                 </div>
                              </div>
                              <?php } ?>
                              <?php if ($option['type'] == 'text') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                                 <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                              </div>
                              <?php } ?>
                              <?php if ($option['type'] == 'textarea') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                                 <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
                              </div>
                              <?php } ?>
                              <?php if ($option['type'] == 'file') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label"><?php echo $option['name']; ?></label>
                                 <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                                 <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
                              </div>
                              <?php } ?>
                              <?php if ($option['type'] == 'date') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                                 <div class="input-group date">
                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                    </span></div>
                              </div>
                              <?php } ?>
                              <?php if ($option['type'] == 'datetime') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                                 <div class="input-group datetime">
                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span></div>
                              </div>
                              <?php } ?>
                              <?php if ($option['type'] == 'time') { ?>
                              <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                                 <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                                 <div class="input-group time">
                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span></div>
                              </div>
                              <?php } ?>
                              <?php } ?>
                              <?php } ?>
                              <?php if ($recurrings) { ?>
                              <hr>
                              <h3><?php echo $text_payment_recurring ?></h3>
                              <div class="form-group required">
                                 <select name="recurring_id" class="form-control">
                                    <option value=""><?php echo $text_select; ?></option>
                                    <?php foreach ($recurrings as $recurring) { ?>
                                    <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>
                                    <?php } ?>
                                 </select>
                                 <div class="help-block" id="recurring-description"></div>
                              </div>
                              <?php } ?>
                              </div>
                           </div>
                           <?php } ?>
                           
                           <div class="form-group">
                                 <input type="hidden" name="quantity" value="1" size="2" id="input-quantity" class="form-control" />
                                 <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                              </div>
                           
                        </div>
                        <div class="clear"></div>
                        <?php if ($images) { ?>
                        <div class="rltd-imgz">
                           <ul class="thumbnails">
                              <?php foreach ($images as $image) { ?>
                              <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
                              <?php } ?>
                           </ul>
                        </div>
                        <?php } ?>
                        <h1><i class="fa fa-location-arrow"></i> About <?php echo $name ?></h1>
                        <p><?php echo $description; ?></p>
                        <?php if ($filters){ ?>
                        <div class="hed2">Car Features</div>
                        <?php foreach ($filters as $filter){ ?>
                        <div class="col-sm-4 car-ftr"><i class="fa fa-check"></i> <?php echo $filter['name']; ?></div>
                        <?php } ?>
                        <?php } ?>
                        
                        <!--<p class="margin-top-20 margin-bottom-10"> <a href="#" class="link"><span><i class="fa fa-flag"></i></span><span>Report this listing</span></a> </p>--> 
                        
                        <!--<div class="row">
                           <div class="col-md-6 col-sm-6 col-xs-6">
                              <h2>FEATURES</h2>
                           </div>
                           <div class="col-md-6 col-sm-6 col-xs-6">
                              <h2>MILEAGE LIMITS</h2>
                           </div>
                        </div>
                        <div class="row margin-bottom-10">
                           <div class="col-md-6 col-sm-6 col-xs-6"> <img src="images/gaer.png"> Automatic transmission </div>
                           <div class="col-md-6 col-sm-6 col-xs-6"> 200 mi/day </div>
                        </div>
                        <div class="row margin-bottom-10">
                           <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-music gray"></i> Audio input </div>
                           <div class="col-md-6 col-sm-6 col-xs-6"> 1,000 mi/week </div>
                        </div>
                        <div class="row margin-bottom-10">
                           <div class="col-md-6 col-sm-6 col-xs-6"> <img src="images/car-icon.png"> Convertible </div>
                           <div class="col-md-6 col-sm-6 col-xs-6"> 1,500 mi/month </div>
                        </div>--> 
                        
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-sm-12">
                        <h2>DELIVERY</h2>
                        <div class="margin-bottom-10 col-sm-4"> <i class="fa fa-plane gray"></i> Airport <span class="gray">: <?php echo $airport; ?></span> </div>                        
                        <div class="margin-bottom-10 col-sm-4"> <i class="fa fa-lock gray"></i> Insurance Required <span class="gray">: <?php if($insurance=='0' || $insurance == '') { echo 'No'; } else { echo 'Yes'; } ?></span> </div>
                        <div class="margin-bottom-10 col-sm-4"> <i class="fa fa-money gray"></i> Security Deposit <span class="gray">: <?php echo $security; ?></span> </div>
                        <?php if ($min_age!=''){ ?>
                        <div class="margin-bottom-10 col-sm-4"> <i class="fa fa-user gray"></i> Minimum Age <span class="gray">: <?php echo $min_age; ?></span> </div>
                        <?php } ?>
                        <div class="margin-bottom-10 col-sm-4"> <i class="fa fa-map-marker gray"></i> Your Location <span class="gray">: <?php echo $delivery; ?></span> </div>
                        
                        <div class="clear"></div>
                        
                        <div class="rating">
                           <div class="jambo_heading">REVIEWS FROM RENTERS <span id="wrt-rvw" class="pull-right"><i class="fa fa-comments"></i> &nbsp;Write / View Reviews</span> </div>
                           <div class="tog-dv" style="display:none;">
                              <form class="form-horizontal">
                                 <div id="review"></div>
                                 <h2><?php echo $text_write; ?></h2>
                                 <?php if ($review_guest) { ?>
                                 <div class="form-group required">
                                    <div class="col-sm-12">
                                       <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                       <input type="text" name="name" value="" id="input-name" class="form-control" />
                                    </div>
                                 </div>
                                 <div class="form-group required">
                                    <div class="col-sm-12">
                                       <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                                       <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                                       <div class="help-block"><?php echo $text_note; ?></div>
                                    </div>
                                 </div>
                                 <div class="form-group required">
                                    <div class="col-sm-12">
                                       <label class="control-label"><?php echo $entry_rating; ?></label>
                                       &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                                       <input type="radio" name="rating" value="1" />
                                       &nbsp;
                                       <input type="radio" name="rating" value="2" />
                                       &nbsp;
                                       <input type="radio" name="rating" value="3" />
                                       &nbsp;
                                       <input type="radio" name="rating" value="4" />
                                       &nbsp;
                                       <input type="radio" name="rating" value="5" />
                                       &nbsp;<?php echo $entry_good; ?></div>
                                 </div>
                                 <div class="form-group required">
                                    <div class="col-sm-12">
                                       <label class="control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
                                       <input type="text" name="captcha" value="" id="input-captcha" class="form-control" />
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <div class="col-sm-12"> <img src="index.php?route=tool/captcha" alt="" id="captcha" /> </div>
                                 </div>
                                 <div class="buttons">
                                    <div class="pull-right">
                                       <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                                    </div>
                                 </div>
                                 <?php } else { ?>
                                 <?php echo $text_login; ?>
                                 <?php } ?>
                              </form>
                           </div>
                           <!--<span class="stars"></span>
         <h2>9 Tips</h2>--> 
                        </div>
                     </div>
                  </div>
                  <div class="clear">&nbsp;</div>
               </div>
               <div class="col-sm-4">
                  <div class="light_box">
                     <div class="row">
                        <div class="col-sm-4"><span class="price"><?php echo $daily; ?></span>
                           <p>Per Day</p>
                        </div>
                        <div class="col-sm-4"><span class="price"><?php echo $weekly; ?></span>
                           <p>Per Week</p>
                        </div>
                        <div class="col-sm-4"><span class="price"><?php echo $monthly; ?></span>
                           <p>Per Month</p>
                        </div>
                        <div class="clear">
                           <hr>
                        </div>
                        <div class="col-sm-12">
                           <div class="frmtital">Pick Up Date *</div>
                           <div class="input-group date">
                              <input type="text" id="start_date" name="start_date" class="form-control fld-txt12 " data-date-format="MM/DD/YYYY" value="<?php echo $start_date; ?>">
                              <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="frmtital">Pick Up Time *</div>
                           <div class="input-group time">
                              <input type="text" id="start_time" name="start_time" class="form-control fld-txt12 " data-date-format="hh:00 A" value="<?php echo $start_time; ?>">
                              <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="frmtital">Drop Off Date *</div>
                           <div class="input-group date">
                              <input type="text" id="end_date" name="end_date" class="form-control fld-txt12 " data-date-format="MM/DD/YYYY" value="<?php echo $end_date; ?>">
                              <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="frmtital">Drop Off Time *</div>
                           <div class="input-group time">
                              <input type="text" id="end_time" name="end_time" class="form-control fld-txt12 " data-date-format="hh:00 A" value="<?php echo $end_time; ?>">
                              <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="lnht16">&nbsp;</div>
                           <a class="btn-block" onclick="kk()">Check Availability</a> </div>
                        <?php if ($available!='2'){ ?>
                        <div class="col-sm-12">
                           <div class="frmtital">Total Rent</div>
                           <div class="totprc"> <strong><?php echo $rate; ?></strong> </div>
                        </div>
                        <?php } ?>
                        <?php if ($available=='1'){ ?>
                        <div class="col-sm-12">
                            <div class="alert alert-success mrg-top10 text-center" role="alert">Vehicle available in selected date range.</div>
                            <a id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn-block-default text-center"><i class="fa fa-paper-plane"></i> Rent this car</a></div>
                            <?php } else if ($available=='0'){ ?>
                            <div class="col-sm-12">
                            <div class="alert alert-danger text-center" role="alert"> <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Vehicle not available in selected date range.</div>
                            </div>
                            <?php } ?>
                        <div class="clear">&nbsp;</div>
                     </div>
                     
                     <!--<p class="margin-top-20 margin-bottom-0">33 drivers call this a favorite</p>--> 
                  </div>
                  <div id="owner_details">
                     <h1>OWNED BY <?php echo $nickname; ?></h1>
                     <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-4 lr-pad10"><img src="<?php echo $avatar; ?>" width="100%"></div>
                        <div class="col-md-8 col-sm-8 col-xs-8 lr-pad10"> 
                           <!--<h2><?php echo $nickname; ?></h2>-->
                           <div class="row">
                              <div class="col-md-12 col-sm-12 col-xs-12 lr-pad10"><?php echo $agency_zone.', '.$agency_country; ?></div>
                              <?php if($agencyrating != '0'){ ?>
                              <div class="col-md-6 col-sm-6 col-xs-6 lr-pad10 rating">
                                 <div class="stars"><img src="catalog/view/theme/cars/image/<?php echo $agencyrating; ?>.png"/></div>
                              </div>
                              <?php } ?>
                           </div>
                           <a href="<?php echo $agencyurl; ?>" class="contact_btn">View <?php echo $nickname; ?> Vehicles</a> </div>
                     </div>
                  </div>
                  <!--<div class="callender"><img src="images/callender.png" width=""></div>
                  <div class="map">
                     <div class="map_title"> <i class="fa fa-marker"></i> San Francisco, CA 94110 </div>
                     <div class="map_body"> <img src="images/map.jpg" width="100%"> </div>
                  </div>--> 
               </div>
            </div>
         </div>
      </div>
      
      <!--<div class="remarks">
         <div class="container">
            <div class="row">
               <div class="col-md-2 col-sm-2"></div>
               <div class="col-md-8 col-sm-8">
                  <div class="row comment_row">
                     <div class="col-md-1 col-sm-3 col-xs-3 commentImg"> <img src="catalog/view/theme/cars/image/img4-small.jpg"> </div>
                     <div class="col-md-11 col-sm-9 col-xs-9 commentDesc">
                        <div class="row">
                           <div class="col-md-6 col-sm-8 col-xs-12">
                              <h1>Noor Muhammad</h1>
                           </div>
                           <div class="col-md-6 col-sm-4 col-xs-12 text-right date"><i class="fa fa-clock-o"></i> 20 January 2015</div>
                        </div>
                        <p> <i class="fa fa-pencil-square-o"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt 
                           ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                           laboris nisi ut aliquip ex ea commodo consequat. </p>
                     </div>
                  </div>
                  <div class="row comment_row">
                     <div class="col-md-1 col-sm-3 col-xs-3 commentImg"> <img src="catalog/view/theme/cars/image/img4-small.jpg"> </div>
                     <div class="col-md-11 col-sm-9 col-xs-9 commentDesc">
                        <div class="row">
                           <div class="col-md-6 col-sm-8 col-xs-12">
                              <h1>Noor Muhammad</h1>
                           </div>
                           <div class="col-md-6 col-sm-4 col-xs-12 text-right date"><i class="fa fa-clock-o"></i> 20 January 2015</div>
                        </div>
                        <p> <i class="fa fa-pencil-square-o"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt 
                           ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                           laboris nisi ut aliquip ex ea commodo consequat. </p>
                     </div>
                  </div>
                  <div class="row comment_row">
                     <div class="col-md-1 col-sm-3 col-xs-3 commentImg"> <img src="catalog/view/theme/cars/image/img4-small.jpg"> </div>
                     <div class="col-md-11 col-sm-9 col-xs-9 commentDesc">
                        <div class="row">
                           <div class="col-md-6 col-sm-8 col-xs-12">
                              <h1>Noor Muhammad</h1>
                           </div>
                           <div class="col-md-6 col-sm-4 col-xs-12 text-right date"><i class="fa fa-clock-o"></i> 20 January 2015</div>
                        </div>
                        <p> <i class="fa fa-pencil-square-o"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt 
                           ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                           laboris nisi ut aliquip ex ea commodo consequat. </p>
                     </div>
                  </div>
                  <div class="row comment_row">
                     <div class="col-md-1 col-sm-3 col-xs-3 commentImg"> <img src="catalog/view/theme/cars/image/img4-small.jpg"> </div>
                     <div class="col-md-11 col-sm-9 col-xs-9 commentDesc">
                        <div class="row">
                           <div class="col-md-6 col-sm-8 col-xs-12">
                              <h1>Noor Muhammad</h1>
                           </div>
                           <div class="col-md-6 col-sm-4 col-xs-12 text-right date"><i class="fa fa-clock-o"></i> 20 January 2015</div>
                        </div>
                        <p> <i class="fa fa-pencil-square-o"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt 
                           ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                           laboris nisi ut aliquip ex ea commodo consequat. </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>--> 
   </div>
</div>
<script type="text/javascript"><!--
$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('input[type=\'text\'], input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			<!--$('.alert, .text-danger').remove();-->
			<!--$('.form-group').removeClass('has-error');-->

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));
						
						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}
				
				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}
				
				if (json['error']['seller']) {
					$('#content').parent().before('<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i> ' + json['error']['seller'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
				
				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}
			
			if (json['success']) {
				/*$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('#cart-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				$('#cart > ul').load('index.php?route=common/cart/info ul li');*/
				window.location='index.php?route=checkout/checkout';
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			
			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;
	
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
	
	$('#form-upload input[name=\'file\']').trigger('click');
	
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			
			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();
					
					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}
					
					if (json['success']) {
						alert(json['success']);
						
						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script> 
<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
			$('#captcha').attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());
			$('input[name=\'captcha\']').val('');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();
			
			if (json['error']) {
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});

$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
//--></script> 
<script>
$(".closeit").click(function(event){
   $(document).find(".bootstrap-datetimepicker-widget").hide();
});

 $('#wrt-rvw').click(function() {

	    $('.tog-dv').slideToggle('slow');

	    return false;

	});
</script> 
<script type="text/javascript">
function kk(){
	var start_date = $('#start_date').val();
	var start_time = $('#start_time').val();
	var end_date = $('#end_date').val();
	var end_time = $('#end_time').val();
  
	var url = '';
	
	if (start_date!=''){
		var url = url.concat('&start_date=' + start_date);
	}
	
	if (start_time!=''){
		var url = url.concat('&start_time=' + start_time);
	}
  
	if (end_date!=''){
		var url = url.concat('&end_date=' + end_date);
	}
  
	if (end_time!=''){
		var url = url.concat('&end_time=' + end_time);
	}
	
	if (end_time!=''){
		var url = url.concat('&end_time=' + end_time);
	}
	
	if (start_date != '' && start_time != '' && end_date != '' && end_time != ''){
	var sdarr = start_date.split("/");
	var new_start_date = sdarr[2] + '-' + sdarr[0] + '-' + sdarr[1];
	var edarr = end_date.split("/");
	var new_end_date = edarr[2] + '-' + edarr[0] + '-' + edarr[1];
	var new_start_time = ConvertTimeformat("24", start_time);
	var new_end_time = ConvertTimeformat("24", end_time);
	
	if (new_start_date + ' ' + new_start_time >= new_end_date + ' ' + new_end_time){
		alert('End Time should be greater than Start Time.');
		return false;
	}
	}
	window.location.href="index.php?route=product/product&product_id=<?php echo $product_id; ?>" + url;
	}
</script> 
<script language="javascript">
function ConvertTimeformat(format, str) {
    var time = str;
    var hours = Number(time.match(/^(\d+)/)[1]);
    var minutes = Number(time.match(/:(\d+)/)[1]);
    var AMPM = time.match(/\s(.*)$/)[1];
    if (AMPM == "PM" && hours < 12) hours = hours + 12;
    if (AMPM == "AM" && hours == 12) hours = hours - 12;
    var sHours = hours.toString();
    var sMinutes = minutes.toString();
    if (hours < 10) sHours = "0" + sHours;
    if (minutes < 10) sMinutes = "0" + sMinutes;
    return(sHours + ":" + sMinutes);
}
</script> 
<?php echo $footer; ?> 