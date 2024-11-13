<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>게시판</title>
    <!-- CSS 파일 불러오기 -->
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->

    <!-- 여기서 추가한 CSS를 입력해도 됩니다. -->
    <style>
        /* 모달 스타일 */
        .modal {
            display: none;
            /* 기본적으로 모달을 숨김 */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
        }

        .close {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }

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
    <div class="container">
        <header>
            <h1>게시판</h1>
        </header>

        <main>
            <!-- 컨텐츠를 여기에 삽입 -->
            @yield('content')
        </main>


    </div>

</body>

</html>