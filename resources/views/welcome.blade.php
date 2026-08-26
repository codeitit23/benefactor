<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>منصة التبرعات - من أجل مستقبل أفضل</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700,800&family=changa:600,700,800&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --bg-0: #f8fafc;
            --bg-1: #eef2f7;
            --bg-2: #e2e8f0;
            --text-strong: #172033;
            --text-soft: #475569;
            --text-muted: #64748b;
            --card: rgba(255, 255, 255, 0.88);
            --card-border: rgba(71, 85, 105, 0.18);
            --accent: #f59e0b;
            --accent-2: #22c55e;
            --accent-3: #0ea5e9;
            --button-bg: #f59e0b;
            --button-bg-hover: #fbbf24;
            --button-ghost: rgba(15, 23, 42, 0.06);
            --button-ghost-border: rgba(71, 85, 105, 0.24);
            --shadow: 0 24px 60px rgba(71, 85, 105, 0.16);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: radial-gradient(1200px 600px at 10% 10%, rgba(14, 165, 233, 0.16), transparent 55%),
                        radial-gradient(900px 600px at 90% 15%, rgba(245, 158, 11, 0.18), transparent 55%),
                        radial-gradient(1000px 700px at 50% 85%, rgba(34, 197, 94, 0.14), transparent 60%),
                        linear-gradient(180deg, var(--bg-1), var(--bg-0));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text-strong);
            position: relative;
            overflow-x: hidden;
        }

        .cover-photo {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: auto;
            width: calc(100% - 430px);
            z-index: 0;
            background-position: center;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            opacity: 1;
            pointer-events: none;
        }

        body::before,
        /*body::after {*/
        /*    content: "";*/
        /*    position: absolute;*/
        /*    inset: -20% -10% auto auto;*/
        /*    width: 520px;*/
        /*    height: 520px;*/
        /*    border-radius: 50%;*/
        /*    background: radial-gradient(circle, rgba(245, 158, 11, 0.25), transparent 65%);*/
        /*    filter: blur(30px);*/
        /*    opacity: 0.7;*/
        /*    z-index: 0;*/
        /*    animation: drift 16s ease-in-out infinite;*/
        /*}*/

        body::after {
            inset: auto auto -25% -10%;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.22), transparent 65%);
            animation-delay: -6s;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .welcome-main {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 0;
            min-height: 100vh;
        }

        /* Filament-style CTA Section */
        .cta-section {
            width: 430px;
            min-height: 100vh;
            margin: 0 auto 0 0;
            padding: 3.5rem 2.25rem;
            background: linear-gradient(145deg, #dcd0be 0%, #e9e1d4 48%, #cbbda6 100%);
            border-radius: 0;
            text-align: center;
            box-shadow: var(--shadow);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(2px);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.75rem;
            animation: rise 0.8s ease both;
        }

        .cta-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(400px 180px at 80% 0%, rgba(245, 158, 11, 0.18), transparent 60%),
                        radial-gradient(400px 200px at 10% 100%, rgba(34, 197, 94, 0.16), transparent 60%);
            z-index: 0;
        }

        .cta-section > * {
            position: relative;
            z-index: 1;
        }

        .cta-title {
            font-family: 'Changa', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-strong);
            letter-spacing: 0.2px;
            line-height: 1.25;
        }

        .cta-description {
            font-size: 0.9rem;
            color: var(--text-soft);
            margin-bottom: 2.25rem;
            max-width: 620px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.8;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Filament-style Buttons */
        .btn {
            padding: 0.75rem 1.75rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, border-color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border: 1px solid transparent;
            cursor: pointer;
            letter-spacing: 0.2px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--button-bg), var(--button-bg-hover));
            color: #0b0f14;
            box-shadow: 0 16px 30px rgba(245, 158, 11, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 34px rgba(245, 158, 11, 0.32);
        }

        .btn-secondary {
            background: var(--button-ghost);
            color: var(--text-strong);
            border-color: var(--button-ghost-border);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            border-color: rgba(248, 250, 252, 0.4);
        }

        /* Filament-style Footer */
        .filament-footer {
            background: rgba(8, 12, 18, 0.7);
            border-top: 1px solid rgba(148, 163, 184, 0.12);
            padding: 0.5rem 0.75rem 0.35rem;
            margin-top: auto;
            backdrop-filter: blur(12px);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.5rem;
        }

        .footer-section {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .footer-section h3 {
            font-size: 0.75rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-strong);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .contact-list {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.25rem 0.75rem;
        }

        .footer-section ul {
            list-style: none;
            margin: 0;
        }

        .footer-section ul li {
            margin-bottom: 0;
            color: var(--text-soft);
            font-size: 0.75rem;
        }

        .footer-section ul li a {
            color: var(--text-soft);
            text-decoration: none;
            transition: color 0.2s ease;
            font-size: 0.75rem;
        }

        .footer-section ul li a:hover {
            color: var(--accent);
        }

        .footer-section p {
            color: var(--text-soft);
            font-size: 0.75rem;
            line-height: 1.2;
            margin: 0;
        }

        .footer-section i {
            color: var(--accent);
        }

        .social-links {
            display: flex;
            gap: 0.4rem;
        }

        .social-link {
            width: 24px;
            height: 24px;
            background: rgba(248, 250, 252, 0.08);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-soft);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.7rem;
            border: 1px solid rgba(248, 250, 252, 0.12);
        }

        .social-link:hover {
            background: var(--accent);
            color: #0b0f14;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 0.35rem;
            margin-top: 0.35rem;
            border-top: 1px solid rgba(148, 163, 184, 0.12);
            color: var(--text-muted);
            font-size: 0.7rem;
        }

        /* Filament-style badge/decoration */
        .filament-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(248, 250, 252, 0.08);
            color: var(--text-soft);
            padding: 0.35rem 1.1rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            border: 1px solid rgba(248, 250, 252, 0.12);
        }

        .filament-badge i {
            color: var(--accent-2);
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes drift {
            0% {
                transform: translateY(0) translateX(0);
            }
            50% {
                transform: translateY(20px) translateX(-15px);
            }
            100% {
                transform: translateY(0) translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-main {
                padding: 1.5rem 1rem;
                min-height: 100vh;
            }

            .cover-photo {
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                width: auto;
                background-size: 100% 100%;
                opacity: 0.45;
            }

            .cta-section {
                width: 100%;
                min-height: 0;
                margin: 1rem auto;
                padding: 2.5rem 1.75rem;
            }

            .cta-title {
                font-size: 1.5rem;
            }

            .cta-description {
                font-size: 0.8rem;
                padding: 0;
            }

            .btn {
                padding: 0.65rem 1.4rem;
                font-size: 0.85rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .social-links {
                justify-content: center;
            }

        }
    </style>
</head>
@php
    $coverUrl = !empty($homepageSetting?->cover_path) ? Storage::disk('public')->url($homepageSetting->cover_path) : null;
@endphp
<body>
@if ($coverUrl)
    <div class="cover-photo" style="background-image: url('{{ $coverUrl }}');"></div>
@endif
<div class="page-wrap">
<!-- Main Content - Filament-style CTA Section -->
<main class="welcome-main">
    <section class="cta-section">
        <!-- Filament badge -->
{{--        <div class="filament-badge">--}}
{{--            <i class="fas fa-heart"></i>--}}
{{--            منصة موثوقة ومعتمدة--}}
{{--        </div>--}}

        <h2 class="cta-title">هل أنت مستعد للمساهمة؟</h2>
        <p class="cta-description">
            تبرعك البسيط يمكن أن يغير حياة شخص ما. انضم إلى آلاف المتبرعين وكن جزءاً من التغيير الإيجابي في مجتمعنا.
        </p>
        <div class="cta-buttons">
            <a href="{{ url('/donate') }}" class="btn btn-primary">
                <i class="fas fa-heart"></i>
                تبرع بدون تسجيل
            </a>
            <a href="{{ url('/admin/login') }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                تسجيل الدخول
            </a>
            <a href="{{ url('/admin/register') }}" class="btn btn-secondary">
                <i class="fas fa-user-plus"></i>
                إنشاء حساب جديد
            </a>
        </div>
    </section>
</main>

<!--
Filament-style Footer
<footer class="filament-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>منصة التبرعات</h3>
            <p>
                منصة رائدة في مجال العمل الخيري.
            </p>
        </div>

{{--        <div class="footer-section">--}}
{{--            <h3>روابط سريعة</h3>--}}
{{--            <ul>--}}
{{--                <li><a href="#">عن المنصة</a></li>--}}
{{--                <li><a href="#">المشاريع الحالية</a></li>--}}
{{--                <li><a href="#">قصص النجاح</a></li>--}}
{{--                <li><a href="#">الأسئلة الشائعة</a></li>--}}
{{--            </ul>--}}
{{--        </div>--}}

        <div class="footer-section">
            <h3>تواصل معنا</h3>
            <ul class="contact-list">
                <li><i class="fas fa-phone"></i> +961 ------</li>
                <li><i class="fas fa-envelope"></i> info@donation.com</li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>تابعنا</h3>
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2026 منصة التبرعات. جميع الحقوق محفوظة.</p>
    </div>
</footer>
-->
</div>
</body>
</html>

