<div class="table-responsive">
   <table class="table table-bordered roles no-margin">
      <thead>
         <tr>
            <th>Feature</th>
            <th>Capabilities</th>
         </tr>
      </thead>
      <tbody>
         <?php
            if(isset($member)){
                $is_admin = is_admin($member->staffid);
            }
          // print_r(get_available_staff_permissions($funcData));
         foreach(get_available_staff_permissions($funcData) as $feature => $permission) { ?>
         <tr data-name="<?php echo $feature; ?>">
            <td>
               <b><?php echo $permission['name']; ?></b>
            </td>
            <td>
               <?php
                  if(isset($permission['before'])){
                   echo $permission['before'];
                  }
                  ?>
               <?php foreach ($permission['capabilities'] as $capability => $name) {
                  $checked = '';
                  $disabled = '';
                  if((isset($is_admin) && $is_admin) ||
                   (is_array($name) && isset($name['not_applicable']) && $name['not_applicable']) ||
                   (
                      ($capability == 'view_own' || $capability == 'view'
                          && array_key_exists('view_own', $permission['capabilities']) && array_key_exists('view', $permission['capabilities']))
                      &&
                        ((isset($member)
                         && staff_can(($capability == 'view' ? 'view_own' : 'view'), $feature, $member->staffid))
                        ||
                        (isset($role)
                         && has_role_permission($role->roleid, ($capability == 'view' ? 'view_own' : 'view'), $feature))
                      )
                   )
                  ){
                   $disabled = ' disabled ';
                  } else if((isset($member) && staff_can($capability, $feature, $member->staffid))
                    || isset($role) && has_role_permission($role->roleid, $capability, $feature)){
                  $checked = ' checked ';
                  }
                  ?>
               <div class="checkbox">
                  <input
                     <?php if($capability == 'view') { ?> data-can-view <?php } ?>
                     <?php if($capability == 'view_own') { ?> data-can-view-own <?php } ?>
                     <?php if(is_array($name) && isset($name['not_applicable']) && $name['not_applicable']){ ?> data-not-applicable="true" <?php } ?>
                     type="checkbox"
                     <?php echo $checked;?>
                     class="capability"
                     id="<?php echo $feature .'_'.$capability; ?>"
                     name="permissions[<?php echo $feature; ?>][]"
                     value="<?php echo $capability; ?>"
                     <?php echo $disabled; ?>>
                  <label for="<?php echo $feature .'_'.$capability; ?>">
                  <?php echo !is_array($name) ? $name : $name['name']; ?>
                  </label>
                    <?php
                      if(isset($permission['help']) && array_key_exists($capability, $permission['help'])) {
                          echo '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="'.$permission['help'][$capability].'"></i>';
                      }
                    ?>
               </div>
               <?php } ?>
               <?php
                  if(isset($permission['after'])){
                    echo $permission['after'];
                  }
                  ?>
            </td>
         </tr>
         <?php } ?>
         <!-- <tr>
           <td width="50%">
                 <b><?php echo _l('pricing_categories'); ?></b>
              </td>
            <td width="50%">
                <?php foreach ($pricing_categories as $key => $value) {?>
                  <div class="checkbox">
                    <input type="checkbox" class="capability" id="pricing_category_<?php echo $value['id']?>" name="pricing_category[]" value="<?php echo $value['id']?>">
                    <label for="pricing_category_<?php echo $value['id']?>">
                      <?php echo $value['name']?>
                    </label>
                  </div>
                <?php }?>
              </td>
            </tr> -->
      </tbody>
   </table>
</div>


<div class="table-responsive" style="margin-top: 10px">
   <table class="table table-bordered roles no-margin">
      <thead>
         <tr>
            <th><?php echo _l('extra_permission')?></th>
            <th><?php echo _l('content')?></th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td width="50%">
               <b><?php echo _l('pricing_categories'); ?></b>
            </td>
            <td width="50%">
              <?php   
              if(isset($pricing_categories))
              foreach ($pricing_categories as $key => $value) {?>
                <div class="checkbox">
                  <input type="checkbox" class="capability price_cateogry" id="pricing_category_<?php echo $value['id']?>" name="pricing_category_permission[]" value="<?php echo $value['id']?>">
                  <label for="pricing_category_<?php echo $value['id']?>">
                    <?php echo $value['name']?>
                  </label>
                </div>
              <?php }?>
            </td>
         </tr>
      </tbody>
   </table>
</div>

