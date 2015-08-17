<?php echo $header; ?>

<div class="container">
   <div class="row"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
      <?php $class = 'col-sm-12'; ?>
      <?php } ?>
      <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
         <div class="container">
            <ul class="breadcrumb">
               <?php foreach ($breadcrumbs as $breadcrumb) { ?>
               <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
               <?php } ?>
            </ul>
            <div class="listdp-bar"> <span class="lfttxt">Our Agencies</span>
               <div class="pull-right">
                  <div class="btn-group hidden-xs">
                     <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="List View"><i class="fa fa-th-list"></i></button>
                     <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="Grid View"><i class="fa fa-th"></i></button>
                  </div>
               </div>
            </div>
            <div class="row">
               <?php foreach($seller_details as $seller_detail) { ?>
               <div class="product-layout product-list">
                  <div class="height agncy-lstg">
                     <div class="col-sm-4 img"> <a href="<?php echo $seller_detail['href']; ?>"><img class="img-responsive thumbnail" src="<?php echo $seller_detail['avatar']; ?>" /></a></div>
                     <div class="col-sm-8 desc">
                        <div class="agncy-topabar">
                           <h1 class="lftset tog"> <a href="<?php echo $seller_detail['href']; ?>"><?php echo $seller_detail['nickname']; ?></a> </h1>
                           <div class="col-sm-6 pull-right tog">
                              <ul role="tablist" class="nav nav-pills pull-right">
                                 <li class="active mrg-btm0" role="presentation"> <strong><?php echo $cars_available; ?></strong>&nbsp;&nbsp;<span class="badge drkbtn"><?php echo $seller_detail['total_seller_cars']; ?></span> </li>
                              </ul>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="agncy-lctn"><i class="fa fa-map-marker"></i><?php echo $seller_detail['location']; ?></div>
                           <?php if ($seller_detail['rating']){ ?>
                           <div class="rating mrg-btm5 pull-right">
                              <div class="stars"><img src="catalog/view/theme/cars/image/<?php echo $seller_detail['rating']; ?>.png"></div>
                           </div>
                           <?php } ?>
                        </div>
                        <div class="col-sm-12">
                           <p class="dscrp"><?php echo $seller_detail['description']; ?></p>
                        </div>
                     </div>
                     <!--<div class="col-sm-12"><p class="dscrp"><?php echo $seller_detail['description']; ?></p>
                     <?php if ($seller_detail['agencyfilters']){ ?>
                     <?php foreach ($seller_detail['agencyfilters'] as $filter){ ?>
                     <div class="col-sm-4 car-ftr"><i class="fa fa-check"></i> <?php echo $filter['name']; ?></div>
                     <?php } ?>
                     <?php } ?></div>--> 
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
      <?php echo $content_bottom; ?></div>
   <?php echo $column_right; ?> </div>
<?php echo $footer; ?> 