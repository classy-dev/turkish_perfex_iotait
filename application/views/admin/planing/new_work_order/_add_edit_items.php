<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel-body mtop10">
   <div class="row">
      <div class="col-md-4">
          <?php $this->load->view('admin/products/product_recipe/select_package'); ?>
      </div>
   </div>
   <div class="table-responsive s_table">
      <table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop">
         <thead>
            <tr>
              <th width="25%" align="center"><?php echo _l('product_name'); ?></th>
              <th width="15%" align="center"><?php echo _l('pack_capacity'); ?></th>
              <th width="15%" align="center"><?php echo _l('qty'); ?></th>
              <th width="15%" align="center"><?php echo _l('unit'); ?></th>
              <th width="15%" align="center"><?php echo _l('approval_need'); ?></th>
              <th width="15%" align="center"><?php echo _l('notes'); ?></th>
              <th align="center"><i class="fa fa-cog"></i></th>
            </tr>
         </thead>
         <tbody>
            <tr class="main">
              <td>
                <input type="text" name="product_name" class="form-control">
                <input type="hidden" name="rel_product_id">
              </td>
              <td>
                <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                  <select data-fieldto="pack_capacity" data-fieldid="pack_capacity" name="pack_capacity" id="pack_capacity" class="selectpicker form-control pack_capacity" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98" >
                      <option value=""></option>
                  </select>
                </div>
              </td>
              <td>
                <input type="number" name="qty" min="0" class="form-control" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">
              </td>
              <td>
                <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                    <select data-fieldto="unit" data-fieldid="unit" name="unit" id="unit" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                        <option value=""></option>
                      <?php foreach ($units as $key => $unit) {?>
                        <option value="<?php echo $unit['unitid'];?>"><?php echo $unit['name'];?></option>
                      <?php } ?>
                    </select>
                </div>
              </td>
              <td>
                <div class="checkbox" style="margin-top: 8px;padding-left: 50%">
                    <input type="checkbox" id="approval_need" name="approval_need" disabled>
                    <label for="approval_need"></label>
                  </div>
              </td>
              <td>
                <input type="text" name="notes" class="form-control" placeholder="<?php echo _l('notes'); ?>">
              </td>
              <td>
                  <?php
                     $new_item = 'undefined';
                     if(isset($estimate)){
                       $new_item = true;
                     }
                     ?>
                  <button type="button" onclick="add_item_to_table_new_type_quote('undefined','undefined',<?php echo $new_item; ?>); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
              </td>
            </tr>
            <?php if (isset($estimate)) {
               $i               = 1;
               $items_indicator = 'newitems';
               if (isset($estimate)) {
                 $add_items       = $estimate->items;
                 $items_indicator = 'items';
               }
               foreach ($add_items as $item) {
                 $manual    = false;

                 $capacity_option = '<option></option>';
                  foreach ($item[0] as $key => $pack) {
                      if($pack['packing_id'] == $item['pack_capacity'])
                          $capacity_option.='<option value="'.$pack['packing_id'].'" selected>'.$pack['pack_capacity'].'</option>';
                      else
                          $capacity_option.='<option value="'.$pack['packing_id'].'">'.$pack['pack_capacity'].'</option>';
                  }
                $unit_option = '<option></option>';

                foreach ($units as $key => $unit) {
                    if($unit['unitid'] == $item['unit'])
                        $unit_option.='<option value="'.$unit['unitid'].'" selected>'.$unit['name'].'</option>';
                    else
                        $unit_option.='<option value="'.$unit['unitid'].'">'.$unit['name'].'</option>';
                }

                $amount = $item['qty'] * $item['sale_price'];
                $amount = app_format_number($amount);

                 $table_row = '<tr class="sortable item">';
               
                 $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);

                 $table_row .= '<td class="bold description"><input type="text"  name="' . $items_indicator . '[' . $i . '][product_name]" class="form-control" value="' . $item['product_name'] . '"><input type="hidden" class="rel_product_id" name="' . $items_indicator . '[' . $i . '][rel_product_id]" value="' . $item['rel_product_id'] . '" ></td>';

                 $table_row .= '<td> <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="pack_capacity" data-fieldid="pack_capacity" name="'.$items_indicator.'['.$i.'][pack_capacity]" class="selectpicker form-control pack_capacity" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'.$capacity_option.'</select></div></td>';

                 $table_row .= '<td><input type="number" data-quantity name="' . $items_indicator . '[' . $i . '][qty]" class="form-control" value="'.$item['qty'].'"></td>';

                 $table_row .= '<td> <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="unit" data-fieldid="unit" name="'.$items_indicator.'['.$i.'][unit]" class="selectpicker form-control unit" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'.$unit_option.'</select></div></td>';

                 if ($item['approval_need'] == 1) {

                      $table_row .= '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox" checked  name="' . $items_indicator . '[' . $i . '][approval_need]" disabled><label ></label></div>';
                  } else {
                      $table_row .= '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox" name="' . $items_indicator . '[' . $i . '][approval_need]" disabled><label ></label></div>';

                  }

                 $table_row .= '<td><input type="text" name="' . $items_indicator . '[' . $i . '][notes]" class="form-control" value="'.$item['notes'].'"></td>';
                 
                 $table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_quote_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
                 $table_row .= '</tr>';
                 echo $table_row;
                 $i++;
                 }
               }
               ?>
         </tbody>
      </table>
   </div>
</div>
