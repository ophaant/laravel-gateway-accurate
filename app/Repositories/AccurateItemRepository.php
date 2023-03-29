<?php

namespace App\Repositories;

use App\Exceptions\handleDatabaseException;
use App\Helpers\errorCodes;
use App\Interfaces\AccurateItemInterfaces;
use App\Models\Item;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccurateItemRepository implements AccurateItemInterfaces
{
    use ApiResponse, HasUuids;
    public function storeItem(array $data, int $database)
    {
        try {
            DB::beginTransaction();
            $col = collect($data);

            $existingItems = Item::where('code_database', $database)->get();

// loop through existing customers and delete if not in $col
            foreach ($existingItems as $existingItem) {
                $found = $col->first(function($item) use($existingItem) {
                    return $item['id'] === $existingItem->item_id;
                });
                if (!$found) {
                    $existingItem->delete();
                }
            }
            $item = $col->map(function($item,$key) use($database) {
                return [
                    'item_id' => $item['id'],
                    'code_database' => $database,
                    'item_no' => $item['no'],
                    'item_name' => $item['name'],
                    'id'=>$this->newUniqueId(),
                    'code_array'=>$database.'-'.$key
                ];
            })
                ->chunk(1000)
                ->each(function (Collection $chunk) {
                    Item::upsert($chunk->all(), 'code_array');
                });
            DB::commit();
            return $this->successResponse($item, 200, 'Item Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }catch (\PDOException $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        }
    }


}
