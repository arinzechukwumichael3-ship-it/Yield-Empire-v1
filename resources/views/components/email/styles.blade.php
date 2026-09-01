<style>
  /* ===== YieldEmpire email design system (shared by all emails) ===== */
  html, body { margin: 0 !important; padding: 0 !important; height: 100% !important; width: 100% !important; }
  * { -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; }

  body, body *:not(html):not(style):not(br):not(tr):not(code) {
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji';
    position: relative;
  }
  body {
    -webkit-text-size-adjust: none;
    background-color: #eef2fb;
    color: #475569;
    line-height: 1.5;
    width: 100% !important;
  }
  p, ul, ol, blockquote { line-height: 1.6; text-align: left; }
  a { color: #3b5bdb; text-decoration: none; }
  a img { border: none; }
  h1 { color: #0b1f4d; font-size: 20px; font-weight: 800; margin-top: 0; }
  h2 { font-size: 16px; font-weight: 700; margin-top: 0; color: #0b1f4d; }
  h3 { font-size: 14px; font-weight: 700; margin-top: 0; color: #0b1f4d; }
  p { font-size: 15px; line-height: 1.6; margin-top: 0; color: #475569; }
  img { max-width: 100%; border: 0; }

  /* Hidden preheader */
  .preheader {
    display: none !important;
    max-height: 0;
    overflow: hidden;
    mso-hide: all;
    font-size: 1px;
    line-height: 1px;
    color: #eef2fb;
    opacity: 0;
  }

  /* Layout */
  .wrapper { background-color: #eef2fb; margin: 0; padding: 0; width: 100%; }
  .content { margin: 0; padding: 0; width: 100%; }
  .body { background-color: #eef2fb; margin: 0; padding: 0; width: 100%; }
  .email-shell { width: 600px; max-width: 600px; margin: 0 auto; }
  .email-card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 14px 38px rgba(11, 31, 77, 0.14);
    background-color: #ffffff;
  }
  .inner-body { background-color: #ffffff; width: 600px; max-width: 600px; }
  .content-cell { padding: 36px 40px; max-width: 100vw; }

  /* Brand band / header */
  .brand-band {
    background: linear-gradient(135deg, #0b1f4d 0%, #3b5bdb 55%, #15aabf 120%);
    padding: 28px 40px;
    text-align: center;
  }
  .brand-logo {
    color: #ffffff;
    font-weight: 800;
    font-size: 24px;
    letter-spacing: .5px;
    font-family: -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    line-height: 1.1;
  }
  .brand-logo .accent { color: #15aabf; }
  .brand-tag {
    margin-top: 6px;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.70);
    font-family: Arial, Helvetica, sans-serif;
    letter-spacing: 1.5px;
    text-transform: uppercase;
  }

  /* Body typography helpers */
  .greeting { margin: 0 0 16px; color: #0b1f4d; font-size: 18px; font-weight: 700; }
  .lead { margin: 0 0 18px; color: #475569; font-size: 15px; line-height: 1.65; }
  .muted { color: #64748b; }

  /* Panels / callouts */
  .panel { margin: 20px 0; }
  .panel-content {
    background-color: #f1f5ff;
    border-left: 4px solid #3b5bdb;
    border-radius: 0 12px 12px 0;
    color: #334155;
    padding: 18px 20px;
  }
  .panel-content p { color: #334155; }
  .panel-item { padding: 0; }
  .panel-item p:last-of-type { margin-bottom: 0; padding-bottom: 0; }

  /* Highlighted code / OTP box */
  .otp-wrap { text-align: center; margin: 22px 0; letter-spacing: 12px; }
  .otp {
    display: inline-block;
    background: #f1f5ff;
    border: 1px solid #dbe3ff;
    border-radius: 10px;
    color: #0b1f4d;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: 6px;
    padding: 12px 22px;
    font-family: Consolas, Menlo, monospace;
  }
  .code-pill {
    display: inline-block;
    background: #0b1f4d;
    color: #ffffff;
    font-size: 22px;
    font-weight: 800;
    letter-spacing: 4px;
    padding: 12px 24px;
    border-radius: 10px;
    font-family: Consolas, Menlo, monospace;
  }

  /* Summary table (transaction / details) */
  .summary {
    width: 100%;
    max-width: 540px;
    margin: 22px 0;
    border-collapse: collapse;
    background: #f8faff;
    border: 1px solid #dbe3ff;
    border-radius: 14px;
    overflow: hidden;
  }
  .summary thead td {
    padding: 16px 20px;
    background: #0b1f4d;
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
  }
  .summary td {
    padding: 13px 18px;
    font-size: 14px;
    border-bottom: 1px solid #e8edf7;
  }
  .summary .label {
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
    font-weight: 700;
    font-size: 11px;
    width: 42%;
  }
  .summary .value {
    color: #0b1f4d;
    font-weight: 600;
    font-family: Consolas, Menlo, monospace;
    font-size: 15px;
  }

  /* Buttons */
  .action { -premailer-width: 100%; margin: 28px auto; text-align: center; width: 100%; }
  .button {
    -webkit-text-size-adjust: none;
    border-radius: 10px;
    color: #ffffff !important;
    display: inline-block;
    font-weight: 700;
    font-size: 15px;
    text-decoration: none;
    padding: 14px 32px;
  }
  .button-primary, .button-blue {
    background: linear-gradient(135deg, #3b5bdb, #5f3dc4);
    box-shadow: 0 10px 22px rgba(59, 91, 219, 0.35);
  }
  .button-success, .button-green {
    background: linear-gradient(135deg, #0ca678, #15aabf);
    box-shadow: 0 10px 22px rgba(21, 170, 191, 0.30);
  }
  .button-error, .button-red {
    background: linear-gradient(135deg, #e03131, #f76707);
    box-shadow: 0 10px 22px rgba(224, 49, 49, 0.28);
  }

  /* Subcopy */
  .subcopy { border-top: 1px solid #eef0f6; margin-top: 26px; padding-top: 22px; }
  .subcopy p { font-size: 13px; color: #94a3b8; }

  /* Footer */
  .footer { margin: 0 auto; padding: 0; text-align: center; width: 600px; max-width: 600px; }
  .footer p { color: #94a3b8; font-size: 12px; text-align: center; line-height: 1.6; }
  .footer a { color: #3b5bdb; text-decoration: underline; }
  .footer .footer-brand { color: #64748b; font-weight: 600; font-size: 13px; margin: 0 0 8px; }

  /* Tables component */
  .table table { -premailer-width: 100%; margin: 26px auto; width: 100%; }
  .table th { border-bottom: 1px solid #eef0f6; padding-bottom: 8px; color: #0b1f4d; }
  .table td { color: #475569; font-size: 14px; padding: 10px 0; }

  .break-all { word-break: break-all; }

  /* Responsive */
  @media only screen and (max-width: 600px) {
    .email-shell, .inner-body, .footer, .content-cell,
    .summary, .otp-wrap { width: 100% !important; }
    .content-cell { padding: 28px 22px !important; }
    .brand-band { padding: 22px 18px !important; }
  }

  /* Progressive enhancement only (content stays visible without it) */
  @media (prefers-reduced-motion: no-preference) {
    .email-card { animation: yeFadeUp .6s cubic-bezier(.2,.8,.2,1) both; }
    .brand-band { background-size: 220% 220%; animation: yeShimmer 7s ease infinite; }
  }
  @keyframes yeFadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes yeShimmer { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
</style>
