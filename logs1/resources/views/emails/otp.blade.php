<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OTP Verification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #16a34a; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .otp-code { 
            font-size: 32px; 
            font-weight: bold; 
            text-align: center; 
            letter-spacing: 5px; 
            color: #16a34a;
            margin: 20px 0;
        }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $brand ?? 'Microfinancial Logistics I' }}</h1>
            <p>OTP Verification Code</p>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $name }}</strong>,</p>
            
            <p>You are attempting to log in to your {{ $brand ?? 'Microfinancial Logistics I' }} account. 
               Please use the following One-Time Password (OTP) to complete your login:</p>
            
            <div class="otp-code">{{ $otp }}</div>
            
            <p>This OTP will expire in <strong>{{ $expires_in }} minutes</strong>.</p>
            
            <p>If you did not request this OTP, please ignore this email or contact system administrator.</p>
            
            <p>Best regards,<br>
            {{ $brand ?? 'Microfinancial Logistics I' }} Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>