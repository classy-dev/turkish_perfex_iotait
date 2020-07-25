<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel-body mtop10">
    <div class="row">
        <div class="col-md-6">
            <?php 
                $value = (isset($pricing_calc_data) ? $pricing_calc_data->other_cost_details : ''); 
                echo render_input('other_cost_details', _l('other_cost_details'), $value, 'number', array('placeholder' => _l('other_cost_details'))); ?>
        </div>
        <div class="col-md-6">
            <?php 
                $value = (isset($pricing_calc_data) ? $pricing_calc_data->other_cost : '');
                echo render_input('other_cost', _l('other_cost'), $value, 'number', array('placeholder' => _l('other_cost'))); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?php 
                $value = (isset($pricing_calc_data) ? $pricing_calc_data->op_cost_per_sec : '');
                echo render_input('op_cost_per_sec', _l('op_cost_per_sec'), $value, 'number', array('placeholder' => _l('op_cost_per_sec'))); ?>
        </div>
        <div class="col-md-4">
            <?php 
                $value = (isset($install_time) ? $install_time->consumed_time : '');
                echo render_input('consumed_time', _l('installation_consumed_time'), $value, 'number', array('placeholder' => _l('installation_consumed_time'))); ?>
        </div>

        <div class="col-md-4">
           <label><?php echo _l('installation_cost')?></label><input type="number" readonly id="ins_cost" name="ins_cost" class="form-control" align="right" placeholder="<?php echo _l('installation_cost'); ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php $this->load->view('admin/products/product_recipe/select_package'); ?>
        </div>
    </div>

    <div class="table-responsive s_table" id="item-section">
        <table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop">
            <thead>
            <tr>
                <th width="9%"><?php echo _l('product_name'); ?></th>
                <th width="4%"><?php echo _l('pre_produced'); ?></th>
                <th width="9%"><?php echo _l('used_qty'); ?></th>
                <th width="9%"><?php echo _l('rate_of_waste'); ?></th>
                <th width="10%"><?php echo _l('default_machine'); ?></th>
                <th width="10%"><?php echo _l('mould_id'); ?></th>
                <th width="10%"><?php echo _l('mould_cavity'); ?></th>
                <th width="9%"><?php echo _l('cycle_time'); ?></th>
                <th width="10%"><?php echo _l('material_cost'); ?></th>
                <th width="10%"><?php echo _l('production_cost'); ?></th>
                <th width="10%"><?php echo _l('expected_profit'); ?></th>
                <th align="center"><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
                <tr class="main">
                    <td>
                        <input type="text" name="product_name" class="form-control">
                        <input type="hidden" name="ingredient_item_id">
                    </td>
                    <td>
                        <div class="checkbox" style="margin-top: 8px;padding-left: 50%">
                            <input type="checkbox" id="pre_produced" name="pre_produced" >
                            <label for="pre_produced"></label>
                        </div>
                    </td>
                    <td>
                        <input type="number" name="used_qty" class="form-control material" onkeyup="material_cost_calc()">
                    </td>
                    <td>
                        <input type="number" name="rate_of_waste"  class="form-control material" onkeyup="material_cost_calc()">
                    </td>
                    <td>
                        <input type="text" name="default_machine" readonly class="form-control">
                    </td>
                    <td>
                        <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="mould" data-fieldid="mould" name="mould" id="mould" class="selectpicker form-control" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">
                                <option value=""></option>
                              <?php foreach ($moulds as $key => $mould) {?>
                                <option value="<?php echo $mould['id'];?>"><?php echo $mould['mould_name'];?></option>
                              <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td>
                        <input type="text" readonly name="mould_cavity" class="form-control">
                    </td>
                    <td>
                        <input type="number"  name="cycle_time" class="form-control">
                    </td>
                    <td>
                        <input type="number" readonly name="material_cost" class="form-control">
                    </td>
                    <td>
                        <input type="number" readonly name="production_cost" class="form-control">
                    </td>
                    <td>
                        <input type="number" readonly name="expected_profit" class="form-control">
                    </td>
                    <td>
                        <?php
                            $new_item = 'undefined';
                            if (isset($product_receipe_item)) {
                                $new_item = true;
                            }
                        ?>
                        <button type="button" onclick="add_item_to_table_product_recipe('undefined','undefined',<?php echo $new_item; ?>); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                    </td>
                </tr>
                <?php if (isset($product_receipe_item)) {
                    $i               = 1;
                    $items_indicator = 'newitems';
                    if (isset($product_receipe_item)) {
                        $items_indicator = 'items';
                    }

                    foreach ($product_receipe_item as $item) {
                        $manual    = false;

                        $option = '<option></option>';
                        foreach ($moulds as $key => $mould) {
                            if($mould['id'] == $item['mould'])
                                $option.='<option value="'.$mould['id'].'" selected>'.$mould['mould_name'].'</option>';
                            else
                                $option.='<option value="'.$mould['id'].'">'.$mould['mould_name'].'</option>';
                        }

                        $table_row = '<tr>';
                        // $table_row .= '<td>';

                        $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][item_id]', $item['id']);

                        $table_row .= '<td class="bold description"><input type="text"  name="' . $items_indicator . '[' . $i . '][product_name]" class="form-control" value="' . $item['product_name'] . '"><input type="hidden" name="' . $items_indicator . '[' . $i . '][ingredient_item_id]" value="' . $item['ingredient_item_id'] . '" ></td>';

                        if ($item['pre_produced'] == 1) {

                            $table_row .= '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox" checked  name="' . $items_indicator . '[' . $i . '][pre_produced]"  value="'.$item['pre_produced'].'"><label ></label></div>';
                        } else {
                            $table_row .= '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox" name="' . $items_indicator . '[' . $i . '][pre_produced]"  value="'.$item['pre_produced'].'"><label ></label></div>';

                        }
                        
                        $table_row .= '<td><input type="number" name="' . $items_indicator . '[' . $i . '][used_qty]" class="form-control material" value="'.$item['used_qty'].'" onkeyup = "material_cost_calc_for_added(this)"></td>';

                        $table_row .= '<td><input type="number"  name="'.$items_indicator.'['.$i.'][rate_of_waste]" class="form-control material" value="'.$item['rate_of_waste'].'" onkeyup = "material_cost_calc_for_added(this)"></td>';

                        $table_row .= '<td><input type="text"  name="'.$items_indicator.'['.$i.'][default_machine]" class="form-control" value="'.$item['default_machine'].'"></td>';

                        $table_row .= '<td>
                            <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="mould" data-fieldid="mould" name="'.$items_indicator.'['.$i.'][mould]" class="selectpicker form-control mouldid" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'.$option.'</select></div>
                        </td>';

                        $table_row .= '<td><input type="text"  name="'.$items_indicator.'['.$i.'][mould_cavity]" class="form-control" value="'.$item['mould_cavity'].'"></td>';

                        $table_row .= '<td><input type="number"  name="'.$items_indicator.'['.$i.'][cycle_time]" class="form-control cycle_time" value="'.$item['cycle_time'].'"></td>';

                        $table_row .= '<td><input type="number" readonly name="'.$items_indicator.'['.$i.'][material_cost]" class="form-control" value="'.$item['material_cost'].'"></td>';

                        $table_row .= '<td><input type="number" readonly name="'.$items_indicator.'['.$i.'][production_cost]" class="form-control" value="'.$item['production_cost'].'"></td>';

                        $table_row .= '<td><input type="number" readonly name="'.$items_indicator.'['.$i.'][expected_profit]" class="form-control" value="'.$item['expected_profit'].'"></td>';

                        $table_row .= '<input type="hidden" class="subtotal" name="'.$items_indicator.'['.$i.'][subtotal]" value="'.$item['subtotal'].'">';

                        $table_row .= '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_product_recipe_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';

                        $table_row .= '</tr>';
                        echo $table_row;
                        $i++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-8 col-md-offset-4">
        <table class="table text-right">
            <tbody>
                <td><span class="bold"><?php echo _l('estimate_total'); ?> :</span>
                </td>
                <td>
                    <span class="bold" id="total">
                        <?php 
                            if(isset($pricing_calc_data)) echo $pricing_calc_data->price;
                            else echo '';
                        ?> 
                    </span>
                </td>

                <input type="hidden" id="total_value" name="total_value">
            </tr>
            </tbody>
        </table>
    </div>
    <div id="removed-items"></div>