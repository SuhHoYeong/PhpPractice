<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;  // Log 사용을 위한 네임스페이스 추가

class InformationController extends Controller
{
    // 게시글 목록 + 검색 및 페이징
    public function index(Request $request)
    {
        $query = Information::where('delete_flg', '0');

        // 검색 조건 추가
        if ($request->filled('search')) {
            $query->where('information_title', 'like', '%' . $request->search . '%');
        }

        // 페이징 (페이지 당 10개씩)
        $informations = $query->paginate(3);

        return view('information.index', compact('informations'))->with('search', $request->search);
    }

    // 게시글 작성 폼
    public function create()
    {
        return view('information.create');
    }

    // 게시글 저장
    public function store(Request $request)
    {
        $request->validate([
            'information_title' => 'required|string|max:100',
            'information_kbn' => 'required|string|max:1',
            'keisai_ymd' => 'required|string|max:8',
            'enable_start_ymd' => 'required|string|max:8',
            'enable_end_ymd' => 'required|string|max:8',
            'information_naiyo' => 'required|string',
            'create_user_cd' => 'required|string|max:40',
        ]);

        // 'create_user_cd' 값을 'update_user_cd'에 할당
        $data = $request->all();
        $data['update_user_cd'] = $request->create_user_cd;  // 'create_user_cd' 값을 'update_user_cd'에 설정

        // 데이터 저장
        $information = Information::create($data);

        // 성공 시 JSON 응답 반환
        return response()->json(['success' => true]);
    }

    // 게시글 보기
    public function show($id)
    {
        $information = Information::findOrFail($id);
        return view('information.show', compact('information'));
    }

    // 게시글 수정 데이터를 JSON으로 반환하는 메서드
    public function edit($id)
    {
        // ID에 해당하는 게시글 정보 가져오기
        $information = Information::findOrFail($id);

        // 데이터가 성공적으로 조회되면 JSON 형식으로 반환
        return response()->json([
            'success' => true,
            'data' => $information,
        ]);
    }

    // 게시글 수정 처리
    public function update(Request $request, $id)
    {
        $information = Information::findOrFail($id);  // 수정할 게시글을 찾음

        // 폼 데이터를 유효성 검사하고, 데이터를 업데이트
        $validated = $request->validate([
            'information_title' => 'required|string|max:255',
            'information_kbn' => 'required|integer',
            'keisai_ymd' => 'required|date_format:Ymd',
            'enable_start_ymd' => 'required|date_format:Ymd',
            'enable_end_ymd' => 'required|date_format:Ymd',
            'information_naiyo' => 'required|string',
            'create_user_cd' => 'required|string|max:10',
        ]);

        $information->update($validated);  // 유효성 검사된 데이터로 게시글 업데이트

        return redirect()->route('information.index')->with('success', '게시물이 수정되었습니다.');
    }

    //선택삭제
    public function deleteSelected(Request $request)
    {
        // 디버깅을 위한 데이터 출력
        Log::info('Delete Selected Request:', ['data' => $request->all()]);

        // 선택된 게시물 ID를 단일 값으로 받음
        $selectedId = $request->input('selected_items');

        if (!$selectedId) {
            return response()->json(['success' => false, 'message' => '삭제할 게시물이 없습니다.']);
        }

        // 단일 게시물 삭제
        $deleted = Information::where('information_id', $selectedId)->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => '삭제에 실패했습니다.']);
    }

    // 게시글 삭제
    public function destroy($id)
    {
        $information = Information::findOrFail($id);
        $information->delete_flg = '1';
        $information->save();

        return redirect()->route('information.index')->with('success', '게시글이 삭제되었습니다.');
    }
}
