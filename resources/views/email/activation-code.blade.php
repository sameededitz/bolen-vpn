<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=display-width, initial-scale=1.0, maximum-scale=1.0," />
    <title>Activation Code</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px 0;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
        }

        .content h2 {
            margin: 15px 0;
            text-align: center;
        }

        .content p {
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 0 0 8px 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Activation Code</h1>
        </div>
        <div class="content">
            <h2>Welcome to {{ config('app.name') }}!</h2>
            <p>Dear {{ $user->name }},</p>
            <p>Thank you for your purchase! Your activation code is:</p>
            <p><strong>{{ $activationCode }}</strong></p>
            <p>Use this code to activate your premium features. This code is valid until your plan expires.</p>
            <p>Best regards,</p>
            <p>{{ config('app.name') }} Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
