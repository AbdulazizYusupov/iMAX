<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PhoneController extends Controller
{
    /**
     * Telefonlar ro'yxatini ko'rsatish.
     */
    public function index()
    {
        // N+1 muammosini oldini olish uchun 'category' eager load qilindi
        $phones = Phone::with('category')->latest('arrival_date')->paginate(15);
        $categories = Category::all();

        return view('phones.index', compact('phones', 'categories'));
    }

    /**
     * Yangi telefon partiyasini omborga qo'shish.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'color'          => 'nullable|string|max:50',
            'imei'           => 'nullable|string|max:50|unique:phones,imei',
            'cost_price'     => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'margin_percent' => 'nullable|numeric|min:0',
            'ram'            => 'nullable|string|max:20',
            'storage'        => 'nullable|string|max:20',
            'quantity'       => 'required|integer|min:1',
            'arrival_date'   => 'required|date',
            'status'         => 'nullable|boolean',
        ]);

        // Standart holatda sotilganlar soni yangi partiyada 0 bo'ladi
        $validated['sold_quantity'] = 0;
        $validated['status'] = $request->has('status') ? true : false;

        // Agar kelgan sana vaqt bilan birga bo'lsa, Carbon formatga keltiriladi
        $validated['arrival_date'] = Carbon::parse($validated['arrival_date'])->format('Y-m-d H:i:s');

        Phone::create($validated);

        return redirect()->back()->with('success', "Yangi telefon modeli muvaffaqiyatli qo'shildi.");
    }

    /**
     * Telefon ma'lumotlarini yangilash (Edit modal uchun).
     */
    public function update(Request $request, Phone $phone)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'color'          => 'nullable|string|max:50',
            // IMEI faqat joriy telefondan tashqari unique bo'lishi kerak
            'imei'           => 'nullable|string|max:50|unique:phones,imei,' . $phone->id,
            'cost_price'     => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'margin_percent' => 'nullable|numeric|min:0',
            'ram'            => 'nullable|string|max:20',
            'storage'        => 'nullable|string|max:20',
            'quantity'       => 'required|integer|min:1',
            'arrival_date'   => 'required|date',
            'status'         => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status') ? true : false;
        $validated['arrival_date'] = Carbon::parse($validated['arrival_date'])->format('Y-m-d H:i:s');

        // Biznes mantiq xavfsizligi: Jami soni sotilganlar sonidan kam bo'lmasligi kerak
        if ($validated['quantity'] < $phone->sold_quantity) {
            return redirect()->back()->withErrors([
                'quantity' => "Jami miqdor sotilgan tovarlar sonidan (" . $phone->sold_quantity . " ta) kam bo'lishi mumkin emas!"
            ]);
        }

        $phone->update($validated);

        return redirect()->back()->with('success', "Telefon ma'lumotlari yangilandi.");
    }

    /**
     * Modelni o'chirish.
     */
    public function destroy(Phone $phone)
    {
        // Agar ushbu modelda allaqachon sotuv amalga oshirilgan bo'lsa, o'chirishni cheklaymiz
        if ($phone->sold_quantity > 0 || $phone->sales()->exists()) {
            return redirect()->back()->with('error', "Ushbu modelni o'chirib bo'lmaydi, chunki u bo'yicha sotuvlar mavjud!");
        }

        $phone->delete();

        return redirect()->back()->with('success', "Telefon modeli tizimdan o'chirildi.");
    }
}
