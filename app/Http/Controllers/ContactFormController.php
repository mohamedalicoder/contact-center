<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactFormSubmitted;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function show()
    {
        return view('contact.form');
    }

    public function sendEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'message' => 'required|string'
            ]);

            // Send email
            Mail::to(config('mail.admin_address', env('MAIL_USERNAME')))->send(
                new ContactFormSubmitted(
                    $validated['name'],
                    $validated['email'],
                    $validated['message']
                )
            );

            return back()->with('success', 'Your message has been sent successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to send email. Please try again later.']);
        }
    }
}
