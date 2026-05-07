<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('pages.account.index', ['user' => Auth::user()]);
    }

    public function edit(): View
    {
        return view('pages.account.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->name  = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('account.edit')->with('success', 'Account updated.');
    }

    public function address(): View
    {
        return view('pages.account.address', ['user' => Auth::user()]);
    }

    public function updateAddress(Request $request): RedirectResponse
    {
        return redirect()->route('account.address')->with('success', 'Address saved.');
    }

    public function downloads(): View
    {
        return view('pages.account.downloads', ['user' => Auth::user()]);
    }
}
