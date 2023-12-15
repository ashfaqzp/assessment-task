<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {}

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method
		$order = Order::findOrFail($order_id);
		$order->subtotal_price = $data['subtotal_price'];
		/////////////////////////
		$order->customer_email = $data['customer_email']; 
		
		//Creating new affilicate if customer email is not associated with one.
		$affiliateFind = order::where('customer_email', $data['customer_email'])->firstOrFail();
		
		if(!$affiliateFind->id){
			$affiliate = new Affiliate;
			$affiliate->merchant_id = $order->merchant_id; 
			$affiliate->save();
		}
		
		$order->save();
		
    }
}
