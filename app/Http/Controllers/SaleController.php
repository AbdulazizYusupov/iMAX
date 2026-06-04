<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPayment;
use App\Models\Sale;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['phone.category', 'user', 'installmentPayments']);

        $filter = $request->get('filter', 'all');

        // 3. Vaqtga qarab filtrni qo'llaymiz
        switch ($filter) {
            case 'day':
                // Bugungi kun boshidan (00:00:00) hozirgacha
                $query->where('created_at', '>=', Carbon::today());
                break;

            case 'week':
                // Shu haftaning birinchi kunidan (dushanbadan) boshlab hozirgacha
                $query->where('created_at', '>=', Carbon::now()->startOfWeek());
                break;

            case 'month':
                // Shu oyning birinchi kunidan boshlab hozirgacha
                $query->where('created_at', '>=', Carbon::now()->startOfMonth());
                break;
        }

        // 4. Filtrlangan ma'lumotlarni bazadan olamiz
        $sales = $query->latest()->get();

        // 5. Jami savdo summasini ham FAQAT shu filtrlangan sotuvlardan kelib chiqib hisoblaymiz!
        $total_sales_sum = $sales->sum('sold_price');

        // Filtr qiymatini ham view'ga berib yuboramiz (tugmalarni aktiv qilish uchun)
        return view('sales.index', compact('sales', 'total_sales_sum', 'filter'));
    }
    public function create(Request $request)
    {
        // Agar aniq bir telefon ID kelgan bo'lsa, uni srazu formaga tanlangan qilamiz
        $selected_phone = null;
        if ($request->has('phone_id')) {
            $selected_phone = Phone::where('status', true)->findOrFail($request->phone_id);
        }

        // Faqat sotilmagan (ombor dagi) telefonlar ro'yxati
        $available_phones = Phone::with('category')->where('status', true)->latest()->get();

        return view('sales.create', compact('available_phones', 'selected_phone'));
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'phone_id' => ['required', 'exists:phones,id'],
            'sold_price' => ['required', 'numeric', 'min:0'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'string'],
            'installment_months' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        // 1. Telefonni bazadan olamiz
        $phone = Phone::findOrFail($fields['phone_id']);

        // 2. Xavfsizlik tekshiruvi: Omborda sotish uchun tovar qolganmi?
        if ($phone->current_stock <= 0) {
            return redirect()->back()->with('error', 'Ushbu mahsulot omborda qolmagan!');
        }

        // 3. Sotuvni bazaga yozish
        $sale = Sale::create([
            'phone_id' => $phone->id,
            'user_id' => auth()->id(),
            'sold_price' => $fields['sold_price'],
            'customer_name' => $fields['customer_name'],
            'customer_phone' => $fields['customer_phone'],
            'payment_method' => $fields['payment_method'],
            'notes' => $fields['notes'],
        ]);

        // 4. 🔥 MUDDATLI TO'LOV GRAFIGINI YARATISH (Agar tanlangan bo'lsa)
        if ($fields['payment_method'] === 'muddatli' && !empty($fields['installment_months'])) {
            $months = (int)$fields['installment_months'];
            $monthly_amount = round($fields['sold_price'] / $months);

            for ($i = 1; $i <= $months; $i++) {
                $sale->installmentPayments()->create([
                    'month_number' => $i,
                    'amount' => $monthly_amount,
                    'is_paid' => false,
                ]);
            }
        }

        // 5. 🔥 OMBORDAN TOVARNI AYIRISH (Sotilganlar sonini 1 taga oshiramiz)
        $phone->increment('sold_quantity');

        // 6. Agar oxirgi dona sotilgan bo'lsa, statusni yopamiz
        if ($phone->current_stock <= 0) {
            $phone->update(['status' => false]);
        }

        return redirect()->route('sales.index')->with('success', 'Sotuv muvaffaqiyatli yakunlandi!');
    }

    public function payNextMonth($sale_id)
    {
        // Tanlangan sotuvga tegishli to'lanmagan birinchi oyni topamiz
        $nextPayment = InstallmentPayment::where('sale_id', $sale_id)
            ->where('is_paid', false)
            ->orderBy('month_number', 'asc')
            ->first();

        if ($nextPayment) {
            $nextPayment->update([
                'is_paid' => true,
                'paid_at' => now()
            ]);

            return redirect()->back()->with('success', "{$nextPayment->month_number}-oy to'lovi muvaffaqiyatli qabul qilindi!");
        }

        return redirect()->back()->with('error', "Ushbu mahsulot uchun barcha oylar to'lab bo'lingan!");
    }
}
