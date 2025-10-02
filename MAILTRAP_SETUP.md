# Mailtrap Setup Guide for UNISSA Contact Form

## 1. Create Mailtrap Account
1. Go to [https://mailtrap.io/](https://mailtrap.io/)
2. Sign up for a free account
3. Verify your email address

## 2. Get SMTP Credentials
1. Log into your Mailtrap dashboard
2. Go to "Email Testing" > "Inboxes"
3. Create a new inbox or use the default one
4. Click on your inbox name
5. Go to "SMTP Settings" tab
6. Select "Laravel 9+" from the integrations dropdown
7. Copy the credentials shown

## 3. Update Your .env File
Create or update your `.env` file with the Mailtrap credentials:

```bash
# Mail Configuration - Mailtrap for testing
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_actual_mailtrap_username
MAIL_PASSWORD=your_actual_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@unissa.com"
MAIL_FROM_NAME="UNISSA"
MAIL_ADMIN_EMAIL="admin@unissa.com"
```

**Important:** Replace `your_actual_mailtrap_username` and `your_actual_mailtrap_password` with the actual credentials from your Mailtrap inbox.

## 4. Clear Laravel Cache
Run these commands to ensure Laravel picks up the new configuration:

```bash
php artisan config:clear
php artisan cache:clear
```

## 5. Test the Contact Form
1. Go to your website's contact page: `http://your-domain.com/contact`
2. Fill out the contact form with test data
3. Submit the form
4. Check your Mailtrap inbox for the email

## 6. View Test Emails
In your Mailtrap dashboard:
1. Go to your inbox
2. Click on the received email to view it
3. You can see the HTML and text versions
4. Check headers, spam score, and other details

## 7. Troubleshooting
If emails aren't appearing in Mailtrap:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify your credentials are correct
3. Ensure `MAIL_MAILER=smtp` (not `log`)
4. Clear cache again: `php artisan config:clear`

## 8. Production Setup
When ready for production:
1. Update `.env` with your actual SMTP provider
2. Change `MAIL_ADMIN_EMAIL` to your real admin email
3. Update `MAIL_FROM_ADDRESS` to your domain email

## Features Tested
✅ Contact form submission
✅ Email validation and formatting
✅ HTML email template
✅ Reply-to functionality
✅ Error handling and success messages