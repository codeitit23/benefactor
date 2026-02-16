<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>منصة التبرعات - من أجل مستقبل أفضل</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Dark theme only */
        /* Dark theme with Filament v3 Orange */
        :root {
            --bg-primary: #030712;
            --bg-secondary: #111827;
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --border-color: #1f2937;
            --header-bg: #111827;
            --footer-bg: #111827;
            --card-bg: #1f2937;
            --button-secondary-bg: transparent;
            --button-secondary-border: #374151;
            --button-secondary-text: #f9fafb;
            --button-secondary-hover: #1f2937;
            --social-bg: #1f2937;
            --social-color: #d1d5db;
            --social-hover-bg: #f97316; /* Filament v3 Orange */
            --social-hover-color: #ffffff;
            --primary-color: #f97316; /* Filament v3 Orange */
            --primary-hover: #fb923c; /* Lighter orange for hover */
            --badge-bg: #1f2937;
            --badge-color: #d1d5db;
            --link-color: #d1d5db;
            --link-hover: #f97316; /* Orange for link hover */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text-primary);
        }

        /* Filament-style Header */
        .filament-header {
            background: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
            width: 100%;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.025em;
        }

        .logo i {
            color: var(--primary-color);
            font-size: 2rem;
        }

        /* Filament-style CTA Section */
        .cta-section {
            max-width: 800px;
            margin: 4rem auto;
            padding: 3rem;
            background: var(--card-bg);
            border-radius: 1rem;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            border: 1px solid var(--border-color);
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
            letter-spacing: -0.025em;
            line-height: 1.2;
        }

        .cta-description {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Filament-style Buttons */
        .btn {
            padding: 0.625rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid transparent;
            cursor: pointer;
            letter-spacing: -0.01em;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: var(--button-secondary-bg);
            color: var(--button-secondary-text);
            border-color: var(--button-secondary-border);
        }

        .btn-secondary:hover {
            background: var(--button-secondary-hover);
            border-color: var(--border-color);
        }

        /* Filament-style Footer */
        .filament-footer {
            background: var(--footer-bg);
            border-top: 1px solid var(--border-color);
            padding: 2rem 2rem 1rem;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .footer-section ul li a {
            color: var(--link-color);
            text-decoration: none;
            transition: color 0.2s ease;
            font-size: 0.875rem;
        }

        .footer-section ul li a:hover {
            color: var(--link-hover);
        }

        .footer-section p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .footer-section i {
            color: var(--primary-color);
        }

        .social-links {
            display: flex;
            gap: 0.75rem;
        }

        .social-link {
            width: 32px;
            height: 32px;
            background: var(--social-bg);
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--social-color);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .social-link:hover {
            background: var(--social-hover-bg);
            color: var(--social-hover-color);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* Filament-style badge/decoration */
        .filament-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--badge-bg);
            color: var(--badge-color);
            padding: 0.25rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .filament-badge i {
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .cta-title {
                font-size: 2rem;
            }

            .cta-description {
                font-size: 1rem;
                padding: 0 1rem;
            }

            .btn {
                padding: 0.5rem 1.25rem;
                font-size: 0.875rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .social-links {
                justify-content: center;
            }

            .logo {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<!-- Filament-style Header with just title -->
<header class="filament-header">
    <div class="header-content">
        <div class="logo">
            <i class="fas fa-hand-holding-heart"></i>
            منصة التبرعات
        </div>
    </div>
</header>

<!-- Main Content - Filament-style CTA Section -->
<main style="flex: 1; display: flex; align-items: center; padding: 2rem;">
    <section class="cta-section">
        <!-- Filament badge -->
        <div class="filament-badge">
            <i class="fas fa-heart"></i>
            منصة موثوقة ومعتمدة
        </div>

        <h2 class="cta-title">هل أنت مستعد للمساهمة؟</h2>
        <p class="cta-description">
            تبرعك البسيط يمكن أن يغير حياة شخص ما. انضم إلى آلاف المتبرعين وكن جزءاً من التغيير الإيجابي في مجتمعنا.
        </p>
        <div class="cta-buttons">
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

<!-- Filament-style Footer -->
<footer class="filament-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>منصة التبرعات</h3>
            <p>
                منصة رائدة في مجال العمل الخيري، نسعى لصناعة فرق حقيقي في حياة المحتاجين.
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

{{--        <div class="footer-section">--}}
{{--            <h3>تواصل معنا</h3>--}}
{{--            <ul>--}}
{{--                <li><i class="fas fa-phone"></i> +966 123 456 789</li>--}}
{{--                <li><i class="fas fa-envelope"></i> info@donation.com</li>--}}
{{--                <li><i class="fas fa-map-marker-alt"></i> الرياض، السعودية</li>--}}
{{--            </ul>--}}
{{--        </div>--}}

{{--        <div class="footer-section">--}}
{{--            <h3>تابعنا</h3>--}}
{{--            <div class="social-links">--}}
{{--                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>--}}
{{--                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>--}}
{{--                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>--}}
{{--                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>

    <div class="footer-bottom">
        <p>&copy; 2026 منصة التبرعات. جميع الحقوق محفوظة.</p>
    </div>
</footer>
</body>
</html>
