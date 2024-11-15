<form action="{{ route('information.update', $information->information_id) }}" method="POST">
    @csrf
    @method('PUT')  <!-- PUT 메서드를 사용해서 수정 요청을 보냄 -->
    
    <label for="information_title">제목</label>
    <input type="text" id="information_title" name="information_title" value="{{ $information->information_title }}" required><br>

    <label for="information_kbn">카테고리</label>
    <select id="information_kbn" name="information_kbn" required>
        <option value="1" {{ $information->information_kbn == 1 ? 'selected' : '' }}>중요</option>
        <option value="2" {{ $information->information_kbn == 2 ? 'selected' : '' }}>정보</option>
    </select><br>

    <label for="keisai_ymd">게시일</label>
    <input type="text" id="keisai_ymd" name="keisai_ymd" value="{{ $information->keisai_ymd }}" required><br>

    <label for="enable_start_ymd">시작일</label>
    <input type="text" id="enable_start_ymd" name="enable_start_ymd" value="{{ $information->enable_start_ymd }}" required><br>

    <label for="enable_end_ymd">종료일</label>
    <input type="text" id="enable_end_ymd" name="enable_end_ymd" value="{{ $information->enable_end_ymd }}" required><br>

    <label for="information_naiyo">내용</label>
    <textarea id="information_naiyo" name="information_naiyo" required>{{ $information->information_naiyo }}</textarea><br>

    <label for="create_user_cd">등록자 코드</label>
    <input type="text" id="create_user_cd" name="create_user_cd" value="{{ $information->create_user_cd }}" required><br>

    <button type="submit">수정</button>
</form>