<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\UseCases\User\IndexAction;
use App\UseCases\User\ShowAction;
use App\UseCases\User\UpdateAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @param IndexAction $action
     * @return JsonResponse
     */
    public function index(IndexRequest $request, IndexAction $action): JsonResponse
    {
        $users = $action($request);

        return response()->json([
            'total' => $users['total'],
            'per_page' => $users['per_page'],
            'current_page' => $users['current_page'],
            'last_page' => $users['last_page'],
            'first_item' => $users['first_item'],
            'last_item' => $users['last_item'],
            'has_more_pages' => $users['has_more_pages'],
            'data' => UserResource::collection($users['items']),
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param ShowAction $action
     * @return UserResource
     */
    public function show(int $id, ShowAction $action): UserResource
    {
        return new UserResource($action($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param int $id
     * @param UpdateAction $action
     * @return UserResource
     */
    public function update(UpdateRequest $request, int $id, UpdateAction $action): UserResource
    {
        return new UserResource($action($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
