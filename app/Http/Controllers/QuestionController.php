<?php


namespace App\Http\Controllers;

use App\Laravue\Models\Question;
use App\Services\QuestionService;
use Illuminate\Http\Request;

class QuestionController extends Controller {

    /**
     * @var QuestionService
     */
    private $questionService;


    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index()
    {

    }

    public function store(Request $request)
    {
        return $this->questionService->store($request);
    }

    public function show(Question $question)
    {

    }

    public function update(Question $question, Request $request)
    {
        return $this->questionService->update($question, $request);
    }

    public function destroy(Question $question)
    {
        return $this->questionService->delete($question);
    }

    public function copy(Question $question)
    {
        return $this->questionService->copy($question);
    }
}
