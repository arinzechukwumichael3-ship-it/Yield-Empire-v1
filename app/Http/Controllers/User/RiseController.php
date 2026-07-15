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

        // Try to get articles from database
        $dbArticles = FrontendAnnouncement::with('category')->latest()->get();

        if ($dbArticles->count() > 0) {
            $articles = $dbArticles->map(function ($article) {
                return $this->normalizeArticleForFeed($article);
            });
        } else {
            $articles = $this->getStaticArticles();
        }

        $categories = AnnouncementCategory::all();

        return view('user.rise.feed', compact('page_title', 'articles', 'categories'));
    }

    public function articleDetail($slug)
    {
        $page_title = "Article";

        // Try database first
        $dbArticle = FrontendAnnouncement::with('category')->where('slug', $slug)->first();
        if ($dbArticle) {
            $article = $this->normalizeArticleForFeed($dbArticle);
            return view('user.rise.article-detail', compact('page_title', 'article'));
        }

        // Try static articles
        $article = $this->getStaticArticles()->firstWhere('slug', $slug);
        if ($article) {
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
            ->where('type', PaymentGatewayConst::TYPETOPUP)
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
            ],
            'category' => (object)['name' => $catName],
            'created_at' => $article->created_at,
        ];
    }

    private function getStaticArticles(): Collection
    {
        return collect([
            (object)[
                'title' => 'Introducing EnzoBank Virtual Cards: Create Yours Free Today',
                'slug' => 'enzobank-virtual-cards',
                'data' => (object)[
                    'description' => "We're excited to launch virtual cards for all EnzoBank users. Create unlimited cards instantly for online shopping with military-grade security. Each card comes with a unique CVV and card number, and you can set spending limits per card. Start your free virtual card today and experience the future of digital payments with EnzoBank.",
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                    'thumb_icon' => 'card',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-22'),
            ],
            (object)[
                'title' => 'How to Grow Your Wealth with EnzoBank Investment Plans',
                'slug' => 'enzobank-investment-plans',
                'data' => (object)[
                    'description' => "Our investment plans offer competitive returns starting from 15% ROI. Here's how to choose the right plan for your goals. Whether you're a beginner investor or a seasoned pro, EnzoBank has a plan that fits your risk tolerance and financial objectives. Start investing today and watch your wealth grow.",
                    'thumb_gradient' => 'linear-gradient(135deg, #2563EB, #1D4ED8)',
                    'thumb_icon' => 'chart',
                ],
                'category' => (object)['name' => 'Portfolio reports'],
                'created_at' => \Carbon\Carbon::parse('2026-06-20'),
            ],
            (object)[
                'title' => 'EnzoBank Security: How We Keep Your Money Safe 24/7',
                'slug' => 'enzobank-security',
                'data' => (object)[
                    'description' => "Military-grade 256-bit encryption, FDIC insurance, and biometric login protect every transaction you make. Our security infrastructure includes real-time fraud detection, multi-factor authentication, and continuous monitoring. Your financial security is our top priority at EnzoBank.",
                    'thumb_gradient' => 'linear-gradient(135deg, #1E293B, #334155)',
                    'thumb_icon' => 'shield',
                ],
                'category' => (object)['name' => 'Company updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-18'),
            ],
            (object)[
                'title' => 'Global Payments Now Available: Send Money to 150+ Countries',
                'slug' => 'global-payments-now-available',
                'data' => (object)[
                    'description' => "EnzoBank now supports SWIFT, ACH, and SEPA transfers. Send money worldwide in seconds with zero delays. Our global payment network covers 150+ countries with competitive exchange rates and low fees. Whether for business or personal use, sending money abroad has never been easier.",
                    'thumb_gradient' => 'linear-gradient(135deg, #0891B2, #06B6D4)',
                    'thumb_icon' => 'globe',
                ],
                'category' => (object)['name' => 'Market updates'],
                'created_at' => \Carbon\Carbon::parse('2026-06-15'),
            ],
        ]);
    }
}
