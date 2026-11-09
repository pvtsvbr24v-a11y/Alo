@props(['text' => ''])
{{-- يحوّل *كلمة* إلى خط يد. النص ينهرب أول عشان يكون آمن --}}
{!! preg_replace('/\*(.+?)\*/u', '<span class="script-accent">$1</span>', e($text)) !!}
