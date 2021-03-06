<table width="100%" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
      <th>Passed?</th>
    </tr>
  </thead>

  <?php foreach ($test_controllers as $controller => $tests): ?>
    <tr>
      <td colspan="3"><h2><?php echo $controller; ?></h2></td>
    </tr>
    <?php foreach ($tests as $test): ?>
      <tr>
        <td><?php echo $test['Test Name']; ?></td>
        <td><?php echo $test['Notes']; ?></td>
        <td><span class="label <?php if ($test['Result'] == 'Passed'): ?>label-success<?php else: ?>label-danger<?php endif; ?>"><?php echo $test['Result']; ?></span></td>
      </tr>
    <?php endforeach; ?>
  <?php endforeach; ?>
</table>

<div class="row">
  <?php if ($failed > 0): ?>
    <div class="offset3 span5 alert alert-error" style="text-align: center;">
      <b>Not Good!</b> <?php echo $failed ?> of <?php echo $count ?> tests failed!
  <?php else: ?>
    <div class="offset3 span5 alert alert-success" style="text-align: center;">
      <b>Success!</b> Of the <?php echo $count ?> tests ran, all of them passed!
  <?php endif; ?>
  </div>
</div>
