
<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar ">

    <!-- Brand Logo -->
    <a href="https://mayurik.com" class="brand-link">
      <img src="dist/img/log.jpg" alt="logo" class="brand-image ">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item">
            <a href="index.php?page=dashboard" class="nav-link <?php echo $actual_link=='dashboard'?'active':'';?>">
              <i class="material-symbols-outlined">dashboard</i>
              <p> Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="index.php?page=member" class="nav-link <?php echo $actual_link=='member'?'active':'';?>">
              <i class="material-symbols-outlined">supervisor_account</i>
              <p> Customer</p>
            </a>
          </li>

          <!-- PROCUREMENT SECTION -->
          <?php 
            $procurement_pages = ['suppliar', 'buy_product', 'buy_list', 'buy_refund_list', 'purchase_report', 'purchase_pay_report'];
            $is_procurement_active = in_array($actual_link, $procurement_pages);
          ?>
          <li class="nav-item has-treeview <?php echo $is_procurement_active ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo $is_procurement_active ? 'active' : ''; ?>">
              <i class="material-symbols-outlined">shopping_cart</i>
              <p>
                Procurement
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="index.php?page=suppliar" class="nav-link <?php echo $actual_link=='suppliar'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Supplier</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=buy_product" class="nav-link <?php echo $actual_link=='buy_product'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Buy</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=buy_list" class="nav-link <?php echo $actual_link=='buy_list'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Buy List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=buy_refund_list" class="nav-link <?php echo $actual_link=='buy_refund_list'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Refund Buy List</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- INVENTORY OPS SECTION -->
          <?php 
            $categories = $obj->all('catagory');
            $inventory_pages = ['category', 'add_product', 'product_list', 'quick_sell', 'sell_list', 'sell_return_list', 'add_expense', 'exspense_list'];
            $is_inventory_active = in_array($actual_link, $inventory_pages);
            if (!$is_inventory_active) {
                foreach($categories as $cat) {
                    if (isset($_GET['category']) && $_GET['category'] == $cat->name) { $is_inventory_active = true; break; }
                }
            }
          ?>
          <li class="nav-item has-treeview <?php echo $is_inventory_active ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo $is_inventory_active ? 'active' : ''; ?>">
              <i class="material-symbols-outlined">inventory_2</i>
              <p>
                Inventory Ops
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="index.php?page=product_list" class="nav-link <?php echo ($actual_link=='product_list' && !isset($_GET['category']))?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Product List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=category" class="nav-link <?php echo $actual_link=='category'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categories</p>
                </a>
              </li>
              <?php foreach($categories as $cat): ?>
              <li class="nav-item">
                <a href="index.php?page=product_list&category=<?php echo urlencode($cat->name); ?>" class="nav-link <?php echo (isset($_GET['category']) && $_GET['category'] == $cat->name) ? 'active' : ''; ?>">
                  <i class="far fa-dot-circle nav-icon"></i>
                  <p><?php echo $cat->name; ?></p>
                </a>
              </li>
              <?php endforeach; ?>
              <li class="nav-item">
                <a href="index.php?page=quick_sell" class="nav-link <?php echo $actual_link=='quick_sell'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Sell</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=sell_list" class="nav-link <?php echo $actual_link=='sell_list'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sell List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=exspense_list" class="nav-link <?php echo $actual_link=='exspense_list'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Expense List</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- REPORTS & ANALYTICS SECTION -->
          <?php 
            $report_pages = ['profit_loss', 'sales_report', 'sell_pay_report', 'purchase_report', 'purchase_pay_report', 'customers_report', 'suppliar_report'];
            $is_reports_active = in_array($actual_link, $report_pages);
          ?>
          <li class="nav-item has-treeview <?php echo $is_reports_active ? 'menu-open' : ''; ?>">
            <a href="#" class="nav-link <?php echo $is_reports_active ? 'active' : ''; ?>">
              <i class="material-symbols-outlined">analytics</i>
              <p>
                Reports & Analytics
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="index.php?page=profit_loss" class="nav-link <?php echo $actual_link=='profit_loss'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profit Loss Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=sales_report" class="nav-link <?php echo $actual_link=='sales_report'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=purchase_report" class="nav-link <?php echo $actual_link=='purchase_report'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=customers_report" class="nav-link <?php echo $actual_link=='customers_report'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?page=suppliar_report" class="nav-link <?php echo $actual_link=='suppliar_report'?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Supplier Report</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="index.php?page=staff_list" class="nav-link <?php echo ($actual_link=='staff_list' || $actual_link=='add_stuff')?'active':'';?>">
               <i class="material-symbols-outlined">diversity_3</i>
              <p> Staff Management</p>
            </a>
          </li>
        
          <li class="nav-item">
            <a href="index.php?page=backup_database" class="nav-link <?php echo $actual_link=='backup_database'?'active':'';?>">
               <i class="material-symbols-outlined">settings</i>
              <p> Backup Database</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

    </div>
    <?php require_once 'inc/member_add_modal.php'; ?>
    <?php require_once 'inc/catagory_modal.php'; ?>
    <?php require_once 'inc/suppliar_modal.php'; ?>
    <?php require_once 'inc/expense_catagory_modal.php'; ?>