@component('mail::message')
# New Contact Form Submission

You have received a new contact form submission from {{ $name }}.

**Name:** {{ $name }}  
**Email:** {{ $email }}

**Message:**  
{{ $message }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
