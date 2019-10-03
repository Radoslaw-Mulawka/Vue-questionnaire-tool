<?php


namespace App\Utils;

use App\Laravue\Models\Campaign;
use Response;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Res;
use Illuminate\Validation\ValidationException;

class ValidationHelper
{
    use ApiResponser;

    const CREATED = 0;
    const STARTED = 1;
    const PAUSED = 2;
    const FINISHED = 3;

    public function getArrayIfKeyIsValid(Request $request, string $key): array
    {
        $data = $request->isJson() ? $request->json()->all() : $request->all();
        if (!array_key_exists($key, $data)) {
            throw new ValidationException(null, $this->respondWithError('', Res::HTTP_BAD_REQUEST));
        }
        return $data;
    }


    public function checkValidFieldFromAccessFields($fieldFromRequest, array $accessFields)
    {
        if (!array_key_exists($fieldFromRequest, $accessFields)) {
            throw new ValidationException(null, $this->respondWithError(trans('app.wrong_field_to_edit'), Res::HTTP_BAD_REQUEST));
        }
    }

    public function canEdit(Campaign $campaign)
    {
        $status = $campaign->getStatus();
        if ($status === self::STARTED) {
            throw new ValidationException(null, $this->respondValidationError(trans('app.status_campaign_started_cannot_be_modified'), ''));
        }
        if ($status === self::FINISHED) {
            throw new ValidationException(null, $this->respondValidationError(trans('app.campaign_finished'), ''));
        }

    }
}
