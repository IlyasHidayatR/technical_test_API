<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get soal with limit
        $limit = $request->input('limit');
        if ($limit) {
            $soal = Soal::paginate($limit);
        }
        // Get soal with search
        $search = $request->input('search');
        if ($search) {
            $soal = Soal::searchByTitle($search);
        }
        // Get soal with search and limit
        if ($search && $limit) {
            $soal = Soal::searchByTitle($search)->paginate($limit);
        }
        // Get soal without search and limit
        if (!$search && !$limit) {
            $soal = Soal::all();
        }
        // Get soal dengan jumlah jawaban benar terbanyak
        $orderByCorrectAnswerCountDesc = $request->input('orderByCorrectAnswerCountDesc');
        if ($orderByCorrectAnswerCountDesc) {
            $soal = Soal::orderByCorrectAnswerCountDesc();
        }
        // Get soal dengan mengurutkan berdasarkan daftar pertanyaan berdasarkan jumlah jawaban benar secara descending
        if ($search && $orderByCorrectAnswerCountDesc) {
            $soal = Soal::searchByTitle($search)->orderByCorrectAnswerCountDesc();
        }

        // Return soal
        return response()->json($soal, 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'options' => 'required',
            'correct_option' => 'required|integer'
        ]);

        $question = new Soal;
        $question->title = $validatedData['title'];
        $question->content = $validatedData['content'];
        $question->options = json_encode($validatedData['options']);
        $question->correct_option = $validatedData['correct_option'];
        // getcorrectanswercount
        $correctOption = $question->getCorrectAnswerCount();
        $question->save();
    
        // Return soal with message and answer count
        return response()->json([
            'message' => 'Question created successfully',
            'correct_answer_count' => $correctOption,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Soal  $soal
     * @return \Illuminate\Http\Response
     */
    public function show($soal)
    {
        // get soal
        $question = Soal::find($soal);

        $user = Auth::user();

        // Return soal with user 
        return response()->json([
            'question' => $question,
            'user' => $user,
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Soal  $soal
     * @return \Illuminate\Http\Response
     */
    public function edit($soal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Soal  $soal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $soal)
    {
        // update soal
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'options' => 'required',
            'correct_option' => 'required|integer'
        ]);

        $question = Soal::find($soal);
        $question->title = $validatedData['title'];
        $question->content = $validatedData['content'];
        $question->options = json_encode($validatedData['options']);
        $question->correct_option = $validatedData['correct_option'];
        // getcorrectanswercount
        $correctOption = $question->getCorrectAnswerCount();
        $question->save();

        // Return soal with message and answer count
        return response()->json([
            'message' => 'Question updated successfully',
            'correct_answer_count' => $correctOption,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Soal  $soal
     * @return \Illuminate\Http\Response
     */
    public function destroy($soal)
    {
        // delete soal
        $question = Soal::find($soal);
        $question->delete();

        $user = Auth::user();

        // Return soal with message and answer count
        return response()->json([
            'message' => 'Question deleted successfully',
            'user' => $user
        ], 200);
    }
}
