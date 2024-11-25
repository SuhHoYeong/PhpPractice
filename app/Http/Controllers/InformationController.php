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
        // 기본 쿼리 설정 (삭제되지 않은 게시물만 조회)
        $query = Information::where('delete_flg', '0');

        // 검색 필터 값 가져오기
        $search_title = $request->input('search_title', ''); // 제목 검색 값
        $search_kbn = $request->input('search_kbn', ''); // 구분 검색 값
        $search_keisai_ymd = $request->input('search_keisai_ymd');
        $search_enable_start_ymd = $request->input('search_enable_start_ymd');
        $search_enable_end_ymd = $request->input('search_enable_end_ymd');

        // 제목으로 검색
        if ($search_title) {
            $query->where('information_title', 'like', "%$search_title%");
        }

        // 구분으로 검색
        if ($search_kbn) {
            $query->where('information_kbn', $search_kbn);
        }

        if ($search_keisai_ymd) {
            $query->whereDate('keisai_ymd', $search_keisai_ymd);
        }

        if ($search_enable_start_ymd && $search_enable_end_ymd) {
            // 적용개시일이 필터의 종료일보다 이전이어도 포함 (시작일은 포함, 완료일은 포함)
            $query->where(function($query) use ($search_enable_start_ymd, $search_enable_end_ymd) {
                $query->whereDate('enable_start_ymd', '<=', $search_enable_end_ymd)
                      ->whereDate('enable_end_ymd', '>=', $search_enable_start_ymd);
            });
        } else {
            if ($search_enable_start_ymd) {
                $query->whereDate('enable_start_ymd', '>=', $search_enable_start_ymd);
            }
        
            if ($search_enable_end_ymd) {
                $query->whereDate('enable_end_ymd', '<=', $search_enable_end_ymd);
            }
        }
        // 페이징 처리: 페이지 당 5개씩
        $informations = $query->paginate(5);

        // 뷰로 데이터 전달 (검색어와 구분도 함께 전달)
        return view('information.index', compact('informations', 'search_title', 'search_kbn', 'search_keisai_ymd', 'search_enable_start_ymd', 'search_enable_end_ymd'));
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
        $data['create_time'] = now();  // create_time을 현재 시간으로 설정
        $data['update_time'] = now();  // update_time을 현재 시간으로 설정
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

    // PUT 요청 처리
    public function update($id, Request $request)
    {
        // 해당 ID로 정보를 찾기
        $information = Information::find($id);

        // 정보가 없다면 404 반환
        if (!$information) {
            return response()->json(['message' => 'Information not found'], 404);
        }

        // 입력된 데이터로 정보 업데이트
        $information->update([
            'information_title' => $request->input('information_title'),
            'information_kbn' => $request->input('information_kbn'),
            'keisai_ymd' => $request->input('keisai_ymd'),
            'enable_start_ymd' => $request->input('enable_start_ymd'),
            'enable_end_ymd' => $request->input('enable_end_ymd'),
            'information_naiyo' => $request->input('information_naiyo'),
            'create_user_cd' => $request->input('create_user_cd'),
            'update_user_cd' => $request->input('create_user_cd'),
            'update_time' => now(),  // update_time을 현재 시간으로 업데이트
        ]);

        return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }

    //선택삭제
    public function deleteSelected(Request $request)
    {
        // 디버깅을 위한 데이터 출력
        Log::info('Delete Selected Request:', ['data' => $request->all()]);

        // 선택된 게시물 ID를 단일 값으로 받음
        $selectedId = $request->input('selected_items');

        if (!$selectedId) {
            return response()->json(['success' => false, 'message' => '削除するコメントはありません']);
        }

        // 단일 게시물 삭제
        $deleted = Information::where('information_id', $selectedId)->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => '削除失敗しました']);
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
