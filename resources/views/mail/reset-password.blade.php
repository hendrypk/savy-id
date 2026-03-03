<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Reset Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body style="margin:0; padding:0; background-color:#f4f6f9; font-family:Arial, sans-serif;">

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding:20px 0;">
        <tr>
        <td align="center">

        <table width="100%" cellpadding="0" cellspacing="0"
            style="max-width:600px; background:#ffffff; border-radius:8px; overflow:hidden;">

            <!-- LOGO -->
            <tr>
                <td align="center" style="padding:30px 20px 10px 20px;">
                    <img src="{{ asset('landsacape-light.png') }}" 
                            alt="{{ config('app.name') }}" 
                            width="120"
                            style="display:block; max-width:120px; height:auto;">
                </td>
            </tr>

            <!-- TITLE -->
            <tr>
                <td align="center" style="padding:10px 30px;">
                    <h2 style="margin:0; color:#333;">
                        Reset Your Password
                    </h2>
                </td>
            </tr>

            <!-- CONTENT -->
            <tr>
                <td style="padding:20px 30px; color:#555; font-size:15px; line-height:1.6;">

                    <p>Hello <strong>{{ $user->name ?? 'User' }}</strong>,</p>

                    <p>
                        We received a request to reset your password. Click the button below to create a new password.
                    </p>

                    <!-- BUTTON -->
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin:30px 0;">
                        <tr>
                            <td align="center">
                                <a href="{{ $url }}"
                                style="background-color:#ef4444;
                                        color:#ffffff;
                                        padding:14px 28px;
                                        text-decoration:none;
                                        border-radius:6px;
                                        display:inline-block;
                                        font-weight:bold;
                                        font-size:14px;">
                                    Reset Password
                                </a>
                            </td>
                        </tr>
                    </table>

                    <p style="font-size:14px;">
                        This password reset link will expire in 60 minutes.
                    </p>

                    <p style="font-size:14px;">
                        If you did not request a password reset, no further action is required.
                    </p>

                </td>
            </tr>

            <!-- FOOTER -->
            <tr>
                <td align="center"
                    style="padding:20px; font-size:12px; color:#999; border-top:1px solid #eeeeee;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </td>
            </tr>

        </table>

        </td>
        </tr>
        </table>

    </body>
</html>