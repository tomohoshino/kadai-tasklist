<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\User;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check() ) {
        //ここにタスクの一覧表示
           // $tasks = Task::where('id', 'desc')->paginate(10);;
           $tasks = Task::orderBy('id', 'desc')->paginate(10);
         return view('tasks.index', [
            'tasks' => $tasks,
         ]);
         
        } else {
             return view('welcome');
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
            
        ]);
        
        $task = new Task;
        $task->user_id = $request->user_id; //L15
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->user_id = $request->user()->id;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
         // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        //見るユーザのIDとタスクのIDの一致判定
        if (\Auth::id() === $task->user_id) {
            // メッセージ詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
             ]);
        } else {
            $tasks = Task::orderBy('id', 'desc')->paginate(10);
            return view('tasks.index', [
            'tasks' => $tasks,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
         if (\Auth::id() === $task->user_id) { 
        // メッセージ編集ビューでそれを表示
             return view('tasks.edit', [
                'task' => $task,
             ]);
             
         } else {
             //課題修正
            $tasks = Task::orderBy('id', 'desc')->paginate(10);
            return view('tasks.index', [
            'tasks' => $tasks,
            ]);
         }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        
        ]);
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
       
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
        $task = Task::findOrFail($id);
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
