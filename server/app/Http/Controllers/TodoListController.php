<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostReorderTodoListRequest;
use App\Http\Requests\StoreTodoListRequest;
use App\Http\Requests\UpdateTodoListRequest;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNan;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todoLists = auth("api")->user()
            ->todoLists()
            ->latest("order")
            ->get();

        return $this->httpOkResponse("Get all todo list", $todoLists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoListRequest $request)
    {
        $payload = $request->validated();
        $user = auth("api")->user();
        $lastOrder = $user->todoLists()
            ->latest("order")
            ->pluck("order")
            ->first();

        $todoList = TodoList::create([
            "user_id" => $user->id,
            "title" => $payload["title"],
            "order" => $lastOrder === null ? 0 : $lastOrder + 1,
        ]);

        return $this->httpOkResponse("Create todo list success", $todoList, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TodoList $todoList)
    {
        return $this->httpOkResponse("Get todo list", $todoList);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoListRequest $request, TodoList $todoList)
    {
        $payload = $request->validated();

        $todoList->update([
            "title" => $payload["title"],
            "completed" => $payload["completed"],
        ]);

        return $this->httpOkResponse("Update todo list success", $todoList);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TodoList $todoList)
    {
        $todoList->delete();
        return $this->httpOkResponse("Delete todo list success");
    }

    public function reorder(PostReorderTodoListRequest $request)
    {
        DB::beginTransaction();

        try {
            $payload = $request->validated();
            $ids = implode(',', array_column($payload["todos"], 'id'));
            $case = "CASE ";

            foreach ($payload["todos"] as $value) {
                $case .= "WHEN id = {$value['id']} THEN {$value['order']} ";
            }

            $case .= "END";

            DB::update("UPDATE todo_lists SET `order` = $case WHERE id IN ($ids)");
            DB::commit();

            return $this->httpOkResponse("Success reorder todo list");
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return $this->httpErrorResponse("Reorder todo list failed");
        }
    }
}
