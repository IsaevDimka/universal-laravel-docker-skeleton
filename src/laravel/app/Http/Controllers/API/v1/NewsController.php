<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Models\News;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Request;

class NewsController extends ApiController
{
    const DEFAULT_LIMIT = 25;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', self::DEFAULT_LIMIT);

        /** @var \App\Models\User $user */
        $user = $request->user();

        if (!is_null($request->get('withTrashed', null)) && $user->hasAnyRole([\App\Models\Role::ROLE_ADMIN, \App\Models\Role::ROLE_CLIENT, \App\Models\Role::ROLE_ROOT])) {
            $queryBuilder = News::withTrashed()->orderBy('created_at', 'DESC');
        }else{
            $queryBuilder = News::withoutTrashed()->orderBy('created_at', 'DESC');
        }
        $total = $queryBuilder->count();

        return api()->ok('', [
            'items' => NewsResource::collection($queryBuilder->simplePaginate($limit)),
            'total' => $total,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return api()->ok('store', $request->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $news = News::withTrashed()->findOrFail($id);
        return api()->ok('', $news);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $news = News::withTrashed()->findOrFail($request->get('id'));
        $news->update($request->only('title', 'slug', 'content', 'author_id', 'is_active'));

        return api()->ok('update', $request->toArray(), compact('id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return api()->ok('destroy', compact('id'));
    }

    public function delete($id)
    {
        return api()->ok('destroy', compact('id'));
    }
}
