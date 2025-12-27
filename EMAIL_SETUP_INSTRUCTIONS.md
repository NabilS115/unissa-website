# UNISSA Email Configuration Guide

## Current Setup (Testing)
- **Status**: Using Mailtrap for email testing
- **Sender Email**: admin.tijarah@unissa.edu.bn (appears in emails but safely captured in Mailtrap)
- **Testing**: All emails are captured in Mailtrap inbox for review

## Production Setup (Ready for Activation)

### Step 1: Get SMTP Settings from IT Department
Contact your IT department to get the following information for `admin.tijarah@unissa.edu.bn`:

- **SMTP Host**: (e.g., smtp.unissa.edu.bn or mail.unissa.edu.bn)
- **SMTP Port**: (usually 587 for TLS, 465 for SSL)
- **Username**: admin.tijarah@unissa.edu.bn
- **Password**: [Your institutional email password]
- **Encryption**: TLS or SSL

### Step 2: Update .env Configuration
In your `.env` file:

1. **Comment out** the Mailtrap section (lines starting with MAIL_)
2. **Uncomment** the Production Mail Settings section
3. **Update** the settings with values from Step 1:

```env
# Comment out Mailtrap (add # at beginning of each line)
# MAIL_MAILER=smtp
# MAIL_HOST=sandbox.smtp.mailtrap.io
# etc...

# Uncomment and configure Production settings (remove # from beginning)
MAIL_MAILER=smtp
MAIL_HOST=smtp.unissa.edu.bn
MAIL_PORT=587
MAIL_USERNAME=admin.tijarah@unissa.edu.bn
MAIL_PASSWORD=your_actual_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin.tijarah@unissa.edu.bn"
MAIL_FROM_NAME="UNISSA"
MAIL_ADMIN_EMAIL="admin.tijarah@unissa.edu.bn"
```

### Step 3: Test the Configuration
Run the application and test:
- Contact form submissions
- Order confirmation emails
- Order status change notifications

## Email Features in Application

### 1. Contact Form (`ContactFormMail.php`)
- Sends inquiry emails from website visitors
- Goes to admin email address

### 2. Order Confirmation (`OrderConfirmationMail.php`)
- Sent to customers after order placement
- Contains order details and confirmation number

### 3. Order Status Updates (`OrderStatusChangedMail.php`)
- Sent when order status changes (processing, completed, etc.)
- Keeps customers informed about their orders

## Common SMTP Settings for Educational Institutions

Most .edu.bn domains use similar settings:
- **Port**: 587 (TLS) or 465 (SSL)
- **Encryption**: TLS (preferred) or SSL
- **Authentication**: Required
- **Host**: Usually smtp.[domain] or mail.[domain]

## Troubleshooting

If emails don't send after switching to production:
1. Check firewall settings (port 587/465 must be open)
2. Verify username/password with IT department
3. Confirm encryption type (TLS vs SSL)
4. Check Laravel logs: `storage/logs/laravel.log`

## Fallback Option

If institutional SMTP doesn't work immediately, you can:
1. Use Gmail SMTP temporarily
2. Set up SendGrid/Mailgun free tier
3. Keep using Mailtrap for development

Contact IT support for assistance with institutional email configuration.