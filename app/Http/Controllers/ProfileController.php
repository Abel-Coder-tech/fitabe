<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $sessions = DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($s) {
                $s->last_activity_humain = \Carbon\Carbon::createFromTimestamp($s->last_activity)->diffForHumans();
                $s->is_current = $s->id === session()->getId();
                $s->user_agent = $this->parseUserAgent($s->user_agent ?? '');
                return $s;
            });

        return view('profile.edit', [
            'user' => $request->user(),
            'sessions' => $sessions,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('avatar')) {
            $request->validate(['avatar' => 'image|mimes:jpeg,png,jpg,webp|max:2048']);
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->has('preferences')) {
            $user->preferences = array_merge($user->preferences ?? [], $request->preferences);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePreferences(Request $request): RedirectResponse
    {
        $request->validate([
            'preferences' => 'array',
            'preferences.email_notifications' => 'boolean',
            'preferences.compact_mode' => 'boolean',
            'preferences.dark_mode' => 'boolean',
        ]);

        $user = $request->user();
        $user->preferences = array_merge($user->preferences ?? [], $request->preferences);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'preferences-updated');
    }

    public function destroySession(Request $request, string $sessionId): RedirectResponse
    {
        $session = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($session) {
            DB::table('sessions')->where('id', $sessionId)->delete();
        }

        return Redirect::route('profile.edit')->with('status', 'session-revoked');
    }

    public function destroyOtherSessions(Request $request): RedirectResponse
    {
        $request->validateWithBag('sessionRevoke', [
            'password' => ['required', 'current_password'],
        ]);

        DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->where('id', '!=', session()->getId())
            ->delete();

        return Redirect::route('profile.edit')->with('status', 'other-sessions-revoked');
    }

    public function export(Request $request): Response
    {
        $user = $request->user();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at->toIso8601String(),
            'updated_at' => $user->updated_at->toIso8601String(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'donnees-compte-' . date('Y-m-d') . '.json';

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function parseUserAgent(string $ua): array
    {
        $browser = 'Inconnu';
        $os = 'Inconnu';

        if (preg_match('/Firefox\/([\d.]+)/', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome\/([\d.]+)/', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari\/([\d.]+)/', $ua)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edge\/([\d.]+)/', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/MSIE|Trident/', $ua)) {
            $browser = 'Internet Explorer';
        }

        if (preg_match('/Windows NT ([\d.]+)/', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/Mac OS X ([\d_]+)/', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/Linux/', $ua)) {
            $os = 'Linux';
        } elseif (preg_match('/Android/', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/iPhone|iPad/', $ua)) {
            $os = 'iOS';
        }

        return ['browser' => $browser, 'os' => $os];
    }
}
