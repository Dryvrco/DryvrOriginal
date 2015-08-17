<?php echo $header; ?>

<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $heading_title; ?></a></li>
            <li><a href="#tab-license" data-toggle="tab">Expiration</a></li>
            <li><a href="#tab-creditcard" data-toggle="tab">Credit Card</a></li>
            <li><a href="#tab-insurance" data-toggle="tab">Insurance Info</a></li>
          </ul>
          <div style="padding-top:10px"></div>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?> </label>
                <div class="col-sm-10">
                  <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                  <?php if ($error_firstname) { ?>
                  <div class="text-danger"><?php echo $error_firstname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                <div class="col-sm-10">
                  <input type="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Date Of Birth</label>
                <div class="col-sm-2">
                  <select class="form-control" name="dob_mm">
                    <option>- Month -</option>
                    <?php for($i=1;$i<=12;$i++){ ?>
                    <?php $selected = ($i==$dob_mm) ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>>
                    <?php
                    if ($i=='1') echo 'January';
                    if ($i=='2') echo 'February';
                    if ($i=='3') echo 'March';
                    if ($i=='4') echo 'April';
                    if ($i=='5') echo 'May';
                    if ($i=='6') echo 'June';
                    if ($i=='7') echo 'July';
                    if ($i=='8') echo 'August';
                    if ($i=='9') echo 'September';
                    if ($i=='10') echo 'October';
                    if ($i=='11') echo 'November';
                    if ($i=='12') echo 'December';
                    ?>
                    </option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <select class="form-control" name="dob_dd">
                    <option>- Day -</option>
                    <?php for($i=1;$i<=31;$i++){ ?>
                    <?php $selected = ($i==$dob_dd) ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo sprintf('%02d', $i); ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <select class="form-control" name="dob_yy">
                    <option>- Year -</option>
                    <?php for($i=date('Y');$i >= 1900;$i--){ ?>
                    <?php $selected = ($i==$dob_yy) ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                <div class="col-sm-10">
                  <input type="tel" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                  <?php if ($error_telephone) { ?>
                  <div class="text-danger"><?php echo $error_telephone; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
                </div>
              </div>
              <?php foreach ($custom_fields as $custom_field) { ?>
              <?php if ($custom_field['location'] == 'account') { ?>
              <?php if ($custom_field['type'] == 'select') { ?>
              <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $account_custom_field[$custom_field['custom_field_id']]) { ?>
                    <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                  <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'radio') { ?>
              <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="radio">
                      <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $account_custom_field[$custom_field['custom_field_id']]) { ?>
                      <label>
                        <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label>
                        <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
                  <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                  <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'checkbox') { ?>
              <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="checkbox">
                      <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $account_custom_field[$custom_field['custom_field_id']])) { ?>
                      <label>
                        <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label>
                        <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
                  <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                  <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'text') { ?>
              <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                  <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                  <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'textarea') { ?>
              <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
                  <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                  <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'file') { ?>
              <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <button type="button" id="button-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                  <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : ''); ?>" />
                  <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                  <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'date') { ?>
              <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div class="input-group date">
                    <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                  <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                  <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'time') { ?>
              <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div class="input-group time">
                    <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                  <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                  <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'datetime') { ?>
              <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div class="input-group datetime">
                    <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                  <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                  <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </div>
            <div class="tab-pane" id="tab-license">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-country">Country</label>
                <div class="col-sm-10">
                  <select name="country_id" id="input-country" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($countries as $country) { ?>
                    <?php if ($country['country_id'] == $country_id) { ?>
                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                  <?php if ($error_country) { ?>
                  <div class="text-danger"><?php echo $error_country; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-zone">State / Region</label>
                <div class="col-sm-10">
                  <select name="zone_id" id="input-zone" class="form-control">
                  </select>
                  <?php if ($error_zone) { ?>
                  <div class="text-danger"><?php echo $error_zone; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">License</label>
                <div class="col-sm-6">
                  <input type="text" name="driver_license" disabled="disabled" value="" placeholder="<?php echo $str.$editable; ?>" id="togglevis" class="form-control" />
                </div>
                <div class="col-sm-1" id="editdiv"> <a id="edit" class="btn btn-primary">Edit</a> </div>
                <div class="col-sm-1" id="savediv" style="display:none">
                  <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success">Save</button>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Expiration</label>
                <div class="col-sm-2">
                  <select class="form-control" name="dl_expiry_dd">
                    <option>- Day -</option>
                    <?php for($i=1;$i<=31;$i++){ ?>
                    <?php $selected = ($i==$dl_expiry_dd) ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo sprintf('%02d', $i); ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <select class="form-control" name="dl_expiry_mm">
                    <option>- Month -</option>
                    <?php for($i=1;$i<=12;$i++){ ?>
                    <?php $selected = ($i==$dl_expiry_mm) ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>>
                    <?php
                    if ($i=='1') echo 'January';
                    if ($i=='2') echo 'February';
                    if ($i=='3') echo 'March';
                    if ($i=='4') echo 'April';
                    if ($i=='5') echo 'May';
                    if ($i=='6') echo 'June';
                    if ($i=='7') echo 'July';
                    if ($i=='8') echo 'August';
                    if ($i=='9') echo 'September';
                    if ($i=='10') echo 'October';
                    if ($i=='11') echo 'November';
                    if ($i=='12') echo 'December';
                    ?>
                    </option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <input name="dl_expiry_yy" value="<?php echo $dl_expiry_yy; ?>" placeholder="Year" class="form-control" />
                </div>
              </div>
              <div class="form-group required" style="display:none">
                <label class="col-sm-2 control-label" for="input-lastname">Consent for DMV</label>
                <div class="col-sm-10">
                  <input type="text" name="consent" value="<?php echo $consent; ?>" placeholder="Consent" id="input-lastname" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-creditcard">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Card Type</label>
                <div class="col-sm-10">
                  <?php	if ($card_type=='creditcard'){
                    	$ccs = 'selected';
                    } else {
                     	$ccs = '';
                    }
                    if ($card_type=='visa'){
                    	$vs = 'selected';
                    } else {
                     	$vs = '';
                    }
                    if ($card_type=='mastercard'){
                    	$mcs = 'selected';
                    } else {
                     	$mcs = '';
                    }
                ?>
                  <select class="form-control" name="card_type">
                    <option value="">- Select Card Type -</option>
                    <option value="creditcard" selected="<?php echo $css; ?>">Credit Card</option>
                    <option value="visa" selected="<?php echo $vs; ?>">Visa</option>
                    <option value="mastercard" selected="<?php echo $mcs; ?>">Master Card</option>
                  </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Card Number</label>
                <div class="col-sm-6">
                  <input type="text" name="card_number" disabled="disabled" value="" placeholder="<?php echo $str1.$editable1; ?>" id="togglevis1" class="form-control" />
                </div>
                <div class="col-sm-1" id="editdiv1"> <a id="edit1" class="btn btn-primary">Edit</a> </div>
                <div class="col-sm-1" id="savediv1" style="display:none">
                  <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success">Save</button>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Expiration</label>
                <div class="col-sm-10">
                  <div class="col-sm-2 pad-lft0">
                    <select class="form-control" name="expiry_date_mm">
                      <option>- Month -</option>
                      <?php for($i=1;$i<=12;$i++){ ?>
                      <?php $selected = ($i==$expiry_date_mm) ? ' selected="selected"' : ''; ?>
                      <option value="<?php echo $i; ?>" <?php echo $selected; ?>>
                      <?php
                    if ($i=='1') echo 'January';
                    if ($i=='2') echo 'February';
                    if ($i=='3') echo 'March';
                    if ($i=='4') echo 'April';
                    if ($i=='5') echo 'May';
                    if ($i=='6') echo 'June';
                    if ($i=='7') echo 'July';
                    if ($i=='8') echo 'August';
                    if ($i=='9') echo 'September';
                    if ($i=='10') echo 'October';
                    if ($i=='11') echo 'November';
                    if ($i=='12') echo 'December';
                    ?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-sm-1 pad-lft0">
                    <input type="text" name="expiry_date_yy" value="<?php echo $expiry_date_yy; ?>" placeholder="yyyy" class="form-control" style= "width: 70px;" />
                  </div>
                  <div class="col-sm-2 lnht35 pad-lft20"> ( mm / yyyy ) </div>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Name on Card</label>
                <div class="col-sm-10">
                  <input type="text" name="card_name" value="<?php echo $card_name; ?>" placeholder="Name on Card" class="form-control" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-insurance">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Insurance Info</label>
                <div class="col-sm-10">
                  <textarea name="insurance_info" class="form-control"><?php echo $insurance_info; ?></textarea>
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Company</label>
                <div class="col-sm-10">
                  <input type="text" name="ins_company_name" value="<?php echo $ins_company_name; ?>" placeholder="Company" id="input-lastname" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Agent Name</label>
                <div class="col-sm-10">
                  <input type="text" name="ins_agent_name" value="<?php echo $ins_agent_name; ?>" placeholder="Agent Name" id="input-lastname" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Policy Number</label>
                <div class="col-sm-10">
                  <input type="text" name="ins_policy_number" value="<?php echo $ins_policy_number; ?>" placeholder="Policy Number" id="input-lastname" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Expiration</label>
                <div class="col-sm-2">
                  <select class="form-control" name="ins_expiry_dd">
                    <option>- Day -</option>
                    <?php for($i=1;$i<=31;$i++){ ?>
                    <?php $selected = ($i==$ins_expiry_dd) ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo sprintf('%02d', $i); ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <select class="form-control" name="ins_expiry_mm">
                    <option>- Month -</option>
                    <?php for($i=1;$i<=12;$i++){ ?>
                    <?php $selected = ($i==$ins_expiry_mm) ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>>
                    <?php
                    if ($i=='1') echo 'January';
                    if ($i=='2') echo 'February';
                    if ($i=='3') echo 'March';
                    if ($i=='4') echo 'April';
                    if ($i=='5') echo 'May';
                    if ($i=='6') echo 'June';
                    if ($i=='7') echo 'July';
                    if ($i=='8') echo 'August';
                    if ($i=='9') echo 'September';
                    if ($i=='10') echo 'October';
                    if ($i=='11') echo 'November';
                    if ($i=='12') echo 'December';
                    ?>
                    </option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <input name="ins_expiry_yy" value="<?php echo $ins_expiry_yy; ?>" placeholder="Year" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Name on Insurance</label>
                <div class="col-sm-10">
                  <input type="text" name="ins_name" value="<?php echo $ins_name; ?>" placeholder="Name on Insurance" id="input-lastname" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Agent Contact No.</label>
                <div class="col-sm-10">
                  <input type="text" name="ins_phone" value="<?php echo $ins_phone; ?>" placeholder="Agent Contact No." id="input-lastname" class="form-control" />
                </div>
              </div>
            </div>
          </div>
        </form>
        <div class="page-header">
          <div class="container-fluid">
            <div class="pull-right">
              <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
              <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
          </div>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$("#edit").click(function(event){
   event.preventDefault();
   $('#togglevis').prop("disabled", false);
   $('#togglevis').prop("placeholder", 'Change Lisence Code');
   document.getElementById("togglevis").value= "";
   document.getElementById("editdiv").style.display = 'none';
   $('#savediv').show();
});

$("#edit1").click(function(event){
   event.preventDefault();
   $('#togglevis1').prop("disabled", false);
   $('#togglevis1').prop("placeholder", 'Change Card Number');
   document.getElementById("togglevis1").value= "";
   document.getElementById("editdiv1").style.display = 'none';
   $('#savediv1').show();
});

// Sort the custom fields
$('.form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('.form-group').length) {
		$('.form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('.form-group').length) {
		$('.form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('.form-group').length) {
		$('.form-group:first').before(this);
	}
});
//--></script> 
<script type="text/javascript"><!--
$('button[id^=\'button-custom-field\']').on('click', function() {
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
					$(node).parent().find('.text-danger').remove();
					
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
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	if (this.value!=''){
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode\']').parent().parent().removeClass('required');
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
					}
				
					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	
	});
	}
});

$('select[name=\'country_id\']').trigger('change');
//--></script> 
<?php echo $footer; ?>