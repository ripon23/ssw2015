<!DOCTYPE html>
<html>
<head>
  <?php echo $this->load->view('head', array('title' => lang('users_page_name'))); ?>
</head>
<body>

<?php echo $this->load->view('header'); ?>


    <div class="span2">
      <?php echo $this->load->view('account/account_menu', array('current' => 'manage_users')); ?>
    </div>

    <div class="span10">

      <h2><?php echo lang('users_page_name'); ?></h2>
      <hr>
      <!-- <div class="well">
        <?php //echo lang('users_description'); ?>
      </div> -->
      <form class="form-horizontal" role="form" id="create-site-form"  name="create-site-form" action="<?php echo base_url()."account/manage_users";?>" method="post">  
        <table class="table table-bordered">
          
          <tr class="info">
            
            <td>
            <input id="user_name" name="user_name" type="text" placeholder="Username" value="<?php echo set_value('user_name');?>" class="form-control input-sm"> 
            </td>
            <td>
            <div class="col-md-12">
            <select name="role_id" class="form-control input-sm" id="user_role">
                <option value="">All</option>            
                <?php foreach ($all_roles as $role) : ?>
                <option value="<?php echo $role->id; ?>" <?php if(set_value('role_id')==$role->id) echo "selected";?> ><?php echo $role->name; ?></option>
                <?php endforeach; ?>
            </select>
            </div>
            </td>
            <td>
            <input id="email" name="email" type="text" placeholder="Email" value="<?php echo set_value('email');?>" class="form-control input-sm">
            </td>        
            <td>
            <input id="fullname" name="fullname" type="text" placeholder="Full name" value="<?php echo set_value('fullname');?>" class="form-control input-sm">
            </td>
             <td>
             <input type="submit" class="btn btn-info btn-sm" name="search_submit" value="Search">
             <!-- <button type="submit" class="btn btn-info btn-sm" name="search_submit"><span class='glyphicon glyphicon-search'></span></button>
             -->
            </td>
          </tr>
          
        </table>
      </form>
      <div class="pull-left" style="font-size:16px; margin:20px 0px">
        Total User <?php echo $total_donor;?>
      </div>
      <div class="pull-right">
        <?php echo $links; ?>
      </div>
      <table class="table table-condensed table-bordered table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th><?php echo lang('users_username'); ?></th>
              
              <th><?php echo lang('settings_fullname'); ?></th>
              <th><?php echo lang('settings_email'); ?></th>
              <th>
                <?php if( $this->authorization->is_permitted('create_users') ): ?>
                  <a href="account/manage_users/save" class="btn btn-primary btn-small"><?php echo lang('website_create'); ?></a>
                <?php endif; ?>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php if( count($all_accounts) > 0 ) : ?>
            <?php foreach( $all_accounts as $acc ) : ?>
              <tr>
                <td><?php echo $acc['id']; ?></td>
                <td>
                  <?php echo $acc['username']; ?>
                  <?php if( $acc['is_banned'] ): ?>
                    <span class="label label-important"><?php echo lang('users_banned'); ?></span>
                  <?php elseif( isset($acc['role'])): 
                    //print_r($acc['role']);
                    foreach ($acc['role'] as $role) {
                      ?>
                      <span class="label label-info"><?php echo $role->name//lang('users_admin'); ?></span>
                      <?php 
                    }
                  ?>

                    
                  <?php endif; ?>
                </td>
                <td><?php echo $acc['fullname']; ?></td>
                <td><?php echo $acc['email']; ?></td>
                
                <td>
                  <?php if( $this->authorization->is_permitted('update_users') ):  ?>
                    
                 <a href="account/manage_users/save/<?php echo $acc['id']; ?>" class="btn btn-info btn-small <?php echo ($acc['username']=='admin')? "hide":""?>"><?php echo lang('website_update'); ?></a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php else: ?>
              <tr class="danger">
                <th colspan="5">
                  No Available data!
                </th>
              </tr>
            <?php endif; ?>
          </tbody>
          
        </table>
        <div>
          <?php echo $links; ?>
        </div>
    </div>
  </div>
</div>

<?php echo $this->load->view('footer'); ?>

</body>
</html>