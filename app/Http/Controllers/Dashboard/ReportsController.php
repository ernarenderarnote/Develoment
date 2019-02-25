<?php

namespace App\Http\Controllers\Dashboard;

use Auth;
use Gate;

use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\File\UploadFileFormRequest;
use App\Models\Order;
use App\Models\ProductVariant;

class ReportsController extends Controller
{

    /**
     * Show the page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $store_id = null)
    {
        $store_id = (int)$store_id;
        $stores = auth()->user()->stores;
        
        $selectedStore = null;
        if ($store_id) {
            $selectedStore = $stores->where('id', $store_id)->first();
        }
        
        if ($store_id && !$selectedStore) {
            return redirect('/dashboard/reports');
        }
        else if (!$stores->isEmpty() && !$selectedStore) {
            $selectedStore = $stores->first();
            return redirect('/dashboard/reports/'.$selectedStore->id);
        }
        
        return view('pages.dashboard.reports.index', [
            'stores' => $stores,
            'selectedStore' => $selectedStore
        ]);
    }
    
    
    public function getData(Request $request, $store_id)
    {
        $filterYear = (int)$request->get('year');
        if (!$filterYear) {
            $filterYear = date('Y');
        }
        
        $selectedStore = auth()->user()->stores
            ->where('id', (int)$store_id)
            ->first();
        
        $incomeData = [];
        $ordersData = [];
        for ($month = 1; $month <=12; $month++) {
            $incomeData[$month] = 0;
            $ordersData[$month] = 0;
        }
        
        $popularProductsData = [];
        if ($selectedStore) {
            
            $incomeData = array_values(array_replace($incomeData, 
                Order::getIncomeStatsForStore($selectedStore->id, $filterYear)
                    ->pluck('sum', 'month')->toArray()
            ));
                
            $ordersData = array_values(array_replace($ordersData, 
                Order::getOrdersStatsForStore($selectedStore->id, $filterYear)
                    ->pluck('orders', 'month')->toArray()
            ));
            
            $popularProductsData = ProductVariant::getPopularProductsStatsForStore(
                $selectedStore->id,
                $filterYear,
                'product'
            )
                ->pluck('products', 'category')->toArray();
        }

        return response()->api(
            null,
            [
                'incomeData' => $incomeData,
                'ordersData' => $ordersData,
                'popularProductsData' => $popularProductsData
            ]
        );
    }
    
}
