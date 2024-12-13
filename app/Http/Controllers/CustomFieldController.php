<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index()
    {
        $customFields = CustomField::all();
        return view('custom-fields.index', compact('customFields'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:custom_fields',
            'type' => 'required|string|in:text,number,date,select,email,phone',
            'options' => 'required_if:type,select|array',
            'is_required' => 'boolean'
        ]);

        CustomField::create($validated);

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field created successfully');
    }

    public function update(Request $request, CustomField $customField)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:custom_fields,name,' . $customField->id,
            'type' => 'required|string|in:text,number,date,select,email,phone',
            'options' => 'required_if:type,select|array',
            'is_required' => 'boolean'
        ]);

        $customField->update($validated);

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field updated successfully');
    }

    public function destroy(CustomField $customField)
    {
        $customField->delete();

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field deleted successfully');
    }
}
