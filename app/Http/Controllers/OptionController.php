<?php


namespace App\Http\Controllers;

use App\Laravue\Models\Option;
use App\Services\OptionService;
use Illuminate\Http\Request;

class OptionController extends Controller
{


    /**
     * @var OptionService
     */
    private $optionService;

    public function __construct(OptionService $optionService)
    {
        $this->optionService = $optionService;
    }

    public function index()
    {

    }

    public function store(Request $request)
    {
        return $this->optionService->createOption($request);
    }

    public function show(Option $option)
    {
        $option->setLabel('daniel');
    }

    public function update(Option $option, Request $request)
    {
        return $this->optionService->updateOption($option, $request);
    }

    public function destroy(Option $option)
    {
        return $this->optionService->destroyOption($option);
    }

}

