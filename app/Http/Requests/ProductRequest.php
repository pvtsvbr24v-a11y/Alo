<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999'],
            'compare_at_price' => ['nullable', 'numeric', 'gt:price', 'max:999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'image_file' => ['nullable', 'image', 'max:1024'],
            'image_url' => ['nullable', 'url', 'max:2000'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'القسم',
            'name' => 'اسم المنتج',
            'description' => 'الوصف',
            'price' => 'السعر',
            'compare_at_price' => 'السعر قبل الخصم',
            'stock' => 'الكمية',
            'image_file' => 'الصورة',
            'image_url' => 'رابط الصورة',
        ];
    }
}
