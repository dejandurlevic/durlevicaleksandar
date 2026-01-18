<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Registration Invitation</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e5e7eb; border-top: none;">
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <p style="margin: 10px 0; color: #4b5563;">Dear {{ $name }},</p>
            <p style="margin: 10px 0; color: #4b5563;">Thank you for your interest in our fitness platform! Your registration request for the <strong style="color: #1f2937;">{{ $plan }}</strong> plan has been approved.</p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            <p style="margin: 10px 0 20px 0; color: #4b5563;">Click the button below to complete your registration:</p>
            <a href="{{ $registerUrl }}" style="display: inline-block; background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%); color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px;">Complete Registration</a>
        </div>
        
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
            <p style="margin: 0; color: #856404; font-size: 14px;"><strong>Note:</strong> This invitation link will expire in 7 days. If you have any questions, please contact us.</p>
        </div>
        
        <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <p style="margin: 0; color: #6b7280; font-size: 12px;"><strong>Registration Token:</strong></p>
            <p style="margin: 5px 0 0 0; color: #1f2937; font-family: monospace; word-break: break-all; font-size: 12px;">{{ $token }}</p>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 12px;">
            <p style="margin: 0;">This invitation was sent from the FitCoachAleksandar platform.</p>
        </div>
    </div>
</body>
</html>

