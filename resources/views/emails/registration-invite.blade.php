<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Invitation - FitCoachAleksandar</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Welcome to FitCoachAleksandar!</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e5e7eb; border-top: none;">
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <p style="margin: 10px 0; color: #1f2937; font-size: 16px;">Hello {{ $name }},</p>
            <p style="margin: 10px 0; color: #4b5563;">Great news! Your subscription inquiry for <strong style="color: #1f2937;">{{ $plan }}</strong> has been approved!</p>
            <p style="margin: 10px 0; color: #4b5563;">You can now create your account and start your fitness journey with us.</p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px; text-align: center;">
            <a href="{{ $registrationUrl }}" 
               style="display: inline-block; background: linear-gradient(135deg, #1f2937 0%, #111827 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; margin: 10px 0;">
                Complete Registration
            </a>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
            <p style="margin: 10px 0; color: #4b5563; font-size: 14px;">
                <strong style="color: #1f2937;">Important:</strong> This registration link is valid for 7 days. If you have any issues, please contact us.
            </p>
            <p style="margin: 10px 0; color: #6b7280; font-size: 12px; word-break: break-all;">
                Or copy this link: <br>
                <span style="color: #4b5563;">{{ $registrationUrl }}</span>
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="margin: 5px 0; color: #6b7280; font-size: 12px;">© {{ date('Y') }} FitCoachAleksandar. All rights reserved.</p>
        </div>
    </div>
</body>
</html>












