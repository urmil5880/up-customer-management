<?php 
class Qd_Plan99_Customer_Management_List_Table extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'customer',
            'plural' => 'customer',
        ));
    }

    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    
    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_first_name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &customer=2
        /*$actions = array(
            'edit' => sprintf('<a href="?page=qd_customer_manager&id=%s">%s</a>', $item['id'], __('Edit', 'qd_customer_manager')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'qd_customer_manager')),
        );*/

        //print_r($item);

        $customer_id = $item['icustomer_id'];
        $customer_first_name = $item['first_name'];
        $customer_last_name = $item['last_name'];
        $item = sprintf('<a href="?page=customer_view&id=%s">%s</a>', $customer_id, __($customer_first_name.' '.$customer_last_name, 'qd_customer_manager'));
        return $item;
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['icustomer_id']
        );
    }

    function extra_tablenav($which ){
                if ( 'top' != $which )
            return;?>
    <?php }

    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'first_name' => __('Name', 'qd_customer_manager'),
            'city' => __('City', 'qd_customer_manager'),
            'state' => __('State', 'qd_customer_manager'),
            'email' => __('Email', 'qd_customer_manager'),
            'plan_type' => __('Plan', 'qd_customer_manager'),
            'birth_day' => __('Birth Date', 'qd_customer_manager'),
            'dsignup_date' => __('Signup Date', 'qd_customer_manager'),
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
     
    
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'first_name' => array('first_name', true),
            'city' => array('city', false),
            'state' => array('state', false),
            'email' => array('email', false),
            'plan_type' => array('plan_type', false),
            'birth_day' => array('birth_day', false),
            'dsignup_date' => array('dsignup_date', false),
        );
        return $sortable_columns;
    }

    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete',
        );
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = 'customer'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE icustomer_id IN($ids)");
            }
        }
    }

    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_name = 'customer'; // do not forget about tables prefix

        $per_page = 25; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden         = array(); // No columns to hide, but we must set as an array.
        $sortable       = array(); // No reason to make sortable columns.
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        $search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
        
        if($search){
            if ( ! empty( $_GET['_wp_http_referer'] ) ) {
                wp_redirect( remove_query_arg( array( '_wp_http_referer' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
                exit;
            }
        }
        
        $search_strig = '';
        if($search){
            // First, escape the link for use in a LIKE statement.
            $search_strig = $wpdb->esc_like( $search );
            $search_strig = '%' . $search_strig . '%';
            $sql_total_items = "SELECT COUNT(icustomer_id) FROM $table_name WHERE (first_name LIKE  %s) AND ( plan_type LIKE 'amg_99' )";
        } else {
            $sql_total_items = "SELECT COUNT(icustomer_id) FROM $table_name WHERE ( plan_type LIKE 'amg_99' )";
        }
        
        $sql_total_items = $wpdb->prepare( $sql_total_items, $search_strig);
        $total_items = $wpdb->get_var( $sql_total_items );
        
        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? abs((int)$_REQUEST['paged']) : 1;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'first_name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $offset = ( $paged * $per_page ) - $per_page;
        //$offset;
         $search_strig_item = "";
        if($search){
            
            $search_strig_item = $wpdb->esc_like( $search );
            $search_strig_item = '%' . $search_strig_item . '%';
            $sql_get_items = "SELECT *,DATE_FORMAT(birth_day,'%%m-%%d-%%Y') AS birth_day,DATE_FORMAT(dsignup_date,'%%m-%%d-%%Y') AS dsignup_date FROM $table_name WHERE (first_name LIKE  %s) AND ( plan_type LIKE 'amg_99' ) ORDER BY `$table_name`.`$orderby` $order LIMIT $offset, $per_page";
            
            $sql_get_items = $wpdb->prepare( $sql_get_items, $search_strig_item);
            $this->items = $wpdb->get_results( $sql_get_items, ARRAY_A);
        
        } else {
            
            $sql_get_items = "SELECT *,DATE_FORMAT(birth_day,'%%m-%%d-%%Y') AS birth_day,DATE_FORMAT(dsignup_date,'%%m-%%d-%%Y') AS dsignup_date FROM $table_name where ( plan_type LIKE 'amg_99' ) ORDER BY `$table_name`.`$orderby` $order LIMIT $offset, $per_page";
            $sql_get_items = $wpdb->prepare( $sql_get_items, $search_strig_item);
            $this->items = $wpdb->get_results( $sql_get_items, ARRAY_A);
        
        }
        
        
        
        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}



    global $wpdb;

    $table = new Qd_Plan99_Customer_Management_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'qd_customer_manager'), count($_REQUEST['id'])) . '</p></div>';
    }

    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Customer List', 'qd_customer_manager')?></h2>
    <?php echo $message; ?>

    <form id="qd-customer-table" method="get">
		
		<div class="tablenav">
				
		<?php $table->search_box( __( 'Search' ), 'example' );
		foreach ($_GET as $key => $value) { // http://stackoverflow.com/a/8763624/1287812
        if( 's' !== $key ) // don't include the search query
            echo("<input type='hidden' name='$key' value='$value' />");
		} ?>
		
		<select class="alignright" name="search_by">
			<option value="first_name">First name</option>
			<option value="last_name">Last name</option>
			<option value="city">City</option>
			<option value="state">State</option>
			<option value="email">Email</option>
		</select>
		</div>
        
        <?php /* ?><input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/> <?php */ ?>
        <?php $table->display() ?>
    </form>

</div>

