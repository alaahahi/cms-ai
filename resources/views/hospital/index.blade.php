<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="/asset/img/1-images-0.jpg" type="image/x-icon">
    <meta name="description" content="بطاقة مستشفى الحياة الدولي - الموصل">
    <title>بطاقة مستشفى الحياة الدولي</title>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #0b1a33;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            color: #fff;
            overflow-x: hidden;
        }

        .page-wrap {
            max-width: 1080px;
            margin: 0 auto;
            padding: 0 0 3rem;
        }

        .hero-strip {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(11, 26, 51, 0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 178, 178, 0.25);
            padding: 0.65rem 1rem;
            text-align: center;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            color: #7fe0e0;
            animation: fadeDown 0.7s ease both;
        }

        .gallery {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .gallery-item {
            position: relative;
            opacity: 0;
            transform: translateY(36px) scale(0.985);
            transition:
                opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.7s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.35s ease;
            will-change: opacity, transform;
        }

        .gallery-item.is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .gallery-item img {
            display: block;
            width: 100%;
            height: auto;
            max-width: 1080px;
            margin: 0 auto;
            object-fit: cover;
            vertical-align: middle;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.35);
        }

        .gallery-item:hover img {
            box-shadow: 0 14px 40px rgba(0, 178, 178, 0.22);
        }

        .gallery-item[data-i="0"] { transition-delay: 0.04s; }
        .gallery-item[data-i="1"] { transition-delay: 0.08s; }
        .gallery-item[data-i="2"] { transition-delay: 0.12s; }

        .gallery-item:first-child.is-visible img {
            animation: heroIn 1s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .back-top {
            position: fixed;
            bottom: 1.4rem;
            left: 1.4rem;
            z-index: 30;
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 50%;
            background: #00b2b2;
            color: #0b1a33;
            font-size: 1.25rem;
            cursor: pointer;
            opacity: 0;
            pointer-events: none;
            transform: translateY(12px);
            transition: opacity 0.35s ease, transform 0.35s ease, background 0.2s ease;
            box-shadow: 0 6px 20px rgba(0, 178, 178, 0.4);
        }

        .back-top.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .back-top:hover {
            background: #7fe0e0;
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes heroIn {
            from {
                opacity: 0.65;
                transform: scale(1.02);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .gallery-item,
            .gallery-item:first-child.is-visible img,
            .hero-strip,
            .back-top {
                animation: none !important;
                transition: none !important;
            }

            .gallery-item {
                opacity: 1;
                transform: none;
            }
        }

        @media (max-width: 640px) {
            .hero-strip {
                font-size: 0.78rem;
                padding: 0.5rem 0.75rem;
            }

            .back-top {
                bottom: 1rem;
                left: 1rem;
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>

<body>
    <div class="hero-strip">بطاقة مستشفى الحياة الدولي — الموصل · حي الضباط · شارع الكورنيش</div>

    <main class="page-wrap">
        <section class="gallery" id="gallery" aria-label="معرض بطاقة المستشفى">
            @for ($i = 0; $i <= 23; $i++)
                <figure class="gallery-item" data-i="{{ $i % 3 }}">
                    <img
                        src="/asset/img/1-images-{{ $i }}.jpg"
                        alt="بطاقة مستشفى الحياة الدولي — صفحة {{ $i + 1 }}"
                        @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif
                        decoding="async"
                        width="1080"
                        height="1528"
                    />
                </figure>
            @endfor
        </section>
    </main>

    <button type="button" class="back-top" id="backTop" aria-label="العودة للأعلى">↑</button>

    <script>
        (function () {
            var items = document.querySelectorAll('.gallery-item');
            var backTop = document.getElementById('backTop');

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(
                    function (entries) {
                        entries.forEach(function (entry) {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-visible');
                                observer.unobserve(entry.target);
                            }
                        });
                    },
                    { rootMargin: '0px 0px -8% 0px', threshold: 0.12 }
                );

                items.forEach(function (el) {
                    observer.observe(el);
                });
            } else {
                items.forEach(function (el) {
                    el.classList.add('is-visible');
                });
            }

            window.addEventListener(
                'scroll',
                function () {
                    if (window.scrollY > 420) {
                        backTop.classList.add('show');
                    } else {
                        backTop.classList.remove('show');
                    }
                },
                { passive: true }
            );

            backTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();
    </script>
</body>

</html>
