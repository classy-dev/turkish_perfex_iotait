<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
	.ware-trans-th
	{
		font-size: 12px;font-weight: 500;border:1px solid #bfcbd9;text-align: center;
	}
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#stock_lists_modal"><?php echo _l('new_stock_list'); ?></a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						<?php render_datatable(array(
							_l('product_code'),
							_l('product_photo'),
							_l('product_name'),
							_l('unit'),
							_l('category'),
							_l('price'),
							_l('currency_id'),
							_l('stock_level'),
							_l('options'),
						),'stock_lists'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="stock_lists_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">
					<span class="edit-title"><?php echo _l('edit_stock_list'); ?></span>
					<span class="add-title"><?php echo _l('add_stock_list'); ?></span>
				</h4>
			</div>
			<?php echo form_open_multipart('admin/warehouses/stock_lists_manage',array('id'=>'stock_lists_form')); ?>
			<?php echo form_hidden('stocklistId'); ?>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<?php echo render_input('product_code',_l('product_code'),'','text'); ?>
						<?php echo render_input('product_photo',_l('product_photo'),'','file'); ?>
						<?php echo render_input('product_name',_l('product_name'),'','text'); ?>
						<?php echo render_select('unit',$stock_units,array('unitid','name'),_l('unit')); ?>
						<?php echo render_select('category',$stock_categories,array('id','name'),_l('category')); ?>
						<?php echo render_input('price',_l('price'),'','number'); ?>
						<?php echo render_select('currency_id',$currency_exchange,array('id','name'),_l('currency_id')); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="warehouse_qty">
							<!-- <label style="font-size: 14px;font-weight: 500"><?php echo _l('stock_by_warehouse')?></label>
							<table width="100%" style="border:1px solid;">
								<thead style="border:1px solid;">
									<th width="60%" class="ware-trans-th"><?php echo _l('warehouse_name')?></th>
									<th width="40%" class="ware-trans-th"><?php echo _l('transaction_qty')?></th>
								</thead>
								<tbody style="border:1px solid #bfcbd9;">

								</tbody>
							</table> -->
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){

		initDataTable('.table-stock_lists', window.location.href, [2], [2]);

		appValidateForm($('form'), {
			product_code: 'required',
			product_name: 'required',
			unit: 'required',
			category: 'required',
			price: 'required',
			currency_id: 'required',
		}, manage_stock_lists);

		$('#stock_lists_modal').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget)
			var id = button.data('id');
			$('#stock_lists_modal input[name="product_code"]').val('');
			$('#stock_lists_modal input[name="product_photo"]').val('');
			$('#stock_lists_modal input[name="product_name"]').val('');
			$('#stock_lists_modal input[name="unit"]').val('');
			$('#stock_lists_modal input[name="category"]').val('');
			$('#stock_lists_modal input[name="price"]').val('');
			$('#stock_lists_modal input[name="currency_id"]').val('');
			$('#stock_lists_modal input[name="stocklistId"]').val('');

			$('#stock_lists_modal .add-title').removeClass('hide');
			$('#stock_lists_modal .edit-title').addClass('hide');

			if (typeof(id) !== 'undefined') {
				$('input[name="stocklistId"]').val(id);
				$('#stock_lists_modal .add-title').addClass('hide');
				$('#stock_lists_modal .edit-title').removeClass('hide');

				var $stockListModal = $('#stock_lists_modal');
                requestGetJSON('warehouses/get_stock_list_by_id/' + id).done(function (response) {
                    $stockListModal.find('#product_code').val(response.product_code);
                    $stockListModal.find('#product_name').val(response.product_name);
                    $stockListModal.find('#stock_level').val(response.stock_level);
                    $stockListModal.find('#price').val(response.price);
                    $stockListModal.find('#unit').selectpicker('val', response.unit);
                    $stockListModal.find('#category').selectpicker('val', response.category);
                    if(response.category == 2){
                        $('[data-id="category"]').prop('disabled', true);
                    }
                    $stockListModal.find('#currency_id').selectpicker('val', response.currency_id);
                    init_selectpicker();
                });

                //Apeending Warehouse and transaction qty from transfer..
                var tranferReqUrl = admin_url +'warehouses/get_transfers_by_product_code/' + id ;
                requestGetJSON(tranferReqUrl).done(function (results) {
                	console.log(results);
                	$('.warehouse_qty').empty();
                	if(results.length > 0)
                	{
                		var data_row = '';
                		data_row += '<label style="font-size: 14px;font-weight: 500"><?php echo _l('stock_by_warehouse')?></label><table width="100%" style="border:1px solid;"><thead style="border:1px solid;"><th width="60%" style="font-size: 12px;font-weight: 500;border:1px solid #bfcbd9;text-align: center;"><?php echo _l('warehouse_name')?></th><th width="40%" style="font-size: 12px;font-weight: 500;border:1px solid #bfcbd9;text-align: center;"><?php echo _l('qty')?></th></thead><tbody style="border:1px solid #bfcbd9;">';

                		results.forEach( e => {
                			console.log(e)
                			data_row += '<tr><td style="border:1px solid #bfcbd9;text-align: center;">'+e.warehouse+'</td><td style="border:1px solid #bfcbd9;text-align: center;">'+e.qty+'</td></tr>';
                		})
                		data_row += '</tbody></table>';
                		$('.warehouse_qty').append(data_row);
                	}
                });

			}
		});
	});
	/* CURRENCY MANAGE FUNCTIONS */
	function manage_stock_lists(form) {
		var formData = new FormData($(form)[0]);
		$.ajax({
		    url: form.action,
		    type: form.method,
		    data: formData,
		    processData: false,
		    contentType: false,
		    success: function (response) {
		    	console.log(response)
		    	response = JSON.parse(response);
				if (response.success == true) {
					$('.table-stock_lists').DataTable().ajax.reload();
					alert_float('success', response.message);
				}
				$('#stock_lists_modal').modal('hide');
		    }
		});
		return false;
	}

</script>
</body>
</html>
