<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Category;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    /**
     * Telefonlar ro'yxati
     */
    public function index()
    {
        $phones = Phone::with('category')->latest()->get()->map(function ($phone) {
            // datetime-local inputi qabul qiladigan formatni (Y-m-dTH:i) oldindan tayyorlab qo'yamiz
            $phone->formatted_arrival_date = $phone->arrival_date->format('Y-m-d\TH:i');
            return $phone;
        });

        $categories = Category::where('status', true)->get();

        return view('phones.index', compact('phones', 'categories'));
    }

    /**
     * Yangi telefon saqlash
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'imei' => ['nullable', 'string', 'unique:phones,imei'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'ram' => ['nullable', 'string'],
            'storage' => ['nullable', 'string'],
            'arrival_date' => ['required', 'date'],
        ]);

        $fields['status'] = $request->has('status');

        Phone::create($fields);

        return redirect()->route('phones.index')->with('success', 'Telefon muvaffaqiyatli qo‘shildi!');
    }

    /**
     * Telefon ma'lumotlarini yangilash
     */
    public function update(Request $request, Phone $phone)
    {
        $fields = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'imei' => ['nullable', 'string', 'unique:phones,imei,' . $phone->id],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'ram' => ['nullable', 'string'],
            'storage' => ['nullable', 'string'],
            'arrival_date' => ['required', 'date'],
        ]);

        $fields['status'] = $request->has('status');

        $phone->update($fields);

        return redirect()->route('phones.index')->with('success', "Telefon ma'lumotlari yangilandi!");
    }

    public function destroy(Phone $phone)
    {
        $phone->delete();
        return redirect()->route('phones.index')->with('success', 'Telefon muvaffaqiyatli o‘chirildi!');
    }
}
