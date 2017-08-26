<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-screen.php' );//added
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );//added
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    require_once( ABSPATH . 'wp-admin/includes/template.php' );
}

class My_Log_Table extends WP_List_Table {

	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Log', 'elevaweb' ), //singular name of the listed records
			'plural'   => __( 'Log', 'elevaweb' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );
	}

	function get_columns(){
	  $columns = array(
		'post_title' => __('Post Title','elevaweb'),
		'original_post_url' => __('Original Post Link','elevaweb'),
		'feed_category' => __('Category','elevaweb'),
		//'post_category' => 'Post Category',
		'published_date'    => __('Date and time for publication','elevaweb'),
	  );
	  return $columns;
	}

	function column_post_category($item) {
		$id = $item['post_category'];
		if(!empty($id)) {
			echo get_cat_name($id);
		}
	}

	function get_myposts($per_page = 5, $page_number = 1) {
		global $wpdb;
		$userData = $_SESSION['elevaweb_userdata'];
		$sql = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post_log WHERE author_id = ".$userData->customer_id." ORDER BY ID DESC";
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		$userData = $_SESSION['elevaweb_userdata'];
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}elevaweb_scheduled_post_log WHERE author_id = ".$userData->customer_id." ORDER BY ID DESC";
		return $wpdb->get_var( $sql );
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		//$this->_column_headers = $this->get_column_info();
		/** Process bulk action */
		$this->process_bulk_action();
		$per_page     = $this->get_items_per_page( 'myposts_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );
		$columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = self::get_myposts( $per_page, $current_page );
	}

	function column_default( $item, $column_name ) {
		return $item[ $column_name ];
	}

}

$myLogTable = new My_Log_Table();
