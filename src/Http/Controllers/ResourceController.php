<?php


namespace RaadaaPartners\RaadaaBase\Http\Controllers;


use Illuminate\Http\Request;
use RaadaaPartners\RaadaaBase\Actions\Resource\CreateResource;
use RaadaaPartners\RaadaaBase\Actions\Resource\DeleteResource;
use RaadaaPartners\RaadaaBase\Actions\Resource\GetResource;
use RaadaaPartners\RaadaaBase\Actions\Resource\UpdateResource;

class ResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index($endpoint)
    {
        return (new \SearchResource(['endpoint' => $endpoint]))->execute();
    }

    /**
     * Create a new resource
     *
     * @param Request $request
     * @param $endpoint
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request, $endpoint)
    {
        return (new CreateResource(['endpoint' => $endpoint, 'data' => $request->all()]))->execute();
    }

    /**
     * Get a specified resource from storage
     *
     * @param $endpoint
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($endpoint, $id)
    {
        return (new GetResource(['endpoint' => $endpoint, 'id' => $id]))->execute();
    }

    /**
     * Update a specified resource
     *
     * @param Request $request
     * @param $endpoint
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $endpoint, $id)
    {
        $data = array_merge($request->all(), ['id' => $id]);
        return (new UpdateResource(['endpoint' => $endpoint, 'data' => $data]))->execute();
    }

    /**
     * Delete a specified resource from storage
     *
     * @param $endpoint
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($endpoint, $id)
    {
        return (new DeleteResource(['endpoint' => $endpoint, 'id' => $id]))->execute();
    }
}