<?php


namespace App\Laravue;


use App\Laravue\Models\Option;
use App\Laravue\Models\Question;

class QuestionResult
{
    private $name;
    private $type;
    private $answers;
    private $question;
    private $results = [];
    private $totalNumberOfUniqueAnswers;
    private $respondents;
    private $omissions;
    private $numberOfAnswers;

    public function __construct(Question $question)
    {
        $this->question = $question;
        $this->answers = $question->answers;
        $this->totalNumberOfUniqueAnswers = $this->answers->groupBy('guest_phid')->count();
        $this->name = $question->getQuestion();
        $this->type = $question->getOptionType();
    }

    public function calculateAnswersForGivenQuestion(int $numberOfRespondents)
    {
        switch ($this->type) {
            case 'checkbox':
            case 'radio':
                $this->calculateAnswersWithOptions();
                break;
            case 'text':
                $this->getTextAnswers();
                break;
            case 'votes':
                $this->calculateVoteAnswers();
                break;
        }
        $this->calculateNumberOfAnswers($numberOfRespondents);
    }

    private function calculateAnswersWithOptions()
    {
        /** @var Option $option */
        foreach ($this->question->options as $option) {
            $optionCount = $this->countAnswers($option->getId());

            $this->results[] = [
                'label' => $option->getLabel(),
                'results' => $optionCount,
                'percentage' => $this->calculatePercentage($optionCount)
            ];
        }
    }

    private function countAnswers(int $answer): int
    {
        $groupingValue = $this->getGroupingValue();
        $groupedAnswersByOption = $this->answers->groupBy($groupingValue)->toArray();
        if ($this->hasBeenAnswered($answer, $groupedAnswersByOption)) {
            return count($groupedAnswersByOption[$answer]);
        }
        return 0;
    }

    private function getGroupingValue(): string
    {
        switch ($this->type) {
            case 'radio':
            case 'checkbox':
                return 'options_id';
                break;
            case 'votes':
                return 'a_value';
                break;
        }
    }

    private function hasBeenAnswered(int $answerId, $groupedAnswers): bool
    {
        $selectedOptions = array_keys($groupedAnswers);
        return in_array($answerId, $selectedOptions);
    }

    private function getTextAnswers()
    {
        $answers = $this->answers->pluck('a_value');
        $this->results = $answers;
    }

    private function calculateVoteAnswers()
    {
        $sum = 0;
        $avg = 0;
        for ($starRating = 1; $starRating < 6; $starRating++) {
            $selectedAnswersCount = $this->countAnswers($starRating);
            $this->results[$starRating] = ['results' => $selectedAnswersCount];
            $this->results[$starRating] += ['percent' => $this->calculatePercentage($selectedAnswersCount)];
            $sum += $starRating * $selectedAnswersCount;

            if ($sum !== 0) {
                $avg = round($sum / $this->totalNumberOfUniqueAnswers, 2);
            }
        }
        $this->results['questionVotesAverage'] = $avg;
    }

    private function calculatePercentage(int $selectedAnswersCount): float
    {
        if ($this->totalNumberOfUniqueAnswers !== 0) {
            return round(($selectedAnswersCount / $this->totalNumberOfUniqueAnswers) *
                100, 2);
        }
        return 0;
    }

    private function calculateNumberOfAnswers(int $numberOfRespondents)
    {
        $this->respondents = $numberOfRespondents;
        $this->omissions = $numberOfRespondents - $this->totalNumberOfUniqueAnswers;
        $this->numberOfAnswers = $this->respondents - $this->omissions;
    }

    public function toArray()
    {
        return [
            "name" => $this->name,
            "type" => $this->type,
            "respondents" => $this->respondents,
            "omissions" => $this->omissions,
            "numberOfAnswers" => $this->numberOfAnswers,
            "options" => $this->results
        ];
    }
}
