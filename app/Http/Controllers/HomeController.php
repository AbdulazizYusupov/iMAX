<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Expense;
use App\Models\Sale;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Omborda hozir tayyor turgan telefonlar (Sotilmaganlar)
        $active_phones_count = Phone::where('status', true)->count();
        $active_phones_cost = Phone::where('status', true)->sum('cost_price');

        // 2. Jami Oborot (Sotilgan umumiy qiymat)
        $total_turnover = Sale::sum('sold_price');

        // 3. Sotilgan tovarlarning asl tannarxi (Kelgan narxi)
        // Katta bazalarda muammo bo'lmasligi uchun join orqali tezkor hisoblaymiz
        $sold_goods_cost = Sale::join('phones', 'sales.phone_id', '=', 'phones.id')
            ->sum('phones.cost_price');

        // 4. Jami umumiy xarajatlar (Ijara, oylik va hokazo)
        $total_expenses = Expense::sum('amount');

        // 5. Haqiqiy Sof Foyda = Oborot - Sotilganlar Tannarxi - Xarajatlar
        $real_net_profit = $total_turnover - $sold_goods_cost - $total_expenses;

        // 6. Jadvallar uchun oxirgi ma'lumotlar
        $latest_phones = Phone::with('category')->where('status', true)->latest()->take(5)->get();
        $latest_sales = Sale::with('phone')->latest()->take(5)->get();

        return view('dashboard', compact(
            'active_phones_count',
            'active_phones_cost',
            'total_turnover',
            'real_net_profit',
            'total_expenses',
            'latest_phones',
            'latest_sales'
        ));
    }
}
