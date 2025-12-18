<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Subscription Inquiry</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">New Subscription Inquiry</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e5e7eb; border-top: none;">
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <p style="margin: 10px 0;"><strong style="color: #1f2937;">Inquiry ID:</strong> <span style="color: #4b5563;">#{{ $inquiryId ?? 'N/A' }}</span></p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <p style="margin: 10px 0;"><strong style="color: #1f2937;">Name:</strong> <span style="color: #4b5563;">{{ $name ?? 'Not provided' }}</span></p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <p style="margin: 10px 0;"><strong style="color: #1f2937;">Email:</strong> <span style="color: #4b5563;">{{ $email }}</span></p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <p style="margin: 10px 0;"><strong style="color: #1f2937;">Phone:</strong> <span style="color: #4b5563;">{{ $phone }}</span></p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <p style="margin: 10px 0;"><strong style="color: #1f2937;">Selected Plan:</strong> <span style="color: #4b5563; font-weight: 600;">{{ $plan }}</span></p>
        </div>
        
        @if(!empty($inquiryMessage))
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <p style="margin: 0 0 10px 0;"><strong style="color: #1f2937;">Message:</strong></p>
            <p style="margin: 0; color: #4b5563; white-space: pre-wrap;">{{ $inquiryMessage }}</p>
        </div>
        @endif
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 12px;">
            <p style="margin: 0;">This inquiry was submitted from the FitCoachAleksandar platform.</p>
            <p style="margin: 5px 0 0 0;">Review and approve at: <a href="{{ url('/admin/inquiries') }}" style="color: #1f2937;">Admin Panel</a></p>
        </div>
    </div>
</body>
</html>




