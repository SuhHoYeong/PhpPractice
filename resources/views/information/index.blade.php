@extends('layouts.app')

@section('content')


<!-- 오류 메시지를 타이틀 위에 배치 -->
<div id="title-error-message" class="error-message" style="display: none;">
    お知らせタイトルは100文字以内で入力してください。
</div>
<div id="date-error-message" class="error-message" style="display: none;">
    有効開始年月日は有効終了年月日より前の日付を指定してください。
</div>
<form class="search-form" action="{{ route('information.index') }}" method="GET" style="margin-left: 60px; margin-right: 60px;" onsubmit="return validateForm()">

<!-- 타이틀과 구분을 같은 줄에 배치 -->
    <div class="search-row">
        <label for="search_title" class="search-label">お知らせタイトル</label>
        <input type="text" name="search_title" id="search_title" placeholder="タイトル検索" value="{{ old('search_title', $search_title) }}">

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

        <label for="search_enable_period" class="search-label">適用期間</label>
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
        <h2>お知らせの登録</h2>

        <!-- 등록 폼 (Ajax로 제출) -->
        <form id="createForm" onsubmit="submitForm(event)" lang="ja">
            @csrf
            <label id="label_information_title" for="information_title">お知らせタイトル</label>
            <input type="text" id="information_title" name="information_title"><br>

            <label id="label_information_kbn" for="information_kbn">お知らせ区分</label>
            <select id="information_kbn" name="information_kbn">
                <option value="1">重要</option>
                <option value="2">情報</option>
            </select><br>

            <label id="label_keisai_ymd" for="keisai_ymd">掲載日</label>
            <input type="date" id="keisai_ymd" name="keisai_ymd" placeholder="YYYYMMDD"><br>

            <label id="label_enable_start_ymd" for="enable_start_ymd">有効開始年月日</label>
            <input type="date" id="enable_start_ymd" name="enable_start_ymd" placeholder="YYYYMMDD"><br>

            <label id="label_enable_end_ymd" for="enable_end_ymd">有効終了年月日</label>
            <input type="date" id="enable_end_ymd" name="enable_end_ymd" placeholder="YYYYMMDD"><br>

            <label id="label_information_naiyo" for="information_naiyo">お知らせ内容</label>
            <textarea id="information_naiyo" name="information_naiyo"></textarea><br>

            <label id="label_create_user_cd" for="create_user_cd">登録者コード</label>
            <input type="text" id="create_user_cd" name="create_user_cd"><br>

            <!-- 버튼들을 감싸는 컨테이너 -->
            <div class="button-container">
                <button type="submit" class="button">登録</button>
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

            <!-- お知らせタイトル -->
            <label for="information_title_edit" id="label_information_title_edit">お知らせタイトル</label>
            <input type="text" id="information_title_edit" name="information_title"><br>
            <!-- 필수 입력란 에러 메시지 -->
            <span class="error-message" id="error_information_title_edit" style="color:red;display:none;">お知らせタイトルを入力してください。</span>

            <!-- お知らせ区分 -->
            <label for="information_kbn_edit" id="label_information_kbn_edit">お知らせ区分</label>
            <select id="information_kbn_edit" name="information_kbn">
                <option value="1">重要</option>
                <option value="2">情報</option>
            </select><br>
            <!-- 필수 입력란 에러 메시지 -->
            <span class="error-message" id="error_information_kbn_edit" style="color:red;display:none;">お知らせ区分を選択してください。</span>

            <!-- 掲載日 -->
            <label for="keisai_ymd_edit" id="label_keisai_ymd_edit">掲載日</label>
            <input type="date" id="keisai_ymd_edit" name="keisai_ymd"><br>
            <!-- 필수 입력란 에러 메시지 -->
            <span class="error-message" id="error_keisai_ymd_edit" style="color:red;display:none;">掲載日を入力してください。</span>

            <!-- 有効開始年月日 -->
            <label for="enable_start_ymd_edit" id="label_enable_start_ymd_edit">有効開始年月日</label>
            <input type="date" id="enable_start_ymd_edit" name="enable_start_ymd"><br>
            <!-- 필수 입력란 에러 메시지 -->
            <span class="error-message" id="error_enable_start_ymd_edit" style="color:red;display:none;">有効開始年月日を入力してください。</span>

            <!-- 有効終了年月日 -->
            <label for="enable_end_ymd_edit" id="label_enable_end_ymd_edit">有効終了年月日</label>
            <input type="date" id="enable_end_ymd_edit" name="enable_end_ymd"><br>
            <!-- 필수 입력란 에러 메시지 -->
            <span class="error-message" id="error_enable_end_ymd_edit" style="color:red;display:none;">有効終了年月日を入力してください。</span>

            <!-- お知らせ内容 -->
            <label for="information_naiyo_edit" id="label_information_naiyo_edit">お知らせ内容</label>
            <textarea id="information_naiyo_edit" name="information_naiyo"></textarea><br>
            <!-- 필수 입력란 에러 메시지 -->
            <span class="error-message" id="error_information_naiyo_edit" style="color:red;display:none;">お知らせ内容を入力してください。</span>

            <!-- 登録者コード -->
            <label for="update_user_cd_edit" id="label_update_user_cd_edit">登録者コード</label>
            <input type="text" id="update_user_cd_edit" name="update_user_cd"><br>
            <!-- 필수 입력란 에러 메시지 -->
            <span class="error-message" id="error_update_user_cd_edit" style="color:red;display:none;">登録者コードを入力してください。</span>

            <!-- 버튼들을 감싸는 컨테이너 -->
            <div class="button-container">
                <button type="submit" class="button">変更</button> <!-- 버튼을 "변경"으로 수정 -->
                <button type="button" class="button" onclick="closeEditModal()">戻る</button>
            </div>
        </form>
    </div>
</div>

<div id="alertModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAlertModal()">&times;</span>
        <h2>警告</h2>
        <p id="alertMessage">行を選択してください。</p>
        <button class="button" onclick="closeAlertModal()">閉じる</button>
    </div>
</div>

<!--登録完了モーダル-->
<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeSuccessModal()">&times;</span>
        <h2>情報</h2>
        <p id="alertMessageSuccess">お知らせの登録が完了しました。</p>
        <button class="button" onclick="closeSuccessModal()">閉じる</button>
    </div>
</div>

<!--削除完了モーダル-->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
        <h2>情報</h2>
        <p id="alertMessageDelete">お知らせの削除が完了しました。</p>
        <button class="button" onclick="closeDeleteModal()">閉じる</button>
    </div>
</div>

<!--変更完了モーダル-->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUpdateModal()">&times;</span>
        <h2>情報</h2>
        <p id="alertMessageUpdate">お知らせの変更が完了しました。</p>
        <button class="button" onclick="closeUpdateModal()">閉じる</button>
    </div>
</div>

<!-- 삭제 확인 모달 -->
<div id="deleteConfirmationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAlertModal1()">&times;</span>
        <h2>削除確認</h2>
        <p id="deleteConfirmationMessage">レコードを削除します。よろしいでしょうか。</p>
        <div class="button-container">
            <button id="confirmDeleteButton" class="button">削除</button>
            <button class="button" onclick="closeAlertModal1()">キャンセル</button>
        </div>
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

    // 알림 모달 열기
    function openAlertModal(message) {
        document.getElementById('alertMessage').textContent = message; // 메시지를 동적으로 변경
        document.getElementById('alertModal').style.display = 'flex';
    }

    // 알림 모달 닫기
    function closeAlertModal() {
        document.getElementById('alertModal').style.display = 'none';
    }

    // 모달 닫기 함수
    function closeAlertModal1() {
        document.getElementById('deleteConfirmationModal').style.display = 'none';
    }

    // 삭제 확인 모달을 열기 위한 함수
    function openDeleteConfirmationModal() {
        // 모달에 삭제 확인 메시지 표시
        document.getElementById('deleteConfirmationMessage').innerText = "レコードを削除します。よろしいでしょうか。";
        // 모달 열기
        document.getElementById('deleteConfirmationModal').style.display = 'block';

        // '削除' 버튼에 클릭 이벤트 추가
        document.getElementById('confirmDeleteButton').addEventListener('click', function() {
            deleteSelectedRecord(); // 삭제 함수 호출
        });
    }
    //성공하였습니다 모달 열기
    function showSuccessModal() {
        const modal = document.getElementById('successModal');
        modal.style.display = 'block';
    }
    //성공하였습니다 모달 닫기
    function closeSuccessModal() {
        location.reload()
    }

    //삭제성공하였습니다 모달 열기
    function showDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.style.display = 'block';
    }
    //삭제성공하였습니다 모달 닫기
    function closeDeleteModal() {
        location.reload()
    }


    // 모달 외부를 클릭해도 닫히지 않도록 설정
    window.onclick = function(event) {
        const modal = document.getElementById('successModal');
        if (event.target === modal) {
            event.stopPropagation(); // 모달 외부 클릭을 차단
        }
    }

    //변경성공하였습니다 모달 열기
    function showUpdateModal() {
        const modal = document.getElementById('updateModal');
        modal.style.display = 'block';
    }

    //삭제성공하였습니다 모달 닫기
    function closeUpdateModal() {
        location.reload()
    }

    function validateForm() {
    console.log("validateForm called"); // 호출 확인
    const title = document.getElementById('search_title');
    const startDate = document.getElementById('search_enable_start_ymd');
    const endDate = document.getElementById('search_enable_end_ymd');
    const dateErrorMessage = document.getElementById('date-error-message');
    const titleErrorMessage = document.getElementById('title-error-message');

    let isValid = true;

    if (!title) {
        console.error("Element with ID 'search_title' not found.");
        return false;
    }
    if (!startDate || !endDate) {
        console.error("Start or End Date element not found.");
        return false;
    }

    const titleValue = title.value.trim();
    console.log("Title:", titleValue);
    console.log("Start Date:", startDate.value, "End Date:", endDate.value);

    if (titleValue.length > 100) {
        titleErrorMessage.style.display = 'block';
        isValid = false;
    } else {
        titleErrorMessage.style.display = 'none';
    }

    if (startDate.value && endDate.value && new Date(startDate.value) > new Date(endDate.value)) {
        dateErrorMessage.style.display = 'block';
        isValid = false;
    } else {
        dateErrorMessage.style.display = 'none';
    }

    return isValid;
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
<div class="action-container">
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
</div>

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
            openAlertModal("行を選択してください。");
            return;
        }

        // 삭제 확인 모달 열기
        openDeleteConfirmationModal();
    });


    // 삭제 처리 함수
    function deleteSelectedRecord() {
        // 선택된 게시물 ID를 hidden input에 할당
        document.getElementById('selected_items').value = selectedId;

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
                    showDeleteModal();
                    console.log('お知らせの削除が完了しました。');
                } else {
                    alert(data.message || '削除失敗');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('error');
            });

        closeAlertModal1(); // 모달 닫기
    }


    // 수정 버튼 클릭 시 실행되는 메서드 (데이터 가져오기)
    function editSelectedRecord() {
        if (!selectedId) {
            openAlertModal("行を選択してください。");
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
                    document.getElementById('update_user_cd_edit').value = data.create_user_cd;

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

        // 필수 입력란 및 메시지 설정
        const fields = [{
                id: 'information_title_edit',
                labelId: 'label_information_title_edit',
                message: 'お知らせタイトルを入力してください。'
            },
            {
                id: 'information_kbn_edit',
                labelId: 'label_information_kbn_edit',
                message: 'お知らせ区分を選択してください。'
            },
            {
                id: 'keisai_ymd_edit',
                labelId: 'label_keisai_ymd_edit',
                message: '掲載日を入力してください。'
            },
            {
                id: 'enable_start_ymd_edit',
                labelId: 'label_enable_start_ymd_edit',
                message: '有効開始年月日を入力してください。'
            },
            {
                id: 'enable_end_ymd_edit',
                labelId: 'label_enable_end_ymd_edit',
                message: '有効終了年月日を入力してください。'
            },
            {
                id: 'information_naiyo_edit',
                labelId: 'label_information_naiyo_edit',
                message: 'お知らせ内容を入力してください。'
            },
            {
                id: 'update_user_cd_edit',
                labelId: 'label_update_user_cd_edit',
                message: '登録者コードを入力してください。'
            },
        ];

        let valid = true;

        // 모든 에러 메시지 초기화
        document.querySelectorAll('.error-message').forEach(errorMsg => errorMsg.remove());

        // 각 필드에 대해 유효성 검사
        fields.forEach(field => {
            const input = document.getElementById(field.id);
            const label = document.getElementById(field.labelId);

            if (!input.value) {
                valid = false;

                // 에러 메시지를 라벨 옆에 추가
                const errorMessage = document.createElement('span');
                errorMessage.className = 'error-message';
                errorMessage.style.color = 'red';
                errorMessage.style.marginLeft = '10px'; // 라벨과 메시지 간 간격 추가
                errorMessage.textContent = field.message;

                // 라벨 옆에 에러 메시지 삽입
                label.insertAdjacentElement('afterend', errorMessage);
            }

            // 타이틀이 100자 초과하는지 체크
            if (field.id === 'information_title_edit' && input.value.length > 100) {
                valid = false;

                const errorMessage = document.createElement('span');
                errorMessage.className = 'error-message';
                errorMessage.style.color = 'red';
                errorMessage.style.marginLeft = '10px';
                errorMessage.textContent = 'タイトルは100文字以内で入力してください。';
                label.insertAdjacentElement('afterend', errorMessage);
            }

            // 등록자 코드가 40자 초과하는지 체크
            if (field.id === 'update_user_cd_edit' && input.value.length > 40) {
                valid = false;

                const errorMessage = document.createElement('span');
                errorMessage.className = 'error-message';
                errorMessage.style.color = 'red';
                errorMessage.style.marginLeft = '10px';
                errorMessage.textContent = '登録者コードは40文字以内で入力してください。';
                label.insertAdjacentElement('afterend', errorMessage);
            }

        });

        // 유효개시일과 유효종료일 비교
        const startDate = document.getElementById('enable_start_ymd_edit').value;
        const endDate = document.getElementById('enable_end_ymd_edit').value;
        const endLabel = document.getElementById('label_enable_end_ymd_edit');

        if (startDate && endDate && startDate > endDate) {
            valid = false;

            // 에러 메시지를 유효종료일 라벨 옆에 추가
            const errorMessage = document.createElement('span');
            errorMessage.className = 'error-message';
            errorMessage.style.color = 'red';
            errorMessage.style.marginLeft = '10px';
            errorMessage.textContent = '有効開始年月日は有効終了年月日より前の日付を指定してください。';

            endLabel.insertAdjacentElement('afterend', errorMessage);
        }

        if (!valid) {
            return; // 유효성 검사를 통과하지 못하면 서버 요청 중단
        }

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
                    showUpdateModal('게시물이 성공적으로 수정되었습니다.');
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
        // 필수 입력란 및 메시지 설정
        const fields = [{
                id: 'information_title',
                labelId: 'label_information_title',
                message: 'お知らせタイトルを入力してください。'
            },
            {
                id: 'information_kbn',
                labelId: 'label_information_kbn',
                message: 'お知らせ区分を選択してください。'
            },
            {
                id: 'keisai_ymd',
                labelId: 'label_keisai_ymd',
                message: '掲載日を入力してください。'
            },
            {
                id: 'enable_start_ymd',
                labelId: 'label_enable_start_ymd',
                message: '有効開始年月日を入力してください。'
            },
            {
                id: 'enable_end_ymd',
                labelId: 'label_enable_end_ymd',
                message: '有効終了年月日を入力してください。'
            },
            {
                id: 'information_naiyo',
                labelId: 'label_information_naiyo',
                message: 'お知らせ内容を入力してください。'
            },
            {
                id: 'create_user_cd',
                labelId: 'label_create_user_cd',
                message: '登録者コードを入力してください。'
            },
        ];

        let valid = true;

        // 모든 에러 메시지 초기화
        document.querySelectorAll('.error-message').forEach(errorMsg => errorMsg.remove());

        fields.forEach(field => {
            const input = document.getElementById(field.id);
            const label = document.getElementById(field.labelId);

            if (!input.value) {
                valid = false;

                // 에러 메시지를 라벨 옆에 추가
                const errorMessage = document.createElement('span');
                errorMessage.className = 'error-message';
                errorMessage.style.color = 'red';
                errorMessage.style.marginLeft = '10px'; // 라벨과 메시지 간 간격 추가
                errorMessage.textContent = field.message;

                // 라벨 옆에 에러 메시지 삽입
                label.insertAdjacentElement('afterend', errorMessage);
            }

            // 타이틀이 100자를 초과하는 경우 처리
            if (field.id === 'information_title' && input.value.length > 100) {
                valid = false;

                // 에러 메시지를 라벨 옆에 추가
                const errorMessage = document.createElement('span');
                errorMessage.className = 'error-message';
                errorMessage.style.color = 'red';
                errorMessage.style.marginLeft = '10px'; // 라벨과 메시지 간 간격 추가
                errorMessage.textContent = 'タイトルは100文字以内で入力してください。';

                // 라벨 옆에 에러 메시지 삽입
                label.insertAdjacentElement('afterend', errorMessage);
            }

            // 등록자코드가 40자를 초과하는 경우 처리
            if (field.id === 'create_user_cd' && input.value.length > 40) {
                valid = false;

                // 에러 메시지를 라벨 옆에 추가
                const errorMessage = document.createElement('span');
                errorMessage.className = 'error-message';
                errorMessage.style.color = 'red';
                errorMessage.style.marginLeft = '10px'; // 라벨과 메시지 간 간격 추가
                errorMessage.textContent = '登録者コードは40文字以内で入力してください。';

                // 라벨 옆에 에러 메시지 삽입
                label.insertAdjacentElement('afterend', errorMessage);
            }
        });

        // 유효개시일과 유효종료일 비교
        const startDate = document.getElementById('enable_start_ymd').value;
        const endDate = document.getElementById('enable_end_ymd').value;
        const endLabel = document.getElementById('label_enable_end_ymd');

        if (startDate && endDate && startDate > endDate) {
            valid = false;

            // 에러 메시지를 유효종료일 라벨 옆에 추가
            const errorMessage = document.createElement('span');
            errorMessage.className = 'error-message';
            errorMessage.style.color = 'red';
            errorMessage.style.marginLeft = '10px';
            errorMessage.textContent = '有効開始年月日は有効終了年月日より前の日付を指定してください。';

            endLabel.insertAdjacentElement('afterend', errorMessage);
        }

        if (!valid) {
            return; // 유효성 검사를 통과하지 못하면 서버 요청 중단
        }
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
                    showSuccessModal();
                    // location.reload(); // 페이지 새로고침
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