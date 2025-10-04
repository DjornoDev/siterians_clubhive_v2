<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Password Reset</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f7fa; line-height: 1.6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f5f7fa; padding: 40px 20px;">
        <tr>
            <td style="text-align: center;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); padding: 50px 40px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0; letter-spacing: -0.5px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ config('app.name') }}</h1>
                            <p style="color: #bfdbfe; font-size: 16px; margin: 12px 0 0 0; font-weight: 400; opacity: 0.9;">Student Club Management System</p>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 50px 40px;">
                            <!-- Greeting -->
                            <h2 style="font-size: 28px; font-weight: 700; color: #1a202c; margin-bottom: 24px; margin-top: 0; letter-spacing: -0.5px;">
                                @if (! empty($greeting))
                                    {{ $greeting }}
                                @else
                                    @if ($level === 'error')
                                        Security Alert
                                    @else
                                        Password Reset Request
                                    @endif
                                @endif
                            </h2>

                            <!-- Main Message -->
                            <p style="font-size: 18px; color: #2d3748; margin-bottom: 28px; line-height: 1.7; font-weight: 400;">
                                You are receiving this email because we received a password reset request for your account.
                            </p>

                            <!-- Security Notice -->
                            <table role="presentation" style="width: 100%; margin: 28px 0;">
                                <tr>
                                    <td style="background-color: #fef7e7; border-left: 5px solid #f59e0b; padding: 20px; border-radius: 0 8px 8px 0; border: 1px solid #fed7aa;">
                                        <p style="font-size: 16px; color: #92400e; margin: 0; line-height: 1.6; font-weight: 500;">
                                            ⚠️ For your security, this link will expire in 60 minutes. If you did not request this password reset, please ignore this email.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Action Button -->
                            @isset($actionText)
                                <table role="presentation" style="width: 100%; margin: 36px 0;">
                                    <tr>
                                        <td style="text-align: center;">
                                            <a href="{{ $actionUrl }}" style="display: inline-block; background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 18px 36px; border-radius: 10px; font-weight: 600; font-size: 18px; text-align: center; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3); transition: all 0.2s ease;">
                                                {{ $actionText }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endisset

                            <!-- Additional Info -->
                            <p style="font-size: 16px; color: #4a5568; margin-top: 28px; line-height: 1.6; font-weight: 400;">
                                If you did not request a password reset, no further action is required.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 40px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="font-size: 16px; color: #4a5568; margin-bottom: 12px; font-weight: 500;">
                                @if (! empty($salutation))
                                    {{ $salutation }}
                                @else
                                    Best regards,<br>
                                    <strong style="color: #2d3748;">{{ config('app.name') }} Team</strong>
                                @endif
                            </p>
                            <p style="font-size: 14px; color: #718096; margin: 0; line-height: 1.5;">
                                This is an automated message. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                    <!-- Subcopy -->
                    @isset($actionText)
                        <tr>
                            <td style="padding: 30px 40px;">
                                <table role="presentation" style="width: 100%; background-color: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                                    <tr>
                                        <td style="padding: 24px;">
                                            <p style="font-size: 14px; color: #4a5568; margin: 0 0 12px 0; line-height: 1.5; font-weight: 500;">
                                                If you're having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below into your web browser:
                                            </p>
                                            <p style="font-size: 13px; margin: 0; word-break: break-all; line-height: 1.4;">
                                                <a href="{{ $actionUrl }}" style="color: #2563eb; text-decoration: underline;">{{ $displayableActionUrl }}</a>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endisset
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
