<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use App\Models\Transaction;
use App\Models\Portfolio;
use App\Models\PortfolioHolding;
use App\Models\InvestmentAsset;
use App\Models\Frontend\AnnouncementCategory;
use App\Models\Admin\Currency;
use App\Models\Admin\SiteSections;
use App\Models\Frontend\Announcement as FrontendAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Collection;

class RiseController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function home()
    {
        $page_title = "Home";
        $user = $this->user;
        $wallet = UserWallet::auth()->first();
        $usd_wallet = UserWallet::auth()->whereHas('currency', fn($q) => $q->where('code', 'USD'))->first();
        $gbp_wallet = UserWallet::auth()->whereHas('currency', fn($q) => $q->where('code', 'GBP'))->first();
        $eur_wallet = UserWallet::auth()->whereHas('currency', fn($q) => $q->where('code', 'EUR'))->first();

        $transactions = Transaction::auth()->orderByDesc("id")->latest()->take(5)->get();

        $investment_plans = collect([]);
        $portfolio = Portfolio::auth()->first();

        $sections = SiteSections::get();

        $banner = $sections->where('key', 'banner-section')->first();
        $testimonials = $sections->where('key', 'client-feedback-section')->first();

        return view('user.rise.home', compact(
            'page_title', 'user', 'wallet', 'usd_wallet', 'gbp_wallet', 'eur_wallet',
            'transactions', 'investment_plans', 'portfolio', 'banner', 'testimonials'
        ));
    }

    public function invest()
    {
        $page_title = "Invest";
        $user = $this->user;
        $portfolio = Portfolio::auth()->first();
        $holdings = $portfolio ? PortfolioHolding::where('portfolio_id', $portfolio->id)->get() : collect([]);
        $assets = InvestmentAsset::all();

        return view('user.rise.invest', compact('page_title', 'user', 'portfolio', 'holdings', 'assets'));
    }

    public function wallet()
    {
        $page_title = "Wallet";
        $user = $this->user;
        $usd_wallet = UserWallet::auth()->whereHas('currency', fn($q) => $q->where('code', 'USD'))->first();
        $gbp_wallet = UserWallet::auth()->whereHas('currency', fn($q) => $q->where('code', 'GBP'))->first();
        $transactions = Transaction::auth()->orderByDesc("id")->latest()->take(10)->get();

        return view('user.rise.wallet', compact('page_title', 'user', 'usd_wallet', 'gbp_wallet', 'transactions'));
    }

    public function feed()
    {
        $page_title = "Feed";

        // Use static articles for richer content with real images
        $articles = $this->getStaticArticles();

        $categories = AnnouncementCategory::all();

        return view('user.rise.feed', compact('page_title', 'articles', 'categories'));
    }

    public function articleDetail($slug)
    {
        $page_title = "Article";

        // Try static articles first
        $article = $this->getStaticArticles()->firstWhere('slug', $slug);
        if ($article) {
            return view('user.rise.article-detail', compact('page_title', 'article'));
        }

        // Try database as fallback
        $dbArticle = FrontendAnnouncement::with('category')->where('slug', $slug)->first();
        if ($dbArticle) {
            $article = $this->normalizeArticleForFeed($dbArticle);
            return view('user.rise.article-detail', compact('page_title', 'article'));
        }

        // Not found - redirect back to feed
        return redirect()->route('user.rise.feed')->with(['error' => ['Article not found.']]);
    }

    public function account()
    {
        $page_title = "Account";
        $user = $this->user;

        return view('user.rise.account', compact('page_title', 'user'));
    }

    public function refer()
    {
        $page_title = "Refer & Earn";
        $user = $this->user;
        $wallet = UserWallet::auth()->first();
        $usd_wallet = UserWallet::auth()->whereHas('currency', fn($q) => $q->where('code', 'USD'))->first();

        $usd_balance = $usd_wallet ? $usd_wallet->balance : 0;

        // Count referrals (users who registered with this user's ID)
        $referral_count = \App\Models\User::where('referral_id', $user->id)->count();

        // Total referral earnings from transactions
        $referral_earnings = Transaction::where('user_id', $user->id)
            ->where('type', PaymentGatewayConst::TYPEADDMONEY)
            ->where('remark', 'referral')
            ->sum('request_amount');

        return view('user.rise.refer', compact(
            'page_title', 'user', 'wallet', 'usd_wallet', 'usd_balance',
            'referral_count', 'referral_earnings'
        ));
    }

    private function normalizeArticleForFeed($article)
    {
        $data = $article->data;

        // Handle language-nested data structure if present
        $description = $data->description ?? '';
        if (empty($description) && isset($data->language->en->description)) {
            $description = $data->language->en->description;
        }

        $title = $article->title ?? '';
        if (empty($title) && isset($data->language->en->title)) {
            $title = $data->language->en->title;
        }

        // Handle category name (could be object with language structure)
        $catName = 'General';
        if ($article->category) {
            $cn = $article->category->name;
            if (is_string($cn)) {
                $catName = $cn;
            } elseif (is_object($cn) && isset($cn->language->en->name)) {
                $catName = $cn->language->en->name;
            }
        }

        return (object)[
            'title' => $title,
            'slug' => $article->slug,
            'data' => (object)[
                'description' => $description,
                'thumb_gradient' => $data->thumb_gradient ?? 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                'thumb_icon' => $data->thumb_icon ?? 'default',
                'thumb_url' => $data->thumb_url ?? null,
            ],
            'category' => (object)['name' => $catName],
            'created_at' => $article->created_at,
        ];
    }

    private function getStaticArticles(): Collection
    {
        return collect([
            // === COMPANY UPDATES ===
            (object)[
                'title' => 'EnzoBank Launches Premium Credit Cards with Unlimited Rewards',
                'slug' => 'enzobank-premium-credit-cards',
                'data' => (object)[
                    'description' => "EnzoBank is proud to announce the launch of our Premium Credit Card line, offering unlimited cashback rewards, zero foreign transaction fees, and exclusive airport lounge access worldwide. Available to qualifying users starting July 2026, the card features a titanium build, contactless payment, and integrated digital wallet support. Apply directly from your EnzoBank dashboard and get a decision in under 60 seconds.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-14'),
            ],
            (object)[
                'title' => 'EnzoBank Reaches 2 Million Active Users Milestone',
                'slug' => 'enzobank-2-million-users',
                'data' => (object)[
                    'description' => "We're thrilled to announce that EnzoBank has officially crossed 2 million active users worldwide. This milestone reflects the trust our customers place in us and our commitment to delivering best-in-class digital banking services. From our headquarters to our global operations, every team member shares in this achievement. Here's to the next 2 million — with even more innovative features on the horizon.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0F172A, #1E293B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-12'),
            ],
            (object)[
                'title' => 'New Mobile App Update: Biometric Login & Dark Mode',
                'slug' => 'enzobank-app-update-july',
                'data' => (object)[
                    'description' => "The latest EnzoBank mobile app update (v3.2) brings biometric fingerprint and face ID login, system-wide dark mode, and real-time push notifications for all transactions. The update also includes performance improvements making the app 40% faster on older devices. Available now on iOS App Store and Google Play Store. Update today to experience the new features.",
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #7C3AED)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-10'),
            ],
            (object)[
                'title' => 'EnzoBank Partners with Stripe for Enhanced Payment Processing',
                'slug' => 'enzobank-stripe-partnership',
                'data' => (object)[
                    'description' => "EnzoBank has entered a strategic partnership with Stripe to power next-generation payment processing for our business customers. The integration enables real-time settlement, recurring billing, and multi-currency support for over 135 currencies. Business accounts can now connect their Stripe dashboard directly to EnzoBank for seamless fund flows and automated reconciliation.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #0891B2)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://images.unsplash.com/photo-1553729459-afe8f2e2a275?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-08'),
            ],
            (object)[
                'title' => 'Introducing EnzoBank Virtual Cards: Create Yours Free Today',
                'slug' => 'enzobank-virtual-cards',
                'data' => (object)[
                    'description' => "We're excited to launch virtual cards for all EnzoBank users. Create unlimited cards instantly for online shopping with military-grade security. Each card comes with a unique CVV and card number, and you can set spending limits per card. Start your free virtual card today and experience the future of digital payments with EnzoBank.",
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-05'),
            ],

            // === PORTFOLIO REPORTS ===
            (object)[
                'title' => 'Q2 2026 Portfolio Review: Technology Sector Leads with 18% Gains',
                'slug' => 'q2-2026-portfolio-review',
                'data' => (object)[
                    'description' => "Our Q2 2026 portfolio analysis reveals technology stocks as the top-performing sector with 18.3% gains, followed by healthcare at 12.1% and renewable energy at 9.8%. The EnzoBank Balanced Growth Fund outperformed its benchmark by 3.2 percentage points. We recommend maintaining overweight positions in AI and cloud computing infrastructure for H2 2026. Download the full report from your portfolio dashboard.",
                    'thumb_gradient' => 'linear-gradient(135deg, #059669, #10B981)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-07-13'),
            ],
            (object)[
                'title' => 'How to Grow Your Wealth with EnzoBank Investment Plans',
                'slug' => 'enzobank-investment-plans',
                'data' => (object)[
                    'description' => "Our investment plans offer competitive returns starting from 15% ROI. Here's how to choose the right plan for your goals. Whether you're a beginner investor or a seasoned pro, EnzoBank has a plan that fits your risk tolerance and financial objectives. Start investing today and watch your wealth grow with our professionally managed portfolios.",
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-07-11'),
            ],
            (object)[
                'title' => 'Bond Market Outlook: Opportunities in Emerging Market Debt',
                'slug' => 'bond-market-outlook-july-2026',
                'data' => (object)[
                    'description' => "Our fixed-income team sees attractive opportunities in emerging market sovereign bonds, particularly in Asia and Latin America. With yields averaging 6.8% and improving credit fundamentals, EM debt offers compelling risk-adjusted returns. We recommend a 15-20% allocation to EM bonds within the fixed-income portion of your portfolio. The full analysis is available in your investment dashboard.",
                    'thumb_gradient' => 'linear-gradient(135deg, #D97706, #F59E0B)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-07-09'),
            ],
            (object)[
                'title' => 'Dividend Growth Strategy: Top 10 Stocks for July 2026',
                'slug' => 'dividend-growth-stocks-july-2026',
                'data' => (object)[
                    'description' => "Our dividend growth strategy for July 2026 highlights ten stocks with consistent dividend increases over the past five years. The portfolio yields an average of 3.4% with projected dividend growth of 8-12% annually. Top picks include Microsoft, Johnson & Johnson, Procter & Gamble, and new additions in the renewable energy sector. Full analysis with entry prices available in the EnzoBank Research Center.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E40AF, #3B82F6)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-07-07'),
            ],

            // === MARKET UPDATES ===
            (object)[
                'title' => 'Federal Reserve Holds Rates Steady — What It Means for Investors',
                'slug' => 'federal-reserve-july-2026-rates',
                'data' => (object)[
                    'description' => "The Federal Reserve announced it will hold interest rates at current levels following its July meeting, citing moderate economic growth and cooling inflation. The decision was widely expected by markets. For investors, the steady rate environment supports continued equity market strength, particularly in growth and technology sectors. Bond yields may see modest declines as the market prices in potential rate cuts later this year.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #475569)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1611122281389-4c0f3c7e7e1e?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-14'),
            ],
            (object)[
                'title' => 'S&P 500 Hits New All-Time High — Tech Stocks Surge',
                'slug' => 'sp500-all-time-high-july-2026',
                'data' => (object)[
                    'description' => "The S&P 500 reached a new all-time high on Wednesday, closing at 6,847.23, driven by strong earnings reports from major technology companies. The Nasdaq Composite surged 2.3% as AI-related stocks continued their rally. Market breadth improved with advancing stocks outpacing decliners by a 3:1 ratio. Analysts cite robust corporate earnings and optimistic forward guidance as key catalysts for the rally.",
                    'thumb_gradient' => 'linear-gradient(135deg, #059669, #34D399)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-13'),
            ],
            (object)[
                'title' => 'Global Payments Now Available: Send Money to 150+ Countries',
                'slug' => 'global-payments-now-available',
                'data' => (object)[
                    'description' => "EnzoBank now supports SWIFT, ACH, and SEPA transfers. Send money worldwide in seconds with zero delays. Our global payment network covers 150+ countries with competitive exchange rates and low fees. Whether for business or personal use, sending money abroad has never been easier. Instant transfers are available to 45 countries with same-day settlement.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0891B2, #06B6D4)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-06'),
            ],
            (object)[
                'title' => 'Oil Prices Drop 5% as OPEC+ Announces Production Increase',
                'slug' => 'oil-prices-drop-opec-production',
                'data' => (object)[
                    'description' => "Crude oil prices fell sharply on Monday after OPEC+ confirmed plans to increase production by 400,000 barrels per day starting next month. Brent crude settled at $72.15 per barrel, while WTI crude fell to $68.40. The decision aims to stabilize global energy markets amid concerns about supply constraints. Lower oil prices could help ease inflationary pressures and benefit consumer spending sectors.",
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #D97706)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1586190848860-803a63bf12e4?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-04'),
            ],
            (object)[
                'title' => 'European Markets Rally on Strong Corporate Earnings Reports',
                'slug' => 'european-markets-rally-july-2026',
                'data' => (object)[
                    'description' => "European stock markets posted strong gains this week, with the STOXX 600 index rising 2.8% as second-quarter corporate earnings largely exceeded analyst expectations. The German DAX added 3.1%, while France's CAC 40 gained 2.5%. Sectors leading the rally included luxury goods, automotive, and financial services. The positive earnings momentum is expected to continue through the remainder of earnings season.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #2563EB)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-07-02'),
            ],

            // === ADDITIONAL COMPANY UPDATES ===
            (object)[
                'title' => 'EnzoBank Security: How We Keep Your Money Safe 24/7',
                'slug' => 'enzobank-security',
                'data' => (object)[
                    'description' => "Military-grade 256-bit encryption, FDIC insurance, and biometric login protect every transaction you make. Our security infrastructure includes real-time fraud detection, multi-factor authentication, and continuous monitoring. Your financial security is our top priority at EnzoBank. We undergo regular third-party security audits and maintain SOC 2 Type II certification.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #334155)',
                    'thumb_icon' => 'shield',
                    'thumb_url' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-30'),
            ],
            (object)[
                'title' => 'EnzoBank Launches Financial Literacy Program for Students',
                'slug' => 'enzobank-financial-literacy-program',
                'data' => (object)[
                    'description' => "EnzoBank is launching a comprehensive financial literacy program aimed at high school and college students. The program covers budgeting, saving, investing, credit management, and cryptocurrency fundamentals. Partnering with 50 universities across North America and Europe, the program offers interactive workshops, digital courses, and a simulated trading platform. Registration opens August 1st, 2026.",
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #A78BFA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-28'),
            ],
            (object)[
                'title' => 'EnzoBank Named Best Digital Bank 2026 by FinTech Awards',
                'slug' => 'enzobank-best-digital-bank-2026',
                'data' => (object)[
                    'description' => "EnzoBank has been named \"Best Digital Bank 2026\" at the annual Global FinTech Awards ceremony held in London. The award recognizes our innovative approach to digital banking, exceptional user experience, and commitment to financial inclusion. Judges particularly noted our AI-powered financial insights platform and seamless cross-border payment capabilities. This is the third major industry award for EnzoBank this year.",
                    'thumb_gradient' => 'linear-gradient(135deg, #B45309, #F59E0B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-25'),
            ],

            // ===== 2026 H1 =====
            (object)[
                'title' => 'EnzoBank Expands Operations to Latin America with New Office in São Paulo',
                'slug' => 'enzobank-latin-america-expansion',
                'data' => (object)[
                    'description' => "EnzoBank is expanding its global footprint with a new regional headquarters in São Paulo, Brazil. The expansion will serve customers across Brazil, Argentina, Chile, and Colombia with localized banking products, Portuguese and Spanish language support, and real-time跨境 payments. The São Paulo office will create 500 new jobs and is expected to be fully operational by Q3 2026.",
                    'thumb_gradient' => 'linear-gradient(135deg, #16A34A, #22C55E)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-20'),
            ],
            (object)[
                'title' => 'Summer Investment Playbook: Sectors to Watch in Q3 2026',
                'slug' => 'summer-investment-playbook-q3-2026',
                'data' => (object)[
                    'description' => "Our Q3 2026 investment playbook highlights five sectors poised for growth: artificial intelligence infrastructure, renewable energy storage, healthcare innovation, cybersecurity, and consumer staples. We expect AI infrastructure stocks to lead with 25%+ projected growth driven by continued enterprise adoption. The full playbook with specific tickers and entry price targets is available exclusively to EnzoBank Premium subscribers.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #14B8A6)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-06-18'),
            ],
            (object)[
                'title' => 'Federal Reserve Minutes Signal Potential Rate Cut Later This Year',
                'slug' => 'fed-minutes-rate-cut-signal',
                'data' => (object)[
                    'description' => "The Federal Reserve's June meeting minutes revealed growing support among policymakers for a potential rate cut in late 2026, citing progress on inflation and a cooling labor market. Market participants are now pricing in a 65% probability of a 25-basis-point cut at the September meeting. Bond yields have already begun to decline, with the 10-year Treasury yield falling to 4.12%.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1611122281389-4c0f3c7e7e1e?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-15'),
            ],
            (object)[
                'title' => 'EnzoBank Launches AI-Powered Financial Advisor for All Users',
                'slug' => 'enzobank-ai-financial-advisor',
                'data' => (object)[
                    'description' => "EnzoBank's new AI-powered financial advisor, 'Eve,' is now available to all users. Eve provides personalized investment recommendations, spending analysis, savings goals tracking, and retirement planning using advanced machine learning algorithms trained on millions of financial data points. Early users report an average 23% improvement in savings rates and a 15% boost in investment returns within the first three months of use.",
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #8B5CF6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-12'),
            ],
            (object)[
                'title' => 'Market Volatility Update: Navigating the Summer Trading Season',
                'slug' => 'market-volatility-summer-2026',
                'data' => (object)[
                    'description' => "Summer 2026 has brought increased market volatility with the VIX index climbing to 22.4, driven by geopolitical uncertainties and mixed economic data. Our trading desk recommends maintaining a diversified portfolio with increased allocations to defensive sectors and dividend-paying stocks. We've also identified several hedging strategies using low-cost put options that can protect against downside risk while maintaining upside exposure.",
                    'thumb_gradient' => 'linear-gradient(135deg, #DC2626, #EF4444)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-10'),
            ],
            (object)[
                'title' => 'EnzoBank Q1 2026 Earnings: Revenue Up 34% Year Over Year',
                'slug' => 'enzobank-q1-2026-earnings',
                'data' => (object)[
                    'description' => "EnzoBank reported strong first-quarter results for 2026, with total revenue reaching $847 million, a 34% increase year over year. Net income grew to $312 million, driven by higher net interest income and record fee revenue from payment processing. The bank added 280,000 new customer accounts during the quarter. 'Our results reflect the strength of our digital-first strategy and the trust our customers place in us,' said CEO Marcus Chen.",
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #10B981)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-05-28'),
            ],
            (object)[
                'title' => 'Crypto Market Rally: Bitcoin Breaks $120,000 for First Time',
                'slug' => 'bitcoin-120k-milestone',
                'data' => (object)[
                    'description' => "Bitcoin surged past the $120,000 mark for the first time in history, reaching a new all-time high of $124,350 amid growing institutional adoption and positive regulatory developments. Ethereum also rallied, crossing $8,000 for the first time. The total cryptocurrency market capitalization now exceeds $4.5 trillion. Analysts cite the approval of spot Bitcoin ETFs and increasing corporate treasury allocations as key drivers of the rally.",
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #F59E0B)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1621761191319-c6fb62004040?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-05-20'),
            ],
            (object)[
                'title' => 'Retirement Planning Guide: Maximizing Your 401(k) and IRA in 2026',
                'slug' => 'retirement-planning-guide-2026',
                'data' => (object)[
                    'description' => "With 2026 contribution limits at their highest ever — $23,500 for 401(k) plans and $7,500 for IRAs — now is the perfect time to optimize your retirement savings strategy. Our comprehensive guide covers catch-up contributions for those over 50, Roth vs. traditional account considerations, asset allocation strategies by age, and how to use EnzoBank's retirement planning tools to model your future income needs.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #475569)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-05-15'),
            ],
            (object)[
                'title' => "EnzoBank Wins 'Best Mobile Banking App' at Global Finance Awards",
                'slug' => 'best-mobile-banking-app-award',
                'data' => (object)[
                    'description' => "EnzoBank's mobile application has been named 'Best Mobile Banking App' at the Global Finance Awards 2026. The award recognizes the app's intuitive user interface, robust security features, and innovative financial management tools. Judges particularly highlighted the app's AI-powered budgeting feature and seamless integration with third-party financial services. This is the fourth industry award EnzoBank has received this year.",
                    'thumb_gradient' => 'linear-gradient(135deg, #B45309, #F59E0B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-05-10'),
            ],
            (object)[
                'title' => 'Real Estate Investment Trusts (REITs): A Comprehensive Guide for 2026',
                'slug' => 'reit-investment-guide-2026',
                'data' => (object)[
                    'description' => "REITs continue to offer attractive yields in 2026, with the average dividend yield at 4.8%. Our comprehensive guide covers the different types of REITs — equity, mortgage, and hybrid — and provides our top picks in each category based on fundamentals, dividend sustainability, and growth prospects. We particularly favor data center and industrial REITs given the ongoing demand for cloud infrastructure and logistics space.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #2DD4BF)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1560520031-3a4dc964e915?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-05-05'),
            ],

            // ===== 2026 Q1 =====
            (object)[
                'title' => 'EnzoBank Introduces Fractional Share Trading for All Accounts',
                'slug' => 'fractional-share-trading-launch',
                'data' => (object)[
                    'description' => "EnzoBank now offers fractional share trading, allowing users to invest in any stock or ETF with as little as $1. This feature makes it possible to build a diversified portfolio even with small amounts, invest in high-priced stocks like Berkshire Hathaway and Nvidia, and easily implement dollar-cost averaging strategies. Over 5,000 US stocks and ETFs are available for fractional trading with zero commissions.",
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #60A5FA)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-04-22'),
            ],
            (object)[
                'title' => 'Tax Season 2026: Key Deadlines and Deductions Every Investor Should Know',
                'slug' => 'tax-season-2026-investor-guide',
                'data' => (object)[
                    'description' => "With the April 15 tax deadline approaching, our tax experts have compiled essential information for investors. Key topics include tax-loss harvesting strategies, qualified dividend treatment, capital gains holding period rules, and the new Section 1202 Qualified Small Business Stock exclusion. We also cover the latest IRS guidance on cryptocurrency reporting and how to use EnzoBank's tax document center to streamline your filing.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #4B5563)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-04-08'),
            ],
            (object)[
                'title' => 'Oil Market Analysis: Supply Constraints Drive Prices Above $85',
                'slug' => 'oil-market-analysis-april-2026',
                'data' => (object)[
                    'description' => "Crude oil prices have climbed above $85 per barrel for the first time since 2024, driven by OPEC+ production cuts, geopolitical tensions in the Middle East, and stronger-than-expected global demand. Our energy sector analysts expect prices to remain elevated in the near term, potentially averaging $82-88 per barrel in Q2 2026. We recommend energy sector exposure through diversified ETFs and select E&P companies with strong cash flow generation.",
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #D97706)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1586190848860-803a63bf12e4?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-04-02'),
            ],

            // ===== 2025 =====
            (object)[
                'title' => 'EnzoBank Year in Review 2025: Record Growth and Innovation',
                'slug' => 'enzobank-year-in-review-2025',
                'data' => (object)[
                    'description' => "2025 was a landmark year for EnzoBank. We grew our customer base to 1.8 million users, processed over $12 billion in transactions, launched 47 new features, and expanded to 12 new countries. Our investment platform saw a 156% increase in assets under management. As we look ahead to 2026, we remain committed to our mission of making banking accessible, intelligent, and secure for everyone.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-12-30'),
            ],
            (object)[
                'title' => 'Holiday Spending Trends 2025: Consumer Behavior and Market Impact',
                'slug' => 'holiday-spending-trends-2025',
                'data' => (object)[
                    'description' => "Holiday retail sales in 2025 exceeded expectations, growing 4.2% year over year to a record $1.2 trillion. E-commerce continued its upward trajectory, accounting for 22% of total retail sales. Our analysis reveals shifting consumer preferences toward experiences over goods, with travel and dining spending up 18%. These trends have significant implications for retail, hospitality, and payment processing stocks heading into 2026.",
                    'thumb_gradient' => 'linear-gradient(135deg, #B91C1C, #EF4444)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-12-20'),
            ],
            (object)[
                'title' => 'EnzoBank Launches Green Bond Fund for Sustainable Investors',
                'slug' => 'enzobank-green-bond-fund',
                'data' => (object)[
                    'description' => "EnzoBank's new Green Bond Fund invests in investment-grade green bonds issued by corporations and governments to finance environmentally sustainable projects. The fund targets a 4-6% annual return with lower volatility than traditional bond funds. Eligible projects include renewable energy, energy efficiency, clean transportation, sustainable water management, and green building initiatives. Minimum investment is $500.",
                    'thumb_gradient' => 'linear-gradient(135deg, #065F46, #10B981)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2025-12-05'),
            ],
            (object)[
                'title' => 'S&P 500 Year-End Outlook 2025: Targets and Top Picks',
                'slug' => 'sp500-year-end-outlook-2025',
                'data' => (object)[
                    'description' => "Our year-end 2025 S&P 500 target is 6,650, implying approximately 8% upside from current levels. We expect earnings growth of 12% year over year, driven by margin expansion in technology and financial sectors. Our top sector picks for Q4 2025 are technology, healthcare, and financials. Key risks include geopolitical tensions, inflation persistence, and potential disruptions from the upcoming presidential election.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #3B82F6)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-11-20'),
            ],
            (object)[
                'title' => 'EnzoBank Partners with Mastercard for Premium Debit Card Line',
                'slug' => 'enzobank-mastercard-partnership',
                'data' => (object)[
                    'description' => "EnzoBank has partnered with Mastercard to launch a new line of premium debit cards featuring enhanced rewards, travel benefits, and security features. The EnzoBank x Mastercard World Elite Debit Card offers 3% cashback on dining and entertainment, 2% on groceries, and 1% on all other purchases, with no annual fee. Cardholders also receive access to Mastercard's Priceless Cities program and exclusive travel concierge services.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E1E2E, #3B82F6)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-11-10'),
            ],
            (object)[
                'title' => 'US Election 2025: Market Implications and Investment Strategies',
                'slug' => 'us-election-2025-market-impact',
                'data' => (object)[
                    'description' => "As the 2025 election cycle intensifies, our political and market research team analyzes the potential impact of various election outcomes on financial markets. Key policy areas to watch include corporate tax rates, healthcare reform, energy regulation, and trade policy. Historically, election years produce above-average market volatility in the third quarter followed by a year-end rally. We recommend maintaining strategic positioning with a focus on quality stocks.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #4B5563)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-10-28'),
            ],
            (object)[
                'title' => 'EnzoBank Surpasses $10 Billion in Assets Under Management',
                'slug' => 'enzobank-10-billion-aum',
                'data' => (object)[
                    'description' => "EnzoBank's investment platform has surpassed $10 billion in assets under management, a milestone achieved just 18 months after launching our wealth management division. The platform offers over 200 professionally managed portfolios, robo-advisory services, and self-directed trading. 'Reaching $10 billion in AUM demonstrates the strong demand for accessible, technology-driven investment solutions,' said Chief Investment Officer Sarah Mitchell.",
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #34D399)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1553729459-afe8f2e2a275?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-10-05'),
            ],
            (object)[
                'title' => 'Bond Market Update: Yield Curve Normalization and Duration Strategy',
                'slug' => 'bond-market-yield-curve-october-2025',
                'data' => (object)[
                    'description' => "The Treasury yield curve has continued its normalization process, with the 2s10s spread turning positive for the first time since 2022. This development has significant implications for fixed-income investors. We recommend extending portfolio duration to capture higher yields on longer-term bonds while maintaining adequate liquidity. Corporate bonds continue to offer attractive spreads, particularly in the BBB-rated segment.",
                    'thumb_gradient' => 'linear-gradient(135deg, #D97706, #FBBF24)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2025-09-25'),
            ],
            (object)[
                'title' => 'EnzoBank Launches Small Business Banking Platform',
                'slug' => 'enzobank-small-business-banking',
                'data' => (object)[
                    'description' => "EnzoBank is proud to announce EnzoBank Business, a comprehensive banking platform designed specifically for small and medium-sized enterprises. Features include free business checking with no minimum balance, integrated invoicing and payment processing, payroll management, expense tracking, and business credit cards with rewards tailored to business spending. Over 10,000 businesses have joined the waitlist ahead of the official launch.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #2DD4BF)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-09-15'),
            ],
            (object)[
                'title' => 'AI in Finance: How Machine Learning Is Transforming Investment Management',
                'slug' => 'ai-machine-learning-finance-2025',
                'data' => (object)[
                    'description' => "Artificial intelligence and machine learning are revolutionizing the investment management industry. EnzoBank's AI research team shares insights on how ML algorithms are being used for portfolio optimization, risk management, sentiment analysis, and trade execution. Our proprietary AI model has outperformed the S&P 500 by an average of 3.2% annually since its deployment. We explore the opportunities and challenges of AI-driven investing in this comprehensive report.",
                    'thumb_gradient' => 'linear-gradient(135deg, #5B21B6, #8B5CF6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2025-08-28'),
            ],
            (object)[
                'title' => 'Federal Reserve Chair Testifies Before Congress: Key Takeaways',
                'slug' => 'fed-chair-testimony-august-2025',
                'data' => (object)[
                    'description' => "Federal Reserve Chair Jerome Powell's semiannual testimony before Congress provided important insights into the central bank's policy outlook. Powell emphasized the Fed's data-dependent approach, noting progress on inflation while stopping short of committing to a specific rate cut timeline. Markets interpreted the testimony as slightly dovish, with the S&P 500 gaining 1.2% on the day. Interest rate futures now price in two rate cuts by year-end 2025.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #1D4ED8)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1611122281389-4c0f3c7e7e1e?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-08-15'),
            ],
            (object)[
                'title' => 'EnzoBank Launches Youth Banking Accounts for Teens',
                'slug' => 'enzobank-youth-banking-teens',
                'data' => (object)[
                    'description' => "EnzoBank's new youth banking accounts, designed for teenagers aged 13-17, combine financial education with real-world banking experience. Features include a prepaid debit card with parental controls, savings goals with automatic round-ups, educational modules on budgeting and investing, and the ability to track allowance and chore payments. Parents can monitor spending and set limits through their own EnzoBank dashboard.",
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #A78BFA)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-08-01'),
            ],
            (object)[
                'title' => 'Healthcare Sector Deep Dive: Investment Opportunities in Biotech',
                'slug' => 'healthcare-biotech-investment-opportunities',
                'data' => (object)[
                    'description' => "The healthcare sector presents compelling investment opportunities in 2025, particularly in biotechnology. Our analysts have identified several promising sub-sectors including gene therapy, precision oncology, and mRNA technology platforms. Key companies to watch feature strong pipelines with multiple late-stage clinical trials. We provide detailed financial models and risk assessments for each recommendation in our comprehensive healthcare sector report.",
                    'thumb_gradient' => 'linear-gradient(135deg, #BE185D, #EC4899)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2025-07-20'),
            ],
            (object)[
                'title' => 'Global Trade Update: Supply Chain Resilience and New Trade Corridors',
                'slug' => 'global-trade-supply-chain-2025',
                'data' => (object)[
                    'description' => "Global trade patterns continue to evolve as companies prioritize supply chain resilience over pure cost efficiency. New trade corridors are emerging between Southeast Asia, India, and Latin America. The reshaping of global trade has significant implications for shipping, logistics, and manufacturing companies. Our analysis identifies key beneficiaries and provides investment recommendations across the global trade ecosystem.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0891B2, #22D3EE)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2025-07-08'),
            ],
            (object)[
                'title' => 'EnzoBank Reaches 1 Million Users: A Look Back at Our Journey',
                'slug' => 'enzobank-1-million-users',
                'data' => (object)[
                    'description' => "EnzoBank has officially reached 1 million active users, a milestone that reflects the growing demand for innovative digital banking solutions. From our founding with a simple idea — that banking should be accessible, transparent, and intelligent — we've grown to a team of over 2,000 employees serving customers in 30 countries. This milestone belongs to our users, whose feedback and trust have shaped every feature we've built.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #60A5FA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2025-06-15'),
            ],

            // ===== 2024 =====
            (object)[
                'title' => 'Annual Market Outlook 2025: Forecasts and Strategies',
                'slug' => 'annual-market-outlook-2025',
                'data' => (object)[
                    'description' => "Our 2025 Annual Market Outlook provides a comprehensive analysis of the macroeconomic environment, asset class expectations, and strategic portfolio recommendations for the year ahead. We forecast S&P 500 earnings growth of 10-12%, supported by margin expansion and moderate revenue growth. We expect the Fed to begin cutting rates in mid-2025, which should support bond prices and provide a tailwind for growth stocks.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #3B82F6)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2024-12-30'),
            ],
            (object)[
                'title' => 'EnzoBank Year-End Bonuses and 2025 Outlook for Employees',
                'slug' => 'enzobank-2025-employee-outlook',
                'data' => (object)[
                    'description' => "EnzoBank CEO Marcus Chen announced year-end bonuses averaging 18% of base salary for all employees, reflecting the company's strong 2024 performance. The company also unveiled plans to hire 500 additional employees in 2025, with positions in engineering, product design, customer support, and compliance. New office expansions are planned for Singapore, Dubai, and Berlin as part of the company's global growth strategy.",
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #34D399)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-12-18'),
            ],
            (object)[
                'title' => 'Holiday Investment Guide: 12 Days of Financial Tips',
                'slug' => 'holiday-investment-guide-2024',
                'data' => (object)[
                    'description' => "Our '12 Days of Financial Tips' holiday series covers everything from year-end tax planning to New Year's financial resolutions. Topics include maximizing retirement contributions before the deadline, charitable giving strategies, reviewing your investment portfolio for rebalancing, setting up automated savings plans, and protecting your identity during the holiday shopping season. Each tip includes actionable steps you can take today.",
                    'thumb_gradient' => 'linear-gradient(135deg, #B91C1C, #FCA5A5)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2024-12-10'),
            ],
            (object)[
                'title' => 'Thanksgiving Market History: What Seasonal Trends Tell Us',
                'slug' => 'thanksgiving-market-history-2024',
                'data' => (object)[
                    'description' => "Historical market data reveals interesting seasonal patterns around Thanksgiving. Since 1950, the S&P 500 has posted positive returns in November 78% of the time, with an average gain of 1.6%. The 'Santa Claus Rally' period — the last five trading days of December and first two of January — has produced positive returns 79% of the time. We analyze these and other seasonal patterns to help inform your year-end investment strategy.",
                    'thumb_gradient' => 'linear-gradient(135deg, #92400E, #F59E0B)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1577083552768-45b2c5c1beaf?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2024-11-25'),
            ],
            (object)[
                'title' => 'EnzoBank Launches International Money Transfer with Zero Fees',
                'slug' => 'enzobank-zero-fee-international-transfers',
                'data' => (object)[
                    'description' => "EnzoBank now offers zero-fee international money transfers to over 100 countries, with competitive exchange rates that save users an average of 4.5% compared to traditional banks. The feature supports real-time tracking, scheduled transfers, and multi-currency wallets. 'International remittances are a vital financial service for millions of families, and we're committed to making them affordable and accessible,' said VP of Payments, Elena Rodriguez.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0891B2, #67E8F9)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-11-05'),
            ],
            (object)[
                'title' => 'Post-Election Market Analysis: What History Teaches Us',
                'slug' => 'post-election-market-analysis-2024',
                'data' => (object)[
                    'description' => "With the 2024 election concluded, our market analysis team examines historical patterns of post-election market performance. Since 1928, the S&P 500 has averaged a 7.2% gain in the 12 months following presidential elections, regardless of which party won. Key sectors that tend to perform well post-election include healthcare, defense, and infrastructure. We provide specific investment recommendations based on the new policy landscape.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #4B5563)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2024-11-06'),
            ],
            (object)[
                'title' => 'EnzoBank Launches High-Yield Savings Account at 5.2% APY',
                'slug' => 'enzobank-high-yield-savings-5-percent',
                'data' => (object)[
                    'description' => "EnzoBank's new High-Yield Savings account offers a competitive 5.2% annual percentage yield with no minimum balance requirements and no monthly fees. The account includes features like automatic savings round-ups, goal-based savings buckets, and instant transfers to your EnzoBank checking account. FDIC insured up to $250,000, the high-yield savings account is an excellent option for emergency funds and short-term savings goals.",
                    'thumb_gradient' => 'linear-gradient(135deg, #065F46, #6EE7B7)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-10-15'),
            ],
            (object)[
                'title' => 'Third Quarter 2024 Portfolio Performance Review',
                'slug' => 'q3-2024-portfolio-performance',
                'data' => (object)[
                    'description' => "Q3 2024 delivered mixed results across major asset classes. The S&P 500 gained 4.5%, driven by technology and communication services stocks. International developed markets underperformed, returning just 1.2%. Fixed income posted positive returns as yields declined modestly. Our recommended balanced portfolio returned 3.8% for the quarter. We review performance attribution and provide updated asset allocation guidance for Q4 2024.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #60A5FA)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2024-10-05'),
            ],
            (object)[
                'title' => 'EnzoBank Introduces AI-Powered Fraud Detection System',
                'slug' => 'enzobank-ai-fraud-detection',
                'data' => (object)[
                    'description' => "EnzoBank has deployed a new AI-powered fraud detection system that analyzes transaction patterns in real time to identify and prevent fraudulent activity. The system, trained on millions of transactions, can detect unusual patterns within milliseconds and has already prevented over $50 million in potential fraud since its pilot launch. The system achieves a 99.7% accuracy rate with false positive rates below 0.1%, minimizing inconvenience to legitimate users.",
                    'thumb_gradient' => 'linear-gradient(135deg, #5B21B6, #A78BFA)',
                    'thumb_icon' => 'shield',
                    'thumb_url' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2024-09-20'),
            ],
            (object)[
                'title' => 'Retirement Planning in Your 30s and 40s: A Practical Guide',
                'slug' => 'retirement-planning-30s-40s',
                'data' => (object)[
                    'description' => "Your 30s and 40s are critical decades for retirement planning. With time on your side but competing financial priorities — mortgage, children's education, career changes — it's essential to have a clear strategy. This guide covers optimal asset allocation by age, the power of compounding with concrete examples, balancing retirement savings with other financial goals, and how to catch up if you're behind on your retirement targets.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #64748B)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2024-08-25'),
            ],

            // ===== 2023 =====
            (object)[
                'title' => 'EnzoBank Year in Review 2023: Building the Foundation',
                'slug' => 'enzobank-year-in-review-2023',
                'data' => (object)[
                    'description' => "2023 was a foundational year for EnzoBank. We launched our mobile app, introduced our first investment products, and grew our user base to 250,000. Key milestones included the launch of our AI-powered budgeting tool, partnerships with major payment networks, and the opening of our second office in Manchester, UK. We also raised $200 million in Series C funding, valuing the company at $2.5 billion.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-12-28'),
            ],
            (object)[
                'title' => 'Holiday Budgeting Tips: Enjoy the Season Without Financial Stress',
                'slug' => 'holiday-budgeting-tips-2023',
                'data' => (object)[
                    'description' => "The holiday season can put significant strain on household budgets. Our financial wellness team shares practical tips for enjoying the holidays without overspending: create a holiday spending plan, use cash envelopes for gift purchases, take advantage of cashback rewards, consider homemade gifts and experiences over material items, and start a holiday savings fund in January for next year. Small changes can make a big difference in your financial well-being.",
                    'thumb_gradient' => 'linear-gradient(135deg, #B91C1C, #FCA5A5)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1512389142860-9c449e58a714?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2023-12-10'),
            ],
            (object)[
                'title' => 'Market Outlook 2024: Navigating the New Interest Rate Environment',
                'slug' => 'market-outlook-2024',
                'data' => (object)[
                    'description' => "As we look ahead to 2024, investors face a complex landscape shaped by higher interest rates, persistent inflation, and geopolitical uncertainty. Our 2024 outlook provides detailed forecasts for equities, fixed income, commodities, and currencies. We expect moderate equity returns of 5-8% with continued leadership from technology and healthcare sectors. Bond yields are likely to decline as the Fed begins its easing cycle in the second half of 2024.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A8A, #2563EB)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2023-12-01'),
            ],
            (object)[
                'title' => 'EnzoBank Raises $350 Million in Series D Funding Round',
                'slug' => 'enzobank-series-d-funding',
                'data' => (object)[
                    'description' => "EnzoBank has raised $350 million in Series D funding, led by Sequoia Capital and Accel Partners, bringing the company's valuation to $4.8 billion. The funding will be used to accelerate product development, expand into new markets across Asia and Latin America, and double the engineering team. 'This investment validates our vision of building the world's most intelligent digital bank,' said founder and CEO Marcus Chen.",
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #34D399)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-11-08'),
            ],
            (object)[
                'title' => 'Understanding Dollar-Cost Averaging: A Beginner Investment Strategy',
                'slug' => 'dollar-cost-averaging-beginners',
                'data' => (object)[
                    'description' => "Dollar-cost averaging is one of the most effective investment strategies for beginners and experienced investors alike. By investing a fixed amount at regular intervals, you buy more shares when prices are low and fewer when prices are high, potentially reducing your average cost per share over time. We explain how DCA works, its advantages over lump-sum investing, and how to set up automated DCA investments with EnzoBank.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #2DD4BF)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2023-10-20'),
            ],
            (object)[
                'title' => 'Geopolitical Risk and Markets: Navigating Uncertainty in 2023',
                'slug' => 'geopolitical-risk-markets-2023',
                'data' => (object)[
                    'description' => "Geopolitical tensions continue to influence global financial markets in 2023. From the ongoing conflict in Eastern Europe to US-China trade dynamics and instability in the Middle East, our geopolitical risk team analyzes the potential market impact of these developments. We provide a framework for investors to assess and manage geopolitical risk in their portfolios, including diversification strategies and hedging techniques.",
                    'thumb_gradient' => 'linear-gradient(135deg, #991B1B, #EF4444)',
                    'thumb_icon' => 'globe',
                    'thumb_url' => 'https://images.unsplash.com/photo-1447069387593-a5de0862481e?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2023-10-05'),
            ],
            (object)[
                'title' => 'EnzoBank Launches First-Ever Debit Card with 2% Unlimited Cashback',
                'slug' => 'enzobank-2-percent-cashback-debit-card',
                'data' => (object)[
                    'description' => "EnzoBank's new debit card offers 2% unlimited cashback on all purchases, with no categories to track and no spending caps. The card is made from recycled materials and features contactless payment, digital wallet integration, and real-time transaction notifications. Users can redeem cashback directly into their EnzoBank savings or investment accounts. The card has already received over 100,000 sign-ups in its first week.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #60A5FA)',
                    'thumb_icon' => 'card',
                    'thumb_url' => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-09-10'),
            ],
            (object)[
                'title' => 'Back-to-School Financial Planning for Parents',
                'slug' => 'back-to-school-financial-planning-2023',
                'data' => (object)[
                    'description' => "Back-to-school season can be expensive, with the average family spending over $800 on supplies, clothing, and electronics. Our financial planning team provides strategies to manage these costs without derailing your long-term financial goals. Topics include setting up education savings accounts (ESAs), 529 plan contribution strategies, budgeting for school-year expenses, and teaching children about money management.",
                    'thumb_gradient' => 'linear-gradient(135deg, #7C3AED, #A78BFA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2023-08-15'),
            ],
            (object)[
                'title' => 'EnzoBank Surpasses 500,000 Users in First Year',
                'slug' => 'enzobank-500k-users-first-year',
                'data' => (object)[
                    'description' => "Just 12 months after our public launch, EnzoBank has surpassed 500,000 registered users. The rapid growth reflects strong demand for our digital-first banking approach. Users have collectively saved over $200 million using our goal-based savings tools and earned $15 million in cashback rewards. 'Our users are at the heart of everything we do, and we're just getting started,' said COO James Wright.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2023-07-20'),
            ],
            (object)[
                'title' => 'Summer 2023 Market Trends: What\'s Driving the Rally?',
                'slug' => 'summer-2023-market-rally',
                'data' => (object)[
                    'description' => "Financial markets have experienced a strong rally through summer 2023, with the S&P 500 up 15% year to date. The rally has been driven by enthusiasm around artificial intelligence, resilient corporate earnings, and expectations that the Federal Reserve is nearing the end of its rate hiking cycle. We analyze the key drivers, identify which sectors are leading the charge, and provide our outlook for the remainder of 2023.",
                    'thumb_gradient' => 'linear-gradient(135deg, #059669, #6EE7B7)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2023-07-05'),
            ],

            // ===== 2022 =====
            (object)[
                'title' => 'EnzoBank Officially Launches Across North America and Europe',
                'slug' => 'enzobank-official-launch',
                'data' => (object)[
                    'description' => "After a successful beta period with 50,000 users, EnzoBank is officially launching across North America and Europe. The platform offers checking and savings accounts, investment management, international transfers, and a suite of AI-powered financial tools. 'We started EnzoBank to build the bank of the future — one that's accessible, intelligent, and truly customer-centric,' said founder Marcus Chen at the launch event in New York City.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E3A5F, #3B82F6)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2022-09-15'),
            ],
            (object)[
                'title' => 'EnzoBank Raises $120 Million in Series A and B Funding',
                'slug' => 'enzobank-series-a-b-funding',
                'data' => (object)[
                    'description' => "EnzoBank has raised $120 million across Series A and B funding rounds, with investments from Andreessen Horowitz, Index Ventures, and individual investors including former Treasury Secretary Lawrence Summers. The funding will support product development, regulatory compliance, and initial market expansion. EnzoBank's innovative approach to digital banking — combining AI-powered insights with human-centered design — has attracted significant investor interest.",
                    'thumb_gradient' => 'linear-gradient(135deg, #047857, #34D399)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2022-06-10'),
            ],
            (object)[
                'title' => 'The Future of Digital Banking: EnzoBank\'s Vision for 2030',
                'slug' => 'future-of-digital-banking-2030',
                'data' => (object)[
                    'description' => "EnzoBank founder Marcus Chen shares his vision for the future of digital banking in this in-depth interview. From AI-powered financial advisors to decentralized finance integration, Chen outlines how EnzoBank plans to stay at the forefront of financial innovation. 'In 2030, your bank will be an intelligent financial partner that anticipates your needs, protects your assets, and helps you achieve your life goals,' says Chen.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #4B5563)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2022-04-20'),
            ],
            (object)[
                'title' => 'Market Volatility in 2022: Strategies for Uncertain Times',
                'slug' => 'market-volatility-2022-strategies',
                'data' => (object)[
                    'description' => "2022 has been one of the most challenging years for financial markets in recent history, with both stocks and bonds declining simultaneously. Our investment strategy team provides actionable advice for navigating volatile markets: maintain a long-term perspective, rebalance systematically, focus on quality companies with strong balance sheets, consider alternative investments for diversification, and avoid making emotional investment decisions based on short-term market movements.",
                    'thumb_gradient' => 'linear-gradient(135deg, #991B1B, #FCA5A5)',
                    'thumb_icon' => 'chart',
                    'thumb_url' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2022-03-15'),
            ],
            (object)[
                'title' => 'EnzoBank Beta Launch: First 50,000 Users Get Early Access',
                'slug' => 'enzobank-beta-launch',
                'data' => (object)[
                    'description' => "EnzoBank is now accepting beta users! The first 50,000 people to sign up will get early access to our platform, including free premium membership for life. Beta testers will have the opportunity to shape our product with direct feedback to our development team. Features available in beta include mobile banking, peer-to-peer payments, budgeting tools, and our AI-powered spending insights engine.",
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #60A5FA)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&q=80',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2022-02-01'),
            ],
            (object)[
                'title' => 'New Year Financial Resolutions: Setting Yourself Up for Success in 2022',
                'slug' => 'new-year-financial-resolutions-2022',
                'data' => (object)[
                    'description' => "A new year brings an opportunity to reset your financial habits and set meaningful goals. Our financial wellness experts share their top New Year's resolutions: build a 3-6 month emergency fund, pay down high-interest debt, increase retirement contributions to at least 15% of income, create a realistic monthly budget using the 50/30/20 rule, and review your insurance coverage. Small consistent actions lead to significant long-term results.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0F766E, #5EEAD4)',
                    'thumb_icon' => 'default',
                    'thumb_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=400&q=80',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2022-01-05'),
            ],
        ]);
    }
}
