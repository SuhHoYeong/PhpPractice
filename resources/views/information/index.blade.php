@extends('layouts.app')

@section('content')
<h1>게시글 목록</h1>

<form action="{{ route('information.index') }}" method="GET">
    <input type="text" name="search" placeholder="제목 검색" value="{{ old('search', $search) }}">
    <button type="submit">검색</button>
</form>

<!-- 선택된 게시물 삭제 버튼 -->
<form id="deleteSelectedForm" action="/api/information/deleteSelected" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="selected_items" id="selected_items" value="">
    <button type="submit" id="deleteSelectedButton">선택된 게시물 삭제</button>
</form>

<button class="button" onclick="openModal()">새 게시글 등록</button>

<!-- 선택된 게시물 수정 버튼 -->
<button type="button" id="editSelectedButton" onclick="editSelectedRecord()">선택된 게시물 수정</button>

<!-- 모달 구조 -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>게시글 등록</h2>

        <!-- 등록 폼 (Ajax로 제출) -->
        <form id="createForm" onsubmit="submitForm(event)">
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
    </div>
</div>

<script>
    // 모달 열기
    function openModal() {
        document.getElementById('modal').style.display = 'flex';
    }

    // 모달 닫기
    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }
</script>
@foreach ($informations as $information)
<div>
    <h2>{{ $information->information_title }}</h2>
    <p>{{ $information->information_naiyo }}</p>
    <a href="{{ route('information.show', $information->information_id) }}">상세보기</a>
    <!-- 선택 버튼 -->
    <button type="button" class="select-button" data-id="{{ $information->information_id }}">선택</button>
</div>
@endforeach

<script>
    let selectedId = null; // 선택된 게시물 ID를 저장할 변수

    // 게시물 선택 버튼 클릭 시
    document.querySelectorAll('.select-button').forEach(button => {
        button.addEventListener('click', function() {
            // 모든 선택 버튼에서 'selected' 클래스 제거
            document.querySelectorAll('.select-button').forEach(btn => btn.classList.remove('selected'));

            // 현재 선택된 게시물 ID 업데이트 및 선택 표시
            selectedId = this.getAttribute('data-id');
            this.classList.add('selected'); // 선택된 버튼에만 'selected' 클래스 추가

            // 선택된 게시물이 있으면 삭제 버튼 활성화
            document.getElementById('deleteSelectedButton').disabled = false;

            // 선택된 게시물 ID를 hidden input에 할당
            document.getElementById('selected_items').value = selectedId;

            // 디버깅을 위한 콘솔 로그
            console.log("선택된 게시물 ID: " + selectedId);
        });
    });

    document.getElementById('deleteSelectedForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // 선택된 게시물이 없는 경우 알림 표시 후 함수 종료
        if (!selectedId) {
            alert("선택된 게시물이 없습니다.");
            return;
        }

        // 삭제 확인 메시지
        if (confirm("선택된 게시물을 삭제하시겠습니까?")) {
            // 선택된 게시물 ID를 hidden input에 할당
            document.getElementById('selected_items').value = selectedId;

            // 디버깅을 위한 콘솔 로그
            console.log("서버로 전송되는 데이터: ", {
                selected_items: selectedId
            });

            // 디버깅을 위한 URL 로그 출력
            console.log("Delete URL:", "{{ route('information.deleteSelected') }}");

            // 서버에 삭제 요청 보내기
            fetch("/api/information/deleteSelected", {
                    method: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        selected_items: selectedId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("HTTP status " + response.status); // 상태 오류 발생 시 오류 발생
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('선택된 게시물이 삭제되었습니다.');
                        location.reload();
                    } else {
                        alert(data.message || '게시물 삭제 실패');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('서버 오류가 발생했습니다.');
                });
        } else {
            console.log("삭제가 취소되었습니다.");
        }
    });

    function submitForm(event) {
        event.preventDefault(); // 기본 폼 제출 방지

        // createForm 폼에서 데이터를 가져와 FormData 객체 생성
        const formElement = document.getElementById('createForm');
        const formData = new FormData(formElement);

        // FormData를 JSON 형식으로 변환하여 fetch에 전달
        const jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });

        fetch("/api/information/store", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(jsonData),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("HTTP status " + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('게시물이 성공적으로 등록되었습니다.');
                    location.reload(); // 페이지 새로고침
                } else {
                    alert(data.message || '게시물 등록 실패');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('서버 오류가 발생했습니다.');
            });
    }
</script>
@endsection