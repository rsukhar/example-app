<?php

namespace App\Http\Controllers\User\Boards\Cards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    protected array $attachments;

    public function index(int $board, int $card)
    {
        $comments = Comment::with(['orders' => function ($q) {
            $q->where('created_at', '>', now()->subWeek());
        }])->get();
        $otherServiceToken = 'w12we4o567nj';
        // Новый комментарий на русском языке
        return response()->json(['comments' => $comments, 'service_token' => $otherServiceToken]);
    }

    public function create($card)
    {
        $serviceToken = env('SOME_SERVICE_TOKEN');
        // Новый комментарий
        return 'Вывести форму для создания комментария в карточке ' . $card . 'с токеном' . $serviceToken;
    }

    /**
     * Сохраняет новый комментарий
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
            'publish_at' => 'nullable|date',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $commentId)
    {
        $comment = Comment::where('id', $commentId)->firstOrFail();
        return Inertia::render('Comment/Show', [
            'comment' => $comment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($card, string $id)
    {
        return 'Вывести форму для редактирования комментария ' . $id . ' из карточки ' . $card;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return 'Изменить комментарий ' . $id . ' из карточки ' . $card;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($card, string $id)
    {
        return 'Удалить комментарий ' . $id . ' из карточки ' . $card;
    }
}
