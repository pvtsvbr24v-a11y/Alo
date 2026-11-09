<?php

/*
| رسائل التحقق بالعربي (القواعد المستخدمة في المتجر).
*/

return [
    'boolean' => 'قيمة :attribute غير صحيحة.',
    'digits' => ':attribute لازم يكون :digits أرقام.',
    'enum' => ':attribute غير صحيح.',
    'exists' => ':attribute غير موجود.',
    'gt' => [
        'numeric' => ':attribute لازم يكون أكبر من :value.',
    ],
    'image' => ':attribute لازم تكون صورة.',
    'in' => ':attribute غير صحيح.',
    'integer' => ':attribute لازم يكون رقم صحيح.',
    'max' => [
        'file' => ':attribute لازم يكون أصغر من :max كيلوبايت.',
        'numeric' => ':attribute لازم يكون أقل من أو يساوي :max.',
        'string' => ':attribute لازم يكون أقل من :max حرف.',
    ],
    'min' => [
        'numeric' => ':attribute لازم يكون :min أو أكثر.',
        'string' => ':attribute لازم يكون :min أحرف على الأقل.',
    ],
    'numeric' => ':attribute لازم يكون رقم.',
    'regex' => 'صيغة :attribute غير صحيحة.',
    'required' => 'حقل :attribute مطلوب.',
    'string' => ':attribute لازم يكون نص.',
    'url' => ':attribute لازم يكون رابط صحيح.',

    'attributes' => [
        'quantity' => 'الكمية',
        'status' => 'الحالة',
        'code' => 'الرمز',
        'phone' => 'رقم الجوال',
    ],
];
