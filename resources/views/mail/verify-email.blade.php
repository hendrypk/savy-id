<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
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
                            <h2 style="margin:0; color:#333333;">
                                Verify Your Email Address
                            </h2>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding:20px 30px; color:#555555; font-size:15px; line-height:1.6;">

                            <p style="margin-top:0;">
                                Hello <strong>{{ $user->name ?? 'User' }}</strong>,
                            </p>

                            <p>
                                Thank you for registering. Please click the button below to verify your email address.
                            </p>

                            <!-- BUTTON -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}"
                                           style="background-color:#4f46e5;
                                                  color:#ffffff;
                                                  padding:14px 28px;
                                                  text-decoration:none;
                                                  border-radius:6px;
                                                  display:inline-block;
                                                  font-weight:bold;
                                                  font-size:14px;">
                                            Verify Email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p>
                                If you did not create an account, no further action is required.
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td align="center" 
                            style="padding:20px; font-size:12px; color:#999999; border-top:1px solid #eeeeee;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>