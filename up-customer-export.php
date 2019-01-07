<?php
if(isset($_GET['customer_export']))
{
	//output headers so that the file is downloaded rather than displayed
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=customer.csv');

	ob_end_clean();
	$output = fopen('php://output', 'w');   
	// output the column headings
	fputcsv($output, array('Email',' Name',' Gender',' Birth Date',' City',' State',' Phone',' Zip',' Plan type',' Effective Date Month','  Effective Date Year'));

	//query the database
	global $wpdb;
	$customer = $wpdb->get_results( 'SELECT * FROM customer', ARRAY_A );

	foreach($customer as $customers){
		$data = array($customers['email'],$customers['first_name'].' '.$customers['last_name'],$customers['gender'],$customers['birth_day'],$customers['city'],$customers['state'],$customers['phone'],$customers['zipcode'],$customers['plan_type'],$customers['effective_date_month'],$customers['effective_date_year']);
		fputcsv($output, $data);
	}

	fclose($output);
	exit;
}
?>
<div class="wrap">
		<h2>Download Customer</h2>
		<p><a href="?page=customer_export&customer_export">DOWNLOAD EXPORTED FILE</a></p>
</div>
