<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DestroyRequest;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\RestoreRequest;
use App\Http\Requests\User\UpdatePermissionRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\UseCases\User\DestroyAction;
use App\UseCases\User\IndexAction;
use App\UseCases\User\RestoreAction;
use App\UseCases\User\ShowAction;
use App\UseCases\User\UpdateAction;
use App\UseCases\User\UpdatePermissionAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return UserResource
     */
    public function mySelf(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * Display a listing of the resource.
     *
     * @param  IndexRequest  $request
     * @param  IndexAction  $action
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
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  int  $id
     * @param  ShowAction  $action
     * @return UserResource
     */
    public function show(Request $request, int $id, ShowAction $action): UserResource
    {
        return new UserResource($action($request, $id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  int  $id
     * @param  UpdateAction  $action
     * @return UserResource
     */
    public function update(UpdateRequest $request, int $id, UpdateAction $action): UserResource
    {
        return new UserResource($action($request, $id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePermissionRequest  $request
     * @param  int  $id
     * @param  UpdatePermissionAction  $action
     * @return Response
     */
    public function updatePermission(UpdatePermissionRequest $request, int $id, UpdatePermissionAction $action): Response
    {
        $action($request, $id);

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyRequest  $request
     * @param  int  $id
     * @param  DestroyAction  $action
     * @return Response
     */
    public function destroy(DestroyRequest $request, int $id, DestroyAction $action): Response
    {
        $action($request, $id);

        return response()->noContent();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  RestoreRequest  $request
     * @param  int  $id
     * @param  RestoreAction  $action
     * @return Response
     */
    public function restore(RestoreRequest $request, int $id, RestoreAction $action): Response
    {
        $action($request, $id);

        return response()->noContent();
    }
}
