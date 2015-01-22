<?php $this->load->view('includes/header');?>





<style type="text/css">

    td{ 

        text-align: center;

    }

</style>

<div id="wrapper">

    <!-- Sidebar -->

    <div id="sidebar-wrapper">

        <ul class="sidebar-nav">



            <li class="sidebar-brand"><a href="#">Store Administratoin</a></li>
            
            <li class="<?php if( isset($active) && $active == 'user') print 'active' ?>"> <?php echo anchor('auth' , 'User Management<i class="glyphicon glyphicon-user"></i>');?> 
            </li>



            <li class="<?php if( isset($active) && $active == 'showroom') print 'active' ?>"> <?php echo anchor('showroom' , 'Showrooms');?> </li>
            <li class="<?php if( isset($active) && $active == 'supplier') print 'active' ?>"> 
                <?php echo anchor('supplier' , 'Suppliers');?> 
            </li>

            <li class="<?php if( isset($active) && $active == 'group') print 'active' ?>"> <?php echo anchor('group' , 'Groups');?> 
            </li>

            <li class="<?php if( isset($active) && $active == 'sub_group') print 'active' ?>"> <?php echo anchor('subgroup' , 'Sub Groups');?> </li>
            <li class="<?php if( isset($active) && $active == 'item_type') print 'active' ?>"> 
                <?php echo anchor('item_type' , 'Item Types');?> 
            </li>

        
            <li class="<?php if( isset($active) && $active == 'size') print 'active' ?>"> <?php echo anchor('size' , 'Sizes');?> </li>

            <!-- <li class="<?php //if( isset($active) && $active == 'color') print 'active' ?>"> <?php //echo anchor('color' , 'Colors');?> </li> -->
            <li class="<?php if( isset($active) && $active == 'purchase') print 'active' ?>"> <?php echo anchor('purchase' , 'Purchases');?> </li>
            <li class="<?php if( isset($active) && $active == 'transfer') print 'active' ?>"> <?php echo anchor('transfer' , 'Transfers');?> </li>
            <li class="<?php if( isset($active) && $active == 'audit') print 'active' ?>"> <?php echo anchor('items/audit' , 'Audit');?> </li>
            <li class="<?php if( isset($active) && $active == 'survey') print 'active' ?>"> <?php echo anchor('survey' , 'survey');?> </li>
            <li class="<?php if( isset($active) && $active == 'survey_reports') print 'active' ?>"> <?php echo anchor('survey/survey_reports' , 'Survey Reports');?> </li>
            <li class="<?php if( isset($active) && $active == 'sales') print 'active' ?>"> <?php echo anchor('sales' , 'Sales');?> </li>
            <li class="<?php if( isset($active) && $active == 'expense') print 'active' ?>"> <?php echo anchor('expense' , 'Expenses');?> </li>
            <li class="<?php if( isset($active) && $active == 'staff') print 'active' ?>"> <?php echo anchor('staff' , 'Staffs');?> </li>
            <li class="<?php if( isset($active) && $active == 'discount') print 'active' ?>">  <?php echo anchor('items/discount' , 'Discounts');?> </li>
            <li class="<?php if( isset($active) && $active == 'item') print 'active' ?>">  <?php echo anchor('items/search' , 'Item search');?> </li>
            <li class="<?php if( isset($active) && $active == 'item_stock') print 'active' ?>">  <?php echo anchor('items/stock' , 'Stock Information');?> </li>


        </ul>

    </div>



    <!-- Page content -->

    <div id="page-content-wrapper">

        <nav class="navbar navbar-default" role="navigation">

            <div class="collapse navbar-collapse navbar-ex1-collapse">

                <a class="navbar-brand" href="#"><?php print $page_name; ?></a>

                <?php if(isset($links)): ?>

                    <ul class="nav navbar-nav">

                        <?php foreach($links as $link):?>

                            <li class="sub-navigation"><?php print $link; ?> </li>

                        <?php endforeach?>

                    </ul>

                <?php endif ?>





                <ul class="nav navbar-nav navbar-right">

                    <li><a href="<?php echo base_url(); ?>auth/logout">Log out</a></li>

                </ul>

            </div>

        </nav>





        <?php if($this->session->flashdata('message') != ''): ?>

            <div class="row">

                <div class="alert alert-success">

                    <?php echo $this->session->flashdata('message');?>

                </div>

            </div>

        <?php endif ?>

        <?php  $this->load->view($main_content , $vars);?>

    </div>





</div>



<?php $this->load->view('includes/footer');?>