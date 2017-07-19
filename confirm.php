<!DOCTYPE html>
<html>
<head>
	<title>Bootstrap-Confirmation Demo</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>Default options with datta toggle</h1>
				<pre>$('[data-toggle="confirmation"]').confirmation();</pre>
				<a href="#" class="btn btn-default" data-toggle="confirmation">Click me</a>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<h1>With callback functions</h1>
				<pre>$('.confirmation-callback').confirmation({
	onConfirm: function(event) { alert('confirm') },
	onCancel: function(event) { alert('cancel') }
});</pre>

				<a href="#" class="btn btn-default confirmation-callback">Click me</a>

			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
	<script src="/assets/admin/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js" type="text/javascript"></script>

	<script>
	$(function() {
		$('body').confirmation({
			selector: '[data-toggle="confirmation"]'
		});
		$('.confirmation-callback').confirmation({
			onConfirm: function() { alert('confirm') },
			onCancel: function() { alert('cancel') }
		});
	});
	</script>
</body>
</html>