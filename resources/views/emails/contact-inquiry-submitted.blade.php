<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New AndUs Website Inquiry</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6;">
    <div style="max-width: 640px; margin: 0 auto; padding: 32px;">
        <h1 style="margin-bottom: 24px; font-size: 24px;">
            New Website Inquiry
        </h1>

        <p>
            A new contact inquiry was submitted through the AndUs LLC website.
        </p>

        <hr style="margin: 24px 0; border: 0; border-top: 1px solid #e2e8f0;">

        <p><strong>Name:</strong> {{ $inquiry->name }}</p>
        <p><strong>Email:</strong> {{ $inquiry->email }}</p>
        <p><strong>Phone:</strong> {{ $inquiry->phone ?: 'Not provided' }}</p>
        <p><strong>Company:</strong> {{ $inquiry->company ?: 'Not provided' }}</p>
        <p>
            <strong>Service Interested In:</strong>
            {{ $inquiry->service?->title ?: 'No service selected' }}
        </p>

        <p><strong>Message:</strong></p>

        <div style="padding: 16px; border-radius: 8px; background: #f8fafc;">
            {{ $inquiry->message }}
        </div>

        <hr style="margin: 24px 0; border: 0; border-top: 1px solid #e2e8f0;">

        <p style="font-size: 13px; color: #64748b;">
            Inquiry received on {{ $inquiry->created_at->format('F j, Y \a\t g:i A') }}.
        </p>
    </div>
</body>
</html>