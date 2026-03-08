<?php
function convert_url($url) {
    // YouTube youtu.be
    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&mute=1&loop=1&playlist=' . $m[1];
    }
    // YouTube watch?v=
    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&mute=1&loop=1&playlist=' . $m[1];
    }
    // URL lain langsung
    return $url;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BannerPosko</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            background: #000;
            cursor: none;
        }

        .banner-container {
            width: 100vw;
            height: calc(100vh - 70px);
            position: relative;
            overflow: hidden;
        }

        .banner-slide {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0; left: 0;
            opacity: 0;
            transition: opacity 1s ease;
        }
        .banner-slide.active { opacity: 1; }
        .banner-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .banner-slide video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .banner-slide iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .no-banner {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0d0d0f;
            color: #333;
            font-family: monospace;
            font-size: 60px;
        }

        .bottom-bar {
            position: absolute;
            bottom: 0; left: 0;
            width: 100vw;
            height: 70px;
            background: rgba(0,0,0,0.88);
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .datetime-box {
            flex-shrink: 0;
            width: 220px;
            padding: 0 20px;
            border-right: 2px solid rgba(255,255,255,0.2);
        }
        .datetime-time {
            font-family: monospace;
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1;
        }
        .datetime-date {
            font-family: sans-serif;
            font-size: 13px;
            color: #aaaaaa;
            margin-top: 5px;
        }

        .running-text-wrap {
            flex: 1;
            overflow: hidden;
            padding: 0 20px;
        }
        .running-text {
            display: inline-block;
            white-space: nowrap;
            font-family: sans-serif;
            font-size: 24px;
            color: #ffffff;
            animation: marquee 20s linear infinite;
        }
        @keyframes marquee {
            from { transform: translateX(100vw); }
            to { transform: translateX(-100%); }
        }
    </style>
</head>
<body>

<div class="banner-container" id="bannerContainer">
    <?php if (empty($banners)): ?>
        <div class="no-banner">Belum ada banner aktif</div>
    <?php else: ?>
        <?php foreach ($banners as $i => $b): ?>
        <div class="banner-slide <?= $i === 0 ? 'active' : '' ?>" data-tipe="<?= $b->tipe ?>">
            <?php if ($b->tipe === 'video'): ?>
                <video <?= $i === 0 ? 'autoplay' : '' ?> muted playsinline>
                    <source src="<?= base_url('uploads/' . $b->gambar) ?>" type="video/mp4">
                </video>
            <?php elseif ($b->tipe === 'url'): ?>
                <iframe src="<?= convert_url($b->url) ?>"
                    allowfullscreen
                    allow="autoplay; fullscreen"
                    sandbox="allow-same-origin allow-scripts allow-popups allow-forms">
                </iframe>
            <?php else: ?>
                <img src="<?= base_url('uploads/' . $b->gambar) ?>">
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="bottom-bar">
    <div class="datetime-box">
        <div class="datetime-time" id="clock">00:00:00</div>
        <div class="datetime-date" id="date">-</div>
    </div>
    <div class="running-text-wrap">
        <span class="running-text">
            <?= isset($running_text) ? htmlspecialchars($running_text) : 'Selamat Datang di BannerPosko' ?>
        </span>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.requestPointerLock();
    });
    document.addEventListener('click', function() {
        document.body.requestPointerLock();
    });

    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');
        const s = String(now.getSeconds()).padStart(2,'0');
        document.getElementById('clock').textContent = h + ':' + m + ':' + s;

        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        document.getElementById('date').textContent =
            days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Slideshow
    const slides = document.querySelectorAll('.banner-slide');

    if (slides.length === 1) {
        const video = slides[0].querySelector('video');
        if (video) {
            video.loop = true;
            video.play();
        }
    } else if (slides.length > 1) {
        let current = 0;

        function showSlide(index) {
            slides.forEach(function(s) {
                s.classList.remove('active');
                const v = s.querySelector('video');
                if (v) { v.pause(); v.currentTime = 0; }
            });

            slides[index].classList.add('active');
            const video = slides[index].querySelector('video');

            if (video) {
                video.loop = false;
                video.play();
                video.onended = function() {
                    current = (current + 1) % slides.length;
                    showSlide(current);
                };
            } else {
                setTimeout(function() {
                    current = (current + 1) % slides.length;
                    showSlide(current);
                }, 5000);
            }
        }

        showSlide(0);
    }
</script>
</body>
</html>