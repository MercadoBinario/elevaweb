<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    require_once( ABSPATH . 'wp-admin/includes/template.php' );
}

class My_List_Table extends WP_List_Table {
	
	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'My Posts', 'elevaweb' ), //singular name of the listed records
			'plural'   => __( 'My Posts', 'elevaweb' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );
	}
	function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />', $item['ID']
        );    
    }
	
	function get_columns(){
	  $columns = array(
		'cb' => '<input type="checkbox" />',
		'feed' => __('Source Name','elevaweb'),
		'feed_category' => __('Source Category','elevaweb'),
		'post_category' => __('My Site Category','elevaweb'),
		'status' => __('Status','elevaweb'),
		'action' =>  __('Actions','elevaweb'),
		'scheduled_date' => __('Week Day/Time','elevaweb'),
	  );
	  return $columns;
	}
	
	function column_post_category($item) {
		$id = $item['post_category'];
		if(!empty($id)) {
			echo get_cat_name($id);
		}
	}
	
	function column_action($item) {
		echo '<input type="hidden" name="scheduled_post_id" value="'.$item['ID'].'" />';
		echo '<button type="button" class="button" onclick="window.location=\''.admin_url().'admin.php?page=eleva-post-config&action=edit_feed&id='.$item['ID'].'\'">'.__('Edit','elevaweb').'</button>&nbsp;';
		echo '<button type="button" class="button btn_dlt" data-id="'.$item['ID'].'" data-action="delete_post">'.__('Delete','elevaweb').'</button>&nbsp;';
		if($item['running'] == "1") {
			echo '<button type="button" class="button" onclick="window.location=\''.admin_url().'admin.php?page=eleva-post-config&action=pause_post&id='.$item['ID'].'\'">'.__('Pause','elevaweb').'</button>';
		}
		else {
			echo '<button type="button" class="button" onclick="window.location=\''.admin_url().'admin.php?page=eleva-post-config&action=resume_post&id='.$item['ID'].'\'">'.__('Play','elevaweb').'</button>';
		}
	}
	
	function column_status($item) {
		if($item['status'] == "1") {
			echo "<span class='feed-active'>".__('Active','elevaweb')."</span>";
		}
		else if($item['status'] == "2") {
			echo "<span class='feed-error'>".__('Error','elevaweb')."</span>";
		}
	}
	
	function column_scheduled_date($item) {
		$date = $item['scheduled_date'];
		$html_date = '';
		$time = '';
		if(!empty($date)) {
			$date = unserialize($date);
			if($date){
				foreach($date as $d){
					$html_date .= date_i18n("D",strtotime( $d )).',';
					$time = date("H:i",strtotime( $d ));
				}
			}
			echo rtrim($html_date,',').' '.$time;
			//echo implode(', ',$date);
			/* if($date) {
				echo implode(', ',$date);
			}
			else if(!is_array($date)) {
				echo $date;
			} */
		}else if(!is_array($date)) {
			echo date("D H:i",strtotime( $date ));
		}
	}
	
	function get_myposts($per_page = 15, $page_number = 1) {
		global $wpdb;
		$status = '';
		if(isset($_REQUEST['status'])) {
			$status = $_REQUEST['status'];
		}
		
		$sql = "SELECT * FROM {$wpdb->prefix}elevaweb_scheduled_post";
		
		if(!empty($status)) {
			if($status == "active") {
				$sql .= ' WHERE status = 1';
			}
			else {
				$sql .= ' WHERE status = 0';
			}
		}
		
		$sql .= ' ORDER BY ID DESC';
		
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ID ASC' . esc_sql( $_REQUEST['orderby'] );
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
	function get_bulk_actions()
    {
        $actions = array(
            'delete' => __('Delete','elevaweb')
        );
        return $actions;
    }
	public static function record_count() {
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}elevaweb_scheduled_post";
		return $wpdb->get_var( $sql );
	}
	
	public static function active_count() {
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE status = 1";
		return $wpdb->get_var( $sql );
	}
	function process_bulk_action(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'elevaweb_scheduled_post'; // do not forget about tables prefix
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);
            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE ID IN($ids)");
            }
        }
    }
	public static function error_count() {
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}elevaweb_scheduled_post WHERE status = 0";
		return $wpdb->get_var( $sql );
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		//$this->_column_headers = $this->get_column_info();
		/** Process bulk action */
		$this->process_bulk_action();
		$per_page     = $this->get_items_per_page( 'myposts_per_page', 15 );
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
	
	protected function display_tablenav( $which ) { 
		if($which == 'top'):
			$status = '';
			if(isset($_REQUEST['status'])) {
				$status = $_REQUEST['status'];
			}
			$allCount = $this->record_count();
			$activeCount = $this->active_count();
			$errorCount = $this->error_count();
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">

			<?php if ( $this->has_items() ): ?>
			<div class="alignleft actions bulkactions">
				<div class="alignleft actions bulkactions">
					<?php echo $this->bulk_actions( $which ); ?>
				</div>
				<ul class="subsubsub">
					<li class="all"><a href="?page=eleva-post-config" class="<?php echo empty($status) ? "current" : "" ?>"><?php _e('All','elevaweb'); ?> <span class="count">(<?php echo $allCount; ?>)</span></a> |</li>
					<li class="active"><a href="?page=eleva-post-config&status=active" class="<?php echo ($status == "active") ? "current" : "" ?>"><?php _e('Active','elevaweb'); ?> <span class="count">(<?php echo $activeCount; ?>)</span></a> |</li>
					<li class="error-a"><a href="?page=eleva-post-config&status=error" <?php echo ($status == "error") ? "current" : "" ?>><?php _e('Error','elevaweb'); ?> <span class="count">(<?php echo $errorCount; ?>)</span></a></li>
				</ul>
			</div>
			<?php endif;
			?>
			<br class="clear" />
		</div>
		<?php
		elseif($which == 'bottom'):
		?>
		<div class="elevaweb-table-nav">
		<?php
			$this->pagination( $which );
		?>
		</div>
		<?php
		endif;
	}
	
	function column_default( $item, $column_name ) {
		return $item[ $column_name ];
	}
	
}

$myListTable = new My_List_Table();
