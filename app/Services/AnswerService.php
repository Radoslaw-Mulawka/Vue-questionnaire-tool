<?php

namespace App\Services;

use App\Laravue\Models\Campaign;
use App\Laravue\Models\Question;
use App\Laravue\QuestionResult;
use Illuminate\Support\Facades\Storage;

class AnswerService
{
    public function getAnswers(Campaign $campaign)
    {
        $result['id'] = $campaign->getId();
        $result['campaignName'] = $campaign->getName();
        $result['dateTo'] = $campaign->getDateTo();
        $result['dateFrom'] = $campaign->getDateFrom();
        $result['banner'] =  is_null($campaign->getLogo()) ? null : Storage::url($campaign->getLogo());
        $numberOfCampaignRespondents = $campaign->answers->groupBy('guest_phid')->count();
        $questions = Question::with(['options', 'answers'])->where('campaigns_id', $campaign->getId())->get();

        foreach ($questions as $question) {
            $questionResult = new QuestionResult($question);
            $questionResult->calculateAnswersForGivenQuestion($numberOfCampaignRespondents);
            $result['questions'][] = $questionResult->toArray();
        }
        return $result;
    }
}
