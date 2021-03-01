
<?php
session_start();
include 'include/config.php';
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    date_default_timezone_set('Asia/Kolkata'); // change according timezone
    $currentTime = date('d-m-Y h:i:s A', time());

    function actionDefined($con, $result)
    {
        $id = $_GET['id'];
        if ($result == 'Action Required') {
            return $action = '<a type="button" href="validate.php?action=Pass&id=' . $id . '" class="btn btn-success">Accept</a><a type="button" href="validate.php?action=Fack&id=' . $id . '" type="button" class="btn btn-danger">Reject</a>';
        } else if ($result == 'Pass') {
            mysqli_query($con, "UPDATE productreviews SET status=1 WHERE id = $id");
            return $action = 'Pass';
        } else {
            mysqli_query($con, "UPDATE productreviews SET status=2 WHERE id = $id");
            return $action = 'Fack';
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin| Category</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
</head>
<body>
<?php include 'include/header.php';?>

	<div class="wrapper">
		<div class="container">
			<div class="row">
<?php include 'include/sidebar.php';?>
			<div class="span9">
					<div class="content">




	<div class="module">
							<div class="module-head">
								<h3>Review Validation Process</h3>
							</div>
							<div class="module-body table">
<?php
$id = $_GET['id'];
    $action = $_GET['action'];

    $query = mysqli_query($con, "SELECT * FROM productreviews r, products p WHERE r.id= $id AND r.productId = p.id");
    $row = mysqli_fetch_array($query);
    $rew = $row['review'];
    $dsc = strip_tags($row['productDescription']);
    if (!isset($action)) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cb67799bf3d5.ngrok.io',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('desc' => $dsc, 'review' => $rew),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        $action = actionDefined($con, $result['result']);
    } else {
        $action = actionDefined($con, $action);
    }

    ?>

<table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display" width="100%">
<thead>
<tr>
<th>Review<th>
<th>Owner<th>
<th>Review Date<th>
<th>Action<th>
</thead>
</tr>
<tbody>
<tr>
<td><?php echo $rew; ?><td>
<td>Owner<td>
<td>Review Date<td>
<td>
<?php echo $action; ?>
<td>
</tbody>
</tr>
</table>
							</div>
						</div>



					</div><!--/.content-->
				</div><!--/.span9-->
			</div>
		</div><!--/.container-->
	</div><!--/.wrapper-->

<?php include 'include/footer.php';?>

	<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="scripts/datatables/jquery.dataTables.js"></script>
	<script>
		$(document).ready(function() {
			$('.datatable-1').dataTable();
			$('.dataTables_paginate').addClass("btn-group datatable-pagination");
			$('.dataTables_paginate > a').wrapInner('<span />');
			$('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
			$('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
		} );
	</script>
</body>
<?php }?>