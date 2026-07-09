<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $mailSubject }}</title>
</head>
<body style="margin:0;padding:0;background:#f5f5f5;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f5f5;padding:32px 16px;">
  <tr><td align="center">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.08);">
      <!-- Header -->
      <tr>
        <td style="background:#1a6e54;padding:28px 32px;">
          <p style="margin:0;color:#ffffff;font-size:22px;font-weight:700;letter-spacing:-0.3px;">SepoSale</p>
          <p style="margin:6px 0 0;color:rgba(255,255,255,0.75);font-size:13px;">{{ $heading }}</p>
        </td>
      </tr>
      <!-- Body -->
      <tr>
        <td style="padding:32px;">
          <p style="margin:0 0 24px;font-size:15px;color:#374151;white-space:pre-line;">{{ $body }}</p>

          @if($actionUrl && $actionLabel)
          <table cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
            <tr><td style="background:#1a6e54;border-radius:8px;">
              <a href="{{ $actionUrl }}"
                 style="display:inline-block;padding:12px 24px;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;">
                {{ $actionLabel }} →
              </a>
            </td></tr>
          </table>
          @endif

          <p style="margin:0;font-size:13px;color:#6b7280;">Questions? Email <a href="mailto:info@seposale.com" style="color:#1a6e54;">info@seposale.com</a> or call +265 888 69 99 77.</p>
        </td>
      </tr>
      <!-- Footer -->
      <tr>
        <td style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:20px 32px;text-align:center;">
          <p style="margin:0;font-size:12px;color:#9ca3af;">SepoSale Limited &bull; Area 47, Sector 4, Lilongwe, Malawi</p>
          <p style="margin:4px 0 0;font-size:12px;color:#9ca3af;">&copy; {{ date('Y') }} SepoSale. All rights reserved.</p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body>
</html>
