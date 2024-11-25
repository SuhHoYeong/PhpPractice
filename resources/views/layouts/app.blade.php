<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>お知らせ掲示板</title>
    <!-- CSS 파일 불러오기 -->
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->

    <!-- 여기서 추가한 CSS를 입력해도 됩니다. -->
    <style>
        h1 {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            margin: 0;
            padding: 20px;
            background-color: #fff;
            width: 100%;
            text-align: center;
            z-index: 1000;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 2px solid #007bff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* 선택적으로 그림자 추가 */
        }

        body {
            padding-top: 80px;
            /* h1의 높이에 맞게 여백을 조정 */
        }

        /* 검색 폼 스타일 */
        .search-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 20px;
            margin-bottom: 20px;
        }



        .search-row label {
            margin-right: 2px;
            /* 레이블과 입력 필드 간 여백 */
        }

        .search-row {
            display: flex;
            flex-wrap: wrap;
            /* 줄 바꿈 허용 */
            align-items: center;
            /* 수직 중앙 정렬 */
            gap: 20px;
            /* 레이블과 입력 필드 간 간격 */
        }

        .search-label {
            font-size: 16px;
            font-weight: bold;
            margin-right: 10px;
            align-self: center;
        }

        .search-form input[type="text"],
        .search-form input[type="date"],
        .search-form select {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 220px;
            /* 필드의 고정 너비 */
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            /* 필드 안쪽 그림자 */
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-form button:hover {
            background-color: #0056b3;
        }

        .search-form input[type="text"]:focus,
        .search-form select:focus,
        .search-form input[type="date"]:focus {
            border-color: #007bff;
        }

        .table-row.selected {
            background-color: #f0f0f0;
            /* 선택된 행에 배경색을 추가 */
        }

        .button-inline {
            display: inline-block;
            /* 버튼을 한 줄로 배치 */
            margin-right: 2px;
            /* 버튼 사이에 간격 추가 */
        }

        /* 모달 스타일 */
        .modal {
    display: none; /* 기본적으로 모달을 숨김 */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* 반투명 어두운 배경 */
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

#createForm input{
    height: 20px
}

.modal-content {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    width: 50%; /* 너비를 80%로 설정 (필요에 따라 조정) */
    max-width: 1200px; /* 최대 너비 설정 (너무 넓어지지 않게 제한) */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
    box-sizing: border-box; /* 패딩을 포함한 크기 계산 */
    margin-left: auto; /* 수평 중앙 정렬 */
    margin-right: auto; /* 수평 중앙 정렬 */
}

.modal h2 {
    text-align: center;
    font-size: 19px;
    margin-bottom: 10px;
    color: #333; /* 제목 색상 */
}

.modal input,
.modal select,
.modal textarea {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 15px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

.modal input:focus,
.modal select:focus,
.modal textarea:focus {
    border-color: #007bff; /* 포커스 시 테두리 색상 */
}

.modal .close {
    position: absolute; /* 절대 위치 설정 */
    top: 10px; /* 모달 상단에서 10px 위치 */
    right: 10px; /* 모달 우측에서 10px 위치 */
    font-size: 24px;
    cursor: pointer;
    color: #333; /* 버튼 색상 */
}

.modal .close:hover {
    color: #000;
}

.modal .button-container {
    display: flex;
    justify-content: space-between; /* 버튼을 양쪽에 배치 */
    margin-top: 20px;
}

.modal .button {
    padding: 12px 20px;
    font-size: 16px;
    color: white;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s ease;
    text-align: center;
    width: 48%;
}

.modal .button:hover {
    background-color: #0056b3;
}

.modal .button:active {
    background-color: #004085; /* 클릭 시 더 어두운 색상 */
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

        /* 전체 테이블 컨테이너 스타일 */
        .table-container {
            margin: 20px auto;
            width: 90%;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            background-color: #fff;
        }

        /* 테이블 헤더 스타일 */
        .table-header {
            display: flex;
            background-color: #f7f7f7;
            color: #333;
            padding: 12px 0;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }

        .table-header .table-cell {
            flex: 1;
            text-align: center;
            padding: 8px;
            font-size: 14px;
        }

        /* 테이블 행 스타일 */
        .table-row {
            display: flex;
            transition: background-color 0.2s ease;
            padding: 10px 0;
            cursor: pointer;
        }

        .table-row:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table-row:hover {
            background-color: #eaeaea;
        }

        .table-row.selected {
            background-color: #d1e7fd;
            /* 선택된 행 배경색 */
            color: #004085;
            /* 선택된 행 글자색 */
        }

        .table-row .table-cell {
            flex: 1;
            text-align: center;
            padding: 8px;
            font-size: 14px;
            color: #555;
        }

        /* 페이지네이션을 한 줄에 배치 */
        .pagination {
            display: flex;
            flex-wrap: nowrap;
            /* 한 줄에 모두 표시되게 설정 */
            justify-content: center;
            margin: 0;
            /* 여백 초기화 */
            padding: 0;
            /* 여백 초기화 */
            flex: 1;
        }

        /* 페이지 링크 간 간격을 조정 */
        .pagination .page-item {
            margin-right: 5px;
            /* 각 페이지 번호 사이에 약간의 여백 */
        }

        /* 페이지 링크 스타일 */
        .pagination .page-link {
            padding: 10px 15px;
            font-size: 14px;
            color: #007bff;
            /* 기본 텍스트 색상 */
            text-decoration: none;
            /* 링크 밑줄 제거 */
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
            transition: background-color 0.3s, color 0.3s;
        }

        /* 마우스 오버 시 색상 변경 */
        .pagination .page-link:hover {
            background-color: #f1f1f1;
            color: #0056b3;
        }

        /* 활성화된 페이지 번호 스타일 */
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        /* 비활성화된 페이지 링크 스타일 */
        .pagination .page-item.disabled .page-link {
            background-color: #f1f1f1;
            color: #ccc;
            border-color: #ddd;
        }

        /* 마지막 항목에 오른쪽 마진을 없앰 */
        .pagination .page-item:last-child {
            margin-right: 0;
        }

        ul {
            list-style-type: none;
        }

        .action-container {
            display: flex;
            justify-content: space-between;
            /* 페이지네이션과 버튼들이 양 끝에 배치되도록 설정 */
            align-items: center;
            /* 세로 정렬을 가운데로 맞춤 */
            gap: 2px;
            /* 요소들 사이에 간격을 추가 */
            margin-left: 50px;
            margin-right: 50px;
        }

        /* 알림 모달 스타일 */
        #alertModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        #alertModal .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            text-align: center;
        }

        #alertModal .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        #alertModal .button {
            margin-top: 20px;
        }

        /* 삭제 확인 모달에만 적용되는 CSS */
        #deleteConfirmationModal {
            display: none;
            /* 기본적으로 숨김 */
            position: fixed;
            z-index: 1050;
            /* 다른 콘텐츠 위에 표시 */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* 배경을 어두운 반투명 색으로 */
            overflow: auto;
            /* 내용이 길어지면 스크롤 */
        }

        /* 모달 콘텐츠 */
        #deleteConfirmationModal .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            /* 모서리 둥글게 */
            width: 40%;
            /* 너비는 원하는 만큼 설정 */
            max-width: 500px;
            /* 최대 너비 설정 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* 그림자 추가 */
            text-align: center;
        }

        /* 모달 닫기 버튼 */
        #deleteConfirmationModal .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        /* 삭제 확인 메시지 */
        #deleteConfirmationModal h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #333;
        }

        /* 메시지 텍스트 */
        #deleteConfirmationModal #deleteConfirmationMessage {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }

        /* 버튼 컨테이너 */
        #deleteConfirmationModal .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        /* 삭제 버튼 */
        #deleteConfirmationModal #confirmDeleteButton {
            background-color: #f44336;
            /* 빨간색 */
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            /* 모서리 둥글게 */
            transition: background-color 0.3s ease;
        }

        #deleteConfirmationModal #confirmDeleteButton:hover {
            background-color: #d32f2f;
            /* 호버 시 더 어두운 빨간색 */
        }

        /* 취소 버튼 */
        #deleteConfirmationModal .button {
            background-color: #9e9e9e;
            /* 회색 */
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        #deleteConfirmationModal .button:hover {
            background-color: #757575;
            /* 호버 시 어두운 회색 */
        }

        .error-message {
        font-size: 0.9rem;
        color: red;
        margin-top: 4px;
    }
        /* 성공 모달 배경 */
        #successModal {
        display: none; /* 기본적으로 숨김 */
        position: fixed;
        z-index: 1000; /* 다른 요소 위에 표시 */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4); /* 반투명 배경 */
        overflow: auto; /* 콘텐츠가 모달 크기를 넘으면 스크롤 */
    }

    /* 성공 모달 콘텐츠 */
    #successModal .modal-content {
        background-color: #fff;
        margin: 10% auto; /* 화면에서 10% 아래로 위치 */
        padding: 20px;
        border-radius: 8px;
        width: 80%; /* 모달 너비 */
        max-width: 500px; /* 최대 너비 */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* 그림자 효과 */
        text-align: center; /* 텍스트 중앙 정렬 */
    }

    /* 성공 모달 닫기 버튼 */
    #successModal .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    #successModal .close:hover,
    #successModal .close:focus {
        color: black;
        text-decoration: none;
    }

    /* 성공 모달 제목 */
    #successModal h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #28a745; /* 성공적인 색상 (초록) */
    }

    /* 성공 모달 메시지 */
    #successModal p {
        font-size: 18px;
        color: #333; /* 본문 색상 */
        margin-bottom: 30px;
    }

    /* 성공 모달 버튼 */
    #successModal .button {
        background-color: #007bff; /* 성공적인 색상 */
        color: #fff;
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #successModal .button:hover {
        background-color: #218838; /* 버튼 호버 색상 */
    }





























            /* 성공 모달 배경 */
            #deleteModal {
        display: none; /* 기본적으로 숨김 */
        position: fixed;
        z-index: 1000; /* 다른 요소 위에 표시 */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4); /* 반투명 배경 */
        overflow: auto; /* 콘텐츠가 모달 크기를 넘으면 스크롤 */
    }

    /* 성공 모달 콘텐츠 */
    #deleteModal .modal-content {
        background-color: #fff;
        margin: 10% auto; /* 화면에서 10% 아래로 위치 */
        padding: 20px;
        border-radius: 8px;
        width: 80%; /* 모달 너비 */
        max-width: 500px; /* 최대 너비 */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* 그림자 효과 */
        text-align: center; /* 텍스트 중앙 정렬 */
    }

    /* 성공 모달 닫기 버튼 */
    #deleteModal .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    #deleteModal .close:hover,
    #deleteModal .close:focus {
        color: black;
        text-decoration: none;
    }

    /* 성공 모달 제목 */
    #deleteModal h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #28a745; /* 성공적인 색상 (초록) */
    }

    /* 성공 모달 메시지 */
    #deleteModal p {
        font-size: 18px;
        color: #333; /* 본문 색상 */
        margin-bottom: 30px;
    }

    /* 성공 모달 버튼 */
    #deleteModal .button {
        background-color: #007bff; /* 성공적인 색상 */
        color: #fff;
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #deleteModal .button:hover {
        background-color: #218838; /* 버튼 호버 색상 */
    }
   
    







                /* 성공 모달 배경 */
                #updateModal {
        display: none; /* 기본적으로 숨김 */
        position: fixed;
        z-index: 1000; /* 다른 요소 위에 표시 */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4); /* 반투명 배경 */
        overflow: auto; /* 콘텐츠가 모달 크기를 넘으면 스크롤 */
    }

    /* 성공 모달 콘텐츠 */
    #updateModal .modal-content {
        background-color: #fff;
        margin: 10% auto; /* 화면에서 10% 아래로 위치 */
        padding: 20px;
        border-radius: 8px;
        width: 80%; /* 모달 너비 */
        max-width: 500px; /* 최대 너비 */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* 그림자 효과 */
        text-align: center; /* 텍스트 중앙 정렬 */
    }

    /* 성공 모달 닫기 버튼 */
    #updateModal .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    #updateModal .close:hover,
    #updateModal .close:focus {
        color: black;
        text-decoration: none;
    }

    /* 성공 모달 제목 */
    #updateModal h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #28a745; /* 성공적인 색상 (초록) */
    }

    /* 성공 모달 메시지 */
    #updateModal p {
        font-size: 18px;
        color: #333; /* 본문 색상 */
        margin-bottom: 30px;
    }

    /* 성공 모달 버튼 */
    #updateModal .button {
        background-color: #007bff; /* 성공적인 색상 */
        color: #fff;
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #updateModal .button:hover {
        background-color: #218838; /* 버튼 호버 색상 */
    }

    .error-message {
    color: #d9534f; /* 붉은 색상 */
    background-color: #f9d6d5; /* 연한 붉은 배경색 */
    border: 1px solid #d9534f; /* 경계선 */
    border-radius: 5px; /* 부드러운 모서리 */
    padding: 10px 15px; /* 내부 여백 */
    margin: 10px 0; /* 위아래 여백 */
    font-size: 14px; /* 글씨 크기 */
    text-align: center; /* 가운데 정렬 */
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); /* 약간의 그림자 효과 */
    width: 100%; /* 전체 너비로 확장 */
}
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>お知らせ掲示板</h1>
        </header>

        <main>
            <!-- 컨텐츠를 여기에 삽입 -->
            @yield('content')
        </main>


    </div>

</body>

</html>