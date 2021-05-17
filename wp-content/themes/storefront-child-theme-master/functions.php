<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

function pp_scripts() {
    // Registering Bootstrap style
    wp_enqueue_style( 'bootstrap_min', get_stylesheet_directory_uri().'/assets/css/bootstrap.min.css' );
    wp_enqueue_style( 'custom_style', get_stylesheet_directory_uri().'/style.css' );

    wp_enqueue_script('jquery');
    //Registering Bootstrap Script
    wp_enqueue_script( 'bootstrap_min', get_template_directory_uri() .'/assets/js/bootstrap.min.js', array(), '', true );
    }
    add_action( 'wp_enqueue_scripts', 'pp_scripts' );



    add_action( 'init', 'custom_bootstrap_slider' );
/**
 * Register a Custom post type for.
 */
function custom_bootstrap_slider() {
	$labels = array(
		'name'               => _x( 'Slider', 'post type general name'),
		'singular_name'      => _x( 'Slide', 'post type singular name'),
		'menu_name'          => _x( 'Bootstrap Slider', 'admin menu'),
		'name_admin_bar'     => _x( 'Slide', 'add new on admin bar'),
		'add_new'            => _x( 'Add New', 'Slide'),
		'add_new_item'       => __( 'Name'),
		'new_item'           => __( 'New Slide'),
		'edit_item'          => __( 'Edit Slide'),
		'view_item'          => __( 'View Slide'),
		'all_items'          => __( 'All Slide'),
		'featured_image'     => __( 'Featured Image', 'text_domain' ),
		'search_items'       => __( 'Search Slide'),
		'parent_item_colon'  => __( 'Parent Slide:'),
		'not_found'          => __( 'No Slide found.'),
		'not_found_in_trash' => __( 'No Slide found in Trash.'),
	);

	$args = array(
		'labels'             => $labels,
		'menu_icon'	     => 'dashicons-star-half',
    	        'description'        => __( 'Description.'),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => array('title','editor','thumbnail')
	);

	register_post_type( 'slider', $args );
}

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
		require_once "assets/fpdf/fpdf.php";
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