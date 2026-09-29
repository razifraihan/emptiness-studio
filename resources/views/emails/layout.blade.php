<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
</head>
<body style="margin:0;padding:0;background:#ffffff;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#ffffff;">
    <tr>
        <td align="center" style="padding:40px 20px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                   style="max-width:560px;font-family:Helvetica,Arial,sans-serif;color:#171717;">
                <tr>
                    <td style="padding-bottom:32px;font-size:13px;font-weight:600;letter-spacing:0.5px;">
                        Emptiness Studio
                    </td>
                </tr>
                <tr>
                    <td>@yield('content')</td>
                </tr>
                <tr>
                    <td style="padding-top:48px;font-size:12px;line-height:1.6;color:#737373;">
                        Creating meaning in the space between.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>