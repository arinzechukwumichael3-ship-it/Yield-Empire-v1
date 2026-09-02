<style>
  /* ===== YieldEmpire Professional Email Design System ===== */
  html, body { margin: 0 !important; padding: 0 !important; height: 100% !important; width: 100% !important; }
  * { -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; }

  body, body *:not(html):not(style):not(br):not(tr):not(code) {
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    position: relative;
  }
  body {
    -webkit-text-size-adjust: none;
    background-color: #f4f6fb;
    color: #334155;
    line-height: 1.6;
    width: 100% !important;
  }
  p, ul, ol, blockquote { line-height: 1.6; text-align: left; margin: 0 0 16px; }
  a { color: #2563eb; text-decoration: none; }
  a:hover { text-decoration: underline; }
  a img { border: none; }
  h1 { color: #0f172a; font-size: 22px; font-weight: 800; margin: 0 0 16px; letter-spacing: -0.3px; }
  h2 { font-size: 17px; font-weight: 700; margin: 0 0 12px; color: #0f172a; }
  h3 { font-size: 14px; font-weight: 700; margin: 0 0 8px; color: #0f172a; }
  p { font-size: 15px; line-height: 1.65; margin: 0 0 16px; color: #475569; }
  img { max-width: 100%; border: 0; }
  strong { color: #1e293b; }

  /* Preheader */
  .preheader {
    display: none !important;
    max-height: 0;
    overflow: hidden;
    mso-hide: all;
    font-size: 1px;
    line-height: 1px;
    color: #f4f6fb;
    opacity: 0;
  }

  /* Layout */
  .wrapper { background-color: #f4f6fb; margin: 0; padding: 0; width: 100%; }
  .content { margin: 0; padding: 0; width: 100%; }
  .body { background-color: #f4f6fb; margin: 0; padding: 0; width: 100%; }
  .email-shell { width: 600px; max-width: 600px; margin: 0 auto; }
  .email-card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08);
    background-color: #ffffff;
  }
  .inner-body { background-color: #ffffff; width: 600px; max-width: 600px; }
  .content-cell { padding: 40px 44px; max-width: 100vw; }

  /* Header */
  .email-header {
    background: #0f172a;
    padding: 28px 44px;
    text-align: center;
  }
  .email-header-logo {
    color: #ffffff;
    font-weight: 800;
    font-size: 22px;
    letter-spacing: -0.3px;
    line-height: 1.2;
  }
  .email-header-logo .accent { color: #06b6d4; }
  .email-header-tag {
    margin-top: 4px;
    font-size: 11px;
    color: rgba(255, 255, 255, 0.55);
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 600;
  }

  /* Body */
  .greeting { margin: 0 0 12px; color: #0f172a; font-size: 18px; font-weight: 700; }
  .lead { margin: 0 0 16px; color: #475569; font-size: 15px; line-height: 1.65; }
  .muted { color: #64748b; font-size: 13px; }
  .small { font-size: 12px; }

  /* OTP Code */
  .otp-container {
    text-align: center;
    margin: 28px 0;
  }
  .otp-box {
    display: inline-block;
    background: #0f172a;
    color: #ffffff;
    font-size: 28px;
    font-weight: 700;
    letter-spacing: 8px;
    padding: 18px 32px;
    border-radius: 10px;
    font-family: 'SF Mono', Consolas, Menlo, monospace;
  }
  .otp-digits {
    display: inline-block;
    margin: 0 4px;
  }
  .otp-digit {
    display: inline-block;
    width: 44px;
    height: 52px;
    line-height: 52px;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    color: #0f172a;
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    margin: 0 3px;
    font-family: 'SF Mono', Consolas, Menlo, monospace;
  }

  /* Info table */
  .info-table {
    width: 100%;
    max-width: 520px;
    margin: 24px 0;
    border-collapse: collapse;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
  }
  .info-table th {
    padding: 14px 20px;
    background: #0f172a;
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    text-align: left;
  }
  .info-table td {
    padding: 12px 20px;
    font-size: 14px;
    border-bottom: 1px solid #e2e8f0;
  }
  .info-table tr:last-child td { border-bottom: none; }
  .info-table .label {
    color: #64748b;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    width: 40%;
  }
  .info-table .value {
    color: #0f172a;
    font-weight: 600;
    font-family: 'SF Mono', Consolas, Menlo, monospace;
    font-size: 13px;
  }

  /* Button */
  .btn {
    display: inline-block;
    background: #0f172a;
    color: #ffffff !important;
    font-size: 14px;
    font-weight: 600;
    padding: 14px 28px;
    border-radius: 8px;
    text-decoration: none;
    margin: 16px 0;
  }
  .btn:hover { background: #1e293b; text-decoration: none; }

  /* Footer */
  .email-footer {
    padding: 24px 44px;
    text-align: center;
    border-top: 1px solid #e2e8f0;
  }
  .email-footer p {
    margin: 0 0 8px;
    font-size: 12px;
    color: #94a3b8;
    line-height: 1.6;
  }
  .email-footer a { color: #64748b; }
  .email-footer .brand { font-weight: 600; color: #475569; }

  /* Divider */
  .divider {
    height: 1px;
    background: #e2e8f0;
    margin: 24px 0;
    border: none;
  }

  /* Alert box */
  .alert {
    background: #fef3c7;
    border: 1px solid #fcd34d;
    border-radius: 8px;
    padding: 14px 18px;
    margin: 20px 0;
  }
  .alert p { margin: 0; color: #92400e; font-size: 13px; }

  /* Success box */
  .success {
    background: #dcfce7;
    border: 1px solid #86efac;
    border-radius: 8px;
    padding: 14px 18px;
    margin: 20px 0;
  }
  .success p { margin: 0; color: #166534; font-size: 13px; }

  /* Responsive */
  @media only screen and (max-width: 600px) {
    .email-shell, .inner-body, .content-cell { width: 100% !important; }
    .content-cell { padding: 28px 20px !important; }
    .email-header { padding: 24px 20px !important; }
    .otp-box { font-size: 22px; letter-spacing: 4px; padding: 14px 20px; }
    .otp-digit { width: 36px; height: 44px; line-height: 44px; font-size: 20px; }
    .info-table { font-size: 13px; }
  }
</style>
