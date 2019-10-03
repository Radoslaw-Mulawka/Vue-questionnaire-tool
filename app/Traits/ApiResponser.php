<?php

namespace App\Traits;

use Response;
use \Illuminate\Http\Response as Res;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

trait ApiResponser {

  /**
   * @var int
   */
  protected $statusCode = Res::HTTP_OK;
  protected $headers = [];

  /**
   * @return mixed
   */
  public function getStatusCode() {
    return $this->statusCode;
  }

  /**
   * @param $message
   * @return json response
   */
  public function setStatusCode($statusCode) {
    $this->statusCode = $statusCode;
    return $this;
  }

    /**
     * @return mixed
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @param $header
     * @param $value
     * @return ApiResponser
     */
    public function setHeader(string $header, string $value) {
        $this->headers[$header] = $value;
        return $this;
    }

  // 201
  public function respondCreated($message, $data = null) {
    $this->setStatusCode(Res::HTTP_CREATED);
    return $this->respond([
        'status' => 'success',
        'status_code' => Res::HTTP_CREATED,
        'message' => $message,
        'data' => $data
    ]);
  }

  /**
   * 200
   * @param Paginator $paginate
   * @param $data
   * @return mixed
   */
  protected function respondWithPagination(Paginator $paginate, $data, $message) {
    $data = array_merge($data, [
      'paginator' => [
        'total_count' => $paginate->total(),
        'total_pages' => ceil($paginate->total() / $paginate->perPage()),
        'limit' => $paginate->perPage(),
        'current_page' => $paginate->currentPage(),
        'current_count' => $paginate->count(),
        'links' => [
            'prev' => $paginate->previousPageUrl(),
            'next' => $paginate->nextPageUrl(),
            'first' => 1 != $paginate->currentPage() ? $paginate->url(1) : null,
            'last' => $paginate->lastPage() != $paginate->currentPage() ? $paginate->url($paginate->lastPage()) : null,
        ]
      ]
    ]);

    return $this->respond([
        'status' => 'success',
        'status_code' => Res::HTTP_OK,
        'message' => $message,
        'data' => $data
    ]);
  }

  /**
   * 200
   * @param $data
   * @return mixed
   */
  protected function respondSuccess($data, $message) {
    return $this->respond([
        'status' => 'success',
        'status_code' => Res::HTTP_OK,
        'message' => $message,
        'data' => $data
    ]);
  }

  // 404
  public function respondNotFound($message = 'Not Found!') {
    $this->setStatusCode(Res::HTTP_NOT_FOUND);
    return $this->respond([
        'status' => 'error',
        'status_code' => Res::HTTP_NOT_FOUND,
        'message' => $message,
    ]);
  }

  // 406
  public function respondNotAcceptable($data, $message = 'Expired!') {
    $this->setStatusCode(Res::HTTP_NOT_ACCEPTABLE);
    return $this->respond([
        'status' => 'error',
        'status_code' => Res::HTTP_NOT_ACCEPTABLE,
        'message' => $message,
        'data' => $data
    ]);
  }

  // 500
  public function respondInternalError($message) {
    $this->setStatusCode(Res::HTTP_INTERNAL_SERVER_ERROR);
    return $this->respond([
        'status' => 'error',
        'status_code' => Res::HTTP_INTERNAL_SERVER_ERROR,
        'message' => $message,
    ]);
  }

  // 422
  public function respondValidationError($message, $errors) {
    $this->setStatusCode(Res::HTTP_UNPROCESSABLE_ENTITY);
    return $this->respond([
        'status' => 'error',
        'status_code' => Res::HTTP_UNPROCESSABLE_ENTITY,
        'message' => $message,
        'data' => $errors
    ]);
  }

  public function respond($data) {
    return Response::json($data, $this->getStatusCode(), $this->getHeaders());
  }

  // Code
  public function respondWithError($message, $code, $data = null) {
    $this->setStatusCode($code);
    return $this->respond([
        'status' => 'error',
        'status_code' => $code,
        'message' => $message,
        'data' => $data
    ]);
  }

  // 401
  public function respondUnauthorized($message) {
    $this->setStatusCode(Res::HTTP_UNAUTHORIZED);
    return $this->respond([
        'status' => 'error',
        'status_code' => Res::HTTP_UNAUTHORIZED,
        'message' => $message,
    ]);
  }

  // 403
  public function respondForbidden($message) {
    $this->setStatusCode(Res::HTTP_FORBIDDEN);
    return $this->respond([
        'status' => 'error',
        'status_code' => Res::HTTP_FORBIDDEN,
        'message' => $message,
    ]);
  }

  // 204
  public function respondSuccessNoContent($message = '') {
    $this->setStatusCode(Res::HTTP_NO_CONTENT);
    return $this->respond([
        'status' => 'success',
        'status_code' => Res::HTTP_NO_CONTENT,
        'message' => $message,
    ]);
  }

  public function showAll(Collection $collection, $message = 'List of items found!', array $needs = []){
    if($collection->isEmpty()){
      return $this->respondSuccess(['count' => 0,'items' => $collection], $message);
    }
    $transformer = $collection->first()->transformer;
    $transformer = new $transformer;

    $collection = $this->filterData($collection, $transformer);
    if($collection == false){
      return $this->respondWithError(trans('app.cannot_filter'), 409);
    }

    $collection = $this->sortData($collection, $transformer);
    if($collection == false){
      return $this->respondWithError(trans('app.cannot_sort'), 409);
    }

    if(request()->has('paginate') && request()->paginate == 'off'){
      $collection = $transformer->transformCollection($collection->all(), $needs);
      return $this->respondSuccess(['total_count' => count($collection),'items' => $collection], $message);
    }

    $paginated = $this->paginate($collection);
    $collection = $transformer->transformCollection($paginated->all(), $needs);
    return $this->respondWithPagination($paginated, ['items' => $collection], $message);
  }

  public function showOne(Model $model, $message = 'Item found!', array $needs = []) {
    $transformer = new $model->transformer;
    $model = $transformer->transformObject($model, $needs);
    return $this->respondSuccess($model, $message);
  }

  public function showJSON($data, $message = 'Item found!', array $needs = []) {
    $array = (array) $data;
    return $this->respondSuccess($array, $message);
  }

  protected function sortData(Collection $collection, $transformer){
    if(request()->has('sort_by')){
      $attr = $transformer::attributeMapping(request()->sort_by);
      if($attr == 'error'){
        return false;
      }
      if(request()->has('sort_order') && request()->sort_order == 'desc'){
        $collection = $collection->sortByDesc->{$attr};
      }else{
        $collection = $collection->sortBy->{$attr};
      }
    }
    return $collection;
  }

  protected function filterData(Collection $collection, $transformer){
    foreach (request()->query() as $query => $value) {
      $operator = "=";
      $search = $value;
      $expl = explode(':', $value);
      if(count($expl)>1){
        $operator = $this->defineOperator($expl[0]);
        $search = $expl[1];
      }

      $attr = $transformer::attributeMapping($query, 'index', 'filter');
      if($attr == 'error'){
        return false;
      }
      if(isset($attr, $search, $operator)){
        $search = ($operator == 'LIKE') ? '%'.$search.'%': $search;

        $collection = $collection->where($attr, $operator, $search);
      }
    }

    return $collection;
  }

  protected function defineOperator($search) {
    $operators =[
      'eq' => '=',
      'ne' => '<>',
      'gt' => '>',
      'gte' => '>=',
      'lt' => '<',
      'lte' => '<=',
      'lk' => 'LIKE',
    ];

    return isset($operators[$search]) ? $operators[$search] : "=";
  }

  protected function paginate(Collection $collection) {
    $rules = [
      'per_page' => 'integer|min:2|max:100'
    ];
    Validator::validate(request()->all(), $rules);

    $page = Paginator::resolveCurrentPage();

    $perPage = 15;
    if(request()->has('per_page')){
      $perPage = (int) request()->per_page;
    }

    $results = $collection->slice(($page-1)*$perPage, $perPage)->values();

    $paginated = new Paginator($results, $collection->count(), $perPage, $page, [
      'path' => Paginator::resolveCurrentPath(),
    ]);

    $paginated->appends(request()->all());
    return $paginated;
  }
}
