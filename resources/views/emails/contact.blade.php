<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Form Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0d9488;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #0d9488;
            margin-bottom: 5px;
        }
        .field-value {
            background-color: white;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #e5e5e5;
        }
        .message-box {
            background-color: white;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #e5e5e5;
            white-space: pre-wrap;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Contact Form Message</h2>
        <p>UNISSA Website Contact Form</p>
    </div>
    
    <div class="content">
        <div class="field">
            <div class="field-label">From:</div>
            <div class="field-value">{{ $contactName }} ({{ $contactEmail }})</div>
        </div>

        @if($contactSubject)
        <div class="field">
            <div class="field-label">Subject:</div>
            <div class="field-value">{{ $contactSubject }}</div>
        </div>
        @endif

        <div class="field">
            <div class="field-label">Message:</div>
            <div class="message-box">{{ $contactMessage }}</div>
        </div>

        <div class="field">
            <div class="field-label">Received:</div>
            <div class="field-value">{{ now()->format('F j, Y g:i A') }}</div>
        </div>
    </div>

    <div class="footer">
        <p>This message was sent from the UNISSA website contact form.</p>
        <p>You can reply directly to this email to respond to {{ $contactName }}.</p>
    </div>
</body>
</html>