<?php 
namespace Ump{
	class Orders{
		
		public function __construct(){}
		
		public function do_insert($data=array(), $automated_payment=1){
			/*
			 * @param array, int (1 - simple payment, 2 - reccuring)
			 * @return int
			 */
			if (!empty($data['uid']) && isset($data['lid']) && isset($data['amount'])){
				global $wpdb;
				$table = $wpdb->prefix . 'ihc_orders';				
				$q = $wpdb->prepare("INSERT INTO $table VALUES (null, %d, %d, %s, %s, %d, %s, null);", 
											$data['uid'], $data['lid'], $data['amount_type'], $data['amount'], $automated_payment, $data['status']);
				$wpdb->query($q);
				$id = $wpdb->insert_id;
				do_action('ump_payment_check', $id, 'insert');
				return $id;
			}
		}
		
		public function do_insert_update($txn_id=0){
			/*
			 * @param int
			 * @return none
			 */
			 
			if ($txn_id){
				require_once IHC_PATH . 'classes/Transactions.class.php';
				$object = new Transactions($txn_id);
				$data = $object->get_data();
				global $wpdb;
				$table = $wpdb->prefix . 'ihc_orders';
				$q = $wpdb->prepare("SELECT * FROM $table
										WHERE
										uid=%d
										AND lid=%d
										AND amount_value=%s
										AND status='pending'
				", $data['uid'], $data['lid'], $data['amount']);
				$query_result = $wpdb->get_row($q);
				if (empty($query_result->id)){
					/****************** INSERT **************/
					$automated_payment = ($this->is_recuring_payment($data)) ? 2 : 1;/// CHECK if it's reccuring payment
					$the_id = $this->do_insert($data, $automated_payment);
				} else {
					/***************** SIMPLE UPDATE **************/
					$the_id = $query_result->id;
					$q = $wpdb->prepare("UPDATE $table SET status=%s WHERE id=%d", $data['status'], $the_id);
					$wpdb->query($q);
					do_action('ump_payment_check', $the_id, 'update');
				}	

				if (!empty($the_id)){
					$this->update_transaction_table($txn_id, $the_id);					
				}
			}
			
		}
		
		public function get_data($order_id=0){
			/*
			 * @param none
			 * @return array
			 */
			if ($order_id){
				global $wpdb;
				$table = $wpdb->prefix . 'ihc_orders';
				$data = $wpdb->get_row("SELECT * FROM $table WHERE id='" . $order_id . "';");
				if (!empty($data)){
					return (array)$data;
				} else {
					return array();
				}
			}
		}
		
		private function update_transaction_table($txn_id='', $id=0){
			/*
			 * @param string, int
			 * @return none
			 */
			if ($txn_id && $id){
				global $wpdb;
				$table = $wpdb->prefix . 'indeed_members_payments';
				$data = $wpdb->get_row("SELECT orders FROM $table WHERE txn_id='$txn_id';");
				if (!empty($data->order_ids)){
					$ids = unserialize($data->orders);
				}
				$ids[] = $id;
				$ids = serialize($ids);
				$wpdb->query("UPDATE $table SET orders='$ids' WHERE txn_id='$txn_id';");
			}
		}
		
		private function is_recuring_payment($data=array()){
			/*
			 * @param array
			 * @return boolean
			 */
			global $wpdb;
			$table = $wpdb->prefix . 'ihc_orders';
			$q = $wpdb->prepare("SELECT id FROM $table
									WHERE
									uid=%d
									AND lid=%d
									AND automated_payment=1
									AND status='Completed'
									", $data['uid'], $data['lid']
			);
			$query_result = $wpdb->get_row($q);
			if (isset($query_result->id)){
				return TRUE;
			}
			return FALSE;
		}
				
	}
}