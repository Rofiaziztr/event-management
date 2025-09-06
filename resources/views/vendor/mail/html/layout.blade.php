<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
    {!! $head ?? '' !!}
</head>

<body style="font-family: 'Helvetica', Arial, sans-serif; background-color: #f4f4f7; color: #333; margin:0; padding:0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; margin:20px auto; border-radius:12px; overflow:hidden; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding:0;">
                            {{ $header ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px;">
                            {{ $slot }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0;">
                            {{ $footer ?? '' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>


</html>
