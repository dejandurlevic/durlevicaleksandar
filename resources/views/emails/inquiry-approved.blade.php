<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Welcome to FitCoachAleksandar!</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e5e7eb; border-top: none;">
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 20px;">
            Hi {{ $name }},
        </p>
        
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 20px;">
            Great news! Your inquiry for the <strong>{{ $plan }}</strong> has been approved.
        </p>
        
        <p style="color: #4b5563; font-size: 16px; margin-bottom: 30px;">
            Click the button below to complete your registration and get started:
        </p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $registrationUrl }}" style="display: inline-block; background: linear-gradient(135deg, #1f2937 0%, #111827 100%); color: #ffffff; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">
                Complete Registration
            </a>
        </div>
        
        <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
            Or copy and paste this link into your browser:<br>
            <a href="{{ $registrationUrl }}" style="color: #1f2937; word-break: break-all;">{{ $registrationUrl }}</a>
        </p>
        
        <p style="color: #6b7280; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            This invitation link will expire in 7 days. If you have any questions, please contact us.
        </p>
    </div>
</body>
</html>

