<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Unsubscribed - YieldEmpire</title>
<style>
  body { margin:0; padding:0; background:#eef2fb; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; }
  .wrap { max-width:520px; margin:64px auto; background:#fff; border-radius:16px; box-shadow:0 14px 38px rgba(11,31,77,0.14); overflow:hidden; }
  .band { background:linear-gradient(135deg,#0b1f4d 0%,#3b5bdb 55%,#15aabf 120%); padding:30px 40px; text-align:center; }
  .logo { color:#fff; font-weight:800; font-size:24px; letter-spacing:.5px; }
  .logo .accent { color:#15aabf; }
  .cell { padding:40px; text-align:center; }
  h1 { color:#0b1f4d; font-size:22px; margin:0 0 12px; }
  p { color:#475569; font-size:15px; line-height:1.6; margin:0 0 12px; }
  .muted { color:#94a3b8; font-size:13px; }
</style>
</head>
<body>
  <div class="wrap">
    <div class="band">
      <div class="logo">Yield<span class="accent">Empire</span></div>
    </div>
    <div class="cell">
      <h1>You've been unsubscribed</h1>
      <p>We've removed <strong style="color:#0b1f4d;">{{ $email }}</strong> from our marketing and notification mailing list.</p>
      <p class="muted">Account security emails (password resets, login codes, transaction alerts) will still be sent because they are required to keep your account safe.</p>
      <p class="muted">Changed your mind? Just sign in to your YieldEmpire dashboard and we'll start sending helpful updates again. If you need anything, email support@yieldempire.org.</p>
    </div>
  </div>
</body>
</html>
