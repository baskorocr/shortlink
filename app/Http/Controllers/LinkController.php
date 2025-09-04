<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LinkController extends Controller
{
    public function index()
    {
        $links = Auth::user()->links()->latest()->paginate(10);
        
        return view('links.index', compact('links'));
    }

    public function create()
    {
        return view('links.create');
    }

    public function store(Request $request)
    {
   
       
        $validated = $request->validate([
            'original_url' => 'required|url|max:2048',
            'password' => 'nullable|string|min:4|max:255',
            'expires_at' => 'nullable|date',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');
      
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $link = Link::create($validated);

        return redirect()->route('links.index')
            ->with('success', 'Link berhasil dibuat! Short URL: ' . $link->short_url);
    }

    public function show(Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $accesses = $link->accesses()->latest()->paginate(10);
        
        return view('links.show', compact('link', 'accesses'));
    }

    public function edit(Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('links.edit', compact('link'));
    }

    public function update(Request $request, Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'original_url' => 'required|url|max:2048',
            'password' => 'nullable|string|min:4|max:255',
            'expires_at' => 'nullable|date|after:now',
     
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $link->update($validated);

        return redirect()->route('links.index')
            ->with('success', 'Link berhasil diupdate!');
    }

    public function destroy(Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $link->delete();

        return redirect()->route('links.index')
            ->with('success', 'Link berhasil dihapus!');
    }

    public function redirect(Request $request, $shortCode)
    {
        $link = Link::where('short_code', $shortCode)
            ->active()
            ->notExpired()
            ->firstOrFail();

        // Check if password is required
        if ($link->hasPassword()) {
            // Check if password was submitted
            if ($request->has('password')) {
                if (!Hash::check($request->password, $link->password)) {
                    return back()->withErrors(['password' => 'Password salah!']);
                }
            } else {
                // Show password form
                return view('links.password', compact('link'));
            }
        }

        // Track access
        $this->trackAccess($link, $request);
        
        // Increment click count
        $link->incrementClickCount();

        return redirect($link->original_url);
    }

    private function trackAccess(Link $link, Request $request)
    {
        LinkAccess::create([
            'link_id' => $link->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'accessed_at' => now(),
        ]);
    }
}