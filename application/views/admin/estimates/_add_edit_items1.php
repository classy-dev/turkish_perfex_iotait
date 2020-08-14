<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel-body mtop10">
   <div class="table-responsive s_table">
      <table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop">
         <thead>
            <tr>
              <th width="11%" align="center"><?php echo _l('product_name'); ?></th>
              <th width="11%" align="center"><?php echo _l('pack_capacity'); ?></th>
              <th width="11%" align="center"><?php echo _l('qty'); ?></th>
              <th width="11%" align="center"><?php echo _l('unit'); ?></th>
              <th width="11%" align="center"><?php echo _l('volume_m3'); ?></th>
              <th width="4%" align="center"><?php echo _l('approval_need'); ?></th>
              <th width="11%" align="center"><?php echo _l('notes'); ?></th>
              <!-- <th width="8%" align="right"><?php echo _l('estimate_table_amount_heading'); ?></th> -->
              <!-- <th align="center"><i class="fa fa-cog"></i></th> -->
            </tr>
         </thead>
         <tbody>
            <?php if (isset($quote_items)) {
               $i               = 1;
               $items_indicator = 'newitems';
               if (isset($quote_items)) {
                 $items_indicator = 'items';
               }
               foreach ($quote_items as $item) {
                 $manual    = false;

                 $capacity_option = '<option></option>';
                  foreach ($packlist as $key => $pack) {
                      if($pack['pack_capacity'] == $item['pack_capacity'])
                          $capacity_option.='<option value="'.$pack['pack_capacity'].'" selected>'.$pack['pack_capacity'].'</option>';
                      else
                          $capacity_option.='<option value="'.$pack['pack_capacity'].'">'.$pack['pack_capacity'].'</option>';
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
                 // $table_row .= '<td class="dragger">';
               
                 $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);

                 $table_row .= '<td class="bold description"><input type="text"  name="' . $items_indicator . '[' . $i . '][product_name]" class="form-control" value="' . $item['product_name'] . '" disabled><input type="hidden" class="rel_product_id" name="' . $items_indicator . '[' . $i . '][rel_product_id]" value="' . $item['rel_product_id'] . '" ></td>';

                 $table_row .= '<td> <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="pack_capacity" disabled data-fieldid="pack_capacity" name="'.$items_indicator.'['.$i.'][pack_capacity]" class="selectpicker form-control pack_capacity" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'.$capacity_option.'</select></div></td>';

                 $table_row .= '<td><input type="number" data-quantity name="' . $items_indicator . '[' . $i . '][qty]" class="form-control" disabled value="'.$item['qty'].'" onkeyup="calculate_total_quote();" onchange="calculate_total_quote();"></td>';

                 $table_row .= '<td> <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="unit" data-fieldid="unit" disabled name="'.$items_indicator.'['.$i.'][unit]" class="selectpicker form-control" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'.$unit_option.'</select></div></td>';

                 $table_row .= '<td><input type="number"  name="' . $items_indicator . '[' . $i . '][volume_m3]" readonly class="form-control volume_m3" value="'.$item['volume_m3'].'"></td>';

                 if ($item['approval_need'] == 1) {

                      $table_row .= '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox" checked  name="' . $items_indicator . '[' . $i . '][approval_need]" disabled><label ></label></div>';
                  } else {
                      $table_row .= '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox" name="' . $items_indicator . '[' . $i . '][approval_need]" disabled><label ></label></div>';

                  }

                 $table_row .= '<td><input type="text" name="' . $items_indicator . '[' . $i . '][notes]" class="form-control" value="'.$item['notes'].'"></td>';
                 
                 // $table_row .= '<td class="amount" align="right">' . $amount . '</td>';
                 
                 // $table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_quote_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
                 $table_row .= '</tr>';
                 echo $table_row;
                 $i++;
                 }
               }
               ?>
         </tbody>
      </table>
   </div>
   
   <div id="removed-items"></div>
</div>