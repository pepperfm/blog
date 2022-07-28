<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Enumerable;

use App\Services\Images\UserImage;
use App\Factories\UserFactory;

use Illuminate\Support\Facades\Cache;
use App\Models\{User, AkaLists, Address};

class UserService extends BaseFactoryService
{
    public function __construct()
    {
        $this->factory = new UserFactory();
    }

    /**
     * @param array $filters
     *
     * @throws \Throwable
     * @return \Illuminate\Support\Enumerable
     */
    public function getUsers(array $filters = []): Enumerable
    {
        $users = User::query()
            ->with(['programs', 'akaLists', 'addresses'])
            ->select(['id', 'uid', 'last_name', 'type'])
            ->when(isset($filters['name']), function (Builder $query) use ($filters) {
                $query->where('last_name', 'like', "%{$filters['name']}%");
            })
            ->when(isset($filters['type']), function (Builder $query) use ($filters) {
                $query->has('akaLists')
                    ->withAggregate('akaLists as categories_count', 'COUNT(DISTINCT aka_lists.id)')
                    ->having('categories_count', '<=', 1);
                // берёт те записи, у которых хотя бы одна категория соответствует
                // $query->whereHas('akaLists', function (Builder $query) use ($filters) {
                //     $query->where('category', $filters['type']);
                // });

                // псевдоним
                // $query->whereRelation('akaLists', 'category', '=', $filters['type']);

                // исключает запись, если хотя бы одна категория соответствует
                // $query->with(['akaLists' => function (\Illuminate\Database\Eloquent\Relations\HasMany $query) use ($filters) {
                //     $query->where('category', $filters['type']);
                // }]);
            })
            ->get();

        throw_if($users->isEmpty(), new \Exception('No users', "404"));

        return $users;
    }

    /**
     * @param \Illuminate\Support\Enumerable $data
     *
     * @return mixed
     */
    public function updateOrCreate(Enumerable $data): mixed
    {
        if (Cache::has('users')) {
            return Cache::get('users');
        }
        Cache::rememberForever('users', function () use ($data) {
            // todo: репозиторий, все дела + mass insert($model->attributesToArray()), чтобы запрос был меньше 90сек <3
            $data->each(function (Enumerable $items) use (&$users) {
                $items->each(function (UserImage $item) use (&$users) {
                    $user = User::query()->where('uid', $item->uid)->firstOrNew();
                    $user->uid = $item->uid;
                    $user->last_name = $item->lastName;
                    $user->type = $item->type;
                    $user->save();
                    foreach ($item->programList as $program) {
                        $model = $user->programs()->where('name', $program)->firstOrNew();
                        $model->name = $program;
                        $user->programs()->save($model);
                    }
                    foreach ($item->akaList as $list) {
                        $model = AkaLists::query()->where('uid', $item->uid)->firstOrNew();
                        $model->uid = $list->uid;
                        $model->type = $list->type;
                        $model->category = $list->category;
                        $model->name = $list->lastName;
                        $user->akaLists()->save($model);
                    }
                    foreach ($item->addressList as $address) {
                        $model = Address::query()->where('uid', $item->uid)->firstOrNew();
                        $model->uid = $address->uid;
                        $model->address1 = $address->address1;
                        $model->city = $address->city;
                        $model->country = $address->country;
                        $model->postal_code = $address->country;
                        $user->addresses()->save($model);
                    }
                });
            });

            return $this->getUsers();
        });
        // User::query()->upsert([], [], []);

        return Cache::get('users');
    }
}
