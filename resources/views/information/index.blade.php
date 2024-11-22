@extends('layouts.app')

@section('content')


<form class="search-form" action="{{ route('information.index') }}" method="GET" style="margin-left: 60px; margin-right: 60px;">
    <!-- 타이틀과 구분을 같은 줄에 배치 -->
    <div class="search-row">
        <label for="search_title" class="search-label">お知らせタイトル</label>
        <input type="text" name="search_title" placeholder="제목 검색" value="{{ old('search_title', $search_title) }}">

        <label for="search_kbn" class="search-label">お知らせ区分</label>
        <select name="search_kbn">
            <option value="">全て</option>
            <option value="1" {{ old('search_kbn', $search_kbn) == '1' ? 'selected' : '' }}>重要</option>
            <option value="2" {{ old('search_kbn', $search_kbn) == '2' ? 'selected' : '' }}>情報</option>
        </select>
    </div>

    <!-- 게시일과 적용기간은 다른 줄에 배치 -->
    <div class="search-row">
    <label for="search_keisai_ymd" class="search-label">掲載日</label>
    <input type="date" id="search_keisai_ymd" name="search_keisai_ymd" 
           value="{{ old('search_keisai_ymd', $search_keisai_ymd) }}" style="margin-left: 80px;">

    <label for="search_enable_period" class="search-label" >適用期間</label>
    <input type="date" id="search_enable_start_ymd" name="search_enable_start_ymd" 
           value="{{ old('search_enable_start_ymd', $search_enable_start_ymd) }}" style="margin-left: 30px;">
    <span>～</span>
    <input type="date" id="search_enable_end_ymd" name="search_enable_end_ymd" 
           value="{{ old('search_enable_end_ymd', $search_enable_end_ymd) }}">
</div>

    <button type="submit">検索</button>
</form>


<!--登録モーダル-->
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
            <input type="date" id="keisai_ymd" name="keisai_ymd" placeholder="YYYYMMDD" required><br>

            <label for="enable_start_ymd">有効開始年月日</label>
            <input type="date" id="enable_start_ymd" name="enable_start_ymd" placeholder="YYYYMMDD" required><br>

            <label for="enable_end_ymd">有効終了年月日</label>
            <input type="date" id="enable_end_ymd" name="enable_end_ymd" placeholder="YYYYMMDD" required><br>

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

<!--変更モーダル-->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>게시글 수정</h2> <!-- 제목 수정 -->

        <!-- 수정 폼 (Ajax로 제출) -->
        <form id="editForm" onsubmit="submitEditForm(event)">
            @csrf
            <label for="information_title">お知らせタイトル</label>
            <input type="text" id="information_title_edit" name="information_title" required><br>

            <label for="information_kbn">お知らせ区分</label>
            <select id="information_kbn_edit" name="information_kbn" required>
                <option value="1">重要</option>
                <option value="2">情報</option>
            </select><br>

            <label for="keisai_ymd">掲載日</label>
            <input type="date" id="keisai_ymd_edit" name="keisai_ymd" required><br>

            <label for="enable_start_ymd">有効開始年月日</label>
            <input type="date" id="enable_start_ymd_edit" name="enable_start_ymd" required><br>

            <label for="enable_end_ymd">有効終了年月日</label>
            <input type="date" id="enable_end_ymd_edit" name="enable_end_ymd" required><br>

            <label for="information_naiyo">お知らせ内容</label>
            <textarea id="information_naiyo_edit" name="information_naiyo" required></textarea><br>

            <label for="update_user_cd">登録者コード</label>
            <input type="text" id="update_user_cd_edit" name="update_user_cd" required><br>

            <!-- 버튼들을 감싸는 컨테이너 -->
            <div class="button-container">
                <button type="submit" class="button">変更</button> <!-- 버튼을 "변경"으로 수정 -->
                <button type="button" class="button" onclick="closeEditModal()">戻る</button>
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

        // 입력 필드 값 초기화
        document.getElementById('information_title').value = '';
        document.getElementById('information_kbn').value = '1'; // 기본값 설정
        document.getElementById('keisai_ymd').value = '';
        document.getElementById('enable_start_ymd').value = '';
        document.getElementById('enable_end_ymd').value = '';
        document.getElementById('information_naiyo').value = '';
        document.getElementById('create_user_cd').value = '';
    }
    // 수정 모달 열기
    function openEditModal() {
        document.getElementById('editModal').style.display = 'flex';
    }

    // 수정 모달 닫기
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    
</script>

<div class="table-container">
    <div class="table-header">
        <span class="table-cell">お知らせタイトル</span>
        <span class="table-cell">お知らせ区分</span>
        <span class="table-cell">掲載日</span>
        <span class="table-cell">適用期間</span>
    </div>

    <!-- 데이터 행 출력 -->
    @foreach ($informations as $information)
    <div class="table-row" data-id="{{ $information->information_id }}">
        <span class="table-cell">{{ $information->information_title }}</span>
        <span class="table-cell">{{ $information->information_kbn == 1 ? '重要' : '情報' }}</span>
        <span class="table-cell">{{ \Carbon\Carbon::parse($information->keisai_ymd)->format('Y/m/d') }}</span>
        <span class="table-cell">
            {{ \Carbon\Carbon::parse($information->enable_start_ymd)->format('Y/m/d') }} ~
            {{ \Carbon\Carbon::parse($information->enable_end_ymd)->format('Y/m/d') }}
        </span>
    </div>
    @endforeach

</div>
<div class="pagination">
    {{ $informations->appends(request()->query())->links('pagination::bootstrap-4') }}
</div>


<button class="button button-inline" onclick="openModal()">登録</button>

<!-- 선택된 게시물 수정 버튼 -->
<button class="button button-inline" id="editSelectedButton" onclick="editSelectedRecord()">変更</button>

<!-- 선택된 게시물 삭제 버튼 -->
<form id="deleteSelectedForm" action="/api/information/deleteSelected" method="POST" class="button-inline">
    @csrf
    @method('DELETE')
    <input type="hidden" name="selected_items" id="selected_items" value="">
    <button class="button" type="submit" id="deleteSelectedButton">削除</button>
</form>

<script>
    let selectedId = null; // 선택된 게시물 ID를 저장할 변수

    // 게시물 선택 버튼 클릭 시
    document.querySelectorAll('.table-row').forEach(button => {
        button.addEventListener('click', function() {
            // 모든 선택 버튼에서 'selected' 클래스 제거
            document.querySelectorAll('.table-row').forEach(btn => btn.classList.remove('selected'));

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
            alert("行を選択してください。");
            return;
        }

        // 삭제 확인 메시지
        if (confirm("レコードを削除します。よろしいでしょうか。")) {
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
                        alert('お知らせの削除が完了しました。');
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

    // 수정 버튼 클릭 시 실행되는 메서드 (데이터 가져오기)
    function editSelectedRecord() {
        if (!selectedId) {
            alert("行を選択してください");
            return;
        }

        // AJAX로 선택된 게시물의 데이터를 가져옵니다.
        fetch(`/api/information/${selectedId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    data = data.data;


                    // YYYYMMDD 형태의 문자열을 YYYY-MM-DD로 변환하는 함수
                    function formatDateString(dateString) {
                        return dateString.substring(0, 4) + '-' + dateString.substring(4, 6) + '-' + dateString.substring(6, 8);
                    }
                    // 폼에 데이터 채우기
                    console.log(data)
                    document.getElementById('information_title_edit').value = data.information_title;
                    document.getElementById('information_kbn_edit').value = data.information_kbn;
                    // 수정 모달을 열 때 데이터 설정
                    document.getElementById('keisai_ymd_edit').value = formatDateString(data.keisai_ymd);
                    document.getElementById('enable_start_ymd_edit').value = formatDateString(data.enable_start_ymd);
                    document.getElementById('enable_end_ymd_edit').value = formatDateString(data.enable_end_ymd);
                    document.getElementById('information_naiyo_edit').value = data.information_naiyo;
                    document.getElementById('update_user_cd_edit').value = data.update_user_cd;

                    // 모달 열기
                    openEditModal();

                    // 수정 모드로 상태 변경
                    document.getElementById('editForm').setAttribute('onsubmit', 'submitEditForm(event)');
                } else {
                    alert('게시물 정보를 가져오는 데 실패했습니다.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('게시물 정보를 가져오는 중 오류가 발생했습니다.');
            });
    }

    // YYYY-MM-DD 형태의 문자열을 YYYYMMDD로 변환하는 함수
    function formatDateToServer(dateString) {
        return dateString.replace(/-/g, '');
    }
    // 수정된 데이터를 전송하는 메서드
    function submitEditForm(event) {
        event.preventDefault(); // 폼 기본 제출 방지

        // 폼 데이터를 가져와서 JSON 객체로 변환
        const jsonData = {
            information_title: document.getElementById('information_title_edit').value,
            information_kbn: document.getElementById('information_kbn_edit').value,
            keisai_ymd: formatDateToServer(document.getElementById('keisai_ymd_edit').value),
            enable_start_ymd: formatDateToServer(document.getElementById('enable_start_ymd_edit').value),
            enable_end_ymd: formatDateToServer(document.getElementById('enable_end_ymd_edit').value),
            information_naiyo: document.getElementById('information_naiyo_edit').value,
            create_user_cd: document.getElementById('update_user_cd_edit').value
        };

        // 수정 요청을 서버로 전송
        fetch(`/api/information/${selectedId}`, {
                method: "PUT",
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
                    alert('게시물이 성공적으로 수정되었습니다.');
                    location.reload(); // 페이지 새로고침
                } else {
                    alert(data.message || '게시물 수정 실패');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('서버 오류가 발생했습니다.');
            });
    }

    function submitForm(event) {
        event.preventDefault(); // 기본 폼 제출 방지

        // createForm 폼에서 데이터를 가져와 FormData 객체 생성
        const formElement = document.getElementById('createForm');
        const formData = new FormData(formElement);

        // FormData를 JSON 형식으로 변환하여 fetch에 전달
        const jsonData = {};
        formData.forEach((value, key) => {
            // 날짜 필드 확인 후 포맷 변환
            if (key === 'keisai_ymd' || key === 'enable_start_ymd' || key === 'enable_end_ymd') {
                // 날짜 값에서 "-" 제거하여 YYYYMMDD 형식으로 변환
                value = value.replace(/-/g, ''); // 예: "2024-11-15" -> "20241115"
            }
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