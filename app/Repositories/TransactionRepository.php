<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function all($request) {
        $perPage = 10;
        if($request->has('page') && $request->has('per_page')) {
            $perPage = $request->input('per_page');
        }

        $query = Transaction::with(['inventory', 'inventory.product', 'inventory.status'])
            ->with('transaction_category')
            ->with('user');

        if($request->has('search')){
            $query->where(function($query) use($request){
                $kywd = '%' . $request->input('search') . '%';
                $query->where('name', 'LIKE', $kywd);
                $query->orWhere('description', 'LIKE', $kywd);
            });
        }

        $data = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::exact('product_id', 'inventory.product_id'),
                AllowedFilter::exact('user_id', 'user_id'),
            ])
            ->allowedSorts([
                    AllowedSort::field('id'),
                ]);

        return $data->paginate($perPage);
    }

    public function find($id) {
        return Transaction::with(['inventory', 'inventory.product', 'inventory.status'])
            ->with('transaction_category')
            ->with('user')
            ->findOrFail($id);
    }

    public function store(array $data) {
        $transactionCategoryFindId = TransactionCategory::find($data['transaction_category_id']);
        if(empty($transactionCategoryFindId)) {
            throw new Exception('Transaction category not available!', 403);
        }

        $inventory_id = 0;
        $inventoryFindBatchNumber = Inventory::where(['product_id' => $data['product_id'], 'batch_number' => $data['batch_number']])->first();
        if($transactionCategoryFindId->key_identifier == 'INBOUND') {
            if(empty($inventoryFindBatchNumber)) {
                $inventory = Inventory::create([
                    'product_id' => $data['product_id'],
                    'status_id' => $data['status_id'],
                    'batch_number' => $data['batch_number'],
                    'serial_number' => $data['serial_number'],
                    'qty' => $data['qty'],
                    'price' => $data['price'],
                    'production_date' => $data['production_date'],
                    'expiration_date' => $data['expiration_date'],
                    'warranty_period' => $data['warranty_period'],
                ]);
                $inventory_id = $inventory->id;
            }else{
                Inventory::whereId($inventoryFindBatchNumber->id)->update([
                    'qty' => $inventoryFindBatchNumber->qty + $data['qty'],
                ]);
                $inventory_id = $inventoryFindBatchNumber->id;
            }
        }else{
            if(empty($inventoryFindBatchNumber)) {
                throw new Exception('Inventory not available!', 403);
            }else{
                $inventory_id = $inventoryFindBatchNumber->id;
                if($inventoryFindBatchNumber->qty < $data['qty']) throw new Exception('Inventory quantity is insufficient.', 403);

                Inventory::whereId($inventoryFindBatchNumber->id)->update([
                    'qty' => $inventoryFindBatchNumber->qty - $data['qty'],
                ]);
            }
        }

        $transaction = [
            'inventory_id' => $inventory_id,
            'transaction_category_id' => $data['transaction_category_id'],
            'user_id' => $data['user_id'],
            'date' => $data['date'],
            'qty' => $data['qty'],
        ];
        return Transaction::create($transaction);
    }

    public function update(array $data, $id) {
        return Transaction::whereId($id)->update($data);
    }

    public function delete($id) {
        return Transaction::destroy($id);
    }
}
