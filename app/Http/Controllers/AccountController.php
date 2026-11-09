<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * صفحة العميل: معلوماته وعنوانه وأفتاره وآخر طلباته.
 */
class AccountController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('account', [
            'user' => $user,
            'orders' => $user->orders()->withCount('items')->take(5)->get(),
            'ordersCount' => $user->orders()->count(),
            'presetAvatars' => User::PRESET_AVATARS,
            'cities' => config('store.cities'),
        ]);
    }

    public function update(AccountRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return back()->with('status', 'انحفظت معلوماتك');
    }

    /**
     * يا صورة مرفوعة (تنحفظ data URI) يا أفتار جاهز من القائمة.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'avatar_file' => ['nullable', 'required_without:preset', 'image', 'max:512'],
            'preset' => ['nullable', 'required_without:avatar_file', Rule::in(User::PRESET_AVATARS)],
        ], [
            'avatar_file.required_without' => 'اختر صورة أو أفتار',
            'avatar_file.image' => 'الملف لازم يكون صورة',
            'avatar_file.max' => 'الصورة لازم تكون أصغر من نص ميجا',
        ]);

        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $avatar = 'data:'.$file->getMimeType().';base64,'.base64_encode($file->getContent());
        } else {
            $avatar = $validated['preset'];
        }

        $request->user()->update(['avatar' => $avatar]);

        return back()->with('status', 'تغيّر الأفتار 🤍');
    }

    public function destroyAvatar(Request $request): RedirectResponse
    {
        $request->user()->update(['avatar' => null]);

        return back()->with('status', 'انشال الأفتار');
    }
}
