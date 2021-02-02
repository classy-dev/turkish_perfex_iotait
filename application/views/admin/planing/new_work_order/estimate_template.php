<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s accounting-template estimate">
   <div class="panel-body">
      <div class="row">
          <div class="col-md-6">
              <?php 
              $selected = 1;
              echo render_select('sale_phase_id',$sale_phase,array('order_no','phase'),'sale_phase',$selected,array('required' => true)); ?>
          </div>
             <input type="hidden" name="currency" value="<?php echo $proposal->currency?>">
          <div class="col-md-6">
            <div class="f_client_id">
             <div class="form-group select-placeholder">
                <label for="clientid" class="control-label"><?php echo _l('estimate_select_customer'); ?></label>
                <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search<?php if(isset($proposal) && empty($proposal->clientid)){echo ' customer-removed';} ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
               <?php $selected = (isset($proposal) ? $proposal->rel_id : '');
                 if($selected == ''){
                   $selected = (isset($customer_id) ? $customer_id: '');
                 }
                 if($selected != ''){
                    $rel_data = get_relation_data('customer',$selected);
                    $rel_val = get_relation_values($rel_data,'customer');
                    echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                 } ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
              <?php echo render_input('general_notes',_l('general_notes'),'','text',array('placeholder'=>_l('general_notes'))); ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
              <?php $value = (isset($transfer) ? _d($transfer->date_and_time) : '') ?>
              <?php echo render_date_input('req_shipping_date',_l('req_shipping_date'),$value,array('required' => true)); ?>
          </div>
          <div class="col-md-6">
              <?php echo render_input('total_price',_l('total_price'),'','text',array('placeholder'=>_l(''),'readonly'=>'readonly')); ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
              <?php $createdUserNameValue = (isset($created_user_name) ? $created_user_name : "");?>
              <?php echo render_input('created_user','Created User',$createdUserNameValue,'text',array('placeholder'=>_l('created user'),'readonly'    => 'readonly')); ?>
          </div>
          <div class="col-md-6">
              <?php $updatedUserNameValue = (isset($updated_user_name) ? $updated_user_name : "");?>
              <?php echo render_input('updated_user','Last Updated User',$updatedUserNameValue,'text',array('placeholder'=>_l('updated user'),'readonly'    => 'readonly')); ?>
          </div>
        </div>    
      </div>


   </div>
   <?php $this->load->view('admin/planing/new_work_order/_add_edit_items_modal'); ?>
</div>
