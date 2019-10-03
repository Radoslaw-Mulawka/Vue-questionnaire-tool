<?php


namespace App\Http\Controllers;

use App\Laravue\Models\Campaign;
use App\Services\AnswerService;

class AnswerController extends Controller
{
    /**
     * @var AnswerService
     */
    private $answerService;

    public function __construct(AnswerService $answerService)
    {
        $this->answerService = $answerService;
    }

    public function show(Campaign $campaign)
    {
        $answers = $this->answerService->getAnswers($campaign);
        return $this->respondSuccess($answers, '');
    }
}
