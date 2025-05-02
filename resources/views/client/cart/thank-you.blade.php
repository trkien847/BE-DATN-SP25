@extends('client.layouts.layout')

@section('content')

<div> 
            <audio id="backgroundMusic" autoplay>
                <source src="{{ asset('audio/titing.mp3') }}" type="audio/mpeg">
            </audio>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const audio = document.getElementById('backgroundMusic');
                audio.volume = 1;
                let playPromise = audio.play();
                
                if (playPromise !== undefined) {
                    playPromise.catch(error => {
                        console.log("Autoplay was prevented");
                    });
                }
                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden && !audio.ended) {
                        audio.play();
                    }
                });
            });
            </script>
    </div>
<style>
    /* CSS cho container và nội dung */
    .body1 {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); /* Gradient xanh lá cây */
        margin: 0;
        overflow: hidden;
        font-family: 'Arial', sans-serif;
    }

    .container2 {
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: 50px auto;
        padding: 30px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        text-align: center;
        animation: fadeIn 1s ease-in-out;
    }

    h2 {
        color: #27ae60;
        font-size: 2.5em;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    p {
        color: #555;
        font-size: 1.2em;
        margin-bottom: 30px;
        line-height: 1.6;
        min-height: 3.6em; 
    }

    .btn.theme-btn-1 {
        display: inline-block;
        padding: 12px 30px;
        background: #27ae60; 
        color: #fff;
        text-decoration: none;
        border-radius: 25px;
        font-size: 1.1em;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
    }

    .btn.theme-btn-1:hover {
        background: #219653; 
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(39, 174, 96, 0.5);
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .leaves {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }

    .leaf {
        position: absolute;
        width: 20px;
        height: 30px;
        background: #27ae60; 
        clip-path: polygon(50% 0%, 0% 100%, 100% 100%); 
        animation: fallingLeaf linear infinite;
    }

    @keyframes fallingLeaf {
        0% {
            transform: translateX(0) translateY(-50px) rotate(0deg);
            opacity: 1;
        }
        50% {
            transform: translateX(50px) translateY(50vh) rotate(180deg);
            opacity: 0.8;
        }
        100% {
            transform: translateX(-50px) translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }

    #matrix-text {
        font-family: 'Courier New', Courier, monospace;
        color: #27ae60; 
        letter-spacing: 1px;
    }
</style>

<div class="body1">
<div class="leaves" id="leaves"></div>
<div class="container2">
    <h2>Cảm ơn bạn đã đặt hàng!</h2>
    <p id="matrix-text">Đơn hàng của bạn đã được ghi nhận. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.</p>
    <a href="{{ route('index') }}" class="btn theme-btn-1">Quay về trang chủ</a>
</div>
</div>

<script>
function createLeaf() {
    const leaf = document.createElement('div');
    leaf.classList.add('leaf');

    const size = Math.random() * 10 + 15;
    leaf.style.width = `${size}px`;
    leaf.style.height = `${size * 1.5}px`;

    leaf.style.left = `${Math.random() * window.innerWidth}px`;
    leaf.style.top = `-${size}px`;

    const duration = Math.random() * 5 + 5;
    leaf.style.animationDuration = `${duration}s`;

    leaf.style.animationDelay = `${Math.random() * 3}s`;

    const shades = ['#27ae60', '#2ecc71', '#219653', '#b7e1cd'];
    leaf.style.background = shades[Math.floor(Math.random() * shades.length)];

    document.getElementById('leaves').appendChild(leaf);

    leaf.addEventListener('animationend', () => {
        leaf.remove();
    });
}

setInterval(createLeaf, 500);


</script>

@endsection