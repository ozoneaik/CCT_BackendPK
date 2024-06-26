<?php

namespace App\Services;
use App\Models\MaTargetCust;
use Illuminate\Support\Facades\DB;

class MaTargetCustService
{
    public function GetList($target_month,$sale_id){
        return MaTargetCust::select([
            'ma_target_cust.custid',
            'ma_customer.custname',
            DB::raw('COALESCE(wi_target_sale.target_sale, 0) AS target_sale'),
            DB::raw('COALESCE(ma_target_cust.percentsale, 0) AS percentsale'),
            DB::raw('SUM(DISTINCT CASE WHEN wi_target_sku.target_month = "'.$target_month.'" THEN wi_target_sku.target_sku_sale END) AS sku_total_count'),
            DB::raw('COUNT(DISTINCT CASE WHEN wi_target_sku.target_month = "'.$target_month.'" THEN wi_target_sku.id END) AS sku_count'),
            DB::raw('COUNT(DISTINCT CASE WHEN wi_target_newsku.new_target_month = "'.$target_month.'" THEN wi_target_newsku.id END) AS new_sku_count'),
            DB::raw('COUNT(DISTINCT CASE WHEN wi_target_pro.pro_month = "'.$target_month.'" THEN wi_target_pro.id END) AS sku_pro_count'),
            DB::raw('COUNT(DISTINCT CASE WHEN wi_target_booth.booth_month = "'.$target_month.'" THEN wi_target_booth.id END) AS sku_booth_count'),
            DB::raw('COUNT(DISTINCT CASE WHEN wi_target_train.train_month = "'.$target_month.'" THEN wi_target_train.id END) AS sku_train_count')
        ])
            ->join('ma_customer', 'ma_target_cust.custid', '=', 'ma_customer.custid')
            ->leftJoin('wi_target_sale', function($join) use ($target_month) {
                $join->on('ma_target_cust.custid', '=', 'wi_target_sale.custid')
                    ->where('wi_target_sale.target_month', '=', $target_month);
            })
            ->leftJoin('wi_target_sku', 'ma_target_cust.custid', '=', 'wi_target_sku.custid')
            ->leftJoin('wi_target_newsku', 'ma_target_cust.custid', '=', 'wi_target_newsku.custid')
            ->leftJoin('wi_target_pro', 'ma_target_cust.custid', '=', 'wi_target_pro.custid')
            ->leftJoin('wi_target_booth', 'ma_target_cust.custid', '=', 'wi_target_booth.custid')
            ->leftJoin('wi_target_train', 'ma_target_cust.custid', '=', 'wi_target_train.custid')
            ->where('ma_target_cust.fieldsaleid', 'LIKE', $sale_id)
            ->groupBy('ma_target_cust.custid', 'ma_customer.custname', 'wi_target_sale.target_sale','ma_target_cust.percentsale')
            ->orderBy('ma_customer.custname','asc')
            ->get();
    }
}
