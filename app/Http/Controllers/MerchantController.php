<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MerchantController extends Controller
{
    public function __construct(
        MerchantService $merchantService
    ) {}

    /**
     * Useful order statistics for the merchant API.
     * 
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
         
		$startDate = Carbon::createFromFormat('Y-m-d', $request->from);
        $endDate = Carbon::createFromFormat('Y-m-d', $request->to);
        
		$data = array(); 
		
		//Total Number of orders
		$orders = Merchant->orders::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->count();
		
		//Total commission_owed of orders
		$commission_owed = Merchant->orders::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('commission_owed');
			
		//Total subtotal of orders
		$subtotal = Merchant->orders::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('subtotal');
		
		return JsonResponse::create($data[$orders, $commission_owed, $subtotal]);
    }
}
