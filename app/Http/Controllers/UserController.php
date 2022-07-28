<?php

namespace App\Http\Controllers;

use Illuminate\Support\LazyCollection;
use Illuminate\Http\Request;

use App\Http\Requests\TreasuryInput;
use App\Services\UserService;

use App\Contracts\ResponseContract;

class UserController extends Controller
{
    public function __construct(
        public ResponseContract $json,
        public UserService $userService
    ) {}

    /**
     * @throws \Throwable
     */
    public function index()
    {
        try {
            $users = $this->userService->getUsers();
        } catch (\Exception $e) {
            return $this->json->response([], $e->getMessage(), $e->getCode());
        }

        return $this->json->response($users->toArray());
    }

    /**
     * @throws \Throwable
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $xml = file_get_contents("https://www.treasury.gov/ofac/downloads/sdn.xml");
        $object = simplexml_load_string($xml);
        $data = [];
        foreach ($object->sdnEntry as $item) {
            $data[] = $this->userService->getFactory()->fromRequest(TreasuryInput::make($item))->make();
        }
        try {
            \DB::beginTransaction();

            $users = $this->userService->updateOrCreate(LazyCollection::make($data)->chunk(200));

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();

            return $this->json->error($e->getMessage(), $e->getCode());
        }

        return $this->json->response($users);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Throwable
     * @return \Illuminate\Http\JsonResponse
     */
    public function filteredUsers(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'name' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
        ]);
        if (!$request->has('type') || $request->input('type') === '') {
            return $this->json->response(
                $this->userService->getUsers()->toArray()
            );
        }
        $users = $this->userService->getUsers($request->input());

        return $this->json->response($users->toArray());
    }
}
