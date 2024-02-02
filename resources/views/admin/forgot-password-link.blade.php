<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Motion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 10px 0 10px 0;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600"
                    style="border: 1px solid #cccccc; border-collapse: collapse;">
                    <tr></tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 15px 30px 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center"
                                        style="color: rgba(204, 146, 139, 1); font-family: serif; font-size: 24px;">
                                        <b>Forgot your password</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 20px 0 10px 0; color: #153643; font-family: serif; font-size: 16px; line-height: 20px;">
                                        <b>Hi ,{{ $name }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 20px 0 10px 0; color: #153643; font-family: serif; font-size: 16px; line-height: 20px;">
                                        Your recently requested to reset your password for your Motion account.
                                        Click the button below to reset it.
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center"
                                        style="
                                                padding: 10px 35px;
                                                color: #fff;
                                                font-family: sen-serif;
                                                font-size: 16px;
                                                line-height: 20px;
                                                background: rgba(0, 55, 95, 1);
                                                color: #fff;
                                                margin: 0px auto 10px;
                                                display: block;
                                                width: 200px;
                                                border-radius: 8px;
                                            ">
                                        <a style="color: white;" href="{{ $link }}">Reset your password</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 10px 0 20px 0; color: #153643; font-family: serif; font-size: 16px; line-height: 20px; border-bottom: 1px dotted;">
                                        If you did not request a password reset, please ignore this email
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
