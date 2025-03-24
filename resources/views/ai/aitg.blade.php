<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Đảm bảo Laravel Blade thêm token -->
    <title>Chat Trợ Lý Ảo</title>
    <style>
        .chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 15px;
            font-family: Arial, sans-serif;
            z-index: 1000;
        }

        .chat-output {
            height: 300px;
            overflow-y: auto;
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 10px;
            border: 1px solid #eee;
        }

        .chat-output p {
            margin: 5px 0;
            padding: 8px 12px;
            border-radius: 8px;
            max-width: 80%;
            word-wrap: break-word;
        }

        .chat-output p strong {
            color: #333;
        }

        .chat-output p:nth-child(even) {
            background: #e0f7fa;
            margin-left: auto;
            text-align: right;
        }

        .chat-output p:nth-child(odd) {
            background: #fff3e0;
        }

        .chat-input-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #007bff;
        }

        input[type="file"] {
            padding: 5px;
        }

        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        .image-preview {
            max-width: 100%;
            max-height: 150px;
            margin-top: 10px;
            border-radius: 10px;
            display: none;
        }

        .chat-image {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
            margin: 5px 0;
        }

        .stars {
            position: absolute;
            pointer-events: none;
        }

        @keyframes starBurst {
            0% { transform: scale(0); opacity: 1; }
            100% { transform: scale(2); opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div id="chatOutput" class="chat-output"></div>
        <div class="chat-input-container">
            <input type="text" id="chatInput" placeholder="Nhập mô tả sản phẩm..." />
            <input type="file" id="imageInput" accept="image/*" />
            <img id="imagePreview" class="image-preview" alt="Ảnh xem trước" />
            <button onclick="sendMessage()">Gửi</button>
        </div>
    </div>

    <script>
        function sendMessage() {
            const input = document.getElementById('chatInput');
            const imageInput = document.getElementById('imageInput');
            const message = input.value.trim();
            const image = imageInput.files[0];
            const output = document.getElementById('chatOutput');
            const preview = document.getElementById('imagePreview');

            if (!message && !image) return;

            // Hiển thị tin nhắn người dùng
            if (message) {
                output.innerHTML += `<p><strong>Bạn:</strong> ${message}</p>`;
                input.value = '';
            }

            // Hiển thị ảnh đã gửi
            if (image) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    output.innerHTML += `
                        <p><strong>Bạn:</strong> [Đã gửi ảnh]<br>
                        <img src="${e.target.result}" class="chat-image" alt="Ảnh đã gửi"></p>`;
                };
                reader.readAsDataURL(image);
                imageInput.value = '';
                preview.style.display = 'none';
            }

            // Hiệu ứng ngôi sao
            createStarEffect();

            // Gửi dữ liệu lên server
            const formData = new FormData();
            if (message) formData.append('message', message);
            if (image) formData.append('image', image);

            fetch('/api/virtual-assistant', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                output.innerHTML += `<p><strong>TG EPA:</strong> ${data.reply}</p>`;
                output.scrollTop = output.scrollHeight;
            })
            .catch(error => {
                output.innerHTML += `<p><strong>TG EPA:</strong> Có lỗi xảy ra: ${error.message}</p>`;
            });
        }

        // Hiển thị ảnh xem trước khi chọn
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Tạo hiệu ứng ngôi sao
        function createStarEffect() {
            const container = document.querySelector('.chat-container');
            for (let i = 0; i < 10; i++) {
                const star = document.createElement('div');
                star.innerHTML = '★';
                star.className = 'stars';
                star.style.position = 'absolute';
                star.style.color = '#ffd700';
                star.style.fontSize = `${Math.random() * 20 + 10}px`;
                star.style.left = `${Math.random() * 100}%`;
                star.style.top = `${Math.random() * 100}%`;
                star.style.animation = 'starBurst 0.5s ease-out forwards';
                container.appendChild(star);

                setTimeout(() => star.remove(), 500);
            }
        }
    </script>
</body>
</html>