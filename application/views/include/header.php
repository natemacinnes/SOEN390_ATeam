<!DOCTYPE html>
<html lang="en">
<head>
	 <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <meta name="description" content="">
	 <meta name="keywords" content="">
	 <meta name="author" content="">

	 <title>YouDeliberate</title>
	 <link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
	 <link href="<?php echo base_url('assets/css/font-awesome.css') ?>" rel="stylesheet">
	 <link href="<?php echo base_url('assets/css/general.css') ?>" rel="stylesheet">
	 <link href="<?php echo base_url('assets/mediaelement/mediaelementplayer.min.css') ?>" rel="stylesheet">
	 <link href="<?php echo base_url('assets/colorbox-master/example2/colorbox.css') ?>" rel="stylesheet">
	 <link href="<?php echo base_url('assets/css/custom.css') ?>" rel="stylesheet">

	 <script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-48900212-1']);
		_gaq.push(['_setDomainName', 'vm.diffingo.com']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>

	 <script type="text/javascript">
		 var yd_settings = {
			 base_url: "<?php echo base_url(); ?>",
			 site_url: "<?php echo site_url(); ?>",
			 constants: {
				NARRATIVE_POSITION_NEUTRAL: <?php echo NARRATIVE_POSITION_NEUTRAL; ?>,
				NARRATIVE_POSITION_AGREE: <?php echo NARRATIVE_POSITION_AGREE; ?>,
				NARRATIVE_POSITION_DISAGREE: <?php echo NARRATIVE_POSITION_DISAGREE; ?>,
				NARRATIVE_PLAYER_IMAGE_UPDATE_INTERVAL: <?php echo NARRATIVE_PLAYER_IMAGE_UPDATE_INTERVAL; ?>,
				NARRATIVE_HISTORY_LIMIT: <?Php echo NARRATIVE_HISTORY_LIMIT; ?>,
				NARRATIVE_HISTORY_VISIBLE: <?Php echo NARRATIVE_HISTORY_VISIBLE; ?>
			 }
		 };
	</script>
</head>
<body>
