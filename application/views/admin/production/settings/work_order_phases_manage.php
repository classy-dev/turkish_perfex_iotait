<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#worok_order_phase_modal"><?php echo _l('new_work_order_phase'); ?></a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						<?php render_datatable(array(
							_l('order_no'),
							_l('name'),
							_l('options'),
						),'work-order-phase'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="worok_order_phase_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">
					<span class="edit-title"><?php echo _l('edit_work_order_phase'); ?></span>
					<span class="add-title"><?php echo _l('add_work_order_phase'); ?></span>
				</h4>
			</div>
			<?php echo form_open('admin/production/manage_work_order_phase',array('id'=>'work_order_phase_form')); ?>
			<?php echo form_hidden('workorderphaseid'); ?>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">

						<?php echo render_input('phase',_l('work_order_phase'),'','text',array('placeholder'=>_l('work_order_phase'))); ?>

						<?php echo render_input('order_no',_l('order_no'),'','number',array('placeholder'=>_l('order_no'))); ?>
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

		initDataTable('.table-work-order-phase', window.location.href, [2], [2]);

		appValidateForm($('form'), {
			phase: 'required',
			order_no: 'required',
		}, manage_work_order);

		$('#worok_order_phase_modal').on('show.bs.modal', function(event) {

			var button = $(event.relatedTarget)
			var id = button.data('id');

			$('#worok_order_phase_modal input[name="phase"]').val('');
			$('#worok_order_phase_modal input[name="order_no"]').val('');
			$('#worok_order_phase_modal input[name="workorderphaseid"]').val('');

			$('#worok_order_phase_modal .add-title').removeClass('hide');
			$('#worok_order_phase_modal .edit-title').addClass('hide');

			if (typeof(id) !== 'undefined') {
				$('input[name="workorderphaseid"]').val(id);
				var phase = $(button).parents('tr').find('td').eq(1).find('span.name').text();
				var orderNo = $(button).parents('tr').find('td').eq(0).text();
				$('#worok_order_phase_modal .add-title').addClass('hide');
				$('#worok_order_phase_modal .edit-title').removeClass('hide');
				$('#worok_order_phase_modal input[name="phase"]').val(phase);
				$('#worok_order_phase_modal input[name="order_no"]').val(orderNo);

			}
		});
	});
	/* manage_work_order phase MANAGE FUNCTIONS */
	function manage_work_order(form) {
		var data = $(form).serialize();
		var url = form.action;
		$.post(url, data).done(function(response) {
			response = JSON.parse(response);
			if (response.success == true) {
				$('.table-work-order-phase').DataTable().ajax.reload();
				alert_float('success', response.message);
			}
			$('#worok_order_phase_modal').modal('hide');
		});
		return false;
	}

</script>
</body>
</html>
