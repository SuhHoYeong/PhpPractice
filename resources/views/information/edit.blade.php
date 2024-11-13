<!-- resources/views/posts/edit.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>게시글 수정</title>
</head>
<body>
    <h1>게시글 수정</h1>

    <form action="{{ route('posts.update', $post->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="title">제목</label>
        <input type="text" name="title" id="title" value="{{ $post->title }}" required><br>

        <label for="content">내용</label>
        <textarea name="content" id="content" required>{{ $post->content }}</textarea><br>

        <button type="submit">게시글 수정</button>
    </form>
</body>
</html>
