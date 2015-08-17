<div class="list-group">
</div>

        <div class="list-group">
          <span class="list-group-item"><b><?php echo $ms_account_seller_account; ?></b></span>
          <?php if ($ms_seller_created) { ?>
          <?php if ($this->MsLoader->MsSeller->getStatus($this->agency->getSellerId()) == MsSeller::STATUS_ACTIVE) { ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-dashboard')) { ?>
          <a class="list-group-item" href="<?php echo $this->url->link('seller/account-dashboard'); ?>"><?php echo $ms_account_dashboard; ?></a>
		  <?php } ?>
          <?php } ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-profile')) { ?>
          <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-profile'); ?>"><?php echo $ms_account_sellerinfo; ?></a>
		  <?php } ?>
		  <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-features'); ?>">Agency Features</a>
          <?php if ($this->MsLoader->MsSeller->getStatus($this->agency->getSellerId()) == MsSeller::STATUS_ACTIVE) { ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-product')) { ?>
          <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-product/preadd'); ?>"><?php echo $ms_account_newproduct; ?></a>
		  <?php } ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-product')) { ?>
          <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-product'); ?>"><?php echo $ms_account_products; ?></a>
		  <?php } ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-bank')) { ?>
		  <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-bank'); ?>"><?php echo $ms_bank_account; ?></a>
		  <?php } ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-reviews')) { ?>
		  <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-reviews'); ?>"><?php echo $ms_reviews; ?></a>
		  <?php } ?>
		  <?php if($this->agency->hasPermission('access', 'user/user')) { ?>
		  <a class="list-group-item" href= "<?php echo $this->url->link('user/user'); ?>"><?php echo $ms_staff; ?></a>
		  <?php } ?>
		  <?php if($this->agency->hasPermission('access', 'user/user_permission')) { ?>
		  <a class="list-group-item" href= "<?php echo $this->url->link('user/user_permission'); ?>" style="display:none"><?php echo $ms_staff_groups; ?></a>
		  <?php } ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-order')) { ?>
          <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-order'); ?>"><?php echo $ms_account_orders; ?></a>
		  <?php } ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-transaction')) { ?>
          <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-transaction'); ?>"><?php echo $ms_account_transactions; ?></a>
		  <?php } ?>
          <?php if ($this->config->get('msconf_allow_withdrawal_requests')) { ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-withdrawal')) { ?>
          <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-withdrawal'); ?>"><?php echo $ms_account_withdraw; ?></a>
		  <?php } ?>
          <?php } ?>
		  <?php if($this->agency->hasPermission('access', 'seller/account-stats')) { ?>
          <a class="list-group-item" href= "<?php echo $this->url->link('seller/account-stats'); ?>"><?php echo $ms_reports; ?></a>
		  <?php } ?>
		  <a class="list-group-item" href="<?php echo $this->url->link('account/logout'); ?>"><?php echo $text_logout; ?></a>
          <?php } ?>
          <?php } else { ?>
          <a class="list-group-item" href="<?php echo $this->url->link('account/login'); ?>"><?php echo $text_login; ?></a>
          <a class="list-group-item" href="<?php echo $this->url->link('account/register-seller'); ?>"><?php echo $ms_account_register_seller; ?></a>
          <?php } ?>
          </div>
      