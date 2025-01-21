<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>紅包功能</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">搶紅包活動</h1>
        
        <!-- 顯示紅包池初始化 -->
        <div class="mt-4">
            <button id="initializeBtn" class="btn btn-success">初始化紅包池</button>
            <p id="initializeMessage" class="mt-2"></p>
        </div>

        <!-- 搶紅包 -->
        <div class="mt-4">
            <button id="grabBtn" class="btn btn-primary">搶紅包</button>
            <p id="grabMessage" class="mt-2"></p>
        </div>

        <!-- 顯示歷史記錄 -->
        <div class="mt-4">
            <button id="historyBtn" class="btn btn-info">查看搶紅包歷史</button>
            <ul id="historyList" class="list-group mt-2"></ul>
        </div>
    </div>

    <script>
        document.getElementById('initializeBtn').addEventListener('click', async () => {
            const response = await fetch('/red-envelope/initialize', { method: 'POST' });
            const data = await response.json();
            document.getElementById('initializeMessage').textContent = data.message;
        });

        document.getElementById('grabBtn').addEventListener('click', async () => {
            const response = await fetch('/red-envelope/grab', { method: 'POST' });
            const data = await response.json();
            if (data.success) {
                document.getElementById('grabMessage').textContent = `成功搶到紅包，金額：${data.amount} 元！`;
            } else {
                document.getElementById('grabMessage').textContent = data.message;
            }
        });

        document.getElementById('historyBtn').addEventListener('click', async () => {
            const response = await fetch('/red-envelope/history');
            const data = await response.json();
            const historyList = document.getElementById('historyList');
            historyList.innerHTML = '';
            data.forEach(item => {
                const listItem = document.createElement('li');
                listItem.textContent = `金額：${item.amount} 元，時間：${item.grab_timestamp}`;
                listItem.classList.add('list-group-item');
                historyList.appendChild(listItem);
            });
        });
    </script>
</body>
</html>
