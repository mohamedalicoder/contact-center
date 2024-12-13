<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingController extends Controller
{
    use AuthorizesRequests;

    // public function __construct()
    // {
    //     $this->middleware(['auth']);
    //     $this->middleware(['permission:view settings'])->only(['index', 'show']);
    //     $this->middleware(['permission:edit settings'])->only(['create', 'store', 'edit', 'update', 'destroy']);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (Auth::check()) {
            $settings = Setting::when(Auth::user()->isAdmin(), function($query) {
                return $query->where('is_public', true);
            })
            ->orderBy('group')
            ->get()
            ->groupBy('group');
        } else {
            $settings = Setting::where('is_public', true)
            ->orderBy('group')
            ->get()
            ->groupBy('group');
        }

        return view('settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()?->isAdmin()) {
            return redirect()->route('settings.index')
                ->with('error', 'You do not have permission to create settings.');
        }

        return view('settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()?->isAdmin()) {
            return redirect()->route('settings.index')
                ->with('error', 'You do not have permission to create settings.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'unique:settings'],
            'value' => ['nullable'],
            'type' => ['required', 'string', 'in:text,number,boolean'],
            'group' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'is_public' => ['boolean'],
        ]);

        $setting = new Setting();
        $setting->name = $validated['name'];
        $setting->key = $validated['key'];
        $setting->type = $validated['type'];
        $setting->group = $validated['group'];
        $setting->label = $validated['label'];

        $setting->description = $validated['description'];
        $setting->is_public = $request->has('is_public');

        // Handle different types of values
        if ($setting->type === 'boolean') {
            $setting->value = $request->has('value');
        } else {
            $setting->value = $validated['value'];
        }

        $setting->save();

        return redirect()->route('settings.index')
            ->with('success', 'Setting created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified setting in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        if (!Auth::user()?->isAdmin() && !$setting->is_public) {
            return back()->with('error', 'You do not have permission to update this setting.');
        }

        $validated = $request->validate([
            'value' => ['required'],
        ]);

        if ($setting->type === 'boolean') {
            $setting->value = $request->has('value');
        } else {
            $setting->value = $validated['value'];
        }

        $setting->save();

        return back()->with('success', 'Setting updated successfully.');
    }

    /**
     * Remove the specified setting from storage.
     */
    public function destroy(Setting $setting)
    {
        if (!Auth::user()?->isAdmin()) {
            return redirect()->route('settings.index')
                ->with('error', 'You do not have permission to delete settings.');
        }

        $setting->delete();

        return redirect()->route('settings.index')
            ->with('success', 'Setting deleted successfully.');
    }

    protected function castValue($value, $type)
    {
        return match($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($value) ? (float)$value : 0,
            'json' => is_array($value) ? json_encode($value) : $value,
            default => (string)$value
        };
    }

    public function createSystemSettings()
    {
        $this->authorize('manage-settings');

        $defaultSettings = [
            [
                'group' => 'general',
                'key' => 'company_name',
                'value' => 'Contact Center',
                'type' => 'text',
                'label' => 'Company Name',
                'is_public' => true,
                'is_system' => true
            ],
            [
                'group' => 'notification',
                'key' => 'email_notifications',
                'value' => true,
                'type' => 'boolean',
                'label' => 'Email Notifications',
                'is_public' => true,
                'is_system' => true
            ],
            [
                'group' => 'calls',
                'key' => 'max_call_duration',
                'value' => 3600,
                'type' => 'number',
                'label' => 'Maximum Call Duration (seconds)',
                'is_public' => false,
                'is_system' => true
            ]
        ];

        foreach ($defaultSettings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        return redirect()->route('settings.index')
            ->with('success', 'System settings initialized successfully');
    }
}
