<?php if ($system_messages || $validation_errors): ?>
<div class="container">
	<div class="alerts system" id="system-messages">
		<?php if ($validation_errors): ?>
			<div class="alert alert-danger alert-dismissable" id="validation-errors">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php echo $validation_errors; ?>
			</div>
		<?php endif; ?>
		<?php foreach($system_messages as $type => $messages): ?>
			<?php foreach($messages as $message): ?>
				<div class="alert alert-<?php echo $type; ?> alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $message; ?>
				</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>
