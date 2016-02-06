<?php
namespace HRE\Stats\Data;

class DataManager
{	
	private static $participant = array('223' => ''); //ids of participant products
	private static $visitor = array('270' => ''); // ids of visitor products
	private static $volunteer = array('255' => ''); // ids of volunteer products
	
	protected $product_id;
		
	public function __construct()
	{
		
	}
	
	private function fetchAllRegistations()
	{
		$sql = "SELECT
			COUNT(orders.order_id) AS Total,
			orders.order_item_name
			FROM wp_woocommerce_order_items AS orders 
			INNER JOIN wp_posts posts ON orders.order_id = posts.id
			WHERE posts.post_type = 'shop_order'
			AND posts.post_status = 'wc-processing'
			GROUP BY orders.order_item_name";

		return $GLOBALS['wpdb']->get_results($sql, ARRAY_A);
	}
	
	private function fetchAllParticipants()
	{
		$product_ids = array_keys(DataManager::$participant);
		$sql_in = '';
		for ($itr=0;$itr<count($product_ids);$itr++) {
			$sql_in .= $product_ids[$itr];
			if ($itr < count($product_ids)-1) {
				$sql_in .= ',';
			}
		}
		$sql = "SELECT
			orders.order_id,
			orders.order_item_name,
			orderName.Name,
			orderDetailCity.city,
			orderDetailGender.gender
			FROM wp_woocommerce_order_items AS orders 
			INNER JOIN (
				SELECT meta.order_item_id FROM wp_woocommerce_order_itemmeta AS meta
				WHERE (meta.meta_key = '_product_id' AND meta.meta_value IN ({$sql_in}))
			) AS orderProduct ON orderProduct.order_item_id = orders.order_item_id
			INNER JOIN (
				SELECT meta.order_item_id,
				meta.meta_value AS Name
				FROM wp_woocommerce_order_itemmeta AS meta
				WHERE (meta.meta_key = 'Naam')
			) AS orderName ON orderName.order_item_id = orders.order_item_id
			INNER JOIN (
				SELECT meta.order_item_id,
				meta.meta_value AS Gender
				FROM wp_woocommerce_order_itemmeta AS meta
				WHERE (meta.meta_key = 'Geslacht')
			) AS orderDetailGender ON orderDetailGender.order_item_id = orders.order_item_id
			INNER JOIN (
				SELECT meta.order_item_id,
				meta.meta_value AS city
				FROM wp_woocommerce_order_itemmeta AS meta
				WHERE (meta.meta_key = 'Woonplaats')
			) AS orderDetailCity ON orderDetailCity.order_item_id = orders.order_item_id
			INNER JOIN wp_posts posts ON orders.order_id = posts.id
			WHERE posts.post_type = 'shop_order'
			AND posts.post_status = 'wc-processing'
			ORDER BY orderDetailGender.gender, orderName.Name, orders.order_id DESC";
		
		return $GLOBALS['wpdb']->get_results($sql, ARRAY_A);
	}
		
	public function data()
	{
		$registrations = $this->fetchAllRegistations();
		return $registrations;
	}
	
	public function participants()
	{
		return $this->fetchAllParticipants();
	}
}