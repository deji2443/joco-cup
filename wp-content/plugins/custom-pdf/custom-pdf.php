<?php
/**
 * Plugin Name: Habib PDF Custom Plugin
 * Plugin URI:  http://www.test.com
 * Description: Customer Order PDF Plugin
 * Version: 1.0
 * Author: Habib
 * Author URI: http://www.test.com
 * Text Domain: Test PDF
 */


 
//PDF Plugin on myorder/order page
add_filter('woocommerce_account_orders_columns', 'add_custom_account_orders_column', 10, 1);
function add_custom_account_orders_column($columns)
{
$ordered_columns = array();

// Inserting a new column in a specific location
$ordered_columns['order-number'] = $columns['order-number'];
$ordered_columns['order-date'] = $columns['order-date'];
$ordered_columns['order-status'] = $columns['order-status'];
$ordered_columns['order-total'] = $columns['order-total'];
$ordered_columns['order-actions'] = $columns['order-actions'];
$ordered_columns['order-downloads'] = __('Pdf', 'woocommerce'); // <== New column

return $ordered_columns;
}

//Insert the content into the new column 
function pdf_column_content($order)
{
    ?>

<?php

$currency_code = $order->get_currency();
$currency_symbol = get_woocommerce_currency_symbol( $currency_code );
$k_mul = <<<EOT

Date: {$order->get_date_created()->date('Y-m-d ')}
Order Number: {$order->get_order_number()}
Payment Method: {$order->get_payment_method_title()}
Name: {$order->get_billing_first_name()} {$order->get_billing_last_name()}
Address: {$order->get_billing_address_1()}
Email: {$order->get_billing_email()}
Total:  {$currency_symbol } {$order->get_total()}

EOT;

?>

    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
    <?php wp_nonce_field('pdf_temp');?> 
        <input type="hidden" name="order_id" 
		value="<?php echo  $k_mul ?>">
        <input type="hidden" name="action" value="pdf_temp">
        <input type="submit" value="Pdf">
    </form>


<?php 
}
add_action('woocommerce_my_account_my_orders_column_order-downloads', 'pdf_column_content');

//when the user click submit then we will call our template 
function exFDPI()
{

    $retrieved_nonce = $_REQUEST['_wpnonce']; 
    if (!wp_verify_nonce($retrieved_nonce, 'pdf_temp')) { //if passed the security check proceed 
        wp_die('Failed security check');
    } else {
		require_once "fpdf/fpdf.php";
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',10);
		// $width = 100;
		// $lineHeight = 10;
		$pdf->MultiCell( 0, 10, $_POST['order_id'], );
		// $pdf->MultiCell($width, $lineHeight, $_POST['order_id']);
		// $pdf->Cell(40,10,$_POST['order_id']);
		$pdf->Output();
        // $order_id = $_POST['order_id'];
        // echo $order_id;
       
    }

}

add_action('admin_post_nopriv_pdf_temp', 'exFDPI');
add_action('admin_post_pdf_temp', 'exFDPI');
?>