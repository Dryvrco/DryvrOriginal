<?php echo $header; ?>

<div class="container">
   <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
   </ul>
   <div class="row"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
      <?php $class = 'col-sm-12'; ?>
      <?php } ?>
      <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
         <div class="row">
            <div class="container">
               <div class="row">
                  <?php foreach($seller_details as $seller_detail) { ?>
                  <div class="col-sm-4 agn-img"> <img  class="img-responsive thumbnail" src="<?php echo $seller_detail['avatar']; ?>" /></div>
                  <div class="col-sm-8">
                     <div class="agncy-topabar">
                        <h1 class="pull-left"> <?php echo $seller_detail['nickname']; ?> </h1>
                        <div class="col-sm-4 pull-right">
                           <ul role="tablist" class="nav nav-pills pull-right">
                              <li class="active mrg-btm0" role="presentation"> <strong><?php echo $cars_available; ?></strong>&nbsp;&nbsp;<span class="badge drkbtn"><?php echo $seller_detail['total_seller_cars']; ?></span> </li>
                           </ul>
                        </div>
                     </div>
                     <div>
                        <div class="agncy-lctn"><i class="fa fa-map-marker"></i><?php echo $seller_detail['location']; ?></div>
                        <div class="rating pull-left">
                           <?php if($seller_detail['rating']) { ?>
                           <?php if ($seller_detail['rating'] == 1) { ?>
                           <div class="stars"><img src="catalog/view/theme/cars/image/1.png"/></div>
                           <?php } elseif($seller_detail['rating']== 2) { ?>
                           <div class="stars"><img src="catalog/view/theme/cars/image/2.png"/></div>
                           <?php } elseif($seller_detail['rating']== 3) {  ?>
                           <div class="stars"><img src="catalog/view/theme/cars/image/3.png"/></div>
                           <?php } elseif($seller_detail['rating']== 4) {  ?>
                           <div class="stars"><img src="catalog/view/theme/cars/image/4.png"/></div>
                           <?php } else { ?>
                           <div class="stars"><img src="catalog/view/theme/cars/image/5.png"/></div>
                           <?php } ?>
                           <?php } ?>
                        </div>
                        <?php } ?>
                        <div class="sico-bar"><span class='st_sharethis_large' displayText='ShareThis'></span> <span class='st_facebook_large' displayText='Facebook'></span> <span class='st_twitter_large' displayText='Tweet'></span> <span class='st_linkedin_large' displayText='LinkedIn'></span> <span class='st_pinterest_large' displayText='Pinterest'></span> <span class='st_email_large' displayText='Email'></span></div>
                     </div>
                     <div class="clear"></div>
                     <p><?php echo $seller_detail['description']; ?></p>
                     
                     <div class="row">
                        <?php if ($agencyfilters){ ?>
                        <?php foreach ($agencyfilters as $filter){ ?>
                        <div class="col-sm-4 car-ftr"><i class="fa fa-check"></i> <?php echo $filter['name']; ?></div>
                        <?php } ?>
                        <?php } ?>
                     </div>
                     
                  </div>
               </div>
            </div>
            <!--End-container-->
            
            <div class="container">
               <div class="listdp-bar"> <span class="lfttxt">Your Vehicles</span>
                  <div class="dropdown pull-right">
                     <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" type="button" class="btn btn-primary" id="dLabel"><?php echo $sort_by; ?> &nbsp;&nbsp; <span class="caret"></span> </button>
                     <ul aria-labelledby="dLabel" role="menu" class="dropdown-menu">
                        <li><a href="<?php echo $url.'&sort=p.daily&order=ASC' ?>">Low to High</a></li>
                        <li><a href="<?php echo $url.'&sort=p.daily&order=DESC' ?>">High to Low</a></li>
                     </ul>
                  </div>
               </div>
               <div class="row">
                  <?php foreach($agency_cars as $agency_car) { ?>
                  <div class="col-sm-6">
                     <div class="agncy-lstg">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                           <div class="vehicle mrg-btm0"> <a href="<?php echo $agency_car['href']; ?>"><img src="<?php echo $agency_car['image']; ?>"></a> </div>
                        </div>
                        <div class="col-sm-9">
                           <h1 class="itm-nam"> <a class="pull-left" href="<?php echo $agency_car['href']; ?>"><?php echo $agency_car['name']; ?></a> </h1>
                           <div class="dscrp"><?php echo $agency_car['description']; ?></div>
                           <div class=" pricing">
                              <div class="row">
                                 <div class="col-sm-3"> <span>Daily</span>
                                    <p><?php echo $agency_car['daily']; ?></p>
                                 </div>
                                 <div class="col-sm-3"><span>Weekly</span>
                                    <p><?php echo $agency_car['weekly']; ?></p>
                                 </div>
                                 <div class="col-sm-3"><span>Monthly</span>
                                    <p><?php echo $agency_car['monthly']; ?></p>
                                 </div>
                              </div>
                              <div> 
                                 <!--<button onclick="cart.add(60)" class="requestBtn">Request Now</button>--> 
                                 <a href="<?php echo $agency_car['href']; ?>" class="requestBtn">View Details</a> </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php } ?>
               </div>
               <div class="clear">&nbsp;</div>
               <div class="row mrg-btm10">
                  <div class="col-sm-6 pgn-ul"><?php echo $pagination; ?></div>
                  <div class="col-sm-6 rslt-rit"><?php echo $results; ?></div>
               </div>
            </div>
            <!--End-container--> 
            
         </div>
      </div>
      <?php echo $content_bottom; ?></div>
   <?php echo $column_right; ?> </div>
<?php echo $footer; ?> 