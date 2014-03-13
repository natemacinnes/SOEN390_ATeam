<!DOCTYPE html>
<html lang="en">
<head>
   <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="">
   <meta name="keywords" content="">
   <meta name="author" content="">

   <title>YouDeliberate</title>

   <?php include_once("application/analyticstracking.php"); ?>
   <link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/css/font-awesome.css') ?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/css/general.css') ?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/mediaelement/mediaelementplayer.min.css') ?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/colorbox-master/example2/colorbox.css') ?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/css/custom.css') ?>" rel="stylesheet">

   <script type="text/javascript">
     var yd_settings = {
       base_url: "<?php echo base_url(); ?>",
       site_url: "<?php echo site_url(); ?>",
       constants: {
        NARRATIVE_POSITION_NEUTRAL: <?php echo NARRATIVE_POSITION_NEUTRAL; ?>,
        NARRATIVE_POSITION_AGREE: <?php echo NARRATIVE_POSITION_AGREE; ?>,
        NARRATIVE_POSITION_DISAGREE: <?php echo NARRATIVE_POSITION_DISAGREE; ?>,
        METRIC_EVENT_LISTEN_START: <?php echo METRIC_EVENT_LISTEN_START; ?>,
        METRIC_EVENT_LISTEN_END: <?php echo METRIC_EVENT_LISTEN_END; ?>,
        METRIC_EVENT_BOOKMARK: <?php echo METRIC_EVENT_BOOKMARK; ?>,
        METRIC_EVENT_SHARE: <?php echo METRIC_EVENT_SHARE; ?>,
        METRIC_EVENT_LIKE: <?php echo METRIC_EVENT_LIKE; ?>,
        METRIC_EVENT_DISLIKE: <?php echo METRIC_EVENT_DISLIKE; ?>,
        NARRATIVE_PLAYER_IMAGE_UPDATE_INTERVAL: <?php echo NARRATIVE_PLAYER_IMAGE_UPDATE_INTERVAL; ?>,
        NARRATIVE_HISTORY_LIMIT: <?Php echo NARRATIVE_HISTORY_LIMIT; ?>,
        NARRATIVE_HISTORY_VISIBLE: <?Php echo NARRATIVE_HISTORY_VISIBLE; ?>
       }
     };
  </script>
</head>
<body>
