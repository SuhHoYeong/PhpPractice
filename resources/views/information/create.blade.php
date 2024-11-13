<!-- resources/views/informations/create.blade.php -->

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시글 등록</title>
    <style>
        /* 버튼 스타일 */
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            margin-right: 10px;
            /* 버튼 간 간격 */
        }

        .button:hover {
            background-color: #0056b3;
        }

        /* 버튼 컨테이너 스타일 */
        .button-container {
            display: flex;
            justify-content: flex-start;
            /* 왼쪽 정렬 */
            margin-top: 20px;
            /* 버튼 위쪽 여백 */
        }
    </style>
</head>

<body>
    <h1>お知らせの登録</h1>

    <form action="{{ route('information.store') }}" method="POST">
        @csrf
        <label for="information_title">お知らせタイトル</label>
        <input type="text" id="information_title" name="information_title" required><br>

        <label for="information_kbn">お知らせ区分</label>
        <select id="information_kbn" name="information_kbn" required>
            <option value="1">重要</option>
            <option value="2">情報</option>
        </select><br>

        <label for="keisai_ymd">掲載日</label>
        <input type="text" id="keisai_ymd" name="keisai_ymd" placeholder="YYYYMMDD" required><br>

        <label for="enable_start_ymd">有効開始年月日</label>
        <input type="text" id="enable_start_ymd" name="enable_start_ymd" placeholder="YYYYMMDD" required><br>

        <label for="enable_end_ymd">有効終了年月日</label>
        <input type="text" id="enable_end_ymd" name="enable_end_ymd" placeholder="YYYYMMDD" required><br>

        <label for="information_naiyo">お知らせ内容</label>
        <textarea id="information_naiyo" name="information_naiyo" required></textarea><br>

        <label for="create_user_cd">登録者コード</label>
        <input type="text" id="create_user_cd" name="create_user_cd" required><br>
        <!-- 버튼들을 감싸는 컨테이너 -->
        <div class="button-container">
            <button type="submit" class="button">登録</button>
            <!-- 뒤로가기 버튼 -->
            <a href="{{ route('information.index') }}" class="button">戻る</a>
        </div>
    </form>




</body>

</html>