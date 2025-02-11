<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>玩家分數排行榜</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .filters {
            text-align: center;
            margin-bottom: 20px;
        }
        .filters a {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
        }
        .filters a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body style="background-image: url(images/background2.jpg);">
    <h1 style="text-align: center;">$遊戲玩家排行榜$</h1>
    <table>
    <thead>
        <tr>
            <th style="color: #007bff;">Rank</th>
            <th style="color: #007bff;">ID</th>
            <th style="color: #007bff;">Name</th>
            <th style="color: #007bff;">Score</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td style="color: #FF7500;">{{ $user['rank'] }}</td>
                <td>{{ $user['id'] }}</td>
                <td>{{ $user['name'] }}</td>
                <td style="color: #F75000;">{{ $user['score'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- 分頁 -->
    <div style="text-align: center; margin-top: 20px;">
    <!-- 上一頁按鈕 -->
    @if ($page > 1)
        <a href="{{ route('leaderboard', ['page' => $page - 1]) }}" style="font-size: 20px; padding: 10px; text-decoration: none;">上一頁</a>
    @endif

    <!-- 頁碼顯示 -->
    <span style="font-size: 24px; padding: 10px;">第 {{ $page }} 頁</span>

    <!-- 下一頁按鈕 -->
    @if ($hasNextPage)
        <a href="{{ route('leaderboard', ['page' => $page + 1]) }}" style="font-size: 20px; padding: 10px; text-decoration: none;">下一頁</a>
    @endif
    </div>

<!-- 顯示頁碼 -->
    <div style="text-align: center; margin-top: 20px;">
    @for ($i = 1; $i <= ceil($totalUsers / $perPage); $i++)
        <a href="{{ route('leaderboard', ['page' => $i]) }}" 
            style="font-size: 24px; padding: 10px; text-decoration: none; 
            {{ $i == $page ? 'font-weight: bold;' : '' }}">
            {{ $i }}
        </a>
    @endfor
    </div>

    </table>
</body>
</html>
